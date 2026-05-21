<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Integration extends Model
{
     // for ecom express start
    public static function hitpostcurl($url, $array_data)
    {
        if ($url != '' && !empty($array_data)) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $array_data,
                CURLOPT_HTTPHEADER => array(
                    'Cookie: AWSALB=G7BF4FlIo4ykNfok3Dk7mrwVNRQ/mebSumzKCDxlB2BgZd3+iZ0aSuxw1XX7qw/2XhhyOc/sHM4LUNzMEGqUMRQY3FM7oY9Dd2vkk9PVVkT5q3pj30WW0wiQ6r4T; AWSALBCORS=G7BF4FlIo4ykNfok3Dk7mrwVNRQ/mebSumzKCDxlB2BgZd3+iZ0aSuxw1XX7qw/2XhhyOc/sHM4LUNzMEGqUMRQY3FM7oY9Dd2vkk9PVVkT5q3pj30WW0wiQ6r4T'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return $response;
        }
    }

    public static function hitgetcurl($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public static function chk_serviceable_pincode($pincode)
    {
        if ($pincode != '') {
            $array_data = array('username' => '', 'password' => 'p63Cu1WHBN', 'pincode' => $pincode);
            $url = 'https://api.ecomexpress.in/apiv3/pincode/';
            $response = Integration::hitpostcurl($url, $array_data);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function get_awb_number($payment_mode)
    {
        if ($payment_mode != '') {
            $array_data = array('username' => '', 'password' => 'p63Cu1WHBN', 'count' => 1, 'type' => $payment_mode);
            $url = 'https://api.ecomexpress.in/apiv2/fetch_awb/';
            $response = Integration::hitpostcurl($url, $array_data);
            return $response;
        }
        else {
            return '';
        }
    }
    public static function shipment_ecom($json_input)
    {
        if ($json_input != '') {
            $json_input = '[' . $json_input . ']';
            $array_data = array('username' => '', 'password' => 'p63Cu1WHBN', 'json_input' => $json_input);
            $url = 'https://api.ecomexpress.in/apiv2/manifest_awb/';
            $response = Integration::hitpostcurl($url, $array_data);
            return $response;
        }
        else {
            return '';
        }
    }
    public static function rev_manifest_awb($json_input)
    {
        if ($json_input != '') {
            // $json_input = '['.$json_input.']';
            $array_data = array('username' => '', 'password' => 'p63Cu1WHBN', 'json_input' => $json_input);
            $url = 'https://api.ecomexpress.in/apiv2/manifest_awb_rev_v2/';
            $response = Integration::hitpostcurl($url, $array_data);
            return $response;
        }
        else {
            return '';
        }
    }
    public static function cancelshipment($awb_no)
    {
        if ($awb_no != '') {
            $array_data = array('username' => '', 'password' => 'p63Cu1WHBN', 'awbs' => $awb_no);
            $url = 'https://api.ecomexpress.in/apiv2/cancel_awb/';
            $response = Integration::hitpostcurl($url, $array_data);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function generate_shiplabel($awb_no)
    {
        if ($awb_no != '') {
            $array_data = array('username' => '', 'password' => 'p63Cu1WHBN', 'awb' => $awb_no);
            $url = 'https://shipment.ecomexpress.in/services/expp/shipping_label';
            $response = Integration::hitpostcurl($url, $array_data);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function track_ecom($tracking_info)
    {
        if ($tracking_info != '') {
            $url = 'https://plapi.ecomexpress.in/track_me/api/mawbd/?username=&password=p63Cu1WHBN&awb=' . $tracking_info;
            $response = Integration::hitgetcurl($url);
            return $response;
        }
        else {
            return '';
        }
    }

    // for ecom express end

    // for ecom xbess start
    public static function generatetoken_xbess($username = null, $password = null, $secretkey = null)
    {
        $username = $username ?: env('XBEES_USERNAME', 'admin@Hyloship.com');
        $password = $password ?: env('XBEES_PASSWORD', 'Xpress@1234567');
        $secretkey = $secretkey ?: env('XBEES_SECRETKEY', '5babb4d7a6c80b45ade918fb4e429068c8480e6125925c474d8d67a27f8190db');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://userauthapis.xbees.in/api/auth/generateToken',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30, // Added timeout
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                "username" => $username,
                "password" => $password,
                "secretkey" => $secretkey
            ]),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return json_encode(['code' => 500, 'message' => 'CURL Error: ' . $error_msg]);
        }
        curl_close($curl);
        return $response;
    }

    public static function hitpostcurl_xbess_svc($url, $data_string, $xb_key = null)
    {
        $xb_key = $xb_key ?: env('XBEES_XB_KEY', 'Plmng39338VdtHa');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_SSL_VERIFYPEER => false, // Bypass SSL for compatibility
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => array(
                'XBKey: ' . $xb_key,
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return json_encode(['ReturnCode' => '500', 'ReturnMessage' => 'CURL Error: ' . $error_msg]);
        }
        curl_close($curl);
        return $response;
    }

    public static function hitpostcurl_xbess($url, $data_string, $token, $xb_key = null)
    {
        $xb_key = $xb_key ?: env('XBEES_XB_KEY', 'Plmng39338VdtHa');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60, // Increased timeout for manifest
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => array(
                'token: ' . $token,
                'versionnumber: v1',
                'XBKey: ' . $xb_key,
                'xbAccessKey: ' . $xb_key,
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return json_encode(['ReturnCode' => '500', 'ReturnMessage' => 'CURL Error: ' . $error_msg]);
        }
        curl_close($curl);
        return $response;
    }

    public static function hitgetcurl_xbess($url, $token)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'token: ' . $token,
                'versionnumber: v1',
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function chk_serviceable_pincode_xbess($array_data, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        $tdat = Integration::generatetoken_xbess($username, $password, $secretkey);
        $tdata = json_decode($tdat, true);
        if (isset($tdata['token'])) {
            if ($array_data != '') {
                $url = env('XBEES_SERVICEABILITY_URL', 'https://xbmasterapi.xbees.in/expose/get/serviceabilitypincode/details');
                return Integration::hitpostcurl_xbess($url, $array_data, $tdata['token'], $xb_key);
            }
            return '';
        }
        return $tdat;
    }

    public static function shipment_express($array_data, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        $tdat = Integration::generatetoken_xbess($username, $password, $secretkey);
        $tdata = json_decode($tdat, true);
        if (isset($tdata['token'])) {
            if ($array_data != '') {
                $url = env('XBEES_SHIPMENT_URL', 'https://apishipmentmanifestation.xbees.in/shipmentmanifestation/forward');
                return Integration::hitpostcurl_xbess($url, $array_data, $tdata['token'], $xb_key);
            }
            return '';
        }
        return $tdata;
    }

    public static function cancelshipment_xbess($array_data, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        $tdat = Integration::generatetoken_xbess($username, $password, $secretkey);
        $tdata = json_decode($tdat, true);
        if (isset($tdata['token'])) {
            if ($array_data != '') {
                $url = env('XBEES_CANCEL_URL', 'https://clientshipupdatesapi.xbees.in/forwardcancellation');
                return Integration::hitpostcurl_xbess($url, $array_data, $tdata['token'], $xb_key);
            }
            return '';
        }
        return $tdata;
    }

    public static function track_xbees($tracking_info, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        $tdat = Integration::generatetoken_xbess($username, $password, $secretkey);
        $tdata = json_decode($tdat, true);
        if (isset($tdata['token'])) {
            if ($tracking_info != '') {
                $array_data = json_encode(array('AWBNumber' => $tracking_info), true);
                $url = env('XBEES_TRACKING_URL', 'https://apishipmenttracking.xbees.in/GetShipmentAuditLog');
                return Integration::hitpostcurl_xbess($url, $array_data, $tdata['token'], $xb_key);
            }
            return '';
        }
        return $tdat;
    }

    public static function get_current_status_xbess($tracking_info, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        $tdat = Integration::generatetoken_xbess($username, $password, $secretkey);
        $tdata = json_decode($tdat, true);
        if (isset($tdata['token'])) {
            if ($tracking_info != '') {
                $array_data = json_encode(array('AWBNumber' => $tracking_info), true);
                $url = env('XBEES_CURRENT_STATUS_URL', 'https://apishipmenttracking.xbees.in/GetCurrentShipmentStatus');
                return Integration::hitpostcurl_xbess($url, $array_data, $tdata['token'], $xb_key);
            }
            return '';
        }
        return $tdat;
    }

    public static function generate_awb_series_xbess($array_data, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        if ($array_data != '') {
            $url = env('XBEES_AWB_URL', 'https://xbclientapi.xbees.in/POSTShipmentService.svc/AWBNumberSeriesGeneration');
            return Integration::hitpostcurl_xbess_svc($url, $array_data, $xb_key);
        }
        return '';
    }

    public static function get_awb_series_xbess($array_data, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        if ($array_data != '') {
            $url = env('XBEES_AWB_FETCH_URL', 'https://xbclientapi.xbees.in/TrackingService.svc/GetAWBNumberGeneratedSeries');
            return Integration::hitpostcurl_xbess_svc($url, $array_data, $xb_key);
        }
        return '';
    }

    public static function update_ndr_date_xbess($array_data, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        $tdat = Integration::generatetoken_xbess($username, $password, $secretkey);
        $tdata = json_decode($tdat, true);
        if (isset($tdata['token'])) {
            if ($array_data != '') {
                $url = 'https://clientshipupdatesapi.xbees.in/client/UpdateNDRDeferredDeliveryDate';
                return Integration::hitpostcurl_xbess($url, $array_data, $tdata['token'], $xb_key);
            }
            return '';
        }
        return $tdata;
    }

    public static function get_tracking_summary_xbess($array_data, $username = null, $password = null, $secretkey = null, $xb_key = null)
    {
        $tdat = Integration::generatetoken_xbess($username, $password, $secretkey);
        $tdata = json_decode($tdat, true);
        if (isset($tdata['token'])) {
            if ($array_data != '') {
                $url = env('XBEES_TRACKING_SUMMARY_URL', 'https://apishipmenttracking.xbees.in/GetShipmentAuditLog');
                return Integration::hitpostcurl_xbess($url, $array_data, $tdata['token'], $xb_key);
            }
            return '';
        }
        return $tdat;
    }

    // for ecom xbess end

    // for ecom delhivary start

    public static function hitgetcurl_delhivary($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function hitgetcurltoken_delhivary($url, $token)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Token ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);


        curl_close($curl);
        return $response;
    }

    public static function hitpostcurl_delhivary($url, $array_data, $token)
    {
        $curl = curl_init();

        $headers = array(
            'Authorization: Token ' . $token,
            'Accept: application/json',
        );

        // Check if data is raw JSON or form-encoded string
        if (is_string($array_data) && (strpos(trim($array_data), '{') === 0 || strpos(trim($array_data), '[') === 0)) {
            $headers[] = 'Content-Type: application/json';
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $array_data,
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    public static function chk_serviceable_pincode_delhivary($pincode, $type)
    {
        if ($pincode != '') {
            $type = strtolower($type);
            $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5';
            $url = 'https://track.delhivery.com/c/api/pin-codes/json/?token=' . $token . '&filter_codes=' . $pincode;
            $response = Integration::hitgetcurl_delhivary($url);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function get_awb_number_delhivary($pincode, $type)
    {
        if ($pincode != '') {
            $type = strtolower($type);
            $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5';
            $url = 'https://track.delhivery.com/waybill/api/bulk/json/?token=' . $token . '&count=1';
            $response = Integration::hitgetcurl_delhivary($url);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function create_warehouse($array_data, $type)
    {
        if ($array_data != '') {
            $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5';
            $url = 'https://track.delhivery.com/api/backend/clientwarehouse/create/';
            $response = Integration::hitpostcurl_delhivary($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }
    public static function edit_warehouse($array_data, $type)
    {
        if ($array_data != '') {
            $type = strtolower($type);
            $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5';
            $url = 'https://track.delhivery.com/api/backend/clientwarehouse/edit/';
            $response = Integration::hitpostcurl_delhivary($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function shipment_delhivary($array_data, $type)
    {
        if ($array_data != '') {
            $type = strtolower($type);
            $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5';
            $url = 'https://track.delhivery.com/api/cmu/create.json';
            $response = Integration::hitpostcurl_delhivary($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }
    public static function cancelshipment_delivary($array_data, $type)
    {
        if ($array_data != '') {
            $type = strtolower($type);
            $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5';
            $url = 'https://track.delhivery.com/api/p/edit';
            $response = Integration::hitpostcurl_delhivary($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }



    
//    public static function generate_shiplabel_delivary($awb){
//        if($awb !=''){
//            
////          $token ='f5ea26499f383776cc79180355c8b9b10deef3a5';
//              $url = 'https://track.delhivery.com/api/p/packing_slip?wbns='.$awb;
//             $response = Integration::hitgetcurltoken_delhivary($url,$token);
//             return $response;
//         }else{
//             return '';
//         }
//    }

    public static function shipment_rev_delhivary($array_data, $type)
    {
        if ($array_data != '') {
            $type = strtolower($type);
            $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5';
            $url = 'https://track.delhivery.com/api/cmu/create.json';
            $response = Integration::hitpostcurl_delhivary($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function track_delhivery($tracking_info)
    {
        if ($tracking_info != '') {


            $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5'; //s
            $url = 'https://track.delhivery.com/api/v1/packages/json/?token=' . $token . '&waybill=' . $tracking_info;
            $response = Integration::hitgetcurl_delhivary($url); //            echo $response;die;
            if (str_contains($response, 'No such waybill or Order Id found')) {
                $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5'; //a
                $url = 'https://track.delhivery.com/api/v1/packages/json/?token=' . $token . '&waybill=' . $tracking_info;
                $response = Integration::hitgetcurl_delhivary($url);
                return $response;
            }
            else {
                return $response;
            }


        }
        else {
            return '';
        }
    }

    // for ecom delhivary end

    // for ecom dtdc start

    public static function hitpostcurl_dtdc($url, $array_data, $token, $api_key = true)
    {
        $curl = curl_init();
        if ($api_key) {
            $headerd = array(
                'Content-Type: application/json',
                'api-key: ' . $token
            );
        }
        else {
            $headerd = array(
                'Content-Type: application/json',
                'x-access-token: GL7569_trk_json:f6e2067f51c04474d1e3cf356dbbd639',
            );
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $array_data,
            CURLOPT_HTTPHEADER => $headerd,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;

    }
    public static function hitgetcurl_dtdc($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-Access-Token: NF941_NL3024_bk:d83d61df2fd0ceefc2f9837641d0b73b',
                'Cookie: JSESSIONID=4E3A8203FD99A5D6E2C2E3453E431F80'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public static function chk_serviceable_pincode_dtdc($pincode)
    {
        if ($pincode != '') {
            $url = 'https://firstmileapi.dtdc.com/dtdc-api/api/custOrder/service/getServiceTypes/201301/' . $pincode;
            $response = Integration::hitgetcurl_dtdc($url);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function shipment_dtdc($array_data)
    {
        if ($array_data != '') {
            //for testing
            //  $token ='b01ed3562b088ab9c52822e3c18f9e';
            //  $url = 'https://demodashboardapi.shipsy.in/api/customer/integration/consignment/softdata';
            //for prod
            $token = 'd1d1f292ed2ad3921b56b5dcdbcef0';
            $url = 'https://dtdcapi.shipsy.io/api/customer/integration/consignment/softdata';
            $response = Integration::hitpostcurl_dtdc($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function cancelshipment_dtdc($array_data)
    {
        if ($array_data != '') {
            //for testing
            //  $token ='b01ed3562b088ab9c52822e3c18f9e';
            //  $url = 'https://demodashboardapi.shipsy.in/api/customer/integration/consignment/cancel';
            //for prod
            $token = 'd1d1f292ed2ad3921b56b5dcdbcef0';
            $url = 'http://dtdcapi.shipsy.io/api/customer/integration/consignment/cancel';
            $response = Integration::hitpostcurl_dtdc($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function generate_shiplabel_dtdc($array_data)
    {
        if ($array_data != '') {
            //for testing
            //  $token ='b01ed3562b088ab9c52822e3c18f9e';
            //  $url = 'https://demodashboardapi.shipsy.in/api/customer/integration/consignment/label/multipiece';
            //for prod
            $token = 'd1d1f292ed2ad3921b56b5dcdbcef0';
            $url = 'https://dtdcapi.shipsy.io/api/customer/integration/consignment/label/multipiece';
            $response = Integration::hitpostcurl_dtdc($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function shipment_rev_dtdc($array_data)
    {
        if ($array_data != '') {
            //for testing
            //  $token ='b01ed3562b088ab9c52822e3c18f9e';
            //  $url = 'https://demodashboardapi.shipsy.in/api/customer/integration/consignment/softdata';
            //for prod
            $token = 'd1d1f292ed2ad3921b56b5dcdbcef0';
            $url = 'https://dtdcapi.shipsy.io/api/customer/integration/consignment/softdata';
            $response = Integration::hitpostcurl_dtdc($url, $array_data, $token);
            return $response;
        }
        else {
            return '';
        }
    }

    public static function track_dtdc($array_data)
    {
        if ($array_data != '') {
            //for prod
            $token = 'd1d1f292ed2ad3921b56b5dcdbcef0';
            $url = 'https://blktracksvc.dtdc.com/dtdc-api/rest/JSONCnTrk/getTrackDetails';
            $response = Integration::hitpostcurl_dtdc($url, $array_data, $token, false);
            return $response;
        }
        else {
            return '';
        }
    }
    // for ecom dtdc end

    // for smartr start

    public static function posthit_smartr($url, $array_data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $array_data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    public static function posthit_smartr_Token($url, $array_data, $token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $array_data,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function hitget_smartr($url, $token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Cookie: csrftoken=nIkpEuSMRY5au3F17eMDuScH9jwxkXiU7De1xfixx8nY7U8lpVO98645vDfrxwL3; sessionid=x42vtp4lhbfaiwgu352krcnfdaiyq76m'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    public static function generatetoken_smartr()
    {
        $url = 'https://api.smartr.in/api/v1/get-token/';
        $array_data = '{"username":"", "password":""}';
        $token_json = Integration::posthit_smartr($url, $array_data);
        $token_array = json_decode($token_json, true);
        if ($token_array['success'] && isset($token_array['data']['access_token'])) {
            return $token_array['data']['access_token'];
        }
        else {
            return '';
        }

    }

    public static function chk_serviceable_pincode_smatr($pin)
    {
        if ($pin) {
            $url = 'https://api.smartr.in/api/v1/pincode/?pincode=' . $pin;
            $token = Integration::generatetoken_smartr();
            if ($token != '') {
                return Integration::hitget_smartr($url, $token);
            }
            else {
                return '';
            }
        }
        else {
            return '';
        }

    }

    public static function shipment_smartr($array_data)
    {
        if ($array_data != '') {
            $url = 'https://api.smartr.in/api/v1/add-order/';
            $token = Integration::generatetoken_smartr();
            if ($token != '') {
                return Integration::posthit_smartr_Token($url, $array_data, $token);
            }
            else {
                return '';
            }
        }
        else {
            return '';
        }
    }

    public static function generate_shiplabel_smartr($array_data, $type)
    {
        if ($array_data != '') {
            if ($type == 'surface') {
                $url = 'https://api.smartr.in/api/v1/shippingLabel/?awbs=' . $array_data;
            }
            else {
                $url = 'https://api.smartr.in/api/v1/generateLabel/?awbs=' . $array_data;
            }
            $token = Integration::generatetoken_smartr();
            if ($token != '') {
                return Integration::hitget_smartr($url, $token);
            }
            else {
                return '';
            }

        }
        else {
            return '';
        }
    }

    public static function cancelshipment_smartr($array_data, $type)
    {
        if ($array_data != '') {
            if ($type == 'cod') {
                $url = 'https://api.smartr.in/api/v1/cancellation/';
            }
            else {
                $url = 'https://api.smartr.in/api/v1/updateCancel/';
            }
            $token = Integration::generatetoken_smartr();
            if ($token != '') {
                return Integration::posthit_smartr_Token($url, $array_data, $token);
            }
            else {
                return '';
            }
        }
        else {
            return '';
        }
    }

    public static function track_smartr($tracking_info)
    {
        if ($tracking_info) {
            $url = 'https://api.smartr.in/api/v1/tracking/?awb=' . $tracking_info;
            $token = Integration::generatetoken_smartr();
            if ($token != '') {
                return Integration::hitget_smartr($url, $token);
            }
            else {
                return '';
            }
        }
        else {
            return '';
        }

    }

    // for smartr end

    public static function hitgetcurl_distance($origin, $destination)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $origin . "&destinations=" . $destination . "&key=AIzaSyCwiCtnKcqvwdyMKTVV5Q8_HIq2YBppXOc";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    // Blue Dart APIGEE Start

    public static function get_bluedart_apigee_token($client_id, $client_secret, $server_mode = 1)
    {
        $baseUrl = ($server_mode == 1) ? 'https://apigateway.bluedart.com' : 'https://apigateway-sandbox.bluedart.com';
        $cacheKey = 'bluedart_token_' . md5($client_id);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $baseUrl . '/in/transportation/token/v1/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'ClientID: ' . $client_id,
                'clientSecret: ' . $client_secret,
                'accept: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);

        if (isset($data['JWTToken'])) {
            Cache::put($cacheKey, $data['JWTToken'], 3500); // Valid for 1 hour, stash for 58 mins
            return $data['JWTToken'];
        }

        return null;
    }

    public static function hitpostcurl_bluedart_apigee($url, $array_data, $token)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $array_data,
            CURLOPT_HTTPHEADER => array(
                'JWTToken: ' . $token,
                'Content-Type: application/json',
                'accept: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function hitgetcurl_bluedart_apigee($url, $token)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'JWTToken: ' . $token,
                'accept: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function chk_serviceable_pincode_bluedart_apigee($pincode, $integration)
    {
        $token = self::get_bluedart_apigee_token($integration->bd_client_id, $integration->bd_client_secret, $integration->server);
        if (!$token) return null;

        $baseUrl = ($integration->server == 1) ? 'https://apigateway.bluedart.com' : 'https://apigateway-sandbox.bluedart.com';
        $url = $baseUrl . '/in/transportation/finder/v1/GetServicesforPincode';

        $data = json_encode([
            "pinCode" => $pincode,
            "profile" => [
                "LoginID" => $integration->login_id,
                "LicenceKey" => $integration->licence_key,
                "Api_type" => "S"
            ]
        ]);

        return self::hitpostcurl_bluedart_apigee($url, $data, $token);
    }

    public static function shipment_bluedart_apigee($data, $integration)
    {
        $token = self::get_bluedart_apigee_token($integration->bd_client_id, $integration->bd_client_secret, $integration->server);
        if (!$token) return null;

        $baseUrl = ($integration->server == 1) ? 'https://apigateway.bluedart.com' : 'https://apigateway-sandbox.bluedart.com';
        $url = $baseUrl . '/in/transportation/waybill/v1/GenerateWayBill';

        return self::hitpostcurl_bluedart_apigee($url, $data, $token);
    }

    public static function track_bluedart_apigee($tracking_info, $integration)
    {
        $token = self::get_bluedart_apigee_token($integration->bd_client_id, $integration->bd_client_secret, $integration->server);
        if (!$token) return null;

        $baseUrl = ($integration->server == 1) ? 'https://apigateway.bluedart.com' : 'https://apigateway-sandbox.bluedart.com';
        
        // Updated URL to include required handler and action parameters to prevent License Mismatch
        $url = $baseUrl . "/in/transportation/tracking/v1/shipment?handler=tnt&loginid={$integration->login_id}&numbers={$tracking_info}&format=json&lickey={$integration->tracking_licence_key}&scan=1&action=custawbquery&verno=1&awb=awb";

        return self::hitgetcurl_bluedart_apigee($url, $token);
    }

    public static function cancelshipment_bluedart_apigee($tracking_info, $integration)
    {
        $token = self::get_bluedart_apigee_token($integration->bd_client_id, $integration->bd_client_secret, $integration->server);
        if (!$token) return null;

        $baseUrl = ($integration->server == 1) ? 'https://apigateway.bluedart.com' : 'https://apigateway-sandbox.bluedart.com';
        $url = $baseUrl . '/in/transportation/waybill/v1/CancelWaybill';

        $data = json_encode([
            "Request" => [
                "AWBNo" => $tracking_info
            ],
            "Profile" => [
                "LoginID" => $integration->login_id,
                "LicenceKey" => $integration->licence_key,
                "Api_type" => "S"
            ]
        ]);

        return self::hitpostcurl_bluedart_apigee($url, $data, $token);
    }

    public static function update_ewaybill_apigee($data, $integration)
    {
        $token = self::get_bluedart_apigee_token($integration->bd_client_id, $integration->bd_client_secret, $integration->server);
        if (!$token) return null;

        $baseUrl = ($integration->server == 1) ? 'https://apigateway.bluedart.com' : 'https://apigateway-sandbox.bluedart.com';
        $url = $baseUrl . '/in/transportation/waybill/v1/UpdateEwayBill';

        $payload = json_encode([
            "ERequest" => [
                "InvoiceDate"   => $data['InvoiceDate'] ?? '',
                "InvoiceNumber" => $data['InvoiceNumber'] ?? '',
                "SellerGSTNo"   => $data['SellerGSTNo'] ?? '',
                "Waybillnumber" => $data['Waybillnumber'] ?? '',
                "eWaybillDate"  => $data['eWaybillDate'] ?? '',
                "eWaybillNumber"=> $data['eWaybillNumber'] ?? ''
            ],
            "Profile" => [
                "LoginID"    => $integration->login_id,
                "LicenceKey" => $integration->licence_key,
                "Api_type"   => "S"
            ]
        ]);

        return self::hitpostcurl_bluedart_apigee($url, $payload, $token);
    }

    public static function import_data_apigee($data, $integration)
    {
        $token = self::get_bluedart_apigee_token($integration->bd_client_id, $integration->bd_client_secret, $integration->server);
        if (!$token) return null;

        $baseUrl = ($integration->server == 1) ? 'https://apigateway.bluedart.com' : 'https://apigateway-sandbox.bluedart.com';
        $url = $baseUrl . '/in/transportation/waybill/v1/ImportData';

        // $data should be an array of order requests.
        $payload = json_encode([
            "Request" => $data,
            "Profile" => [
                "LoginID"    => $integration->login_id,
                "LicenceKey" => $integration->licence_key,
                "Api_type"   => "S"
            ]
        ]);

        return self::hitpostcurl_bluedart_apigee($url, $payload, $token);
    }

    // Blue Dart APIGEE End
    
    public static function get_expected_tat_delhivery($origin_pin, $destination_pin, $mot, $pdt, $expected_pickup_date, $expected_pd)
    {
        $token = 'f5ea26499f383776cc79180355c8b9b10deef3a5';
        $url = "https://track.delhivery.com/api/dc/expected_tat?origin_pin=" . $origin_pin . "&destination_pin=" . $destination_pin . "&mot=" . $mot . "&pdt=" . $pdt . "&expected_pickup_date=" . urlencode($expected_pickup_date);
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Token ' . $token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }   
}
