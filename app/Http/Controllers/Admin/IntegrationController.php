<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Integration;
use App\Models\Admin\Pincode;
use App\Models\Admin\Courier;
use App\Models\Admin\Channel;
use App\Models\Admin\Servicable_pincode;
use App\Models\Admin\Channel_integration;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use App\Models\Admin\Ratecard;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Auth;
use DB;

class IntegrationController extends Controller
{
    public function index()
    {
        $data['couriers'] = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        $data['check'] = Integration::whereUserId(auth()->guard('admin')->user()->id)->get()->pluck('courier_id')->toArray();
        $data['ecom'] = Integration::whereUserId(auth()->guard('admin')->user()->id)->whereCourierId(1)->first();
        $data['delhivery'] = Integration::whereUserId(auth()->guard('admin')->user()->id)->whereCourierId(2)->first();
        $data['bludart'] = Integration::whereUserId(auth()->guard('admin')->user()->id)->whereCourierId(3)->first();
        $data['express'] = Integration::whereUserId(auth()->guard('admin')->user()->id)->whereCourierId(4)->first();
        $data['dtdc'] = Integration::whereUserId(auth()->guard('admin')->user()->id)->whereCourierId(5)->first();
        return view('admin.integration.index',compact('data'));
    }
    public function channel()
    {
        $user = Auth::guard('admin')->user(); 
        $currentcompany_id = $user->company_id;
        $data['channel'] = Channel::where('company_id', $currentcompany_id)->get();
        $data['channel_data'] = Channel_integration::whereUserId($user->id)->where('status','!=','4')->get();
        return view('admin.integration.channel',compact('data'));

    }
    public function test()
    {
        return view('admin.integration.test');
    }

