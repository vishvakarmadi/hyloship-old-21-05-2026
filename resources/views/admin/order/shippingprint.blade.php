<?php
require_once('fpdf/fpdf.php');
require_once('vendor/setasign/fpdi/src/autoload.php');
if($courier_id =='1' || $courier_id =='5' ){
    if(!empty($label_arra)){
        if(count($label_arra) >1){
            for($i=0;$i<count($label_arra);$i++){
                if($courier_id =='5'){
                    $decoded = base64_decode($label_arra[$i]['label'],true);
                }else{
                    $decoded = ($label_arra[$i]['label']);
                }
                file_put_contents($label_arra[$i]['awb'].'.pdf', $decoded);
            }
            $merger = new \setasign\Fpdi\Fpdi('L');
            
            for($j=0;$j<count($label_arra);$j++){
                $pageCount = $merger->setSourceFile($label_arra[$j]['awb'].'.pdf');
                for ($i = 1; $i <= $pageCount; $i++) {
                    $tplId = $merger->importPage($i);
                    $merger->addPage();
                    $merger->useTemplate($tplId);
                }
            }
            

            // Output the merged PDF to a file
            $merger->Output('shipping_'.$id.'.pdf', 'F');
            $file = 'shipping_'.$id.'.pdf';
            // file_put_contents($file, $decoded);

            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
            }
            for($i=0;$i<count($label_arra);$i++){
                File::delete($label_arra[$i]['awb'].'.pdf');
            }
            // File::delete($file);
        }else{
            if($courier_id =='5'){
                $decoded = base64_decode($label_arra[0]['label'],true);
            }else{
                $decoded = ($label_arra[0]['label']);
            }
            $file = 'shipping_'.$id.'.pdf';
            file_put_contents($file, $decoded);
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
            }
        }
        exit;

    }else{
        echo 'Something went wrong, please contact admin1';
    }
    exit;
}else if($courier_id =='6'){
    if(!empty($label_arra)){
        if(count($label_arra) >1){
            for($i=0;$i<count($label_arra);$i++){
                $filed =@file_get_contents($label_arra[$i]['pdf']);
                if($filed === false){
                    echo 'We are generating your file, please try after 10min';
                    exit;
                }
                $decoded = base64_decode(chunk_split(base64_encode($filed)),true);
                file_put_contents($label_arra[$i]['awb'].'.pdf', $decoded);
            }
            $merger = new \setasign\Fpdi\Fpdi('L');
                    
            for($j=0;$j<count($label_arra);$j++){
                $pageCount = $merger->setSourceFile($label_arra[$j]['awb'].'.pdf');
                for ($i = 1; $i <= $pageCount; $i++) {
                    $tplId = $merger->importPage($i);
                    $merger->addPage();
                    $merger->useTemplate($tplId);
                }
            }
            

            // Output the merged PDF to a file
            $merger->Output('shipping_'.$id.'.pdf', 'F');
            $file = 'shipping_'.$id.'.pdf';
            // file_put_contents($file, $decoded);

            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
            }
            for($i=0;$i<count($label_arra);$i++){
                File::delete($label_arra[$i]['awb'].'.pdf');
            }
            // File::delete($file);
        }else{
            $filed = @file_get_contents($label_arra[0]['pdf']);
            // echo $filed;die;
            if($filed === false){
                echo 'We are generating your file, please try after 10min';
                exit;
            }
            $decoded = base64_decode(chunk_split(base64_encode($filed)),true);
            $file = 'shipping_'.$id.'.pdf';
            file_put_contents($file, $decoded);
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
            }
        }    
    }else{
        echo 'Something went wrong, please contact admin2';
    }    
    exit;
}else if($courier_id =='9'){
    if(!empty($label_arra)){
        for($i=0;$i<count($label_arra);$i++){
            $decoded = base64_decode($label_arra[$i]['awb']);
            file_put_contents('image'.$i.''.$courier_id.'.'.$label_arra[$i]['format'], $decoded);
        }
        // Create instance of FPDF
        $pdf = new \setasign\Fpdi\Fpdi();

        // Add a page
        $pdf->AddPage();
        
        // Set initial position
        $x = 30; // Initial x-coordinate
        $y = 10; // Initial y-coordinate

        // Loop through the image paths and add images to the PDF
        for($j=0;$j<count($label_arra);$j++){
            $imagePath = 'image'.$j.''.$courier_id.'.'.$label_arra[$j]['format'];
            // Add image to the PDF
            if (file_exists($imagePath)) {
                // Determine image dimensions
                list($width, $height) = getimagesize($imagePath);
                
                // Calculate aspect ratio to maintain proportions
                $ratio = $width / $height;
                // Calculate new width and height to fit within the page
                $maxWidth = 280; // Maximum width for the image
                $maxHeight = 220; // Maximum height for the image
                $newWidth = min($maxWidth, $width);
                $newHeight = $newWidth / $ratio;
                if ($newHeight > $maxHeight) {
                    $newHeight = $maxHeight;
                    $newWidth = $newHeight * $ratio;
                }
                // Check if adding this image will exceed the page height
                if ($y + $newHeight > $pdf->GetPageHeight()) {
                    // If yes, start a new page
                    $pdf->AddPage();
                    // Reset y-coordinate to top of the page
                    $y = 10;
                }

                // Add image to the PDF
                $pdf->Image($imagePath, $x, $y, $newWidth, $newHeight); // (path, x, y, width, height)

                // Update y-coordinate for the next image
                $y += $newHeight + 10; // Add some spacing between images

            }
            
        }               
        
        // Output the PDF to the browser or save it to a file
        $pdf->Output('shipping_label/'.$name.''.$id.'.pdf', 'F'); // 'F' to save the PDF to a file, 'I' to output to the browser
        $file = 'shipping_label/'.$name.''.$id.'.pdf';
        
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
        }
        for($i=0;$i<count($label_arra);$i++){
            File::delete('image'.$i.''.$courier_id.'.'.$label_arra[$i]['format']);
        }
        // File::delete($file);
            
    }else{
        echo 'Something went wrong, please contact admin2';
    }    
    exit;
}
echo 'Something went wrong, please contact admin3';
exit;
?>

