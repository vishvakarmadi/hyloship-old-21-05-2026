<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Admin\Blitztoken;

class Integration_more extends Model
{
    
    
    public static function puthit_ekart($url,$array_data,$token,$type='PUT'){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://app.elite.ekartlogistics.in/api/v1/package/create',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $type,
          CURLOPT_POSTFIELDS =>$array_data,
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token,
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


           

            return $response;

    }
    public static function deletehit_ekart($url,$token){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'DELETE',
          CURLOPT_HTTPHEADER => array(
             'Authorization: Bearer '.$token,
           ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    public static function pushhit_ekart($url,$array_data,$token) {
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
          CURLOPT_POSTFIELDS =>$array_data,
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token,
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    public static function gethit_ekart($url) {
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
    public static function generatetoken_ekart(){
        $curl = curl_init();
        $ekartClientId = env('EKART_CLIENT_ID', 'YOUR_EKART_CLIENT_ID');
        $ekartUsername = env('EKART_USERNAME', 'YOUR_EKART_USERNAME');
        $ekartPassword = env('EKART_PASSWORD', 'YOUR_EKART_PASSWORD');

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://app.elite.ekartlogistics.in/integrations/v2/auth/token/' . $ekartClientId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode([
            'username' => $ekartUsername,
            'password' => $ekartPassword
        ]),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response,true);
        if(isset($response['access_token'])){
            return $response['access_token'];
        }else{
            return '';
        }
    }

    public static function chk_serviceable_pincode_ekart($pincode){
        if($pincode !=''){
            $token = Integration_more::generatetoken_ekart();
            if($token !=''){
                $url = 'https://api.ekartlogistics.com/v1/offerings';
//                return Integration_more::posthit_ekart($url,$pincode,$token);
            }else{
                return '';
            }
        }else{
            return '';
        }
    }
    
    public static function shipment_ekart($array_data,$pck_ln) {
        if($array_data !=''){
            $token = Integration_more::generatetoken_ekart();
//             print_R($array_data);die;
             if($token !=''){
                 $url ="https://app.elite.ekartlogistics.in/api/v1/package/create";
                 $res_ekart = Integration_more::puthit_ekart($url,$array_data,$token);
                 $res_ekartjon = json_decode($res_ekart,true);
//                 echo '<pre>';print_R($res_ekartjon);d
//                 echo '->>>';print_R(strpos($res_ekartjon['description'],'pickup_location does not exist or is dele'));
                if(isset($res_ekartjon['description']) && strpos($res_ekartjon['message'],'SWIFT_RESOURCE_NOT_FOUND_EXCEPTION') !== false){
//                    echo 'hi';
                        $add_res = Integration_more::pushhit_ekart('https://app.elite.ekartlogistics.in/api/v2/address',$pck_ln,$token);
                        $res_ekart = Integration_more::puthit_ekart($url,$array_data,$token);
//                        echo $add_res;die;
                        return $res_ekart;
                    }else{
                        return $res_ekart;
                    }
//                 $ekrart_res = 
             }else{
                  return '';
             }
        }else{
            return '';
        }
    }
        


    public static function shipment_smartr($array_data){
        if($array_data !=''){
            $token = Integration_more::generatetoken_ekart();
//            echo '<pre>';print_R($token);die;
            if($token !=''){
                $url = 'https://api.ekartlogistics.com/v2/shipments/create';
                        https://app.elite.ekartlogistics.in/api/v1/package/create
                return Integration_more::posthit_ekart($url,$array_data,$token);
//                echo '<pre>';print_R(Integration_more::posthit_ekart($url,$array_data,$token));die;
            }else{
                return '';
            }
        }else{
            return '';
        }
    }
    

    public static function cancelshipment_ekart($awb){
        if($awb !=''){
            $token = Integration_more::generatetoken_ekart();
            if($token !=''){
                
                    $url ="https://app.elite.ekartlogistics.in/api/v1/package/cancel?tracking_id=".$awb;
                return Integration_more::deletehit_ekart($url,$token);
            }else{
                return '';
            }
        }else{
            return '';
        }
    }
    
     public static function track_Ekart($array_data){
        if($array_data !=''){
//            echo '<pre>';print_R($array_data);die;
//            $token = Integration_more::generatetoken_ekart();
//            if($token !=''){
                $url = 'https://app.elite.ekartlogistics.in/api/v1/track/'.$array_data;
                return Integration_more::gethit_ekart($url);
//            }else{
//                return '';
//            }
        }else{
            return '';
        }
    }
    
    public static function gethit_shadowfax($url){
        $token = env('SHADOWFAX_TOKEN', 'YOUR_SHADOWFAX_TOKEN');

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
            'Authorization: Token '.$token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public static function posthit_shadowfax($url,$array_data){
        $token = env('SHADOWFAX_TOKEN', 'YOUR_SHADOWFAX_TOKEN');

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
        CURLOPT_POSTFIELDS =>$array_data,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Token '.$token,
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;


    }

    public static function chk_serviceable_pincode_shadowfax($drop,$pickup){
        $url = "https://dale.shadowfax.in/api/v1/serviceability/?pickup_pincode=".$pickup."&delivery_pincode=$drop";
        return Integration_more::gethit_shadowfax($url);
    }

    public static function get_awb_number_shadowfax($type,$arraydata){
        if($type =='forward'){
            $url = "https://dale.shadowfax.in/api/v3/clients/generate_marketplace_awb/";
        }else{
            $url = "https://dale.shadowfax.in/api/v3/clients/orders/generate_awb/";
        }
        return Integration_more::posthit_shadowfax($url,$arraydata);
    }

    public static function shipment_shadowfax($type,$arraydata){
        if($type =='forward'){
            $url = "https://dale.shadowfax.in/api/v3/clients/orders/";
        }else{
            $url = "https://dale.shadowfax.in/api/v3/clients/requests";
        }
        return Integration_more::posthit_shadowfax($url,$arraydata);
    }
    public static function cancelshipment_shadowfax($type,$arraydata){
        if($type =='forward'){
            $url = "https://dale.shadowfax.in/api/v3/clients/orders/cancel/";
        }else{
            $url = "https://dale.shadowfax.in/api/v2/clients/requests/mark_cancel";
        }
        return Integration_more::posthit_shadowfax($url,$arraydata);
    }
    
    public static function track_shadowfax($tracking_info,$type){
        if($type =='forward'){
            $url = "https://dale.shadowfax.in/api/v4/clients/orders/".$tracking_info."/track/";
        }else{
            $url = "https://dale.shadowfax.in/api/v4/clients/requests/".$tracking_info;
        }
//        echo $url;die;
        return Integration_more::gethit_shadowfax($url);
    }
  
  	public static function generate_token_ats(){
        $curl = curl_init();
        $refreshToken = env('AMAZON_REFRESH_TOKEN', 'YOUR_AMAZON_REFRESH_TOKEN');
        $clientId = env('AMAZON_CLIENT_ID', 'YOUR_AMAZON_CLIENT_ID');
        $clientSecret = env('AMAZON_CLIENT_SECRET', 'YOUR_AMAZON_CLIENT_SECRET');

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.amazon.com/auth/o2/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'grant_type=refresh_token&refresh_token=' . urlencode($refreshToken) . '&client_id=' . urlencode($clientId) . '&client_secret=' . urlencode($clientSecret),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public static function posthit_ats($at,$arraydata,$url){
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
        CURLOPT_POSTFIELDS =>$arraydata,
        CURLOPT_HTTPHEADER => array(
            'x-amz-access-token: '.$at,
            'x-amzn-shipping-business-id: AmazonShipping_IN',
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public static function puthit_ats($at,$url){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_HTTPHEADER => array(
            'x-amz-access-token: '.$at,
            'x-amzn-shipping-business-id: AmazonShipping_IN'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    public static function shipment_ats($arraydata){
        if($arraydata !=''){
            $token_array = Integration_more::generate_token_ats();
            // $token_array = '{"access_token":"Atza|IwEBINy4VU09mFuCnBGZKAK3zvJOtgkIIV2Xnb4xZtURulTCOinMnLc-zyGXVZYVMOm5cIfw2tLh0nuvk29g100eVXOEVjR48tYJ32vC96MDfmZwHsd0-o-RffZ4ftZwocdANKBE9P3K8pTYbge3xnldgj_rECW4q-F0r9QMvudqOOricTtH3ImPewiY7LeXw8g7WeQreHGu9d_3OXyTTjgvKUL3-828UUDDyr8z36uxsBqoEwR8D4uMgIQGX7usx-3ZWMJa6zteQHIht59Oq7bmUowzAB3HLD8gviUtTQ9mn6GD94lmnflr5y6RZ1NfArxs6LbCMgrfWDUWs6CeIImBH6Z8","refresh_token":"Atzr|IwEBIFORYs1R2t8gVf7AgYPjSK6k75Yj13R475X6ffkoUXzc4dvQLyJWWVYNzsgkX3MzpAXy46vGb5wzUmZPSu2OA1urTFugXw1Tlr8nLzvCAVolWpYuhN5QMy0rFWcxj7rMyrl1rUfhEv_Y-WTLj5qsPG0943o3DyMHdkh61L287MxOPA8nsr_m7RmMlC4CEOmcw6UCSptfFMKoJhw1ZnNl1BjcBwQxrh5lk8tSL3W-paSiuBHLoaFfgn1S3p612kYqPMrtM8jCWkxxuHTTca31M3FSseSXKA42MqtgpISRlLvYNwOSERf0-li3tNOVYU0lUSM","token_type":"bearer","expires_in":3600}';
            $token_data = json_decode($token_array,true);
            if(isset($token_data['access_token']) && $token_data['access_token'] !=''){
                $url = 'https://sellingpartnerapi-eu.amazon.com/shipping/v2/oneClickShipment';
                return Integration_more::posthit_ats($token_data['access_token'],$arraydata,$url);
            }else{
                return ''; 
            }
        }else{
            return '';
        }
    }
    public static function cancelshipment_ats($shipment_id){
        if($shipment_id !=''){
            $token_array = Integration_more::generate_token_ats();
            $token_data = json_decode($token_array,true);
            if(isset($token_data['access_token']) && $token_data['access_token'] !=''){
                $url = 'https://sellingpartnerapi-eu.amazon.com/shipping/v2/shipments/'.$shipment_id.'/cancel';
                return Integration_more::puthit_ats($token_data['access_token'],$url);
            }
        }else{
            return '';
        }
        
    }
  
  	public static function gethit_ats($at,$url){
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
            'x-amz-access-token: '.$at,
            'x-amzn-shipping-business-id: AmazonShipping_IN'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    public static function track_ats($tracking_info){
        if($tracking_info !=''){
            $token_array = Integration_more::generate_token_ats();
            $token_data = json_decode($token_array,true);
            if(isset($token_data['access_token']) && $token_data['access_token'] !=''){
                $url = 'https://sellingpartnerapi-eu.amazon.com/shipping/v2/tracking?trackingId='.$tracking_info.'&carrierId=ATS';
                return Integration_more::gethit_ats($token_data['access_token'],$url);
            }
        }else{
            return '';
        }
    }
  
  	public static function generate_shiplabelawb($shipingid,$orderid){
        if($shipingid !='' && $orderid !=''){
            $token_array = Integration_more::generate_token_ats();
            $token_data = json_decode($token_array,true);
            if(isset($token_data['access_token']) && $token_data['access_token'] !=''){
                $url = 'https://sellingpartnerapi-eu.amazon.com/shipping/v2/shipments/'.$shipingid.'/documents?packageClientReferenceId='.$orderid;
                return Integration_more::gethit_ats($token_data['access_token'],$url);
            }
        }else{
            return '';
        }
    }
    
    public static function shipment_token(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://core.optnship.com/api/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "mobile": "",
        "password": ""
        }',
        CURLOPT_HTTPHEADER => array(
            'accept: application/json',
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
	
  	public static function shipment_puthit($url,$token){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS =>'{
        "update_type": "cancel"
        }',
        CURLOPT_HTTPHEADER => array(
            'accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer '.$token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
  
    public static function posthit_optnship($url,$token,$data){
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
        CURLOPT_POSTFIELDS =>$data,
        CURLOPT_HTTPHEADER => array(
            'accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer '.$token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public static function shipment_bludartold($ship_data){
        if($ship_data !=''){
            $token_array = Integration_more::shipment_token();
            $token_array = json_decode($token_array,true);
            if(isset($token_array['api_token']) && $token_array['api_token'] !=''){
                $url = 'https://core.optnship.com/api/booking';
                return Integration_more::posthit_optnship($url,$token_array['api_token'],$ship_data);
            }else{
                return '';
            }
        }else{
            return '';
        }
    }
    public static function ware_bludart($ware_data){
        if($ware_data !=''){
            $token_array = Integration_more::shipment_token();
            $token_array = json_decode($token_array,true);
            if(isset($token_array['api_token']) && $token_array['api_token'] !=''){
                $url = 'https://core.optnship.com/api/assigncourier';
                return Integration_more::posthit_optnship($url,$token_array['api_token'],$ware_data);
            }else{
                return '';
            }
        }else{
            return '';
        }
    }

    public static function track_bluedartold($track_data){
        if($track_data !=''){
            $token_array = Integration_more::shipment_token();
            $token_array = json_decode($token_array,true);
            if(isset($token_array['api_token']) && $token_array['api_token'] !=''){
                $url = 'https://core.optnship.com/api/tracking/WEB';
                return Integration_more::posthit_optnship($url,$token_array['api_token'],$track_data);
            }else{
                return '';
            }
        }else{
            return '';
        }
    }

  public static function cancel_bluedartold($shipmeny_id){
        if($shipmeny_id =='' || $shipmeny_id == null || $shipmeny_id ==0){
            return '';
        }else{
            $token_array = Integration_more::shipment_token();
            $token_array = json_decode($token_array,true);
            if(isset($token_array['api_token']) && $token_array['api_token'] !=''){
                $url = 'https://core.optnship.com/api/orders/'.$shipmeny_id;
                return Integration_more::shipment_puthit($url,$token_array['api_token']);
            }else{
                return '';
            }
        }
    }
    
    public static function shipment_gethit($url,$token){
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
            'accept: */*',
            'Authorization: Bearer '.$token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    public static function checkservicebluedart($pickup,$drop,$length,$breadth,$height,$weight){
            $token_array = Integration_more::shipment_token();
            $token_array = json_decode($token_array,true);
            if(isset($token_array['api_token']) && $token_array['api_token'] !=''){
                $url = 'https://core.optnship.com/api/servicablecouriers?pickup='.$pickup.'&destination='.$drop.'&length='.$length.'&breadth='.$breadth.'&height='.$height.'&weight='.$weight;
                return Integration_more::shipment_gethit($url,$token_array['api_token']);
            }else{
                return '';
            }
    }
// -----------------------------------------------------------------------------------------

    public static function posthit_bd($url,$data){
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
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            'access-token: ' . env('PARCELX_ACCESS_TOKEN', 'YOUR_PARCELX_ACCESS_TOKEN'),
            'Content-Type: application/json',
            'Cookie: px_session=ara42oont7pfe3j9eqpjngsq5qub9d8q'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return  $response;

    }
    public static function gethit_bd($url){
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
            'access-token: ' . env('PARCELX_ACCESS_TOKEN', 'YOUR_PARCELX_ACCESS_TOKEN'),
            'Content-Type: application/json',
            'Cookie: px_session=ara42oont7pfe3j9eqpjngsq5qub9d8q'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return  $response;

    }
    /**
     * Returns a Blue Dart integration object populated from .env (primary)
     * or from the DB record (optional override).
     */
    private static function get_bluedart_integration($user_id = null) {
        // Try DB first (user-specific or global)
        $integration = null;
        if ($user_id) {
            $integration = Integration::where('courier_id', 3)->where('user_id', $user_id)->first();
        }
        if (!$integration) {
            $integration = Integration::where('courier_id', 3)->first();
        }

        // Build a plain object from .env with DB as override
        $obj = new \stdClass();
        $obj->bd_client_id     = ($integration && $integration->bd_client_id)     ? $integration->bd_client_id     : env('BLUEDART_CLIENT_ID', '');
        $obj->bd_client_secret = ($integration && $integration->bd_client_secret) ? $integration->bd_client_secret : env('BLUEDART_CLIENT_SECRET', '');
        $obj->login_id         = ($integration && $integration->login_id)         ? $integration->login_id         : env('BLUEDART_LOGIN_ID', '');
        $obj->licence_key      = ($integration && $integration->licence_key)      ? $integration->licence_key      : env('BLUEDART_LICENCE_KEY', '');
        
        // Tracking often requires a dedicated licence key on Blue Dart
        $obj->tracking_licence_key = env('BLUEDART_TRACKING_LICENCE_KEY', $obj->licence_key);
        
        $obj->pre_paid         = ($integration && $integration->pre_paid)         ? $integration->pre_paid         : env('BLUEDART_CUSTOMER_CODE', '');
        $obj->cod              = ($integration && $integration->cod)              ? $integration->cod              : env('BLUEDART_CUSTOMER_CODE', '');
        $obj->origin_area      = ($integration && $integration->origin_area)      ? $integration->origin_area      : env('BLUEDART_ORIGIN_AREA', '');
        $obj->server           = ($integration && $integration->server)           ? $integration->server           : (str_contains(env('BLUEDART_API_BASE_URL', ''), 'sandbox') ? '0' : '1');
        $obj->packtype         = ($integration && $integration->packtype)         ? $integration->packtype         : '';
        $obj->user_id          = $user_id ?? ($integration->user_id ?? 1);
        return $obj;
    }

    public static function warehouse_bludart($warehusedata){
        $integration = self::get_bluedart_integration();
        if ($integration->login_id != '') {
            // APIGEE: warehouse pre-registration not needed — return bypass token
            return json_encode(['status' => true, 'data' => ['pick_address_id' => 'APIGEE']]);
        }
        $url = 'https://app.parcelx.in/api/v1/create_warehouse';
        return Integration_more::posthit_bd($url,$warehusedata);
    }

    public static function shipment_bludart($order_data, $user_id = null){
        $data = json_decode($order_data, true);
        $integration = self::get_bluedart_integration($user_id);

        if ($integration->login_id != '') {
            if ($integration->bd_client_id == '') {
                return json_encode(['status' => false, 'responsemsg' => ['Blue Dart APIGEE Client ID is missing. Please add BLUEDART_CLIENT_ID to your .env file.']]);
            }
            $apigee_payload = self::prepare_bluedart_apigee_payload($data, $integration);
            $response = Integration::shipment_bluedart_apigee($apigee_payload, $integration);

            $res_data = json_decode($response, true);
            if (isset($res_data['GenerateWayBillResult']['AWBNo']) && $res_data['GenerateWayBillResult']['AWBNo'] != '') {
                $awb = $res_data['GenerateWayBillResult']['AWBNo'];
                return json_encode([
                    'status' => true,
                    'data' => [
                        'awb_number' => $awb,
                        'label_binary' => $res_data['GenerateWayBillResult']['AWBPrintContent'] ?? '',
                        'TokenNumber' => $res_data['GenerateWayBillResult']['TokenNumber'] ?? ''
                    ]
                ]);
            } else {
                $error = 'Unknown Blue Dart Error';
                if (isset($res_data['GenerateWayBillResult']['Status']) && is_array($res_data['GenerateWayBillResult']['Status'])) {
                    $status_bits = $res_data['GenerateWayBillResult']['Status'];
                    $error_parts = [];
                    foreach ($status_bits as $bit) {
                        if (isset($bit['StatusInformation']) && $bit['StatusInformation'] != '') {
                            $error_parts[] = $bit['StatusInformation'];
                        }
                    }
                    if (!empty($error_parts)) {
                        $error = implode(' | ', array_unique($error_parts));
                    }
                } elseif (isset($res_data['GenerateWayBillResult']['Status']['Description'])) {
                    $error = $res_data['GenerateWayBillResult']['Status']['Description'];
                } elseif (isset($res_data['error-response'][0]['Status'][0]['StatusInformation'])) {
                    $error = $res_data['error-response'][0]['Status'][0]['StatusInformation'];
                } elseif (isset($res_data['message'])) {
                    $error = $res_data['message'];
                } elseif (isset($res_data['fault']['faultstring'])) {
                    $error = $res_data['fault']['faultstring'];
                } elseif ($response === null) {
                    $error = 'No response from Blue Dart API (check Client ID/Secret)';
                }
                return json_encode(['status' => false, 'responsemsg' => [$error]]);
            }
        }

        $url = 'https://app.parcelx.in/api/v3/order/create_order';
        return Integration_more::posthit_bd($url,$order_data);
    }

    public static function cancel_bluedart($order_data, $user_id = null){
        // OrderController sends json_encode(['awb' => '...']), so we must decode it.
        $decoded = json_decode($order_data, true);
        $awb = is_array($decoded) && isset($decoded['awb']) ? $decoded['awb'] : $order_data;

        $integration = self::get_bluedart_integration($user_id);
        if ($integration->login_id != '') {
            $response = Integration::cancelshipment_bluedart_apigee($awb, $integration);
            $res_data = json_decode($response, true);
            if (isset($res_data['CancelWaybillResult']['Status'][0]['StatusCode']) && strtolower($res_data['CancelWaybillResult']['Status'][0]['StatusCode']) == 'valid') {
                return json_encode(['status' => true, 'responsemsg' => 'Success']);
            } else {
                $error = $res_data['CancelWaybillResult']['Status'][0]['StatusInformation'] ?? 'Unknown Error';
                return json_encode(['status' => false, 'responsemsg' => $error]);
            }
        }
        $url = 'https://app.parcelx.in/api/v1/order/cancel_order';
        return Integration_more::posthit_bd($url,$awb);
    }

    public static function track_bluedart($awb, $user_id = null){
        $integration = self::get_bluedart_integration($user_id);
        if ($integration->login_id != '') {
            return Integration::track_bluedart_apigee($awb, $integration);
        }
        $url = 'https://app.parcelx.in/api/v1/track_order?awb='.$awb;
        return Integration_more::gethit_bd($url);
    }

    private static function prepare_bluedart_apigee_payload($data, $integration) {
        // Map data to strict Blue Dart APIGEE structure
        $is_cod = (isset($data['payment_mode']) && strtolower(strip_tags($data['payment_mode'])) == 'cod');
        $product_code = 'A';
        
        // Fetch warehouse details
        $sender = [
            "CustomerCode" => $is_cod ? ($integration->cod ?: '') : ($integration->pre_paid ?: ''),
            "OriginArea" => $integration->origin_area ?: '',
            "Sender" => "",
            "CustomerName" => "",
            "CustomerAddress1" => "",
            "CustomerAddress2" => "",
            "CustomerAddress3" => "",
            "CustomerPincode" => "", 
            "CustomerMobile" => "",
            "IsToPayCustomer" => false
        ];
        
        // Look up warehouse by actual ID — prioritize internal shipper_warehouse_id
        $wh_id = $data['shipper_warehouse_id'] ?? ($data['pick_address_id'] !== 'APIGEE' ? $data['pick_address_id'] : null);

        if ($wh_id) {
            $wh = \DB::table('warehouses')->where('id', $wh_id)->first();
        } else {
            // Fallback: try to find any warehouse for this company/user
            $wh = \DB::table('warehouses')
                ->where('company_id', Auth::guard('admin')->user()->company_id ?? 0)
                ->first();
        }

        if ($wh) {
                $sender["Sender"]           = $wh->name;
                $sender["CustomerName"]     = $wh->contact_name ?: $wh->name;
                $sender["CustomerAddress1"] = substr($wh->address, 0, 30);
                $sender["CustomerAddress2"] = substr($wh->address_2 ?: '', 0, 30);
                $sender["CustomerAddress3"] = substr($wh->city ?: '', 0, 30);
                
                // CRITICAL FIX FOR: "Area and Service center does not belong to same region."
                // Blue Dart APIGEE strictly requires the Shipper's CustomerPincode to belong 
                // to the registered OriginArea regardless of physical warehouse location.
                $origin_pincode_map = [
                    'DEL' => '110075',
                    'BOM' => '400001',
                    'MAA' => '600001',
                    'BLR' => '560001',
                    'CCU' => '700001',
                    'HYD' => '500001',
                    'PNQ' => '411001',
                    'AMD' => '380001'
                ];
                $sender["CustomerPincode"]  = (string)$wh->pincode ?: ($origin_pincode_map[$integration->origin_area] ?? '');
                $sender["CustomerMobile"]   = $wh->phone;
        }

        $services = [
            "ActualWeight"       => (float)($data['shipment_weight'][0] ?? 0.5),
            "CollectableAmount"  => $is_cod ? (float)($data['cod_amount'] ?? 0) : 0,
            "TotalCashPaytoCustomer" => $is_cod ? (string)(float)($data['cod_amount'] ?? 0) : "0",
            "Commodity" => [
                "CommodityDetail1" => substr(strip_tags($data['products'][0]['product_name'] ?? "Items"), 0, 30),
                "CommodityDetail2" => "",
                "CommodityDetail3" => ""
            ],
            "CreditReferenceNo" => (string)($data['client_order_id'] ?? time()),
            "DeclaredValue"     => (float)($data['order_amount'] ?? 0),
            "Dimensions" => [
                [
                    "Breadth" => (int)($data['shipment_width'][0] ?? 10),
                    "Count"   => 1,
                    "Height"  => (int)($data['shipment_height'][0] ?? 10),
                    "Length"  => (int)($data['shipment_length'][0] ?? 10)
                ]
            ],
            "InvoiceNo"          => (string)($data['client_order_id'] ?? time()),
            "ItemCount"          => max(1, count($data['products'] ?? [])),
            "PieceCount"         => max(1, count($data['products'] ?? [])),
            "Pieces"             => max(1, count($data['products'] ?? [])),
            "PickupDate"         => "/Date(" . (time() * 1000) . ")/",
            "PickupTime"         => "1600",
            "ProductCode"        => $product_code,
            "ProductType"        => 0,
            "SubProductCode"     => $is_cod ? "C" : "P",
            "RegisterPickup"     => true,
            "PDFOutputNotRequired" => false,
        ];

        if (isset($data['express_type']) && strtolower($data['express_type']) == 'surface') {
            $services['PackType'] = $integration->packtype ?: 'L';
        }

        $payload = [
            "Request" => [
                "Consignee" => [
                    "ConsigneeName" => substr($data['consignee_name'] ?? 'Consignee', 0, 30),
                    "ConsigneeAddress1" => substr($data['consignee_address1'] ?? '', 0, 30),
                    "ConsigneeAddress2" => substr($data['consignee_address2'] ?? '', 0, 30),
                    "ConsigneeAddress3" => substr($data['consignee_city'] ?? '', 0, 30),
                    "ConsigneeAddressType" => "R",
                    "ConsigneeAttention" => substr($data['consignee_name'] ?? 'Consignee', 0, 30),
                    "ConsigneePincode" => $data['consignee_pincode'] ?? '',
                    "ConsigneeMobile" => $data['consignee_mobile'] ?? '',
                    "ConsigneeTelephone" => "",
                    "ConsigneeEmailID" => $data['consignee_emailid'] ?? ''
                ],
                "Services" => array_merge($services, [
                    "OTPBasedDelivery" => 0,
                    "OTPCode" => "",
                    "itemdtl" => [],
                    "noOfDCGiven" => 0
                ]),
                "Returnadds" => [
                    "ReturnAddress1"  => isset($sender["CustomerAddress1"]) ? $sender["CustomerAddress1"] : "",
                    "ReturnAddress2"  => isset($sender["CustomerAddress2"]) ? $sender["CustomerAddress2"] : "",
                    "ReturnAddress3"  => isset($sender["CustomerAddress3"]) ? $sender["CustomerAddress3"] : "",
                    "ReturnContact"   => $sender["CustomerName"] ?? "",
                    "ReturnMobile"    => $sender["CustomerMobile"] ?? "",
                    "ReturnPincode"   => $sender["CustomerPincode"] ?? "",
                    "ReturnEmailID"   => "",
                    "ReturnTelephone" => ""
                ],
                "Shipper" => array_merge($sender, [
                    "VendorCode" => ""
                ])
            ],
            "Profile" => [
                "Api_type"   => "S",
                "LicenceKey" => $integration->licence_key,
                "LoginID"    => $integration->login_id,
                "Version"    => "1.3"
            ]
        ];
        
        // dd($payload);

        $json_payload = json_encode($payload);
        \Log::info('Blue Dart APIGEE Outgoing Payload: ' . $json_payload);
        return $json_payload;
    }
    
    public static function posthit_blitz($url, $array_data, $token) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => false, // for local testing only
            CURLOPT_SSL_VERIFYHOST => false, // for local testing only
            CURLOPT_POSTFIELDS => $array_data,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.$token,
                'Content-Type: application/json',
                'Accept: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        if ($response === false) {
            throw new \Exception('Curl error: ' . curl_error($curl));
        }

        curl_close($curl);
        return $response;
    }


    public static function getBlitzToken()
    {
        // Fetch latest token
        $tokenRow = Blitztoken::latest()->first();

        // If token exists and is less than 23 hours old → reuse
        if ($tokenRow && $tokenRow->created_at->gt(Carbon::now()->subHours(23))) {
            return $tokenRow->token;
        }

        // Otherwise generate new token
        $username = 'sGMugtYxG7Df';
        $password = '~Zp!FspWT5?Kr';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://oyvm2iv4xj.execute-api.ap-south-1.amazonaws.com/v1/auth',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POSTFIELDS =>'{
                "request_type": "authenticate",
                "payload": {
                    "username": "sGMugtYxG7Df",
                    "password": "~Zp!FspWT5?Kr"
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            ));

        $response = curl_exec($curl);
        curl_close($curl);
        // echo '<pre>';print_R($response);die;
        $response = json_decode($response, true);

        if (!isset($response['id_token'])) {
            throw new \Exception('Unable to generate Blitz token');
        }

        $idToken = $response['id_token'];

        // Delete old tokens
        Blitztoken::truncate();

        // Store new token
        Blitztoken::create([
            'token' => $idToken
        ]);

        return $idToken;
    }
    
    public static function shipment_blitz($order_data){
        $url = 'https://xv24xrhpxa.execute-api.ap-south-1.amazonaws.com/v1/waybill/';
        $token = Integration_more::getblitztoken();
        return Integration_more::posthit_blitz($url,$order_data,$token);
    }

    public static function cancelblitz($awb){
        $url ="https://oyvm2iv4xj.execute-api.ap-south-1.amazonaws.com/v1/orin/api/cancel/";
        $order_data_attay[] = array(
                    'field' =>"awb",
                    'value' =>$awb,
                    'cancel_reason' =>"",
                    'cancelled_by' =>"customer",
        );
        $token = Integration_more::getblitztoken();
        return Integration_more::posthit_blitz($url,json_encode($order_data_attay),$token);
    }
    
    public static function getsttausblitz($awb){
//        $awb='GS1227014821';
        $url ="https://oyvm2iv4xj.execute-api.ap-south-1.amazonaws.com/v1/tracking";
        $order_data_attay = array(
                    'field' =>"shipment",
                    'value' =>$awb
        );
//        echo json_encode($order_data_attay);die;
        $token = Integration_more::getblitztoken();
        return Integration_more::posthit_blitz($url,json_encode($order_data_attay),$token);
    }
    
    
//    =====================BLITZ END=========================================
    


//    =====================PCKnDEL=========================================
    
    
    public static function getpckndeltoken(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.pikndel.com/backoffice/api/account/login',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{"Control": {"RequestId": "baab37e9-49d1-4cb6-82c9-43a13f0532ce","Source": 3,"RequestTime": 1578469225,"Version": "1.0"},"Data": {"Username": "hyloship_ndd","Password": "Pikndel@123","GrantType":"password"}} ',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: ci_session=ifj286dc32irhbr865kjdvppanpqaphg'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return  $response;

    }
    
    public static function posthit_pckndel($url,$data,$token){
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
          CURLOPT_POSTFIELDS =>$data,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$token,
            'Cookie: ci_session=ifj286dc32irhbr865kjdvppanpqaphg'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
    public static function shipment_pckndel($order_data) {
        
        $url = "https://api.pikndel.com/backoffice/api/pikndel/place_order";

        $token = Integration_more::getpckndeltoken();
        $token_array = json_decode($token, true);

        if (
            isset($token_array['Control']) &&
            isset($token_array['Control']['MessageCode']) &&
            $token_array['Control']['MessageCode'] == 200
        ) {
            return Integration_more::posthit_pckndel($url, $order_data, $token_array['Data']['Token']);
        } else {
            return $token;
        }
    }
    
    public static function cancelshipment_pckndel($cancel_date){
        $url = "https://api.pikndel.com/backoffice/api/pikndel/order/cancel";

        $token = Integration_more::getpckndeltoken();
        $token_array = json_decode($token, true);

        if (
            isset($token_array['Control']) &&
            isset($token_array['Control']['MessageCode']) &&
            $token_array['Control']['MessageCode'] == 200
        ) {
            return Integration_more::posthit_pckndel($url, $cancel_date, $token_array['Data']['Token']);
        } else {
            return $token;
        }
    }
     public static function getsttauspckndel($tracking_info){
        $url = "https://api.pikndel.com/backoffice/api/pikndel/order/get_status";

        $token = Integration_more::getpckndeltoken();
        $token_array = json_decode($token, true);

        if (
            isset($token_array['Control']) &&
            isset($token_array['Control']['MessageCode']) &&
            $token_array['Control']['MessageCode'] == 200
        ) { 
            $trackarray = array(
                "Control"=>array(
                    "RequestId"=>"10db2584-96ab-402b-91b5-2b0ebdd95ee8",
                    "RequestTime"=>time(),
                    "Source"=>"3",
                    "Version"=>"1.0"
                ),
                "Data"=>array(
                    "AWBNo"=>$tracking_info
                )
            );
            return Integration_more::posthit_pckndel($url, json_encode($trackarray), $token_array['Data']['Token']);
        } else {
            return $token;
        }
    }
}