    public function test_xb()
    {
        $xb_user = env('XBEES_USERNAME', 'admin@Hyloship.com');
        $xb_pass = env('XBEES_PASSWORD', 'Xpress@1234567');
        $xb_secret = env('XBEES_SECRETKEY', '5babb4d7a6c80b45ade918fb4e429068c8480e6125925c474d8d67a27f8190db');
        $xb_key = env('XBEES_XB_KEY', 'Plmng39338VdtHa');

        $steps = [];

        // Step 1: Token Generation
        $token_res = Integration::generatetoken_xbess($xb_user, $xb_pass, $xb_secret);
        $token_data = json_decode($token_res, true);
        $steps['step1_token'] = [
            'url' => 'https://userauthapis.xbees.in/api/auth/generateToken',
            'response' => $token_data
        ];

        // Step 2: AWB Generation (Try to get a NEW BatchID)
        $awb_gen_data = json_encode([
            "BusinessUnit" => "ECOM",
            "ServiceType" => "FORWARD",
            "DeliveryType" => "COD"
        ]);
        $awb_res = Integration::generate_awb_series_xbess($awb_gen_data, $xb_user, $xb_pass, $xb_secret, $xb_key);
        $awb_data = json_decode($awb_res, true);
        $steps['step2_generate'] = [
            'url' => 'https://xbclientapi.xbees.in/POSTShipmentService.svc/AWBNumberSeriesGeneration',
            'payload' => json_decode($awb_gen_data),
            'response' => $awb_data
        ];

        // Step 3: Get Series (Fetch AWBs for the NEW BatchID OR Fallback)
        $batch_id = $awb_data['BatchID'] ?? 'Tvo2T';
        $get_awb_data = json_encode([
            "BusinessUnit" => "ECOM",
            "ServiceType" => "FORWARD",
            "BatchID" => $batch_id
        ]);
        $fetch_res = Integration::get_awb_series_xbess($get_awb_data, $xb_user, $xb_pass, $xb_secret, $xb_key);
        $fetch_data = json_decode($fetch_res, true);
        
        // Persist AWBs into pool
        $added_count = 0;
        if (isset($fetch_data['AWBNoSeries']) && !empty($fetch_data['AWBNoSeries'])) {
            $awbs = explode(',', $fetch_data['AWBNoSeries']);
            $current_company_id = Auth::guard('admin')->user()->company_id;
            
            // Chunked insertion for performance
            $awb_chunks = array_chunk($awbs, 1000);
            foreach ($awb_chunks as $chunk) {
                $insert_data = [];
                foreach ($chunk as $awb) {
                    $awb = trim($awb);
                    if (!empty($awb)) {
                        $insert_data[] = [
                            'awb' => $awb,
                            'used' => '0',
                            'company_id' => $current_company_id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }
                // Use insertOrIgnore if supported, else check existence (manual check is safer for compatibility)
                try {
                    DB::table('xb_pincodes')->insertOrIgnore($insert_data);
                    $added_count += count($insert_data);
                } catch (\Exception $e) {
                    // Fallback for older Laravel versions
                    foreach ($insert_data as $row) {
                        if (!DB::table('xb_pincodes')->where('awb', $row['awb'])->exists()) {
                            DB::table('xb_pincodes')->insert($row);
                            $added_count++;
                        }
                    }
                }
            }
        }

        $steps['step3_fetch'] = [
            'url' => 'https://xbclientapi.xbees.in/TrackingService.svc/GetAWBNumberGeneratedSeries',
            'batch_id_used' => $batch_id,
            'added_to_pool' => $added_count,
            'response' => $fetch_data
        ];

        // Step 4: Serviceability Check (Test with a known pincode from DOCT.MD)
        $svc_check_data = json_encode([
            "BusinessUnit" => "eComm",
            "BusinessFlow" => "Forward",
            "BusinessService" => "Delivery",
            "Pincode" => "410210"
        ]);
        $svc_res = Integration::chk_serviceable_pincode_xbess($svc_check_data, $xb_user, $xb_pass, $xb_secret, $xb_key);
        $steps['step4_serviceability'] = [
            'url' => 'https://xbmasterapi.xbees.in/expose/get/serviceabilitypincode/details',
            'pincode_tested' => '410210',
            'response' => json_decode($svc_res, true)
        ];

        // Step 5: Current Status (Try a sample AWB if we got one in Step 3)
        $sample_awb = $fetch_data['AWBNoSeries'][0] ?? '153933860000000';
        $status_res = Integration::get_current_status_xbess($sample_awb, $xb_user, $xb_pass, $xb_secret, $xb_key);
        $steps['step5_current_status'] = [
            'url' => 'https://apishipmenttracking.xbees.in/GetCurrentShipmentStatus',
            'awb_tested' => $sample_awb,
            'response' => json_decode($status_res, true)
        ];

        return response()->json([
            'status' => 'Diagnostic Complete',
            'config_used' => [
                'xb_user' => $xb_user,
                'xb_key' => $xb_key
            ],
            'steps' => $steps
        ]);
    }

    public function test_manifest()
    {
        $xb_user = env('XBEES_USERNAME', 'admin@Hyloship.com');
        $xb_pass = env('XBEES_PASSWORD', 'Xpress@1234567');
        $xb_secret = env('XBEES_SECRETKEY', '5babb4d7a6c80b45ade918fb4e429068c8480e6125925c474d8d67a27f8190db');
        $xb_key = env('XBEES_XB_KEY', 'Plmng39338VdtHa');
        
        // Step 1: Use the existing BatchID we know works
        $batch_id = 'Tvo2T';
        $get_awb_data = json_encode([
            "BusinessUnit" => "ECOM",
            "ServiceType" => "FORWARD",
            "BatchID" => $batch_id
        ]);
        $fetch_res = Integration::get_awb_series_xbess($get_awb_data, $xb_user, $xb_pass, $xb_secret, $xb_key);
        $fetch_data = json_decode($fetch_res, true);
        
        // Pick a fresh AWB from the series (e.g., index 10 to avoid previous tests)
        $fresh_awb = '';
        if (isset($fetch_data['AWBNoSeries']) && count($fetch_data['AWBNoSeries']) > 10) {
            $fresh_awb = $fetch_data['AWBNoSeries'][rand(10, 100)]; 
        }

        if (!$fresh_awb) {
            return response()->json(['error' => 'Failed to fetch AWB from batch Tvo2T', 'raw_response' => $fetch_res]);
        }

        // Step 2: Manifest it
        $expess_data = array(
            'BusinessAccountName' => env('XBEES_BUSINESS_ACCOUNT', 'Hyloship'),
            'OrderNo' => 'TEST_' . time(),
            'SubOrderNo' => 'TEST_' . time(),
            'OrderType' => 'PrePaid',
            'CollectibleAmount' => '0',
            'DeclaredValue' => '1000',
            'Quantity' => '1',
            'PickupType' => 'Vendor',
            'ServiceType' => 'SD',
            'AirWayBillNO' => $fresh_awb,
            'DropDetails' => array(
                'Addresses' => array(
                    array(
                        'Name' => 'Test Customer',
                        'Address' => 'Delhi Address',
                        'City' => 'Delhi',
                        'EmailID' => 'test@example.com',
                        'PinCode' => '110001',
                        'State' => 'Delhi',
                        'Type' => 'Primary',
                    )
                ),
                'ContactDetails' => array(
                    array(
                        'PhoneNo' => '9999999999',
                        'Type' => 'Primary',
                    )
                )
            ),
            'PickupDetails' => array(
                'Addresses' => array(
                    array(
                        'Name' => 'Hyloship',
                        'Address' => 'Warehouse Address',
                        'City' => 'Delhi',
                        'EmailID' => 'warehouse@example.com',
                        'PinCode' => '110055',
                        'State' => 'Delhi',
                        'Type' => 'Primary',
                    )
                ),
                'ContactDetails' => array(
                    array(
                        'PhoneNo' => '9999999999',
                        'Type' => 'Primary',
                    )
                ),
                'PickupVendorCode' => 'VENDOR123',
            ),
            'RTODetails' => array(
                'Addresses' => array(
                    array(
                        'Name' => 'RTO',
                        'Address' => 'RTO Address',
                        'City' => 'Delhi',
                        'EmailID' => 'rto@example.com',
                        'PinCode' => '110055',
                        'State' => 'Delhi',
                        'Type' => 'Primary',
                    )
                ),
                'ContactDetails' => array(
                    array(
                        'PhoneNo' => '9999999999',
                        'Type' => 'Primary',
                    )
                )
            ),
            'ManifestID' => 'TEST_' . time(),
        );

        $manifestRes = Integration::shipment_express(json_encode($expess_data), $xb_user, $xb_pass, $xb_secret, $xb_key);
        
        return response()->json([
            'awb_used' => $fresh_awb,
            'request_payload' => $expess_data,
            'raw_response' => $manifestRes,
            'decoded_response' => json_decode($manifestRes, true)
        ]);
    }

    public function remove_courier($id){
        Integration::whereUserId(auth()->guard('admin')->user()->id)->whereCourierId($id)->first()->delete();
        return redirect()->route('admin.integration.index')->with('success','Channel Removed successfully');
    }

    public function store(Request $request)
    {
        if(Integration::whereUserId(auth()->guard('admin')->user()->id)->whereCourierId(request('courier'))->exists()){
            $integration = Integration::whereUserId(auth()->guard('admin')->user()->id)->whereCourierId(request('courier'))->first();
        } else {
            $integration = new Integration();
        }
        $integration->courier_id = $request->courier;
        $integration->user_id = auth()->guard('admin')->user()->id;
        $integration->bcarrier_title = $request->bcarrier_title;
        $integration->server = $request->input('server');
        $integration->login_id = $request->login_id;
        $integration->licence_key = $request->licence_key;
        $integration->bd_client_id = $request->bd_client_id;
        $integration->bd_client_secret = $request->bd_client_secret;
        $integration->vendor_code = $request->vendor_code;
        $integration->origin_area = $request->origin_area;
        $integration->pre_paid = $request->pre_paid;
        $integration->cod = $request->cod;
        $integration->isToPayCustomer = $request->isToPayCustomer;
        $integration->packtype = $request->packtype;
        $integration->gst_status = $request->gst_status;
        $integration->auto_pickup = $request->auto_pickup;
        $integration->otp_no = $request->otp_no;
        $integration->esclation_status = $request->esclation_status;

        $integration->dcarrier_title = $request->dcarrier_title;
        $integration->dship_mode = $request->dship_mode;
        $integration->dclient = $request->dclient;
        $integration->dapi_token = $request->dapi_token;

        $integration->xcarrier_title = $request->xcarrier_title;
        $integration->xnshipment_type = $request->xnshipment_type;
        $integration->xxb_key = $request->xxb_key;
        $integration->xnaccount_mode = $request->xnaccount_mode;
        $integration->xusername = $request->xusername;
        $integration->xpassword = $request->xpassword;
        $integration->secret_key = $request->secret_key;
        $integration->b_account_name = $request->b_account_name;
        $integration->xwaccount_mode = $request->xwaccount_mode;

        $integration->mcarrier_title = $request->mcarrier_title;
        $integration->mship_mode = $request->mship_mode;
        $integration->mclient = $request->mclient;
        $integration->mapi_token = $request->mapi_token;

        $integration->ecarrier_title = $request->ecarrier_title;
        $integration->eusername = $request->eusername;
        $integration->epassword = $request->epassword;
        $integration->customer_code = $request->customer_code;
        $integration->otp_enable = $request->otp_enable;

        $integration->bd_login_id = $request->bd_login_id;
        $integration->bd_licence_key = $request->bd_licence_key;
        $integration->bd_customer_code = $request->bd_customer_code;
        $integration->bd_origin_area = $request->bd_origin_area;

        $integration->save();

        return redirect()->route('admin.integration.index')->with('success','Channel Updated successfully');
    }

    public function manage_courier(){
        // Load couriers from courier.json file (static data)
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')), true);
    
        // Get the logged-in admin's company_id
        $companyId = auth()->guard('admin')->user()->company_id;
    
        // Fetch couriers from the database based on company_id (dynamic data)
        $couriersFromDb = Courier::where('company_id', $companyId)->get();
    
        // Count the number of active couriers (status == 1)
        $count = $couriersFromDb->where('status', 1)->count();
    
        // Pass both the JSON data and the database data to the view
        return view('admin.integration.manage', compact('couriers', 'couriersFromDb','companyId' ,'count'));
    }

    
    public function courier_priority()
    {  
        $user = Auth::guard('admin')->user();
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')), true);
        $currcompany = $user->company_id;
        $courierCount = Courier::where('company_id', $currcompany)->count();
        if($courierCount!=null){
        $usercourier_priority = $user->courier_priority;
        $usercourier_priority = $usercourier_priority 
            ? explode(',', $usercourier_priority) 
            : [];
        $courierfromdb = Courier::select('courier_id', 'mode')
            ->where('company_id', $currcompany)
            ->groupBy('courier_id', 'mode')
            ->get();
        $c_array = [];
        foreach ($courierfromdb as $courier) {
            $c_array[] = $courier->courier_id . '' . $courier->mode;
        }
        return view('admin.integration.priority', compact('couriers', 'usercourier_priority', 'courierfromdb', 'c_array'));
    }
    else{
        $couriers = array();
        return view('admin.integration.priority',compact('couriers'))->with('error','No Rate card found');;
        }
    }

    public function prioritystore(Request $request)
    {   
        $id = auth()->guard('admin')->user()->id;
        $admin = Admin::where('id', $id)->first();
        $admin->courier_priority = $request[0].','.$request[1].','.$request[2].','.$request[3];
        $admin->save();
        return redirect()->route('admin.integration.courier_priority')->with('success','Priority has been updated');
    }
    
    public function pincode_download($id){
        $user_id = auth()->guard('admin')->user()->ratecard ? auth()->guard('admin')->user()->id : 1;
        $pincode = Pincode::where(['user_id' => $user_id, 'courier_id' => $id])->get();

        dd($pincode);

    }

    public function courier_status_all(Request $request)
    { 
        $companyId = $request->input('company_id');
        $status = $request->input('status'); // This should be 0 or 1
        if (!in_array($status, [0, 1])) {
            return response()->json(['message' => 'Invalid status'], 400);
        }

        $updated = Courier::where('company_id', $companyId)
                          ->update(['status' => $status]);

        if ($updated) {
            createlogs('all_changed','courier',$companyId);
            return response()->json(['message' => 'Status updated successfully']);
            
        }
        return response()->json(['message' => 'No couriers found with the given company ID'], 404);
    }


    public function courier_status(Request $request)
    { 

        $courier = Courier::where('courier_id', $request->courier_id)
                          ->where('mode', $request->mode)
                          ->where('company_id', $request->company_id)
                          ->first();

        if (!$courier) {
            return response()->json(['message' => 'Courier not found, invalid company, or incorrect mode'], 404);
        }
        $courier->status = $request->status;
        $courier->save();
        
        createlogs('changed','courier',$request->courier_id);
        
        return response()->json(['message' => 'Courier status updated successfully']);
    }
  public function channel_save(Request $request, $id = 0)
    {

        if ($id) {
            $ci = Channel_integration::where(['store_name' => $request->store_name])
            ->where('id','!=',$id)
            ->where('status','!=','4')
            ->first();
        if($ci){
               return redirect()->route('admin.integration.channel')->with('error', 'Store url is already present');
              }
        }else{
              $ci = Channel_integration::where(['store_name' => $request->store_name])->where('status','!=','4')->first();
              if($ci){
               return redirect()->route('admin.integration.channel')->with('error', 'Store url is already present');
              }
        }
    
    
        $vv = $request->validate([
            'store_name' => 'required',
            'store_access' => 'required',
            'customer_key' => 'required',
        ]);
    
        $user_id = Auth::guard('admin')->user()->id;
        
        if ($id) {
                $warehouse = Channel_integration::findOrFail($id);
          		$warehouse->status = '1';
                $message  = "Please Note that the order will be updated automatically every 5 minutes.";
            
        } else {
            $warehouse = new Channel_integration();
            $user_id = Auth::guard('admin')->user()->id;
          	$warehouse->last_id = 0;
            $warehouse->user_id = $user_id;
          	$message  = "Please Note that the order will be updated automatically every 5 minutes.";
        }
        $warehouse->channel_id = $request->channel_id;
        $warehouse->store_name = $request->store_name;
        $warehouse->customer_key = $request->customer_key;
        $warehouse->store_access = $request->store_access;
        $warehouse->updated_at = now();
        $warehouse->save();
        
        return redirect()->route('admin.integration.channel')->with('success', $message);

    }
    
    function delete_channel($id){
            $admin = Channel_integration::findOrFail($id);
            $stname = $admin->store_name;
            $admin->status = 4;
            $admin->store_name = $stname.'-c';
            $admin->save();
            return redirect()->route('admin.integration.channel')->with('success', 'Deleted successfully!');
    }
    
    function distroy_channel($id){
            $admin = Channel_integration::findOrFail($id);
            $admin->status = 4;
            $admin->save();
            return redirect()->route('admin.integration.channel')->with('success', 'Deleted successfully!');
    }
    
    function courier_serviceable(){
        $re_data = $serpincode = $pincodedata = array();
        $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
        if(isset($_REQUEST['pincodes']) && $_REQUEST['pincodes'] !=''){
            $re_data['pincodes'] =$_REQUEST['pincodes'];
            $_REQUEST['pincodes'] = explode(',', $_REQUEST['pincodes']);
            $serpincode = Servicable_pincode::wherein('pincode',$_REQUEST['pincodes'])->where('company_id',Auth::guard('admin')->user()->company_id)->get();
            foreach($serpincode as $pncd){
                $pincodedata[$pncd->pincode]['pincode'] = $pncd->pincode;
                for($i=1;$i<10;$i++){
                    if($i == $pncd->courier_id){
                        $pincodedata[$pncd->pincode][$pncd->courier_id]['courier_id'] = $pncd->courier_id;
                        $pincodedata[$pncd->pincode][$pncd->courier_id]['courier_name'] = $couriers[$pncd->courier_id]['name'];
                        $pincodedata[$pncd->pincode][$pncd->courier_id]['type'] = $pncd->type;
                        $pincodedata[$pncd->pincode][$pncd->courier_id]['mode'] = $pncd->mode;
                        $pincodedata[$pncd->pincode][$pncd->courier_id]['payment'] = $pncd->payment;
                        $pincodedata[$pncd->pincode][$pncd->courier_id]['active'] = $pncd->active;
                    }
                }
            }
        }
        return view('admin.integration.serviceable',compact('re_data','pincodedata'));
    }
    
    function createserviceable(){// couriervise pincode
        return view('admin.integration.createserviceable');
    }
    
    function storeserviceablepincode(Request $request){
        try {
            $collections = (new FastExcel)->import($request->file('excel'));
        } catch (\Exception $exception) {
            return back()->with('error','You have uploaded a wrong format file, please upload the right file.');
        }
        $c_data['Ecom Express'] = 1;
        $c_data['Delhivery'] = 2;
        $c_data['Bluedart'] = 3;
        $c_data['XpressBees'] = 4;
        $c_data['DTDC'] = 5;
        $c_data['Smartr'] = 6;
        $c_data['Ekart'] = 7;
        $c_data['Shadowfax'] = 8;
        $c_data['ATS'] = 9;
        foreach ($collections as $row) {
          $courier_id = $c_data[$row['Courier']];
          $pin = new Servicable_pincode();
          $pin->pincode = $row['pincode'];
          $pin->type = $row['Mode'];//pickup-drop
          $pin->payment = $row['Payment'];
          $pin->mode = $row['Transfer'];//air-surface
          $pin->courier_id = $courier_id;
          $pin->company_id = Auth::guard('admin')->user()->company_id;
          $pin->save();
        }
        return redirect()->route('admin.integration.createserviceable')->with('success','Rate Imported Successfully');
    }
    
    function pincode(){ // all india pincode
        return view('admin.integration.pincode');
    }
    
    function storepincode(Request $request){
        try {
            $collections = (new FastExcel)->import($request->file('excel'));
        } catch (\Exception $exception) {
            return back()->with('error','You have uploaded a wrong format file, please upload the right file.');
        }
        foreach ($collections as $row) {
           
          $pin = new Pincode();
          $pin->courier_id = '1';
          $pin->user_id = Auth::guard('admin')->user()->id;
          $pin->user_id = Auth::guard('admin')->user()->company_id;
          $pin->pincode = $row['Pincode'];
          $pin->city = $row['city'];
          $pin->state = $row['state'];
          $pin->cod = 't';
          $pin->prepaid = 't';
          $pin->pickup = 't';
          $pin->zone = 'z1';
          $pin->metro = ($row['metro'] =='1') ? '1' : '0';
          $pin->special = ($row['special'] =='1') ? '1' : '0';
          $pin->north_east = ($row['north_east'] =='1') ? '1' : '0';
          $pin->save();
        }
        return back()->with('success','Pincode Imported Successfully');
    }
}