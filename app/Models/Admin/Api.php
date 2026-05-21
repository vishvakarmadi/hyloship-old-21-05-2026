<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Order;
use App\Models\Admin\OrderDetail;
use App\Models\Admin\Ratecard;
use App\Models\Admin\Integration;
use App\Models\Admin\Integration_more;
use App\Models\Admin\Country;
use App\Models\Admin\Pincode;
use App\Models\Admin\Servicable_pincode;
use Illuminate\Support\Facades\Auth; 
use App\Models\Admin\Warehouse;
use App\Models\Admin\Admin;
use DB;

class Api extends Model
{
    //order
    public static function ordercreate($data, $user_id)
    {
//        echo '<pre>';print_R($data);die;
        $order = new Order();
        $order->user_id = $user_id;
        $order->order_id = $data->order_id;
        $order->ship_fname = $data->ship_fname ?? null;
        $order->ship_lname = $data->ship_lname ?? null;
        $order->ship_email = $data->ship_email ?? null;
        $order->ship_company = $data->ship_company ?? null;
        $order->ship_phone = $data->ship_phone ?? null;
        $order->ship_address = $data->ship_address ?? null;
        $order->ship_address_2 = $data->ship_address_2 ?? null;
        $order->ship_country = $data->ship_country ?? null;
        $order->ship_pincode = $data->ship_pincode ?? null;
        $order->ship_city = $data->ship_city ?? null;
        $order->ship_state = $data->ship_state ?? null;
        $order->ship_latitude = $data->ship_latitude ?? null;
        $order->ship_longitude = $data->ship_longitude ?? null;
        $order->ship_gstin = $data->ship_gstin ?? null;
        $order->bill_fname = $data->bill_fname ?? null;
        $order->bill_lname = $data->bill_lname ?? null;
        $order->bill_company = $data->bill_company ?? null;
        $order->bill_phone = $data->bill_phone ?? null;
        $order->bill_address = $data->bill_address ?? null;
        $order->bill_address_2 = $data->bill_address_2 ?? null;
        $order->bill_country = $data->bill_country ?? null;
        $order->bill_pincode = $data->bill_pincode ?? null;
        $order->bill_city = $data->bill_city ?? null;
        $order->bill_state = $data->bill_state ?? null;
        $order->bill_latitude = $data->bill_latitude ?? null;
        $order->bill_longitude = $data->bill_longitude ?? null;
        $order->bill_gstin = $data->bill_gstin ?? null;
        $order->e_bill_no = $data->e_bill_no ?? null;
        $order->same_add = $data->same_add;
        $order->discount = $data->order_discount;
        $order->shipping_cost = $data->shipping_cost;
        $order->total = $data->total ?? 0;
        $order->custom_total = $data->custom_total;
        $order->payment_mode = $data->payment_mode;
        $order->vendor_order_id = $data->vendor_order_id;
        $order->channel = $data->channel ?? 'Hyloship';
        $order->weight = $data->weight;
        $order->length = $data->length;
        $order->breadth = $data->breadth;
        $order->height = $data->height;
        $order->note = $data->note ?? null;
        $order->save();

        $id = $order->id;
        $order->order_id = $id;
        $order->save();

        // Saving order details
        foreach($data->name as $key => $row)
        {
            $detail = new OrderDetail();
            $detail->user_id = $user_id;
            $detail->order_id = $order->id;
            $detail->name = $data->name[$key];
            $detail->code = $data->code[$key];
            $detail->price = $data->price[$key];
            $detail->discount = $data->discount[$key];
            $detail->qty = $data->qty[$key];
            $detail->discount_type = $data->discount_type[$key];
            $detail->tax_percent = $data->tax_percent[$key] ?? 0;
            $detail->tax_amount = $data->tax_amount[$key] ?? 0;
            $detail->total_price = $data->total_price[$key];
            $detail->save();
        }
        return $order;
    }

    public static function orderupdate($request,$user_id,$id){

        $order = Order::where('id',$id)->where('user_id',$user_id)->first();
        if($order==""){
            return [
                'error' => true,
                'message' => 'Order NOT FOUND' ,
            ]; 
        }
        
        $originalOrder = $order->getOriginal();
        $originalOrderTrimmed = array_map('trim', $originalOrder);
        $order->user_id = $user_id;
        $order->order_id = $request->order_id;
        $order->ship_fname = $request->ship_fname ?? null;
        $order->ship_lname = $request->ship_lname ?? null;
        $order->ship_email = $request->ship_email ?? null;
        $order->ship_company = $request->ship_company ?? null;
        $order->ship_phone = $request->ship_phone ?? null;
        $order->ship_address = $request->ship_address ?? null;
        $order->ship_address_2 = $request->ship_address_2 ?? null;
        $order->ship_country = $request->ship_country ?? null;
        $order->ship_pincode = $request->ship_pincode ?? null;
        $order->ship_city = $request->ship_city ?? null;
        $order->ship_state = $request->ship_state ?? null;
        $order->ship_latitude = $request->ship_latitude ?? null;
        $order->ship_longitude = $request->ship_longitude ?? null;
        $order->ship_gstin = $request->ship_gstin ?? null;
        $order->bill_fname = $request->bill_fname ?? null;
        $order->bill_lname = $request->bill_lname ?? null;
        $order->bill_company = $request->bill_company ?? null;
        $order->bill_phone = $request->bill_phone ?? null;
        $order->bill_address = $request->bill_address ?? null;
        $order->bill_address_2 = $request->bill_address_2 ?? null;
        $order->bill_country = $request->bill_country ?? null;
        $order->bill_pincode = $request->bill_pincode ?? null;
        $order->bill_city = $request->bill_city ?? null;
        $order->bill_state = $request->bill_state ?? null;
        $order->bill_latitude = $request->bill_latitude ?? null;
        $order->bill_longitude = $request->bill_longitude ?? null;
        $order->bill_gstin = $request->bill_gstin ?? null;
        $order->e_bill_no = $request->e_bill_no ?? null;
        $order->same_add = $request->same_add;
        $order->discount = $request->order_discount;
        $order->shipping_cost = $request->shipping_cost;
        $order->total = $request->total ?? 0;
        $order->custom_total = $request->custom_total;
        $order->payment_mode = $request->payment_mode;
        $order->vendor_order_id = $request->vendor_order_id;
        $order->channel = $request->channel ?? 'Hyloship';
        $order->weight = $request->weight;
        $order->length = $request->length;
        $order->breadth = $request->breadth;
        $order->height = $request->height;
        $order->note = $request->note ?? null;
    
        $order->save();
        OrderDetail::where('order_id', $id)->delete();

        foreach ($request->name as $key => $row) {
            $detail = new OrderDetail();
            $detail->user_id = $user_id;
            $detail->order_id = $id;
            $detail->name = $request->name[$key];
            $detail->code = $request->code[$key];
            $detail->price = $request->price[$key];
            $detail->discount = $request->discount[$key];
            $detail->qty = $request->qty[$key];
            $detail->discount_type = $request->discount_type[$key];
            $detail->tax_percent = $request->tax_percent[$key] ?? 0;
            $detail->tax_amount = $request->tax_amount[$key] ?? 0;
            $detail->total_price = $request->total_price[$key];
            $detail->save();
        }
        $currentOrderTrimmed = array_map('trim', $order->getAttributes());
    
        $changedFields = array_diff_assoc($currentOrderTrimmed,$originalOrderTrimmed);
        unset($changedFields['channel'],$changedFields['user_id'],$changedFields['payment_mode'], $changedFields['updated_at'], $changedFields['extra_weight_status'],$changedFields['status']);
        
        $oldValues = [];
        foreach ($changedFields as $key => $newValue) {
            $oldValues[$key] = $originalOrderTrimmed[$key] ?? 'N/A';
        }
    
        createlogs('updated','order', $id,$changedFields,$oldValues);
    return [
        'success' => true,
        'message' => 'Order updated successfully.',
    ];
    }

    public static function orderdelete($order_ids,$user_id)
    {  $deletedOrders = 0; 
        $error="";
    // dd($order_ids);
    // dd($user_id);
       
        foreach ($order_ids as $id)
        {
          
            $order = DB::table('orders')->where('user_id', $user_id)->where('id', $id)->where('status','1')->first();
            $order_details=DB::table('order_details')->where('user_id', $user_id)->where('order_id', $id)->first();
            //dd($orders);
            // dd($order_details);
            if(isset($order->id)){
            $orderArray = (array)$order;
            DB::table('archive_orders')->insert($orderArray);
            $order_details_array=(array)$order_details;
            DB::table('archive_order_details')->insert($order_details_array);
            $orders = DB::table('orders')->where('user_id', $user_id)->where('id', $id)->delete();
            $orderdetail = DB::table('order_details')->where('user_id', $user_id)->where('order_id', $id)->delete();
            createlogs('deleted', 'order', $order->id);
            $deletedOrders++;
            }else{
                $error.=$id.',';            }
        }
        if( $error!=""){
            return [
                'error' => true,
                'message' => Rtrim($error,',').' can not be deleted or already deleted' ,
            ]; 
        }
        if ($deletedOrders > 0) {
            return [
                'success' => true,
                'message' => "$deletedOrders order(s) deleted successfully.",
            ];
        }
    
        return [
            'error' => true,
            'message' => 'No orders could be deleted.',
        ];
        
    }
    
    public static function ordercreateawb($data, $user_id,$dg_goods = false){
        $id = $data->order_id;
        $orde_user_id =$user_id;
        $retunarray[0]='error';
        $retunarray[1]='Something Went Wrong';
        $ware = $data->warehouse_id;
        $return_warehouse_id = $data->return_warehouse_id;
        
        
        if($data->weight < 0.5){
            $data->weight =0.5;
        }
//        if($data->weight > 0.5 && $data->weight <1){
//            $data->weight =1;
//        }
//        $ratecardupdated = Ratecard::where('user_id',$user_id)->where('status','1')->where('courier_id',$data->courier_id)->where('weight',$data->weightupdated)->where('additional','0')->where('transport',$data->mode)->first();
//        if(!$ratecardupdated){
//            $data->weight =0.5;
//        }
        $ratecard = Ratecard::where('user_id',$user_id)->where('status','1')->where('courier_id',$data->courier_id)->where('weight',$data->weight)->where('additional','0')->where('transport',$data->mode)->first();
        
        if ($ratecard) {
            $required_corr_data=array();
            $cour_id ='';
            $parent_userid = Admin::find($user_id)->parent_id;
            $pickup = Warehouse::whereId($data->warehouse_id)->where('user_id',$user_id)->pluck('pincode')->first();
            if($pickup){
                $order = Order::where('id',$id)->where('user_id',$user_id)->first();
                if($order && strip_tags($order->payment_mode) !='Reverse'){
                    $pincodepicup_sevice = Pincode::where('pincode',$pickup)->first();
                    $pincodedrop_sevice = Pincode::where('pincode',$order->ship_pincode)->first();
                    
                     if($pincodepicup_sevice =='' || $pincodedrop_sevice =='' || ($data->courier_id =='1' && ( in_array($pickup,array('401107','248001')) || in_array($order->ship_pincode,array('401107','248001')) ) ) ){
                         $retunarray[0]='error';
                         $retunarray[1]='Pincode is not Servicable! - Hyloship';
                     }else{
                         if($order->tracking_info ==''){
                                $old_status = strip_tags($order->status);
                                $drop = $order->ship_pincode;
                                $drop = ltrim(rtrim($drop));
                                $pickup = ltrim(rtrim($pickup));
                                $payment =  strip_tags($order->payment_mode) == 'C.O.D' ? 'cod' : 'prepaid';
                                 $get = array();$text = '';
                                 $cour_id =array($data->courier_id);
                                 $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')),true);
                                 if(!empty($cour_id)){
                                     $zone_id = Order::getzone($pickup,$drop);
                                     for($i=0;$i<count($cour_id);$i++){
                                        $pincodes[] = array(
                                            "courier_id" => $cour_id[$i],  
                                            "zone" => $zone_id,
                                        ); 
                                      }
                                      foreach($pincodes as $row){
                                          if($row['zone'] !='0' && $row['zone'] !=''){
                                              $zone = zone($row['zone']);
                                              $c_id = $row['courier_id'];
                                              $trasport_arary = [$data->mode]; 
                                              foreach($trasport_arary as $transport){
                                                foreach([$data->weight] as $c_weigt){
                                                    $totalparent = $codparent = $gstparent = $reverse_chregparent = $gst_codparent = $gst_freightparent = $freightparent = $rateparent = $rateaddparent = 0;
                                                    $rate = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport,'status' => 1,'user_id' => $user_id,'weight' =>$c_weigt,'additional' =>0])->first();
                                                    $rateparent = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport,'status' => 1,'user_id' => $parent_userid,'weight' =>$c_weigt,'additional' =>0])->first();

                                                    if($c_weigt=='0.5'){
                                                        $wadd = '0.5';
                                                    }else{
                                                        $wadd = '1';
                                                    }
                                                    $rateadd = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport,'status' => 1,'user_id' => $user_id,'weight' =>$wadd,'additional' =>1])->first();
                                                    $rateaddparent = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport,'status' => 1,'user_id' => $parent_userid,'weight' =>$wadd,'additional' =>1])->first();

                                                    // $add = Ratecard::where(['courier_id' => $c_id, 'transport' => $transport, 'weight' => 'add','status' => 1,'user_id' => $user_id])->first();
                                                    $vol_weight = ($order['length']*$order['breadth']*$order['height'])/vol_weigh($c_id);
                                                    $weight_to_be_taken = $vol_weight > ($order['weight']/1000) ? $vol_weight : ($order['weight']/1000);
                                                    if($rate == null){
                                                        //  return [
                                                        //     'status' => 0,
                                                        //     'message' => 'Rate card not found for this user'
                                                        // ];
                                                        continue;
                                                    }
                                                    $percent = (($order->total * $rate->cod) / 100); //4339
                                                    $cod = $payment == 'cod' ? $percent > $rate->cod_charges ? $percent : $rate->cod_charges : 0; //0
                                                    $remainging_weight = $weight_to_be_taken - $c_weigt;
                                                    $freight = $rate->$zone;

                                                    if($rateparent !=''){
                                                        $percentparent = (($order->total * $rateparent->cod) / 100); //4339
                                                        $codparent = $payment == 'cod' ? $percentparent > $rateparent->cod_charges ? $percentparent : $rateparent->cod_charges : 0; //0
                                                        $freightparent = $rateparent->$zone;
                                                    }


                                                    if($remainging_weight<0){
                                                        $remainging_weight =0;
                                                    }
                                                    $count =$remainging_charge=$remainging_chargeparent=0;
                                                    if($rateadd == null){
                                                        $getrateadd =0;
                                                    }else{
                                                        $count = ceil($remainging_weight/$rateadd->weight);
                                                        $remainging_charge = $count * $rateadd->$zone;
                                                        $getrateadd = $rateadd->$zone;
                                                    }

                                                    if($rateaddparent == null){
                                                        $getrateaddparent =0;
                                                    }else{
                                                        $remainging_chargeparent = $count * $rateaddparent->$zone;
                                                        $getrateaddparent = $rateaddparent->$zone;
                                                    }
                                                    $freight = $freight + $remainging_charge;
                                                    $freightparent = $freightparent + $remainging_chargeparent;
                                                    $reverse_chreg =$reverse_chregparent =0;
                                                    if($text=='Reverse'){
                                                        $ratereverse = Ratecard::where(['courier_id' => $c_id, 'transport' => 'Reverse','status' => 1,'user_id' => $user_id])->first();
                                                        $freight = $freight + @$ratereverse->$zone;
                                                        if(isset($ratereverse->$zone)){
                                                            $reverse_chreg =$ratereverse->$zone;
                                                        }
                                                        $ratereverseparent = Ratecard::where(['courier_id' => $c_id, 'transport' => 'Reverse','status' => 1,'user_id' => $parent_userid])->first();
                                                        $freightparent = $freightparent + @$ratereverseparent->$zone;
                                                        if(isset($ratereverseparent->$zone)){
                                                            $reverse_chregparent =$ratereverseparent->$zone;
                                                        }
                                                    }
                                                    $gst = (($freight + $cod) * 18) / 100;
                                                    $gst_cod = (($cod) * 18) / 100;
                                                    $gst_freight = (($freight) * 18) / 100;
                                                    if($payment == 'cod'){
                                                        $total = $gst + $freight + $cod;
                                                    } else {
                                                        $total = $gst + $freight;
                                                    }

                                                    $gstparent = (($freightparent + $codparent) * 18) / 100;
                                                    $gst_codparent = (($codparent) * 18) / 100;
                                                    $gst_freightparent = (($freightparent) * 18) / 100;
                                                    if($payment == 'cod'){
                                                        $totalparent = $gstparent + $freightparent + $codparent;
                                                    } else {
                                                        $totalparent = $gstparent + $freightparent;
                                                    }



                                                    $get[] = [
                                                        'courier_id' => $rate->courier_id,
                                                        'name' => $couriers[$rate->courier_id]['name'],
                                                        'img' => asset('public/courier').'/'.$couriers[$rate->courier_id]['image'],
                                                        'mode' => $transport == 'Air' ? 'fa-plane' : 'fa-truck',
                                                        'weight_used' => $c_weigt,
                                                        'weight' => round($weight_to_be_taken,2).' kg',
                                                        'zone' => $zone,

                                                        'price' => 'Rs.'.number_format($total,2),
                                                        'cod' => round($cod,2),
                                                        'reverse_charge' => round($reverse_chreg,2),
                                                        'gst' => round($gst,2),
                                                        'gst_cod' => round($gst_cod,2),
                                                        'gst_freight' => round($gst_freight,2),
                                                        'freight' => round($freight,2),
                                                        'rate' =>round($rate->$zone,2),
                                                        'rateadd' =>round($getrateadd,2),

                                                        'priceparent' => 'Rs.'.number_format($totalparent,2),
                                                        'codparent' => round($codparent,2),
                                                        'reverse_chargeparent' => round($reverse_chregparent,2),
                                                        'gstparent' => round($gstparent,2),
                                                        'gst_codparent' => round($gst_codparent,2),
                                                        'gst_freightparent' => round($gst_freightparent,2),
                                                        'freightparent' => round($freightparent,2),
                                                        'rateparent' =>round(@$rateparent->$zone,2),
                                                        'rateaddparent' =>round($getrateaddparent,2),


                                                    ];
                                                }    
                                               }
                                               if(count($get) == 0){
                                                   $retunarray[0]='error';
                                                   $retunarray[1]='Pincode is not Servicable!!';
                                               }else{
                                                    $required_corr_data =$get[0];
                                                    $number = str_replace('Rs.', '', $required_corr_data['price']);
                                                    $cleanedString = str_replace(',', '', $number);
                                                    $ship_courier_cost = (float) $cleanedString;

                                                    $numberparent = str_replace('Rs.', '', $required_corr_data['priceparent']);
                                                    $cleanedStringparent = str_replace(',', '', $numberparent);
                                                    $ship_courier_costparent = (float) $cleanedStringparent;
                                                    if((Admin::find($orde_user_id)->wallet_blc + Admin::find($orde_user_id)->limit_loan) <= $ship_courier_cost){
                                                        $retunarray[0]='error';
                                                        $retunarray[1]='Minimum money required '.$ship_courier_cost;
                                                    }else{
                                                        $order_with_detail_s = Order::with('detail')->where('id',$id)->first();
                                                        $warehouse = Warehouse::find($ware);
                                                        $warehousereturn = Warehouse::find($return_warehouse_id);
                                                        $awb_no ='';
                                                        $shipment_id = null;
//          ========================================assigning orders================================================--
                                                        if($required_corr_data['courier_id'] =='1'){
                                                            $pmode ='';
                                                            $coll_value =0;
                                                            if(strip_tags($order_with_detail_s['payment_mode']) =='C.O.D'){
                                                                $pmode = 'cod';
                                                                $coll_value = $order_with_detail_s['total'];
                                                            }elseif(strip_tags($order_with_detail_s['payment_mode']) =='Pre-Paid'){
                                                                $pmode = 'ppd';
                                                            }
                                                            $awb = Integration::get_awb_number($pmode);
                                                            if($awb !=''){
                                                                $response = json_decode($awb,true);
                                                                if(isset($response['awb']) && count($response['awb']) >0){
                                                                    $awb_no = $response['awb'][0];
                                                                    $manifest_d =
                                                                        array(
                                                                            'AWB_NUMBER'=>$awb_no,
                                                                            'order_NUMBER'=>$order_with_detail_s['order_id'],
                                                                            'PRODUCT'=>$pmode,
                                                                            'CONSIGNEE'=>$order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                                            'CONSIGNEE_ADDRESS1'=>$order_with_detail_s['ship_address'],
                                                                            'CONSIGNEE_ADDRESS2'=>$order_with_detail_s['ship_address_2'],
                                                                            'CONSIGNEE_ADDRESS3'=>'',
                                                                            'DESTINATION_CITY'=>$order_with_detail_s['ship_city'],
                                                                            'PINCODE'=>$order_with_detail_s['ship_pincode'],
                                                                            'STATE'=>$order_with_detail_s['ship_state'],
                                                                            'MOBILE'=>$order_with_detail_s['ship_phone'],
                                                                            'TELEPHONE'=>'',
                                                                            'ITEM_DESCRIPTION'=>'Multiple items',
                                                                            'PIECES'=>count($order_with_detail_s['detail']),
                                                                            'COLLECTABLE_VALUE'=>$coll_value,
                                                                            'DECLARED_VALUE'=>$order_with_detail_s['total'],
                                                                            'ACTUAL_WEIGHT'=>$order_with_detail_s['weight']/1000,
                                                                            'VOLUMETRIC_WEIGHT'=>($order_with_detail_s['length']*$order_with_detail_s['breadth']*$order_with_detail_s['height'])/5000,
                                                                            'LENGTH'=>$order_with_detail_s['length'],
                                                                            'BREADTH'=>$order_with_detail_s['breadth'],
                                                                            'HEIGHT'=>$order_with_detail_s['height'],
                                                                            'PICKUP_NAME'=>$warehouse['contact_name'],
                                                                            'PICKUP_ADDRESS_LINE1'=>$warehouse['address'],
                                                                            'PICKUP_ADDRESS_LINE2'=>$warehouse['address_2'],
                                                                            'PICKUP_PINCODE'=>$warehouse['pincode'],
                                                                            'PICKUP_PHONE'=>$warehouse['phone'],
                                                                            'PICKUP_MOBILE'=>$warehouse['phone'],
                                                                            'RETURN_NAME'=>$warehousereturn['contact_name'],
                                                                            'RETURN_ADDRESS_LINE1'=>$warehousereturn['address'],
                                                                            'RETURN_ADDRESS_LINE2'=>$warehousereturn['address_2'],
                                                                            'RETURN_PINCODE'=>$warehousereturn['pincode'],
                                                                            'RETURN_PHONE'=>$warehousereturn['phone'],
                                                                            'RETURN_MOBILE'=>$warehousereturn['phone'],
                                                                            'DG_SHIPMENT'=>$dg_goods
                                                                            //Pass ""true"" in case shipment package contains any item that is restricted for air travel as per DGCA guidelines. Otherwise pass ""false"

                                                                        );
                                                                        $manifest = Integration::shipment_ecom(json_encode($manifest_d,true));
//                                                                          echo $manifest;die;
                                                                          
                                                                        //checking api logs
                                                                        api_logs(json_encode($manifest_d,true),$manifest,$order_with_detail_s['id'],'1');
                                                                        $manifest = json_decode($manifest,true);
//                                                                        echo 'hi';die;
                                                                        if($manifest['shipments'][0]['success']){ 
                                                                            
                                                                        }else{$awb_no ='';
                                                                        $retunarray[0]='error';
                                                                            $retunarray[1]=': '.$manifest['shipments'][0]['reason'];
                                                                        }
                                                                }else{
                                                                    $retunarray[0]='error';
                                                                    $retunarray[1]=': AWB not fetched';
                                                                }
                                                            }else{
                                                                $retunarray[0]='error';
                                                                $retunarray[1]=': Payment mode not found';
                                                            }
                                                          }
                                                        else if($required_corr_data['courier_id'] =='2'){
                                                                $coll_value =0;
                                                                if(strip_tags($order_with_detail_s['payment_mode']) =='C.O.D'){
                                                                    $pmode = 'COD';
                                                                    $coll_value = $order_with_detail_s['total'];
                                                                }elseif(strip_tags($order_with_detail_s['payment_mode']) =='Pre-Paid'){
                                                                    $pmode = 'Prepaid';
                                                                }

                                                                if($required_corr_data['mode'] =='fa-plane'){
                                                                    $trsfr_type = 'Express';
                                                                    $ttype = 'a';
                                                                }else{
                                                                    $trsfr_type = 'Surface';
                                                                    $ttype = 's';
                                                                }
                                                                $awb = Integration::get_awb_number_delhivary($pmode,$ttype);
                                                                $awb = json_decode($awb,true);
                                                                $awb_no ='';
                                                                if((int)($awb) == $awb){
                                                                    $awb_no = $awb;
                                                                }
                                                                $s_Aaress = $order_with_detail_s['ship_address'].' '.$order_with_detail_s['ship_address_2'];
                                                                $w_Add = $warehouse['address'].' '.$warehouse['address_2'];
                                                                $delhivery_data = array(
                                                                    'shipments'=> array(array(
                                                                        'add' => str_replace('&','and',$s_Aaress),
                                                                        'phone' => $order_with_detail_s['ship_phone'],
                                                                        'payment_mode' => $pmode,
                                                                        'name' => $order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                                        'pin' => $order_with_detail_s['ship_pincode'],
                                                                        'order' => $order_with_detail_s['order_id'],
                                                                        'shipping_mode' => $trsfr_type,
                                                                        'shipment_height' => $order_with_detail_s['height'],
                                                                        'shipment_width' => $order_with_detail_s['breadth'],
                                                                        'shipment_length' => $order_with_detail_s['length'],
                                                                        'weight' => $order_with_detail_s['weight']/1000,
                                                                        'products_desc' => 'Multiple items',
                                                                        'cod_amount' => $coll_value,
                                                                        'waybill' => $awb_no,
                                                                        'total_amount' => $order_with_detail_s['total'],
                                                                        'quantity' => count($order_with_detail_s['detail']),
                                                                    )),
                                                                    'pickup_location' => array(
                                                                        'name' =>$warehouse['company'].$ttype,
                                                                        'city' => $warehouse['city'],
                                                                        'pin' => $warehouse['pincode'],
                                                                        'phone' => $warehouse['phone'],
                                                                        'add' => str_replace('&','and',$w_Add),
                                                                    )
                                                                );
//                                                                echo '<pre>';print_R($delhivery_data);die;
                                                                $ship_delhivary = Integration::shipment_delhivary('format=json&data='.urlencode(json_encode($delhivery_data)),$ttype);
                                                                //checking api logs
                                                                 api_logs(json_encode($delhivery_data),$ship_delhivary,$order_with_detail_s['id'],'2');
                                                                $d_data = json_decode($ship_delhivary,true);
                                                                if(!$d_data['success']){
                                                                    $rmkk ='';$awb_no ='';
                                                                    if(isset($d_data['packages'][0]) && isset($d_data['packages'][0]['remarks'])){
                                                                        $rmk =$d_data['packages'][0]['remarks'];
                                                                        for($k=0;$k<count($rmk);$k++){
                                                                            $rmkk .=$rmk[$k].',';
                                                                        }
                                                                        $rmkk = rtrim($rmkk,',');
                                                                    }
                                                                    if($rmkk !=''){
                                                                      $retunarray[0]='error';
                                                                      $retunarray[1]=$rmkk;
                                                                    }else{
                                                                        $retunarray[0]='error';
                                                                        $retunarray[1]=$d_data['rmk'];
                                                                    }
                                                                }

                                                            }
                                                        else if($required_corr_data['courier_id'] =='3'){
                                                          $w_id = $rw_id = '';
                                                          if(isset($warehouse->bd_id) && $warehouse->bd_id !=0 && $warehouse->bd_id !=''){
                                                              $w_id = $warehouse->bd_id;
                                                          }else{
                                                              $war_array = array(
                                                                  'address_title'=>$warehouse->id.' '.$warehouse->name,
                                                                  'sender_name'=>$warehouse->contact_name,
                                                                  'full_address'=>$warehouse->address.' '.$warehouse->address_2,
                                                                  'phone'=>$warehouse->phone,
                                                                  'pincode'=>$warehouse->pincode,
                                                              );
                                                              $getWarehouse =  Integration_more::warehouse_bludart(json_encode($war_array,true));
                                                              $warehusedetail = json_decode($getWarehouse,true);

                                                              if($warehusedetail['status'] && isset($warehusedetail['data']['pick_address_id'])){
                                                                  $w_id = $warehusedetail['data']['pick_address_id'];
                                                                  $warehouse->bd_id = $w_id;
                                                                  $warehouse->save();
                                                              }else{
                                                                  $msg = $warehusedetail['responsemsg'][0];
                                                                  $retunarray[0]='error';
                                                                  $retunarray[1]=$msg;
                                                              }

                                                          }

                                                          if($w_id !=''){
                                                              if($warehousereturn->id == $warehouse->id){
                                                                  $rw_id = $w_id;
                                                              }else{
                                                                  if(isset($warehousereturn->bd_id) && $warehousereturn->bd_id !=0 && $warehousereturn->bd_id !=''){
                                                                      $rw_id = $warehousereturn->bd_id;
                                                                  }else{
                                                                      $warret_array = array(
                                                                          'address_title'=>$warehousereturn->id.' '.$warehousereturn->name,
                                                                          'sender_name'=>$warehousereturn->contact_name,
                                                                          'full_address'=>$warehousereturn->address.' '.$warehousereturn->address_2,
                                                                          'phone'=>$warehousereturn->phone,
                                                                          'pincode'=>$warehousereturn->pincode,
                                                                      );
                                                                      $getWarereturnhouse =  Integration_more::warehouse_bludart(json_encode($warret_array,true));
                                                                      $warehusedetailretrun = json_decode($getWarereturnhouse,true);
                                                                      if($warehusedetailretrun['status'] && isset($warehusedetailretrun['data']['pick_address_id'])){
                                                                          $rw_id = $warehusedetailretrun['data']['pick_address_id'];
                                                                          $warehousereturn->bd_id = $rw_id;
                                                                          $warehousereturn->save();
                                                                      }else{
                                                                          $msg = $warehusedetailretrun['responsemsg'];
                                                                          $retunarray[0]='error';
                                                                          $retunarray[1]=$msg  .'for return warehouse';
                                                                      }
                                                                  }
                                                              }
                                                              if($rw_id !=''){    
                                                                  if($required_corr_data['mode'] =='fa-plane'){
                                                                      $p_type = 'air';
                                                                  }else{
                                                                      $p_type = 'surface';
                                                                  }
                                                                  $coll_value =0;
                                                                  $awb_no ='';
                                                                  if($order_with_detail_s['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">C.O.D</span>'){
                                                                      $pmode = 'Cod';
                                                                      $coll_value = $order_with_detail_s['total'];
                                                                  }elseif($order_with_detail_s['payment_mode'] =='<span class="badge text-white" style="background:#3a87ad">Pre-Paid</span>'){
                                                                      $pmode = 'Prepaid';
                                                                  }  
                                                                  $o_details =$order_with_detail_s['detail'];
                                                                  $order_items = array();
                                                                  for($i=0;$i<count($o_details);$i++){
                                                                      $order_items[] = array(
                                                                          'product_sku'=> $o_details[$i]['code'],
                                                                          'product_name'=> $o_details[$i]['name'],
                                                                          'product_value'=> $o_details[$i]['price'],
                                                                          'product_quantity'=>$o_details[$i]['qty'],
                                                                      );
                                                                  }         
                                                                  $bd_data = array(
                                                                      'client_order_id'=>$order_with_detail_s['order_id'],
                                                                      'consignee_emailid'=>$order_with_detail_s['ship_email'],
                                                                      'consignee_pincode'=>$order_with_detail_s['ship_pincode'],
                                                                      'consignee_mobile'=>$order_with_detail_s['ship_phone'],
                                                                      'consignee_phone'=>'',
                                                                      'consignee_address1'=>$order_with_detail_s['ship_address'],
                                                                      'consignee_address2'=>$order_with_detail_s['ship_address_2'],
                                                                      'consignee_name'=>$order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                                      'express_type'=>$p_type,
                                                                      'pick_address_id'=>$w_id,
                                                                      'return_address_id'=>$rw_id,
                                                                      'cod_amount'=>$coll_value,
                                                                      'tax_amount'=>0,
                                                                      'mps'=>'0',
                                                                      'courier_type'=>1,
                                                                      'courier_code'=>'PXBDE01',
                                                                      'products'=>$order_items,
                                                                      'payment_mode'=>$pmode,
                                                                      'order_amount'=>$order_with_detail_s['total'],
                                                                      'shipment_width'=>array(
                                                                          $order_with_detail_s['breadth']
                                                                      ),
                                                                      'shipment_height'=>array(
                                                                          $order_with_detail_s['height']
                                                                      ),
                                                                      'shipment_length'=>array(
                                                                          $order_with_detail_s['length']
                                                                      ),
                                                                      'shipment_weight'=>array(
                                                                          round(($order_with_detail_s['weight']/1000),2)
                                                                      ),
                                                                  );
                                                                  // echo json_encode($bd_data,true);die;
                                                                  $shimedata = Integration_more::shipment_bludart(json_encode($bd_data,true));
                                                                  //checking api logs
                                                                    api_logs(json_encode($bd_data,true),$shimedata,$order_with_detail_s['id'],'3');
                                                                  $shimedata = json_decode($shimedata,true);
                                                                  if($shimedata['status'] && isset($shimedata['data']['awb_number']) && $shimedata['data']['awb_number'] !=''){
                                                                      $awb_no = $shimedata['data']['awb_number'];
                                                                  }else{
                                                                      $msg = $shimedata['responsemsg'];
                                                                      if(gettype($msg) =='array'){
                                                                          if(count($msg)>0){
                                                                              $msg = $msg[0];
                                                                          }else{
                                                                              $msg = 'Issue in BD';
                                                                          }
                                                                      }
                                                                      $retunarray[0]='error';
                                                                      $retunarray[1]=$msg;
                                                                  }
                                                              }else{
                                                                  $retunarray[0]='error';
                                                                  $retunarray[1]='Return Warehouse id not found';
                                                              }
                                                          }else{

                                                              $retunarray[0]='error';
                                                              $retunarray[1]='Warehouse id not found';
                                                          }


                                                        }
                                                         else if($required_corr_data['courier_id'] =='4'){
                                                          $coll_value = 0;
                                                          $pmode = 'Prepaid';
                                                          if(strip_tags($order_with_detail_s['payment_mode']) =='C.O.D'){
                                                              $pmode = 'COD';
                                                              $coll_value = $order_with_detail_s['total'];
                                                          }
                                                          if($required_corr_data['mode']  =='fa-plane'){
                                                              $p_type = 'AIR';
                                                          }else{
                                                              $p_type = 'SURFACE';
                                                          }

                                                          // Fetch user-specific XpressBees credentials
                                                          $xb_integration = Integration::where('user_id', $order_with_detail_s['user_id'])->where('courier_id', 4)->first();
                                                          $xb_user = ($xb_integration && $xb_integration->xusername) ? $xb_integration->xusername : env('XBEES_USERNAME', 'admin@Hyloship.com');
                                                          $xb_pass = ($xb_integration && $xb_integration->xpassword) ? $xb_integration->xpassword : env('XBEES_PASSWORD', 'Xpress@1234567');
                                                          $xb_secret = ($xb_integration && $xb_integration->secret_key) ? $xb_integration->secret_key : env('XBEES_SECRETKEY', '5babb4d7a6c80b45ade918fb4e429068c8480e6125925c474d8d67a27f8190db');
                                                          $xb_biz_name = ($xb_integration && $xb_integration->b_account_name) ? $xb_integration->b_account_name : env('XBEES_BUSINESS_ACCOUNT', 'Hyloship');
                                                          $xb_key = ($xb_integration && $xb_integration->xxb_key) ? $xb_integration->xxb_key : env('XBEES_XB_KEY', 'Plmng39338VdtHa');

                                                          $vol_weight = round((($order_with_detail_s['length']*$order_with_detail_s['breadth']*$order_with_detail_s['height'])/5000),2);
                                                          $phy_weight = round($order_with_detail_s['weight']/1000, 2);
                                                          $bill_weight = max($phy_weight, $vol_weight);
                                                          $manifest_id = date('YmdHi') . $order_with_detail_s['order_id'];

                                                          $expess_data = array(
                                                              'BusinessAccountName' => $xb_biz_name,
                                                              'OrderNo'             => (string)$order_with_detail_s['order_id'],
                                                              'OrderType'           => $pmode,
                                                              'CollectibleAmount'   => (float)$coll_value,
                                                              'DeclaredValue'       => (float)$order_with_detail_s['total'],
                                                              'Quantity'            => count($order_with_detail_s['detail']),
                                                              'PickupType'          => 'Warehouse',
                                                              'ServiceType'         => 'SD',
                                                              'DropDetails' => array(
                                                                  'Addresses' => array(array(
                                                                      'Name'    => $order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                                      'Address' => $order_with_detail_s['ship_address'].' '.$order_with_detail_s['ship_address_2'],
                                                                      'City'    => $order_with_detail_s['ship_city'],
                                                                      'PinCode' => (string)$order_with_detail_s['ship_pincode'],
                                                                      'State'   => $order_with_detail_s['ship_state'],
                                                                      'Type'    => 'Primary',
                                                                  )),
                                                                  'ContactDetails' => array(array(
                                                                      'PhoneNo' => (string)$order_with_detail_s['ship_phone'],
                                                                      'Type'    => 'Primary',
                                                                  )),
                                                              ),
                                                              'PickupDetails' => array(
                                                                  'Addresses' => array(array(
                                                                      'Name'    => $warehouse['name'],
                                                                      'Address' => $warehouse['address'].' '.$warehouse['address_2'],
                                                                      'City'    => $warehouse['city'],
                                                                      'PinCode' => (string)$warehouse['pincode'],
                                                                      'State'   => $warehouse['state'],
                                                                      'Type'    => 'Primary',
                                                                  )),
                                                                  'ContactDetails' => array(array(
                                                                      'PhoneNo' => (string)$warehouse['phone'],
                                                                      'Type'    => 'Primary',
                                                                  )),
                                                                  'PickupVendorCode' => (string)$order_with_detail_s['user_id'],
                                                              ),
                                                              'RTODetails' => array(
                                                                  'Addresses' => array(array(
                                                                      'Name'    => $warehousereturn['name'],
                                                                      'Address' => $warehousereturn['address'].' '.$warehousereturn['address_2'],
                                                                      'City'    => $warehousereturn['city'],
                                                                      'PinCode' => (string)$warehousereturn['pincode'],
                                                                      'State'   => $warehousereturn['state'],
                                                                      'Type'    => 'Primary',
                                                                  )),
                                                                  'ContactDetails' => array(array(
                                                                      'PhoneNo' => (string)$warehousereturn['phone'],
                                                                      'Type'    => 'Primary',
                                                                  )),
                                                              ),
                                                              'PackageDetails' => array(
                                                                  'Dimensions' => array(
                                                                      'Height' => (float)$order_with_detail_s['height'],
                                                                      'Length' => (float)$order_with_detail_s['length'],
                                                                      'Width'  => (float)$order_with_detail_s['breadth'],
                                                                  ),
                                                                  'Weight' => array(
                                                                      'BillableWeight' => (float)$bill_weight,
                                                                      'PhyWeight'      => (float)$phy_weight,
                                                                      'VolWeight'      => (float)$vol_weight,
                                                                  ),
                                                              ),
                                                              'ManifestID' => $manifest_id,
                                                          );
                                                          $expess_data['ServiceType'] = $p_type;

                                                          // Step 1: Generate AWB dynamically
                                                          $awb_gen_data = json_encode([
                                                              "BusinessUnit" => "ECOM",
                                                              "ServiceType" => "FORWARD",
                                                              "DeliveryType" => ($pmode == 'COD') ? 'COD' : 'PREPAID',
                                                              "Count" => 1
                                                          ]);
                                                          $awb_res = Integration::generate_awb_series_xbess($awb_gen_data, $xb_user, $xb_pass, $xb_secret, $xb_key);
                                                          $awb_data = json_decode($awb_res, true);

                                                          if (
                                                              (isset($awb_data['ReturnCode']) && $awb_data['ReturnCode'] == '100') ||
                                                              (isset($awb_data['ReturnMessage']) && stripos($awb_data['ReturnMessage'], 'success') !== false)
                                                          ) {
                                                              $batch_id = $awb_data['BatchID'] ?? null;
                                                              if ($batch_id) {
                                                                  // Step 2: Fetch the generated AWB using BatchID
                                                                  $get_awb_data = json_encode([
                                                                      "BusinessUnit" => "ECOM",
                                                                      "ServiceType" => "FORWARD",
                                                                      "BatchID" => $batch_id
                                                                  ]);
                                                                  $fetch_res = Integration::get_awb_series_xbess($get_awb_data, $xb_user, $xb_pass, $xb_secret, $xb_key);
                                                                  $fetch_data = json_decode($fetch_res, true);

                                                                  if (isset($fetch_data['AWBNoSeries'][0])) {
                                                                      $expess_data['AirWayBillNO'] = $fetch_data['AWBNoSeries'][0];
                                                                  } else if (isset($awb_data['AWBNo']) && $awb_data['AWBNo'] != '') {
                                                                      $expess_data['AirWayBillNO'] = $awb_data['AWBNo'];
                                                                  } else {
                                                                      $retunarray[0]='error';
                                                                      $retunarray[1]='AWB Error: Batch generated but no AWB found in series.';
                                                                  }
                                                              } else if (isset($awb_data['AWBNo']) && $awb_data['AWBNo'] != '') {
                                                                  $expess_data['AirWayBillNO'] = $awb_data['AWBNo'];
                                                              } else {
                                                                  $retunarray[0]='error';
                                                                  $retunarray[1]='AWB Error: No AWB or BatchID returned.';
                                                              }
                                                          } else {
                                                              $awb_msg = $awb_data['ReturnMessage'] ?? 'Failed to generate AWB number';
                                                              $retunarray[0]='error';
                                                              $retunarray[1]='AWB Error: ' . $awb_msg;
                                                          }

                                                          // Step 3: Submit manifest if AWB was obtained
                                                          if (isset($expess_data['AirWayBillNO']) && $expess_data['AirWayBillNO'] != '') {
                                                              $manifestRes = Integration::shipment_express(json_encode($expess_data, true), $xb_user, $xb_pass, $xb_secret, $xb_key);
                                                              api_logs(json_encode($expess_data, true), $manifestRes ?? 'NULL_RESPONSE', $order_with_detail_s['id'], '4');
                                                              $manifest = json_decode($manifestRes, true);

                                                              if ($manifest === null || !is_array($manifest)) {
                                                                  $retunarray[0]='error';
                                                                  $retunarray[1]='XpressBees API did not respond. Raw: ' . substr((string)$manifestRes, 0, 200);
                                                              } else {
                                                                  $isSuccess = false;
                                                                  if (isset($manifest['ReturnCode']) && $manifest['ReturnCode'] == '100') {
                                                                      $isSuccess = true;
                                                                  } elseif (isset($manifest['message']) && stripos($manifest['message'], 'success') !== false) {
                                                                      $isSuccess = true;
                                                                  } elseif (isset($manifest['ReturnMessage']) && stripos($manifest['ReturnMessage'], 'success') !== false) {
                                                                      $isSuccess = true;
                                                                  }

                                                                  if ($isSuccess) {
                                                                      if (isset($manifest['AWBNo']) && $manifest['AWBNo'] != '') {
                                                                          $awb_no = (string)$manifest['AWBNo'];
                                                                      } elseif (isset($manifest['data'][0]['AWBNo']) && $manifest['data'][0]['AWBNo'] != '') {
                                                                          $awb_no = (string)$manifest['data'][0]['AWBNo'];
                                                                      } else {
                                                                          $awb_no = $expess_data['AirWayBillNO'];
                                                                      }
                                                                  } else {
                                                                      $msg = $manifest['ReturnMessage'] ?? $manifest['message'] ?? 'Manifest Error';
                                                                      $retunarray[0]='error';
                                                                      $retunarray[1]=$msg;
                                                                  }
                                                              }
                                                          }



                                                        }
                                                        else if($required_corr_data['courier_id'] =='5'){
                                                            $coll_value =0;
                                                            $awb_no ='';
                                                            $coll_mode = '';
                                                            if(strip_tags($order_with_detail_s['payment_mode']) =='C.O.D'){
                                                                $pmode = 'cod';
                                                                $coll_value = $order_with_detail_s['total'];
                                                                $coll_mode = 'cash';
                                                            }elseif(strip_tags($order_with_detail_s['payment_mode']) =='Pre-Paid'){
                                                                $pmode = 'prepaid';
                                                            }
                                                            if($required_corr_data['mode'] =='fa-plane'){
                                                                $trsfr_type = 'B2C PRIORITY';
                                                            }else{
                                                                $trsfr_type = 'B2C SMART EXPRESS';
                                                            }
                                                            $dtdc = array(
                                                                'consignments' =>array( array(
                                                                    'customer_code' =>'GL7569',
                                                                    'service_type_id' =>$trsfr_type,
                                                                    'load_type' =>'NON-DOCUMENT',
                                                                    'consignment_type' =>'Forward',
                                                                    'dimension_unit' =>'cm',
                                                                    'length' =>$order_with_detail_s['length'],
                                                                    'width' =>$order_with_detail_s['breadth'],
                                                                    'height' =>$order_with_detail_s['height'],
                                                                    'num_pieces' =>count($order_with_detail_s['detail']),
                                                                    'weight_unit' =>'kg',
                                                                    'weight' =>$order_with_detail_s['weight']/1000,
                                                                    'customer_reference_number' =>$order_with_detail_s['order_id'],
                                                                    'commodity_id' =>'7',
                                                                    'reference_number' =>'',
                                                                    'declared_value' =>$order_with_detail_s['total'],
                                                                    'cod_amount' =>$coll_value,
                                                                    'cod_collection_mode'=> $coll_mode,
                                                                    'origin_details' => array(
                                                                        'name' =>$warehouse['name'],
                                                                        'phone' =>$warehouse['phone'],
                                                                        'alternate_phone' =>'8851678080',
                                                                        'address_line_1' =>$warehouse['address'],
                                                                        'address_line_2' =>$warehouse['address_2'],
                                                                        'pincode' =>$warehouse['pincode'],
                                                                        'city' =>$warehouse['city'],
                                                                        'state' =>$warehouse['state'],
                                                                    ),
                                                                    'destination_details'=> array(
                                                                        'name' =>$order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                                        'phone' =>$order_with_detail_s['ship_phone'],
                                                                        'alternate_phone' =>'9958523480',
                                                                        'address_line_1' =>$order_with_detail_s['ship_address'],
                                                                        'address_line_2' =>$order_with_detail_s['ship_address_2'],
                                                                        'pincode' =>$order_with_detail_s['ship_pincode'],
                                                                        'city' =>$order_with_detail_s['ship_city'],
                                                                        'state' =>$order_with_detail_s['ship_state'],
                                                                    ),
                                                                    'return_details'=> array(
                                                                        'name' =>$warehousereturn['name'],
                                                                        'phone' =>$warehousereturn['phone'],
                                                                        'alternate_phone' =>'8851678080',
                                                                        'address_line_1' =>$warehousereturn['address'],
                                                                        'address_line_2' =>$warehousereturn['address_2'],
                                                                        'pincode' =>$warehousereturn['pincode'],
                                                                        'city' =>$warehousereturn['city'],
                                                                        'state' =>$warehousereturn['state'],
                                                                     ),
                                                                )
                                                            ));
                                                            $res_dtdc = Integration::shipment_dtdc(json_encode($dtdc));
                                                            //checking api logs
                                                            api_logs(json_encode($dtdc),$res_dtdc,$order_with_detail_s['id'],'5');
                                                            $res_dtdc = json_decode($res_dtdc,true);
                                                            if(isset($res_dtdc['data']) && isset($res_dtdc['data'][0])){
                                                                if($res_dtdc['data'][0]['success']){
                                                                    $awb_no = $res_dtdc['data'][0]['reference_number'];
                                                                }else{
                                                                  $retunarray[0]='error';
                                                                  $retunarray[1]=$res_dtdc['data'][0]['message'];
                                                                }
                                                            }
                                                        }
                                                        else if($required_corr_data['courier_id'] =='6'){
                                                            $coll_value =0;
                                                            $awb_no ='';
                                                            $p_type='';
                                                            if(strip_tags($order_with_detail_s['payment_mode']) =='C.O.D'){
                                                                $coll_value = $order_with_detail_s['total'];
                                                                if($required_corr_data['mode'] =='fa-plane'){
                                                                    $p_type = 'ACC';
                                                                    $is_surface = false;
                                                                }else{
                                                                    $p_type = 'WKO';
                                                                    $is_surface = true;
                                                                }
                                                            }elseif(strip_tags($order_with_detail_s['payment_mode']) =='Pre-Paid'){
                                                                if($required_corr_data['mode'] =='fa-plane'){
                                                                    $p_type = 'ACP';
                                                                    $is_surface = false;
                                                                }else{
                                                                    $p_type = 'WKO';
                                                                    $is_surface = true;
                                                                }
                                                            }

                                                            $smartr = array( array(
                                                                'packageDetails' =>array(
                                                                    'awbNumber' =>'',
                                                                    'orderNumber' =>$order_with_detail_s['order_id'],
                                                                    'productType' =>$p_type,
                                                                    'collectableValue' =>(string)($coll_value),
                                                                    'declaredValue' =>(string)($order_with_detail_s['total']),
                                                                    'itemDesc' =>"Multiple Items",
                                                                    'dimensions' =>$order_with_detail_s['length'].'~'.$order_with_detail_s['breadth'].'~'.$order_with_detail_s['height'].'~'.count($order_with_detail_s['detail']).'~'.($order_with_detail_s['weight']/1000).'~0',
                                                                    'pieces' =>(string)count($order_with_detail_s['detail']),
                                                                    'weight' =>(string)$order_with_detail_s['weight']/1000,
                                                                    'invoiceNumber' =>'',
                                                                ),
                                                                'deliveryDetails' =>array(
                                                                    'toName'=> $order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                                    'toAdd'=> $order_with_detail_s['ship_address'].' '.$order_with_detail_s['ship_address_2'],
                                                                    'toCity'=> $order_with_detail_s['ship_city'],
                                                                    'toState'=> $order_with_detail_s['ship_state'],
                                                                    'toPin'=> $order_with_detail_s['ship_pincode'],
                                                                    'toMobile'=> $order_with_detail_s['ship_phone'],
                                                                    'toAddType'=> 'Home',
                                                                    'toLat'=> '',
                                                                    'toLng'=> '',
                                                                    'toEmail'=>  $order_with_detail_s['ship_email']
                                                                ),
                                                                'pickupDetails' =>array(
                                                                    'fromName'=> $warehouse['name'],
                                                                    'fromAdd'=> $warehouse['address'].' '.$warehouse['address_2'],
                                                                    'fromCity'=> $warehouse['city'],
                                                                    'fromState'=> $warehouse['state'],
                                                                    'fromPin'=> $warehouse['pincode'],
                                                                    'fromMobile'=> $warehouse['phone'],
                                                                    'fromAddType'=> 'Seller',
                                                                    'fromLat'=> '',
                                                                    'fromLng'=> '',
                                                                    'fromEmail'=> $warehouse['email']
                                                                ),
                                                                'returnDetails' =>array(
                                                                    'rtoName'=> $warehousereturn['name'],
                                                                    'rtoAdd'=> $warehousereturn['address'].' '.$warehousereturn['address_2'],
                                                                    'rtoCity'=> $warehousereturn['city'],
                                                                    'rtoState'=> $warehousereturn['state'],
                                                                    'rtoPin'=> $warehousereturn['pincode'],
                                                                    'rtoMobile'=> $warehousereturn['phone'],
                                                                    'rtoAddType'=> 'Seller',
                                                                    'rtoLat'=> '',
                                                                    'rtoLng'=> '',
                                                                    'rtoEmail'=> $warehousereturn['email']
                                                                ),
                                                                'additionalInformation'=> array(
                                                                    'customerCode'=> '',
                                                                    'essentialFlag'=> '',
                                                                    'otpFlag'=> '',
                                                                    'dgFlag'=> '',
                                                                    'isSurface'=> $is_surface,
                                                                    'isReverse'=> 'false',
                                                                    'sellerGSTIN'=> $warehouse['gst_no'],
                                                                    'sellerERN'=> $order_with_detail_s['e_bill_no']
                                                                )
                                                            ));
                                                            $res_smartr= Integration::shipment_smartr(json_encode($smartr));
                                                            //checking api logs
                                                            api_logs(json_encode($smartr),$res_smartr,$order_with_detail_s['id'],'6');
                                                            $res_smartr = json_decode($res_smartr,true);
                                                            if(isset($res_smartr['success']) && !empty($res_smartr['total_success'])){
                                                                $awb_no = $res_smartr['total_success'][0]['awbNumber'];
                                                            }else{
                                                                if(isset($res_smartr['total_failure'][0])){
                                                                  $retunarray[0]='error';
                                                                  $retunarray[1]=$res_smartr['total_failure'][0]['error'];
                                                                }
                                                            }
                                                        }
                                                        else if($required_corr_data['courier_id'] =='7'){
                                                            $coll_value =0;
                                                            $awb_no ='';
                                                            $p_type='';
                                                            $service_code = 'ECONOMY';
                                                            if($required_corr_data['mode'] =='fa-plane'){
                                                                $service_code = 'REGULAR';
                                                            }
                                                            if($order_with_detail_s['cancel_date'] !='' || $order_with_detail_s['cancel_date'] != null){
                                                                $time = explode(' ',$order_with_detail_s['cancel_date'])[1];
                                                                $time = str_replace(':','',$time);
                                                                $date = explode(' ',$order_with_detail_s['cancel_date'])[0];
                                                                $day = explode('-',$date)[2];
                                                                $o_id =$time.$order_with_detail_s['order_id'].$day;
                                                            }else{
                                                                $o_id = $order_with_detail_s['order_id'];
                                                            }
                                                            if(strlen($o_id)>10){
                                                                $o_id = substr($o_id,0,10);
                                                            }
                                                            if(strip_tags($order_with_detail_s['payment_mode']) =='C.O.D'){
                                                                $coll_value = $order['total'];
                                                                $t_id = 'TOSC'.sprintf('%010d', $o_id);
                                                            }elseif(strip_tags($order_with_detail_s['payment_mode']) =='Pre-Paid'){
                                                                $t_id = 'TOSP'.sprintf('%010d', $o_id);
                                                            }

                                                            $shipment_items = array();
                                                            foreach($order_with_detail_s['detail'] as $detail){
                                                                $shipment_items[] =array(
                                                                    'product_id'=>$detail['code'],
                                                                    'category'=>'miscellaneous',
                                                                    'product_title'=>$detail['name'],
                                                                    'quantity'=>$detail['qty'],
                                                                    'cost'=>array(
                                                                        'totalSaleValue' =>$detail['price']*$detail['qty'],
                                                                        'totalTaxValue'=>$detail['tax_amount'],
                                                                        'tax_breakup'=>array(
                                                                            "cgst"=>'0.00',
                                                                            "sgst"=>'0.00',
                                                                            "igst"=>'0.00',
                                                                        )
                                                                    ),
                                                                    'seller_details' =>array(
                                                                        'seller_reg_name'=>'Hyloship',
                                                                        'vat_id'=>'',
                                                                        'cst_id'=>'',
                                                                        'gstin_id'=>'',
                                                                    ),
                                                                    'hsn'=>'',
                                                                    'item_attributes'=>array(
                                                                        array(
                                                                            'name'=>'order_id',
                                                                            'value'=>(string)($detail['order_id']),
                                                                        ),
                                                                        array(
                                                                            'name'=>'invoice_id',
                                                                            'value'=>'invmy_'.date('my').$detail['user_id'],
                                                                        ),
                                                                    ),
                                                                    'handling_attributes'=>array(),
                                                                );
                                                            }
                                                            // echo '<pre>';print_r($shipment_items);die;
                                                            $ekart = array(
                                                                'request_Id' =>'',
                                                                'client_name' =>'TOS',
                                                                'services' =>array(array(
                                                                    'service_code' =>$service_code,
                                                                    'service_details' =>array(array(
                                                                        'service_leg' =>'FORWARD',
                                                                        'service_data' =>array(
                                                                            'vendor_name' =>$order_with_detail_s['channel'],
                                                                            'amount_to_collect' =>$coll_value,
                                                                            'dispatch_date' =>'',
                                                                            'source' =>array(
                                                                                'address'=>array(
                                                                                    'first_name'=>$warehouse['name'],
                                                                                    'address_line1'=>$warehouse['address'].' '.$warehouse['address_2'],
                                                                                    'pincode'=>$warehouse['pincode'],
                                                                                    'city'=>$warehouse['city'],
                                                                                    'state'=>$warehouse['state'],
                                                                                    'primary_contact_number'=>$warehouse['phone'],
                                                                                )
                                                                            ),
                                                                            'destination'=>array(
                                                                                'address'=>array(
                                                                                    'first_name'=>$order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                                                    'address_line1'=>$order_with_detail_s['ship_address'].' '.$order_with_detail_s['ship_address_2'],
                                                                                    'pincode'=>$order_with_detail_s['ship_pincode'],
                                                                                    'city'=>$order_with_detail_s['ship_city'],
                                                                                    'state'=>$order_with_detail_s['ship_state'],
                                                                                    'primary_contact_number'=>$order_with_detail_s['ship_phone'],
                                                                                )
                                                                            ),
                                                                            'return_location'=>array(
                                                                                'address'=>array(
                                                                                    'first_name'=>$warehousereturn['name'],
                                                                                    'address_line1'=>$warehousereturn['address'].' '.$warehousereturn['address_2'],
                                                                                    'pincode'=>$warehousereturn['pincode'],
                                                                                    'city'=>$warehousereturn['city'],
                                                                                    'state'=>$warehousereturn['state'],
                                                                                    'primary_contact_number'=>$warehousereturn['phone'],
                                                                                )
                                                                            ),
                                                                        ),
                                                                        'shipment'=>array(
                                                                            'tracking_id' =>$t_id,
                                                                            'shipment_value' =>$order_with_detail_s['total'],
                                                                            'shipment_dimensions' =>array(
                                                                                'length'=>array(
                                                                                    'value'=>$order_with_detail_s['length']
                                                                                ),
                                                                                'breadth'=>array(
                                                                                    'value'=>$order_with_detail_s['breadth']
                                                                                ),
                                                                                'height'=>array(
                                                                                    'value'=>$order_with_detail_s['height']
                                                                                ),
                                                                                'weight'=>array(
                                                                                    'value'=>$order_with_detail_s['weight']/1000
                                                                                ),
                                                                            ),
                                                                            'shipment_items'=>$shipment_items,
                                                                        ),


                                                                    ),),
                                                                ),),
                                                            );
                                                            $res_ekart= Integration_more::shipment_smartr(json_encode($ekart));
                                                            //checking api logs
                                                            api_logs(json_encode($ekart),$res_ekart,$order_with_detail_s['id'],'7');
                                                            $res_ekart = json_decode($res_ekart,true);
                                                            if(isset($res_ekart['response'][0]) && $res_ekart['response'][0]['status'] =='REQUEST_RECEIVED'){
                                                                $awb_no = $res_ekart['response'][0]['tracking_id'];
                                                            }else{
                                                                if(isset($res_ekart['response'][0]) && $res_ekart['response'][0]['status'] =='REQUEST_REJECTED'){
                                                                  $retunarray[0]='error';
                                                                  $retunarray[1]=$res_ekart['response'][0]['message'][0];
                                                                }else{
                                                                  $retunarray[0]='error';
                                                                  $retunarray[1]='Ekart is not responding';
                                                                }
                                                            }

                                                        }
                                                        else if($required_corr_data['courier_id'] =='8'){
                                                          $coll_value =0;
                                                          $awb_no = $awb ='';
                                                          $p_type='';
                                                          $arrayawb = array(
                                                              'count' =>1
                                                          );
                                                          $awb_shadowfax = Integration_more::get_awb_number_shadowfax('forward',json_encode($arrayawb,true));
                                                          // $awb_shadowfax ='{"message":"success","awb_numbers":["SF10000006KIM"]}';
                                                          $awb_shadowfax =  json_decode($awb_shadowfax,true);
                                                          if(isset($awb_shadowfax['message']) && $awb_shadowfax['message'] =='success'){
                                                              $awb = $awb_shadowfax['awb_numbers'][0];
                                                          }
                                                          if($awb !=''){
                                                              if(strip_tags($order_with_detail_s['payment_mode']) =='C.O.D'){
                                                                  $pmode = 'COD';
                                                                  $coll_value = $order_with_detail_s['total'];
                                                              }elseif(strip_tags($order_with_detail_s['payment_mode']) =='Pre-Paid'){
                                                                  $pmode = 'Prepaid';
                                                              }
                                                              if($required_corr_data['mode'] =='fa-plane'){
                                                                  $trsfr_type = 'ndd';
                                                              }else{
                                                                  $trsfr_type = 'regular';
                                                              }

                                                              $shipment_items = array();
                                                              foreach($order_with_detail_s['detail'] as $detail){
                                                                  $shipment_items[] =array(
                                                                      'sku_name'=>$detail['code'],
                                                                      'hsn_code'=>'',
                                                                      'invoice_no'=>'invmy_'.date('my').$detail['user_id'],
                                                                      'category'=>'miscellaneous',
                                                                      'price'=>$detail['price'],
                                                                      'seller_details' =>array(
                                                                          'seller_name'=>'Hyloship',
                                                                          'seller_address'=>'',
                                                                          'seller_state'=>'',
                                                                          'gstin_number'=>'',
                                                                      ),
                                                                      'taxes' =>array(
                                                                          "cgst"=>'0.00',
                                                                          "sgst"=>'0.00',
                                                                          "igst"=>'0.00',
                                                                          "total_tax"=>$detail['tax_amount'],
                                                                      ), 
                                                                      'additional_details'=>array(
                                                                          'quantity'=>$detail['qty'],
                                                                      )  
                                                                  );
                                                              }

                                                              $shadowfax_data = array(
                                                                  'order_type' =>'marketplace',
                                                                  'order_details'=>array(
                                                                      'client_order_id'=>$order_with_detail_s['order_id'],
                                                                      'awb_number'=>$awb,
                                                                      'actual_weight'=>$order_with_detail_s['weight'],
                                                                      'volumetric_weight'=>($order_with_detail_s['length']*$order_with_detail_s['breadth']*$order_with_detail_s['height'])/5,
                                                                      'product_value'=>$order_with_detail_s['total'],
                                                                      'payment_mode'=>$pmode,
                                                                      'cod_amount'=>$coll_value,
                                                                      'total_amount'=>$order_with_detail_s['total'],
                                                                      'eway_bill'=>$order_with_detail_s['e_bill_no'],
                                                                      'gstin_number'=>$order_with_detail_s['ship_gstin'],
                                                                      'order_service'=>$trsfr_type,
                                                                  ),
                                                                  'customer_details'=>array(
                                                                      'name'=>$order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                                      'contact'=>$order_with_detail_s['ship_phone'],
                                                                      'address_line_1'=>$order_with_detail_s['ship_address'],
                                                                      'address_line_2'=>$order_with_detail_s['ship_address_2'],
                                                                      'city'=>$order_with_detail_s['ship_city'],
                                                                      'state'=>$order_with_detail_s['ship_state'],
                                                                      'pincode'=>$order_with_detail_s['ship_pincode'],
                                                                      'latitude'=>$order_with_detail_s['ship_latitude'],
                                                                      'longitude'=>$order_with_detail_s['ship_longitude'],
                                                                  ),
                                                                  'pickup_details'=>array(
                                                                      'name'=>$warehouse['company'],
                                                                      'contact'=>$warehouse['phone'],
                                                                      'address_line_1'=>$warehouse['address'],
                                                                      'address_line_2'=>$warehouse['address_2'],
                                                                      'city'=>$warehouse['city'],
                                                                      'state'=>$warehouse['state'],
                                                                      'pincode'=>$warehouse['pincode'],
                                                                      'latitude'=>$warehouse['latitude'],
                                                                      'longitude'=>$warehouse['longitude'],
                                                                  ),
                                                                  'rts_details'=>array(
                                                                      'name'=>$warehousereturn['company'],
                                                                      'contact'=>$warehousereturn['phone'],
                                                                      'address_line_1'=>$warehousereturn['address'],
                                                                      'address_line_2'=>$warehousereturn['address_2'],
                                                                      'city'=>$warehousereturn['city'],
                                                                      'state'=>$warehousereturn['state'],
                                                                      'pincode'=>$warehousereturn['pincode'],
                                                                  ),
                                                                  'product_details'=>$shipment_items,
                                                              );
                                                              $res_shadowfax= Integration_more::shipment_shadowfax('forward',json_encode($shadowfax_data));
                                                              //checking api logs
                                                              api_logs(json_encode($shadowfax_data),$res_shadowfax,$order_with_detail_s['id'],'8');
                                                              $res_shadowfax = json_decode($res_shadowfax,true);
                                                              if(isset($res_shadowfax['message']) && $res_shadowfax['message'] =='Success'){
                                                                  $awb_no = $res_shadowfax['data']['awb_number'];
                                                              }else{
                                                                  $e_msg ='Issue in data';
                                                                  if(isset($res_shadowfax['errors'])){
                                                                      $e_msg = is_array($res_shadowfax['errors']) ? json_encode($res_shadowfax['errors']) : $res_shadowfax['errors'];
                                                                  }
                                                                  else if(isset($res_shadowfax['errorCode'])){
                                                                      $e_msg =$res_shadowfax['errorCode'];
                                                                  }
                                                                  else if(isset($res_shadowfax['message'])){
                                                                      $e_msg =$res_shadowfax['message'];
                                                                  }
                                                                  $retunarray[0]='error';
                                                                  $retunarray[1]=$e_msg;
                                                              }
                                                          }else{
                                                              $retunarray[0]='error';
                                                              $retunarray[1]='Issue in generating awb no. for shadowfax';
                                                          }
                                                        }
                                                        else if($required_corr_data['courier_id'] =='9'){
                                                            $coll_value =0;
                                                            $awb_no = $awb ='';
                                                            $p_type='';

                                                            if(strip_tags($order_with_detail_s['payment_mode']) =='C.O.D'){
                                                                $pmode = 'COD';
                                                                $coll_value = $order_with_detail_s['total'];
                                                                    $cod_Array = array(array(
                                                                    'id'=>'CollectOnDelivery',
                                                                    'amount'=>array(
                                                                        'unit'=>'INR',
                                                                        'value'=>$order_with_detail_s['total'],
                                                                    )

                                                                   ));
                                                            }elseif(strip_tags($order_with_detail_s['payment_mode']) =='Pre-Paid'){
                                                                $pmode = 'Prepaid';
                                                                    $cod_Array =array();
                                                            }
                                                            if($required_corr_data['mode'] =='fa-plane'){
                                                                $trsfr_type = 'SWA-IN-OA';
                                                            }else{
                                                                $trsfr_type = 'SWA-IN-OA';
                                                            }

                                                            $shipment_items = array();
                                                            foreach($order_with_detail_s['detail'] as $detail){
                                                                $shipment_items[] =array(
                                                                    'itemValue'=>array(
                                                                        'value'=>$detail['price'],
                                                                        'unit'=>'INR',
                                                                    ),
                                                                    'weight'=>array(
                                                                        'unit'=>'GRAM',
                                                                        'value'=>50.0,
                                                                    ),
                                                                    'description'=>$detail['name'],
                                                                    'itemIdentifier'=>'"'.$detail['id'].'"',
                                                                    'quantity'=>$detail['qty'],

                                                                );
                                                            }
                                                            if(strlen($order_with_detail_s['ship_address']) >55){
                                                                $order_with_detail_s['ship_address'] = trim(preg_replace('/\s+/', ' ', substr($order_with_detail_s['ship_address'],0,55)));
                                                                $order_with_detail_s['ship_address_2'] = substr(trim(preg_replace('/\s+/', ' ', substr($order_with_detail_s['ship_address'],55,strlen($order_with_detail_s['ship_address'])).' '.$order_with_detail_s['ship_address_2'])),0,55);
                                                            }
                                                            if(strlen($warehouse['address']) >55){
                                                                $warehouse['address'] = trim(preg_replace('/\s+/', ' ', substr($warehouse['address'],0,55)));
                                                                $warehouse['address_2'] = substr(trim(preg_replace('/\s+/', ' ', substr($warehouse['address'],55,strlen($warehouse['address'])).' '.$warehouse['address_2'])),0,55);
                                                            }
                                                            if(strlen($warehousereturn['ship_address']) >55){
                                                                $warehousereturn['address'] = trim(preg_replace('/\s+/', ' ', substr($warehousereturn['address'],0,55)));
                                                                $warehousereturn['address_2'] = substr(trim(preg_replace('/\s+/', ' ', substr($warehousereturn['address'],55,strlen($warehousereturn['address'])).' '.$warehousereturn['address_2'])),0,55);
                                                            }
                                                            $ats_data = array(
                                                                'shipTo'=>array(
                                                                    'name'=>$order_with_detail_s['ship_fname'].' '.$order_with_detail_s['ship_lname'],
                                                                    'addressLine1'=>$order_with_detail_s['ship_address'],
                                                                    'addressLine2'=>$order_with_detail_s['ship_address_2'],
                                                                    // 'companyName'=>$order_with_detail_s['ship_address_2'],
                                                                    'stateOrRegion'=>$order_with_detail_s['ship_state'],
                                                                    'city'=>$order_with_detail_s['ship_city'],
                                                                    'countryCode'=>'IN',
                                                                    'postalCode'=>$order_with_detail_s['ship_pincode'],
                                                                    'email'=>$order_with_detail_s['ship_email'],
                                                                    'phoneNumber'=>$order_with_detail_s['ship_phone'],
                                                                ),
                                                                'shipFrom'=>array(
                                                                    'name'=>$warehouse['name'],
                                                                    'addressLine1'=>$warehouse['address'],
                                                                    'addressLine2'=>$warehouse['address_2'],
                                                                    'companyName'=>$warehouse['company'],
                                                                    'stateOrRegion'=>$warehouse['state'],
                                                                    'city'=>$warehouse['city'],
                                                                    'countryCode'=>'IN',
                                                                    'postalCode'=>$warehouse['pincode'],
                                                                    'email'=>$warehouse['email'],
                                                                    'phoneNumber'=>$warehouse['phone'],
                                                                ),
                                                                'returnTo'=>array(
                                                                    'name'=>$warehousereturn['name'],
                                                                    'addressLine1'=>$warehousereturn['address'],
                                                                    'addressLine2'=>$warehousereturn['address_2'],
                                                                    'companyName'=>$warehousereturn['company'],
                                                                    'stateOrRegion'=>$warehousereturn['state'],
                                                                    'city'=>$warehousereturn['city'],
                                                                    'countryCode'=>'IN',
                                                                    'postalCode'=>$warehousereturn['pincode'],
                                                                    'email'=>$warehousereturn['email'],
                                                                    'phoneNumber'=>$warehousereturn['phone'],
                                                                ),
                                                                'packages'=>array(
                                                                    array(
                                                                        'dimensions' =>array(
                                                                            'length'=>$order_with_detail_s['length'],
                                                                            'width'=>$order_with_detail_s['breadth'],
                                                                            'height'=>$order_with_detail_s['height'],
                                                                            'unit'=>'CENTIMETER',
                                                                        ),
                                                                        'weight' =>array(
                                                                            'unit'=>'GRAM',
                                                                            'value'=>$order_with_detail_s['weight'],
                                                                        ),
                                                                        'insuredValue' =>array(
                                                                            'unit'=>'INR',
                                                                            'value'=>$order_with_detail_s['total'],
                                                                        ),
                                                                        'isHazmat'=>false,
                                                                        'sellerDisplayName'=>'',
                                                                        'packageClientReferenceId'=>$order_with_detail_s['order_id'],
                                                                        'items'=>$shipment_items,

                                                                    ),
                                                                ),
                                                                    'valueAddedServiceDetails'=>$cod_Array,
                                                                'taxDetails'=>array(
                                                                    array(
                                                                        'taxType'=>'GST',
                                                                        'taxRegistrationNumber'=>'"'.$order_with_detail_s['ship_gstin'].'"',
                                                                    ),
                                                                ),
                                                                'channelDetails'=>array(
                                                                    'channelType'=>'EXTERNAL',
                                                                ),
                                                                'serviceSelection'=>array(
                                                                    'serviceId'=>array(
                                                                        $trsfr_type
                                                                    ),
                                                                ),
                                                                'labelSpecifications'=>array(
                                                                    'format'=>'PDF',
                                                                    'size'=>array(
                                                                        'width'=>4,
                                                                        'length'=>6,
                                                                        'unit'=>'INCH',
                                                                    ),
                                                                    'dpi'=>203,
                                                                    'pageLayout'=>'DEFAULT',
                                                                    'needFileJoining'=>false,
                                                                    'requestedDocumentTypes'=>array(
                                                                        'LABEL'
                                                                    ),
                                                                ),
                                                            );
                                                            $res_ats= Integration_more::shipment_ats(json_encode($ats_data));
                                                          //checking api logs
                                                          api_logs(json_encode($ats_data),$res_ats,$order_with_detail_s['id'],'9');
                                                            $re_array = json_decode($res_ats,true);
                                                            if(isset($re_array['payload']) && isset($re_array['payload']['packageDocumentDetails']) && isset($re_array['payload']['packageDocumentDetails'][0]) && isset($re_array['payload']['packageDocumentDetails'][0]['trackingId'])){
                                                                $awb_no =$re_array['payload']['packageDocumentDetails'][0]['trackingId'];
                                                                if(isset($re_array['payload']['shipmentId'])){
                                                                    $shipment_id = $re_array['payload']['shipmentId'];
                                                                }
                                                            }else{
                                                                $e_msg ='Issue in data';
                                                                if(isset($re_array['errors'])){
                                                                    if(isset($re_array['errors'][0]['details'])){
                                                                        $error_d = explode('detected:',$re_array['errors'][0]['details']);
                                                                        if(count($error_d) >1){
                                                                            $e_msg =$error_d[1];
                                                                        }else{
                                                                            $e_msg =$re_array['errors'][0]['details'];
                                                                        }

                                                                    }
                                                                }
                                                                $retunarray[0]='error';
                                                                $retunarray[1]=$e_msg;
                                                            }
                                                      }  
                                                      
                                                        if($awb_no !=''){
                                                            $manifest_order_array[$required_corr_data['courier_id']][] = $id;

                                                            $order->tracking_info = $awb_no;
                                                            $order->shipped_date = now();
                                                            $order->shipment_id = $shipment_id;
                                                            $order->ship_courier_id = $required_corr_data['courier_id'];

                                                            $order->shipping_courier_cost = $ship_courier_cost;
                                                            $order->shipping_courier_type = $required_corr_data['mode'];
                                                            $order->shipping_courier_weight_used = $required_corr_data['weight_used'];
                                                            $order->shipping_courier_weight = $required_corr_data['weight'];
                                                            $order->cod = $required_corr_data['cod'];
                                                            $order->gst = $required_corr_data['gst'];
                                                            $order->gst_freight = $required_corr_data['gst_freight'];
                                                            $order->gst_cod = $required_corr_data['gst_cod'];
                                                            $order->freight = $required_corr_data['freight'];
                                                            $order->reverse_charge = $required_corr_data['reverse_charge'];
                                                            $order->rate = $required_corr_data['rate'];
                                                            $order->rateadd = $required_corr_data['rateadd'];

                                                            $order->shipping_courier_costparent = $ship_courier_costparent;
                                                            $order->codparent = $required_corr_data['codparent'];
                                                            $order->gstparent = $required_corr_data['gstparent'];
                                                            $order->gst_freightparent = $required_corr_data['gst_freightparent'];
                                                            $order->gst_codparent = $required_corr_data['gst_codparent'];
                                                            $order->freightparent = $required_corr_data['freightparent'];
                                                            $order->reverse_chargeparent = $required_corr_data['reverse_chargeparent'];
                                                            $order->rateparent = $required_corr_data['rateparent'];
                                                            $order->rateaddparent = $required_corr_data['rateaddparent'];

                                                            $order->zone = $zone;
                                                            $order->status = 2;
                                                            $order->warehouse_id = $ware;
                                                            $order->return_warehouse_id = $return_warehouse_id;
                                                            $order->save();

                                                            Status::orderstatuslog($id,$old_status,'Shipped',now());
                                                             $dublicartras = Transaction::where('user_id',$orde_user_id)->where('order_id',$order->id)->where('awb',$awb_no)->where('remarks','freight charge')->where('parent_data','0')->first();

                                                            if($dublicartras ==''){
                                                                $balance = Admin::find($orde_user_id);
                                                                $before_blnc = $balance->wallet_blc;
                                                                $balance->wallet_blc = $balance->wallet_blc - $ship_courier_cost;
                                                                $balance->save();

                                                                // $transaction = new Transaction();
                                                                // $transaction->order_id = $order->id;
                                                                // $transaction->user_id = $order->user_id;
                                                                // $transaction->awb = $awb_no;
                                                                // $transaction->tracking_info = $awb_no ."<br>". $required_corr_data['name'];
                                                                // $transaction->credit = "0.00";
                                                                // $transaction->debit = $ship_courier_cost;
                                                                // $transaction->remarks = "Amount Debited for assigning";
                                                                // $transaction->show_data = '0';
                                                                // $transaction->save();

                                                                $transaction = new Transaction();
                                                                $transaction->order_id = $order->id;
                                                                $transaction->user_id = $orde_user_id;
                                                                $transaction->awb = $awb_no;
                                                                $transaction->tracking_info = $awb_no ."<br>". $required_corr_data['name'];
                                                                $transaction->credit = "0.00";
                                                                $transaction->debit = $required_corr_data['freight'] + $required_corr_data['gst_freight'];
                                                                $transaction->closing_blc = $before_blnc-$transaction->debit;
                                                                if($order->reverse_order =='0'){
                                                                    $transaction->remarks = "freight charge";
                                                                }else{
                                                                    $transaction->remarks = "freight & reverse charge";
                                                                }
                                                                $transaction->show_data = '1';
                                                                $transaction->save();
                                                                $before_blnc =$transaction->closing_blc;

                                                                $transactionparent = new Transaction();
                                                                $transactionparent->order_id = $order->id;
                                                                $transactionparent->user_id = $parent_userid;
                                                                $transactionparent->awb = $awb_no;
                                                                $transactionparent->tracking_info = $awb_no ."<br>". $required_corr_data['name'];
                                                                $transactionparent->credit = "0.00";
                                                                $transactionparent->debit = $required_corr_data['freightparent'] + $required_corr_data['gst_freightparent'];
                                                                if($order->reverse_order =='0'){
                                                                    $transactionparent->remarks = "freight charge";
                                                                }else{
                                                                    $transactionparent->remarks = "freight & reverse charge";
                                                                }
                                                                $transactionparent->show_data = '1';
                                                                $transactionparent->parent_data = '1';
                                                                $transactionparent->save();

                                                                if($required_corr_data['cod'] !=0){
                                                                    $transaction = new Transaction();
                                                                    $transaction->order_id = $order->id;
                                                                    $transaction->user_id = $orde_user_id;
                                                                    $transaction->awb = $awb_no;
                                                                    $transaction->tracking_info = $awb_no ."<br>". $required_corr_data['name'];
                                                                    $transaction->credit = "0.00";
                                                                    $transaction->debit = $required_corr_data['cod'] + $required_corr_data['gst_cod'];
                                                                    $transaction->closing_blc = $before_blnc-$transaction->debit;
                                                                    $transaction->remarks = "cod charge";
                                                                    $transaction->show_data = '1';
                                                                    $transaction->save();

                                                                    $transactionparent = new Transaction();
                                                                    $transactionparent->order_id = $order->id;
                                                                    $transactionparent->user_id = $parent_userid;
                                                                    $transactionparent->awb = $awb_no;
                                                                    $transactionparent->tracking_info = $awb_no ."<br>". $required_corr_data['name'];
                                                                    $transactionparent->credit = "0.00";
                                                                    $transactionparent->debit = $required_corr_data['codparent'] + $required_corr_data['gst_codparent'];
                                                                    $transactionparent->remarks = "cod charge";
                                                                    $transactionparent->show_data = '1';
                                                                    $transactionparent->parent_data = '1';
                                                                    $transactionparent->save();
                                                                }
                                                                
                                                                
                                                            }
                                                            createlogs('awb', 'order', $order->id);
                                                            $retunarray[0]='success';
                                                            $retunarray[1]=$awb_no;
                                                            if($user_id =='245'){
                                                                api::ordermanifest($order->id,$user_id);
                                                            }
                                                        }
                                                    }
                                               }
                                          }else{
                                              $retunarray[0]='error';
                                              $retunarray[1]='Pincode is not Servicable! - Hyloship';
                                          }
                                      }
                                    
                                 }
                                
                         }else{
                             $retunarray[0]='error';
                             $retunarray[1]='AWB Already assigned';
                         }
                     }
                }else{
                    $retunarray[0]='error';
                    $retunarray[1]='Wrong Order id';
                }  
            }else{
                $retunarray[0]='error';
                $retunarray[1]='Warehouse not found';
            }    
        }else{
            $retunarray[0]='error';
            $retunarray[1]='Courier not found in ratecard';
        }
        return $retunarray;
    }
    
   public static function ordercancel($id)
    {
    $order = Order::with('detail')->where('id', $id)->first();
    $getuser = DB::table('orders')
            ->select('user_id as user')
            ->where('id','=',$id)
            ->first();
    $user_id = $orde_user_id  = $getuser->user;
    $parent_userid = Admin::find($user_id)->parent_id;
    $warehouse = Warehouse::find($order['warehouse_id']);
    $old_status =strip_tags($order['status']);
    $coll_value=0;$money_reversal=0;
    if($old_status !='Delivered' && in_array($old_status,array('New','Shipped','Pickup Pending'))){
        if($order['tracking_info'] !=''){
            if($order['ship_courier_id'] =='1'){
                $cancelstatus =  Integration::cancelshipment($order['tracking_info']);
                //checking api logs
                api_logs($order['tracking_info'],$cancelstatus,$id,'1','cancelled');
                $cancelstatus = json_decode($cancelstatus,true);
                if(isset($cancelstatus[0]) ){
                    $orderd = Order::where('id', $id)->first();
                    $money_reversal = $orderd->shipping_courier_cost;
                    $money_reversalparent = $orderd->shipping_courier_costparent;
                    $orderd->status = '4';
                    $orderd->order_id = $orderd->order_id.'c';
                    $orderd->warehouse_id = null;
                    $orderd->picked_date = null;
                    $orderd->rto_date = null;
                    $orderd->return_warehouse_id = 0;
                    $orderd->ship_courier_id = 0;
                    $orderd->shipping_courier_cost = 0;
                    $orderd->gst = 0;
                    $orderd->gst_freight = 0;
                    $orderd->gst_cod = 0;
                    $orderd->freight = 0;
                    $orderd->cod = 0;
                    $orderd->shipping_courier_costparent = 0;
                    $orderd->gstparent = 0;
                    $orderd->gst_freightparent = 0;
                    $orderd->gst_codparent = 0;
                    $orderd->freightparent = 0;
                    $orderd->codparent = 0;

                    $orderd->shipping_courier_type = null;
                    $orderd->shipping_courier_weight_used = null;
                    $orderd->shipping_courier_weight = null;
                    $orderd->zone = null;
                    $orderd->manifest_id = 0;
                    $orderd->tracking_info = null;
                    $orderd->cancel_date = now();
                    
                    $balance = Admin::find($orde_user_id);

                    $transaction = new Transaction();
                    $transaction->order_id = $id;
                    $transaction->user_id = $orde_user_id;
                    $transaction->awb = $order['tracking_info'];
                    $transaction->tracking_info = $order['tracking_info'];
                    $transaction->credit = $money_reversal;
                    $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                    $transaction->debit = '0.00';
                    $transaction->remarks = "Amount Credit for cancel";
                    $transaction->save();

                    $transactionparent = new Transaction();
                    $transactionparent->order_id = $id;
                    $transactionparent->user_id = $parent_userid;
                    $transactionparent->awb = $order['tracking_info'];
                    $transactionparent->tracking_info = $order['tracking_info'];
                    $transactionparent->credit = $money_reversalparent;
                    $transactionparent->debit = '0.00';
                    $transactionparent->remarks = "Amount Credit for cancel";
                    $transactionparent->parent_data = '1';
                    $transactionparent->save();
                    
                    // $balance = Auth::guard('admin')->user();
                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                    $balance->save();
                    
                    $orderd->save();

                    Status::orderstatuslog($id,$old_status,'Canceled',now());
                    
                }
            }
            else if($order['ship_courier_id'] =='2'){
                $awb_array = json_encode(
                    array(
                        'waybill'=>$order['tracking_info'],
                        'cancellation'=> true
                    ),true
                );
                if($order['shipping_courier_type'] =='fa-plane'){
                    $ttype ='a';
                }else{
                    $ttype ='s';
                }
                $cancelstatus =  Integration::cancelshipment_delivary($awb_array,$ttype);
                //checking api logs
                api_logs($awb_array,$cancelstatus,$id,'2','cancelled');
                $cancelstatus = json_decode($cancelstatus,true);
                // echo '<pre>';print_R($cancelstatus);die;
                if($cancelstatus['status']){
                    $orderd = Order::where('id', $id)->first();
                    $money_reversal = $orderd->shipping_courier_cost;
                    $money_reversalparent = $orderd->shipping_courier_costparent;
                    $orderd->status = '4';
                    $orderd->order_id = $orderd->order_id.'c';
                    $orderd->warehouse_id = null;
                    $orderd->picked_date = null;
                    $orderd->rto_date = null;
                    $orderd->return_warehouse_id = 0;
                    $orderd->return_warehouse_id = 0;
                    $orderd->ship_courier_id = 0;
                    $orderd->shipping_courier_cost = 0;
                    $orderd->gst = 0;
                    $orderd->gst_freight = 0;
                    $orderd->gst_cod = 0;
                    $orderd->freight = 0;
                    $orderd->cod = 0;
                    $orderd->shipping_courier_costparent = 0;
                    $orderd->gstparent = 0;
                    $orderd->gst_freightparent = 0;
                    $orderd->gst_codparent = 0;
                    $orderd->freightparent = 0;
                    $orderd->codparent = 0;
                    $orderd->shipping_courier_type = null;
                    $orderd->shipping_courier_weight_used = null;
                    
                    $orderd->zone = null;
                    $orderd->manifest_id = 0;
                    $orderd->tracking_info = null;
                    $orderd->cancel_date = now();
                    
                    $balance = Admin::find($orde_user_id);

                    $transaction = new Transaction();
                    $transaction->order_id = $id;
                    $transaction->user_id = $orde_user_id;
                    $transaction->awb = $order['tracking_info'];
                    $transaction->tracking_info = $order['tracking_info'];
                    $transaction->credit = $money_reversal;
                    $transaction->closing_blc =  $balance->wallet_blc + $money_reversal;
                    $transaction->debit = '0.00';
                    $transaction->remarks = "Amount Credit for cancel";
                    $transaction->save();
                    
                    $transactionparent = new Transaction();
                    $transactionparent->order_id = $id;
                    $transactionparent->user_id = $parent_userid;
                    $transactionparent->awb = $order['tracking_info'];
                    $transactionparent->tracking_info = $order['tracking_info'];
                    $transactionparent->credit = $money_reversalparent;
                    $transactionparent->debit = '0.00';
                    $transactionparent->remarks = "Amount Credit for cancel";
                    $transactionparent->parent_data = '1';
                    $transactionparent->save();
                    
                    // $balance = Auth::guard('admin')->user();
                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                    $balance->save();
                    
                    $orderd->save();
                }
            }
            else if($order['ship_courier_id'] =='3'){
                $canceldata = array(
                    'awb'=>$order['tracking_info']
                );
                $cancelstatus =  Integration_more::cancel_bluedart(json_encode($canceldata,true));
                //checking api logs
                api_logs(json_encode($canceldata,true),$cancelstatus,$id,'3','cancelled');
                
                $cancelstatus = json_decode($cancelstatus,true);
                if($cancelstatus['status'] ){
                    $orderd = Order::where('id', $id)->first();
                    $money_reversal = $orderd->shipping_courier_cost;
                    $money_reversalparent = $orderd->shipping_courier_costparent;
                    $orderd->status = '4';
                    $orderd->order_id = $orderd->order_id.'c';
                    $orderd->warehouse_id = null;
                    $orderd->picked_date = null;
                    $orderd->rto_date = null;
                    $orderd->return_warehouse_id = 0;
                    $orderd->return_warehouse_id = 0;
                    $orderd->ship_courier_id = 0;
                    $orderd->shipping_courier_cost = 0;
                    $orderd->gst = 0;
                    $orderd->gst_freight = 0;
                    $orderd->gst_cod = 0;
                    $orderd->freight = 0;
                    $orderd->cod = 0;
                    $orderd->shipping_courier_costparent = 0;
                    $orderd->gstparent = 0;
                    $orderd->gst_freightparent = 0;
                    $orderd->gst_codparent = 0;
                    $orderd->freightparent = 0;
                    $orderd->codparent = 0;
                    $orderd->shipping_courier_type = null;
                    $orderd->shipping_courier_weight_used = null;
                    
                    $orderd->zone = null;
                    $orderd->manifest_id = 0;
                    $orderd->tracking_info = null;
                    $orderd->cancel_date = now();
                    
                    $balance = Admin::find($orde_user_id);

                    $transaction = new Transaction();
                    $transaction->order_id = $id;
                    $transaction->user_id = $orde_user_id;
                    $transaction->awb = $order['tracking_info'];
                    $transaction->tracking_info = $order['tracking_info'];
                    $transaction->credit = $money_reversal;
                    $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                    $transaction->debit = '0.00';
                    $transaction->remarks = "Amount Credit for cancel";
                    $transaction->save();
                    
                    $transactionparent = new Transaction();
                    $transactionparent->order_id = $id;
                    $transactionparent->user_id = $parent_userid;
                    $transactionparent->awb = $order['tracking_info'];
                    $transactionparent->tracking_info = $order['tracking_info'];
                    $transactionparent->credit = $money_reversalparent;
                    $transactionparent->debit = '0.00';
                    $transactionparent->remarks = "Amount Credit for cancel";
                    $transactionparent->parent_data = '1';
                    $transactionparent->save();

                    // $balance = Auth::guard('admin')->user();
                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                    $balance->save();
                    
                    $orderd->save();
                }else{
                    $msg = $cancelstatus['responsemsg'];
                    return [
                        'status' => 0,
                        'message' => $msg,
                    ];
                }
            }
            else if($order['ship_courier_id'] =='4'){
                $awb_array = json_encode(
                    array(
                        'ShippingID'=>$order['tracking_info'],
                        'CancellationReason'=>'Cancel order'
                    ),true
                );
                $cancelstatus =  Integration::cancelshipment_xbess($awb_array);
                //checking api logs
                api_logs($awb_array,$cancelstatus,$id,'4','cancelled');
                $cancelstatus = json_decode($cancelstatus,true);
                if(isset($cancelstatus['ReturnCode']) && $cancelstatus['ReturnCode'] =='100'){
                    $orderd = Order::where('id', $id)->first();
                    $money_reversal = $orderd->shipping_courier_cost;
                    $money_reversalparent = $orderd->shipping_courier_costparent;
                    $orderd->status = '4';
                    $orderd->order_id = $orderd->order_id.'c';
                    $orderd->warehouse_id = null;
                    $orderd->picked_date = null;
                    $orderd->rto_date = null;
                    $orderd->return_warehouse_id = 0;
                    $orderd->return_warehouse_id = 0;
                    $orderd->ship_courier_id = 0;
                    $orderd->shipping_courier_cost = 0;
                    $orderd->gst = 0;
                    $orderd->gst_freight = 0;
                    $orderd->gst_cod = 0;
                    $orderd->freight = 0;
                    $orderd->cod = 0;
                    $orderd->shipping_courier_costparent = 0;
                    $orderd->gstparent = 0;
                    $orderd->gst_freightparent = 0;
                    $orderd->gst_codparent = 0;
                    $orderd->freightparent = 0;
                    $orderd->codparent = 0;
                    $orderd->shipping_courier_type = null;
                    $orderd->shipping_courier_weight_used = null;
                    
                    $orderd->zone = null;
                    $orderd->manifest_id = 0;
                    $orderd->tracking_info = null;
                    $orderd->cancel_date = now();
                    
                    $balance = Admin::find($orde_user_id);

                    $transaction = new Transaction();
                    $transaction->order_id = $id;
                    $transaction->user_id = $orde_user_id;
                    $transaction->awb = $order['tracking_info'];
                    $transaction->tracking_info = $order['tracking_info'];
                    $transaction->credit = $money_reversal;
                    $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                    $transaction->debit = '0.00';
                    $transaction->remarks = "Amount Credit for cancel";
                    $transaction->save();
                    
                    $transactionparent = new Transaction();
                    $transactionparent->order_id = $id;
                    $transactionparent->user_id = $parent_userid;
                    $transactionparent->awb = $order['tracking_info'];
                    $transactionparent->tracking_info = $order['tracking_info'];
                    $transactionparent->credit = $money_reversalparent;
                    $transactionparent->debit = '0.00';
                    $transactionparent->remarks = "Amount Credit for cancel";
                    $transactionparent->parent_data = '1';
                    $transactionparent->save();
                    
                    // $balance = Auth::guard('admin')->user();
                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                    $balance->save();
                    
                    $orderd->save();
                }else{
                    return redirect()->back()->with('error', $cancelstatus['ReturnMessage']);
                }
            }
            else if($order['ship_courier_id'] =='5'){
                $awb_array = json_encode(
                    array(
                        'AWBNo'=>array(
                            $order['tracking_info']
                        ),
                        'customerCode'=> 'GL7569'
                    ),true
                );
                $canceldtdc =  Integration::cancelshipment_dtdc($awb_array);
                //checking api logs
                api_logs($awb_array,$canceldtdc,$id,'5','cancelled');
                $canceldtdc = json_decode($canceldtdc,true);
                if($canceldtdc['success']){
                    $orderd = Order::where('id', $id)->first();
                    $money_reversal = $orderd->shipping_courier_cost;
                    $money_reversalparent = $orderd->shipping_courier_costparent;
                    $orderd->status = '4';
                    $orderd->order_id = $orderd->order_id.'c';
                    $orderd->warehouse_id = null;
                    $orderd->picked_date = null;
                    $orderd->rto_date = null;
                    $orderd->return_warehouse_id = 0;
                    $orderd->return_warehouse_id = 0;
                    $orderd->ship_courier_id = 0;
                    $orderd->shipping_courier_cost = 0;
                    $orderd->gst = 0;
                    $orderd->gst_freight = 0;
                    $orderd->gst_cod = 0;
                    $orderd->freight = 0;
                    $orderd->cod = 0;
                    $orderd->shipping_courier_costparent = 0;
                    $orderd->gstparent = 0;
                    $orderd->gst_freightparent = 0;
                    $orderd->gst_codparent = 0;
                    $orderd->freightparent = 0;
                    $orderd->codparent = 0;
                    $orderd->shipping_courier_type = null;
                    $orderd->shipping_courier_weight_used = null;
                    
                    $orderd->zone = null;
                    $orderd->manifest_id = 0;
                    $orderd->tracking_info = null;
                    $orderd->cancel_date = now();
                    
                    $balance = Admin::find($orde_user_id);

                    $transaction = new Transaction();
                    $transaction->order_id = $id;
                    $transaction->user_id = $orde_user_id;
                    $transaction->awb = $order['tracking_info'];
                    $transaction->tracking_info = $order['tracking_info'];
                    $transaction->credit = $money_reversal;
                    $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                    $transaction->debit = '0.00';
                    $transaction->remarks = "Amount Credit for cancel";
                    $transaction->save();
                    
                    $transactionparent = new Transaction();
                    $transactionparent->order_id = $id;
                    $transactionparent->user_id = $parent_userid;
                    $transactionparent->awb = $order['tracking_info'];
                    $transactionparent->tracking_info = $order['tracking_info'];
                    $transactionparent->credit = $money_reversalparent;
                    $transactionparent->debit = '0.00';
                    $transactionparent->remarks = "Amount Credit for cancel";
                    $transactionparent->parent_data = '1';
                    $transactionparent->save();
                    
                    // $balance = Auth::guard('admin')->user();
                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                    $balance->save();
                    
                    $orderd->save();
                }else{
                    if(isset($canceldtdc['failures'][0])){
                            return redirect()->route('admin.order.all')->with('error', $canceldtdc['failures'][0]['message']);
                        }
                }
            }
            else if($order['ship_courier_id'] =='6'){
                if(strip_tags($order['shipping_courier_type']) =='fa-truck'){
                    $awb_array = json_encode(
                        array(
                            array('waybillNumber'=>array(
                                $order['tracking_info']
                            )),
                        ),true
                    );
                    $type='cod';
                }else{
                    $awb_array = json_encode(
                        array(
                            'awbs'=>array(
                                $order['tracking_info']
                            ),
                        ),true
                    );
                    $type='ppd';
                }
                $cancelsmartr =  Integration::cancelshipment_smartr($awb_array,$type);
                //checking api logs
                api_logs($awb_array,$cancelsmartr,$id,'6','cancelled');
                $cancelsmartr = json_decode($cancelsmartr,true);
                if($type=='ppd'){ // for air
                    if($cancelsmartr['data'] && isset($cancelsmartr['data'][0]) && $cancelsmartr['data'][0]['success']){
                        $orderd = Order::where('id', $id)->first();
                        $money_reversal = $orderd->shipping_courier_cost;
                        $money_reversalparent = $orderd->shipping_courier_costparent;
                        $orderd->status = '4';
                        $orderd->order_id = $orderd->order_id.'c';
                        $orderd->warehouse_id = null;
                        $orderd->picked_date = null;
                        $orderd->rto_date = null;
                        $orderd->return_warehouse_id = 0;
                        $orderd->return_warehouse_id = 0;
                        $orderd->ship_courier_id = 0;
                        $orderd->shipping_courier_cost = 0;
                        $orderd->gst = 0;
                        $orderd->gst_freight = 0;
                        $orderd->gst_cod = 0;
                        $orderd->freight = 0;
                        $orderd->cod = 0;
                        $orderd->shipping_courier_costparent = 0;
                        $orderd->gstparent = 0;
                        $orderd->gst_freightparent = 0;
                        $orderd->gst_codparent = 0;
                        $orderd->freightparent = 0;
                        $orderd->codparent = 0;
                        $orderd->shipping_courier_type = null;
                        $orderd->shipping_courier_weight_used = null;
                        
                        $orderd->zone = null;
                        $orderd->manifest_id = 0;
                        $orderd->tracking_info = null;
                        $orderd->cancel_date = now();

                        $balance = Admin::find($orde_user_id);
                        
                        $transaction = new Transaction();
                        $transaction->order_id = $id;
                        $transaction->user_id = $orde_user_id;
                        $transaction->awb = $order['tracking_info'];
                        $transaction->tracking_info = $order['tracking_info'];
                        $transaction->credit = $money_reversal;
                        $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                        $transaction->debit = '0.00';
                        $transaction->remarks = "Amount Credit for cancel";
                        $transaction->save();
                        
                        $transactionparent = new Transaction();
                        $transactionparent->order_id = $id;
                        $transactionparent->user_id = $parent_userid;
                        $transactionparent->awb = $order['tracking_info'];
                        $transactionparent->tracking_info = $order['tracking_info'];
                        $transactionparent->credit = $money_reversalparent;
                        $transactionparent->debit = '0.00';
                        $transactionparent->remarks = "Amount Credit for cancel";
                        $transactionparent->parent_data = '1';
                        $transactionparent->save();
                        
                        // $balance = Auth::guard('admin')->user();
                        $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                        $balance->save();
                        
                        $orderd->save();
                    }else{
                        if(isset($cancelsmartr['data'][0])){
                            return redirect()->route('admin.order.all')->with('error', $cancelsmartr['data'][0]['message']);
                        }
                    }
                }else{ // for surface
                    if(isset($cancelsmartr[0]) && $cancelsmartr[0]['success']){
                        $orderd = Order::where('id', $id)->first();
                        $money_reversal = $orderd->shipping_courier_cost;
                        $money_reversalparent = $orderd->shipping_courier_costparent;
                        $orderd->status = '4';
                        $orderd->order_id = $orderd->order_id.'c';
                        $orderd->warehouse_id = null;
                        $orderd->picked_date = null;
                        $orderd->rto_date = null;
                        $orderd->return_warehouse_id = 0;
                        $orderd->return_warehouse_id = 0;
                        $orderd->ship_courier_id = 0;
                        $orderd->shipping_courier_cost = 0;
                        $orderd->gst = 0;
                        $orderd->gst_freight = 0;
                        $orderd->gst_cod = 0;
                        $orderd->freight = 0;
                        $orderd->cod = 0;
                        $orderd->shipping_courier_costparent = 0;
                        $orderd->gstparent = 0;
                        $orderd->gst_freightparent = 0;
                        $orderd->gst_codparent = 0;
                        $orderd->freightparent = 0;
                        $orderd->codparent = 0;
                        $orderd->shipping_courier_type = null;
                        $orderd->shipping_courier_weight_used = null;
                        
                        $orderd->zone = null;
                        $orderd->manifest_id = 0;
                        $orderd->tracking_info = null;
                        $orderd->cancel_date = now();

                        $balance = Admin::find($orde_user_id);
                        
                        $transaction = new Transaction();
                        $transaction->order_id = $id;
                        $transaction->user_id = $orde_user_id;
                        $transaction->awb = $order['tracking_info'];
                        $transaction->tracking_info = $order['tracking_info'];
                        $transaction->credit = $money_reversal;
                        $transaction->debit = '0.00';
                        $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                        $transaction->remarks = "Amount Credit for cancel";
                        $transaction->save();
                        
                        $transactionparent = new Transaction();
                        $transactionparent->order_id = $id;
                        $transactionparent->user_id = $parent_userid;
                        $transactionparent->awb = $order['tracking_info'];
                        $transactionparent->tracking_info = $order['tracking_info'];
                        $transactionparent->credit = $money_reversalparent;
                        $transactionparent->debit = '0.00';
                        $transactionparent->remarks = "Amount Credit for cancel";
                        $transactionparent->parent_data = '1';
                        $transactionparent->save();
                        
                        // $balance = Auth::guard('admin')->user();
                        $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                        $balance->save();
                        
                        $orderd->save();
                    }else{
                        return redirect()->route('admin.order.all')->with('error', $cancelsmartr[0]['message']);
                    }
                }    
            }
            else if($order['ship_courier_id'] =='7'){
                if($order->reverse_order =='1'){
                    $reverse ='1';
                    $ord_Array = array(
                        'request_details' =>array(
                            'tracking_id'=>$order->tracking_info,
                            'reason'=>'Cancel the order',
                        ),
                    );
                }else{
                    $reverse = '0';
                    $ord_Array = array(
                        'request_id' =>'',
                        'request_details' =>array(
                            'tracking_id'=>$order->tracking_info,
                            'reason'=>'Cancel the order',
                        ),
                    );
                }
                $cancelekart =  Integration_more::cancelshipment_ekart(json_encode($ord_Array),$reverse);
                //checking api logs
                api_logs(json_encode($ord_Array),$cancelekart,$id,'7','cancelled');
                $cancelekart = json_decode($cancelekart,true);
                if($cancelekart['response'] && isset($cancelekart['response'][0]) && $cancelekart['response'][0]['status']=='REQUEST_RECEIVED'){
                    $orderd = Order::where('id', $id)->first();
                    $money_reversal = $orderd->shipping_courier_cost;
                    $money_reversalparent = $orderd->shipping_courier_costparent;
                    $orderd->status = '4';
                    $orderd->order_id = $orderd->order_id.'c';
                    $orderd->warehouse_id = null;
                    $orderd->picked_date = null;
                    $orderd->rto_date = null;
                    $orderd->return_warehouse_id = 0;
                    $orderd->return_warehouse_id = 0;
                    $orderd->ship_courier_id = 0;
                    $orderd->shipping_courier_cost = 0;
                    $orderd->gst = 0;
                    $orderd->gst_freight = 0;
                    $orderd->gst_cod = 0;
                    $orderd->freight = 0;
                    $orderd->cod = 0;
                    $orderd->shipping_courier_costparent = 0;
                    $orderd->gstparent = 0;
                    $orderd->gst_freightparent = 0;
                    $orderd->gst_codparent = 0;
                    $orderd->freightparent = 0;
                    $orderd->codparent = 0;
                    $orderd->shipping_courier_type = null;
                    $orderd->shipping_courier_weight_used = null;
                    
                    $orderd->zone = null;
                    $orderd->manifest_id = 0;
                    $orderd->tracking_info = null;
                    $orderd->cancel_date = now();

                    $balance = Admin::find($orde_user_id);
                    
                    $transaction = new Transaction();
                    $transaction->order_id = $id;
                    $transaction->user_id = $orde_user_id;
                    $transaction->awb = $order['tracking_info'];
                    $transaction->tracking_info = $order['tracking_info'];
                    $transaction->credit = $money_reversal;
                    $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                    $transaction->debit = '0.00';
                    $transaction->remarks = "Amount Credit for cancel";
                    $transaction->save();
                    
                    $transactionparent = new Transaction();
                    $transactionparent->order_id = $id;
                    $transactionparent->user_id = $parent_userid;
                    $transactionparent->awb = $order['tracking_info'];
                    $transactionparent->tracking_info = $order['tracking_info'];
                    $transactionparent->credit = $money_reversalparent;
                    $transactionparent->debit = '0.00';
                    $transactionparent->remarks = "Amount Credit for cancel";
                    $transactionparent->parent_data = '1';
                    $transactionparent->save();
                    
                    // $balance = Auth::guard('admin')->user();
                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                    $balance->save();
                    
                    $orderd->save();
                }else{
                    if(isset($cancelekart['response'][0]) && $cancelekart['response'][0]['status']=='REQUEST_REJECTED'){
                        return redirect()->back()->with('error', $cancelekart['response'][0]['message'][0]);
                    }else{
                        return redirect()->back()->with('error', 'Ekart not responding in cancel');
                    }
                }
                // echo '<pre>';print_R($cancelekart);die;
                
            }
            else if($order['ship_courier_id'] =='8'){
                if($order->reverse_order =='1'){
                    $type ='backward';
                }else{
                    $type ='forward';
                }
                $ord_Array = array(
                    'request_id'=>$order->tracking_info,
                    'cancel_remarks'=>'Cancel the order',
                );
                $cancelshadowfax =  Integration_more::cancelshipment_shadowfax($type,json_encode($ord_Array));
                //checking api logs
                api_logs(json_encode($ord_Array),$cancelshadowfax,$id,'8','cancelled');
                $cancelshadowfax = json_decode($cancelshadowfax,true);
                if(isset($cancelshadowfax['responseCode']) && ($cancelshadowfax['responseCode'] =='200' || $cancelshadowfax['responseMsg'] =='Request is queued for cancellation')){
                    $orderd = Order::where('id', $id)->first();
                    $money_reversal = $orderd->shipping_courier_cost;
                    $money_reversalparent = $orderd->shipping_courier_costparent;
                    $orderd->status = '4';
                    $orderd->order_id = $orderd->order_id.'c';
                    $orderd->warehouse_id = null;
                    $orderd->picked_date = null;
                    $orderd->rto_date = null;
                    $orderd->return_warehouse_id = 0;
                    $orderd->return_warehouse_id = 0;
                    $orderd->ship_courier_id = 0;
                    $orderd->shipping_courier_cost = 0;
                    $orderd->gst = 0;
                    $orderd->gst_freight = 0;
                    $orderd->gst_cod = 0;
                    $orderd->freight = 0;
                    $orderd->cod = 0;
                    $orderd->shipping_courier_costparent = 0;
                    $orderd->gstparent = 0;
                    $orderd->gst_freightparent = 0;
                    $orderd->gst_codparent = 0;
                    $orderd->freightparent = 0;
                    $orderd->codparent = 0;
                    $orderd->shipping_courier_type = null;
                    $orderd->shipping_courier_weight_used = null;
                    
                    $orderd->zone = null;
                    $orderd->manifest_id = 0;
                    $orderd->tracking_info = null;
                    $orderd->cancel_date = now();

                    $balance = Admin::find($orde_user_id);
                    
                    $transaction = new Transaction();
                    $transaction->order_id = $id;
                    $transaction->user_id = $orde_user_id;
                    $transaction->awb = $order['tracking_info'];
                    $transaction->tracking_info = $order['tracking_info'];
                    $transaction->credit = $money_reversal;
                    $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                    $transaction->debit = '0.00';
                    $transaction->remarks = "Amount Credit for cancel";
                    $transaction->save();
                    
                    $transactionparent = new Transaction();
                    $transactionparent->order_id = $id;
                    $transactionparent->user_id = $parent_userid;
                    $transactionparent->awb = $order['tracking_info'];
                    $transactionparent->tracking_info = $order['tracking_info'];
                    $transactionparent->credit = $money_reversalparent;
                    $transactionparent->debit = '0.00';
                    $transactionparent->remarks = "Amount Credit for cancel";
                    $transactionparent->parent_data = '1';
                    $transactionparent->save();
                    
                    // $balance = Auth::guard('admin')->user();
                    $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                    $balance->save();
                    
                    $orderd->save();
                }
                else{
                    if(isset($cancelshadowfax['errorCode']) ){
                        return redirect()->back()->with('error', $cancelshadowfax['errorCode']);
                    }
                    if(isset($cancelshadowfax['responseMsg']) ){
                        return redirect()->back()->with('error', $cancelshadowfax['responseMsg']);
                    }
                    
                }
                // echo '<pre>';print_R($cancelekart);die;
            }
            else if($order['ship_courier_id'] =='9'){
                $shipment_id = $order->shipment_id;
                if($shipment_id =='' || $shipment_id == null){
                    return redirect()->back()->with('error', 'Shipment id not found');
                }else{
                    $cancelats =  Integration_more::cancelshipment_ats($shipment_id);
                    //checking api logs
                    api_logs($shipment_id,$cancelats,$id,'9','cancelled');
                    $cancelats = json_decode($cancelats,true);
                    if(isset($cancelats['payload']) && empty($cancelats['payload'])){
                        $orderd = Order::where('id', $id)->first();
                        $money_reversal = $orderd->shipping_courier_cost;
                        $money_reversalparent = $orderd->shipping_courier_costparent;
                        $orderd->status = '4';
                        $orderd->order_id = $orderd->order_id.'c';
                        $orderd->warehouse_id = null;
                        $orderd->picked_date = null;
                        $orderd->rto_date = null;
                        $orderd->return_warehouse_id = 0;
                        $orderd->return_warehouse_id = 0;
                        $orderd->ship_courier_id = 0;
                        $orderd->shipping_courier_cost = 0;
                        $orderd->gst = 0;
                        $orderd->gst_freight = 0;
                        $orderd->gst_cod = 0;
                        $orderd->freight = 0;
                        $orderd->cod = 0;
                        $orderd->shipping_courier_costparent = 0;
                        $orderd->gstparent = 0;
                        $orderd->gst_freightparent = 0;
                        $orderd->gst_codparent = 0;
                        $orderd->freightparent = 0;
                        $orderd->codparent = 0;
                        $orderd->shipping_courier_type = null;
                        $orderd->shipping_courier_weight_used = null;
                        
                        $orderd->shipment_id = null;
                        $orderd->zone = null;
                        $orderd->manifest_id = 0;
                        $orderd->tracking_info = null;
                        $orderd->cancel_date = now();

                        $balance = Admin::find($orde_user_id);
                        
                        $transaction = new Transaction();
                        $transaction->order_id = $id;
                        $transaction->user_id = $orde_user_id;
                        $transaction->awb = $order['tracking_info'];
                        $transaction->tracking_info = $order['tracking_info'];
                        $transaction->credit = $money_reversal;
                        $transaction->closing_blc = $balance->wallet_blc + $money_reversal;
                        $transaction->debit = '0.00';
                        $transaction->remarks = "Amount Credit for cancel";
                        $transaction->save();
                        
                        $transactionparent = new Transaction();
                        $transactionparent->order_id = $id;
                        $transactionparent->user_id = $parent_userid;
                        $transactionparent->awb = $order['tracking_info'];
                        $transactionparent->tracking_info = $order['tracking_info'];
                        $transactionparent->credit = $money_reversalparent;
                        $transactionparent->debit = '0.00';
                        $transactionparent->remarks = "Amount Credit for cancel";
                        $transactionparent->parent_data = '1';
                        $transactionparent->save();
                        
                        // $balance = Auth::guard('admin')->user();
                        $balance->wallet_blc = $balance->wallet_blc + $money_reversal;
                        $balance->save();
                        
                        $orderd->save();
                    }else{
                        $erro = 'Something went wrong,please try after sometime';
                        if(isset($cancelats['errors']) && isset($cancelats['errors'][0])){
                            $erro = $cancelats['errors'][0]['details'];
                        }
                        return redirect()->back()->with('error', $erro);
                        
                    }
                }
                // echo '<pre>';print_R($cancelekart);die;
            }
            
        
        }else{
            $orderd = Order::where('id', $id)->first();
            $orderd->status = '4';
            $orderd->order_id = $orderd->order_id.'c';
            $orderd->warehouse_id = null;
            $orderd->picked_date = null;
            $orderd->rto_date = null;
            $orderd->return_warehouse_id = 0;
            $orderd->return_warehouse_id = 0;
            $orderd->ship_courier_id = 0;
            $orderd->shipping_courier_cost = 0;
            $orderd->gst = 0;
            $orderd->gst_freight = 0;
            $orderd->gst_cod = 0;
            $orderd->freight = 0;
            $orderd->cod = 0;
            $orderd->shipping_courier_costparent = 0;
            $orderd->gstparent = 0;
            $orderd->gst_freightparent = 0;
            $orderd->gst_codparent = 0;
            $orderd->freightparent = 0;
            $orderd->codparent = 0;
            $orderd->shipping_courier_type = null;
            $orderd->shipping_courier_weight_used = null;
            
            $orderd->zone = null;
            $orderd->manifest_id = 0;
            $orderd->tracking_info = null;
            $orderd->cancel_date = now();
            $orderd->save();
        }
       Status::orderstatuslog($id,$old_status,'Canceled',now()); 
       
    }else{
        $retunarray[0]='error';
        $retunarray[1]='only pickup pending and new orders can be cancelled';
        return $retunarray;
    }
    $retunarray[0]='success';
    $retunarray[1]='Order Cancelled Successfully!';
    return $retunarray;
}



    //rates
    public static function calculateRates($request, $userId)
    {
    $rateuser = Ratecard::where(['status' => 1, 'user_id' => $userId])->first();
    if (!$rateuser) {
        return [
            'status' => 0,
            'message' => 'Ratecard not found'
        ];
    }
    $couriers = json_decode(file_get_contents(resource_path('views/admin/courier.json')), true);
    
    $cour_ids = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    $zone_id = Order::getzone($request['pickup_pin'], $request['drop_pin']);
    $pincodes = array_map(function ($id) use ($zone_id) {
        return [
            "courier_id" => $id,
            "zone" => $zone_id,
        ];
    }, $cour_ids);

    $results = [];
    $payment = $request['payment'];

    foreach ($pincodes as $row) {
        $zone = zone($row['zone']);
        $courier_id = $row['courier_id'];

        foreach (['Air', 'Surface'] as $transport) {
            foreach (['0.5', '1', '1.5', '2', '5'] as $c_weight) {
                $rate = Ratecard::where([
                    'courier_id' => $courier_id,
                    'transport' => $transport,
                    'status' => 1,
                    'user_id' => $userId,
                    'weight' => $c_weight,
                    'additional' => 0
                ])->first();

                if (!$rate) {
                    continue;
                }

                $wadd = ($c_weight == '0.5') ? '0.5' : '1';
                $rateadd = Ratecard::where([
                    'courier_id' => $courier_id,
                    'transport' => $transport,
                    'status' => 1,
                    'user_id' => $userId,
                    'weight' => $wadd,
                    'additional' => 1
                ])->first();

                $vol_weight = ($request['length'] * $request['breadth'] * $request['height']) / vol_weigh($courier_id);
                $weight_to_be_taken = max($vol_weight, $request['weight']);

                $percent = ($request['value'] * $rate->cod) / 100;
                $cod = ($payment === 'cod') ? max($percent, $rate->cod_charges) : 0;
                $remaining_weight = max($weight_to_be_taken - $c_weight, 0);
                $freight = $rate->$zone;

                if ($rateadd) {
                    $count = ceil($remaining_weight / $rateadd->weight);
                    $remaining_charge = $count * $rateadd->$zone;
                    $freight += $remaining_charge;
                }

                if ($request['shipment_type'] === 'reverse') {
                    $ratereverse = Ratecard::where([
                        'courier_id' => $courier_id,
                        'transport' => 'Reverse',
                        'status' => 1,
                        'user_id' => $userId
                    ])->first();
                    $freight += $ratereverse ? $ratereverse->$zone : 0;
                }

                $gst = (($freight + $cod) * 18) / 100;
                $total = $gst + $freight + $cod;
                $withoutgsttotal = $total - $gst;

                $results[] = [
                    'courier_id' => $rate->courier_id,
                    'name' => $couriers[$rate->courier_id]['name'],
                    'img' => asset('public/courier') . '/' . $couriers[$rate->courier_id]['image'],
                    'mode' => ($transport === 'Air') ? 'fa-plane' : 'fa-truck',
                    'weight_used' => $c_weight,
                    'weight' => round($weight_to_be_taken, 2) . ' kg',
                    'price' => 'Rs.' . number_format($total, 2),
                    'zone' => $zone,
                    'courier' => $couriers[$rate->courier_id]['name'] . ' ' . $rate->transport . ' (' . $c_weight . ' KG)',
                    'freight_charge' => 'Rs ' . number_format($freight, 2),
                    'cod' => 'Rs ' . number_format($cod, 2),
                    'gst' => 'Rs ' . number_format($gst, 2),
                    'total' => 'Rs ' . number_format($total, 2),
                    'withoutgsttotal' => 'Rs ' . number_format($withoutgsttotal, 2),
                ];
               
            }
        }
    }
    return $results;
}

    //warehouse
    public static function warehouse($request,$user_id,$id=0){
        $wr_id=0;
        if ($id) {
            $warehouse = Warehouse::where('id',$id)->where('user_id',$user_id)->first();
            $orginalwarehouse = $warehouse->getOriginal();

            $message  = "Warehouse updated successfully";

            $war_array = array(
                'address_title'=>$request->name.' '.date('ymdhis'),
                'sender_name'=>$request->contact_name,
                'full_address'=>$request->address.' '.$request->address_2,
                'phone'=>$request->phone,
                'pincode'=>$request->pincode,
            );
            $getWarehouse =  Integration_more::warehouse_bludart(json_encode($war_array,true));
            $warehusedetail = json_decode($getWarehouse,true);

            if($warehusedetail['status'] && isset($warehusedetail['data']['pick_address_id'])){
                $w_id = $warehusedetail['data']['pick_address_id'];
                $warehouse->bd_id = $w_id;
                $warehouse->save();
            }   
        } else {

            $warehouse = new Warehouse();
            $warehouse->user_id = $user_id; 
            $message  = "Warehouse added successfully";
        }
        $country = Country::find($request->country_id);
        $warehouse_array_air = json_encode(
            array(
                'phone'=>$request->phone,
                'city'=>$request->city,
                'name'=>$request->company.'a',
                'pin'=>$request->pincode,
                'address'=>$request->address.' '.$request->address_2,
                'country'=>$country['name'],
                'email'=>$request->email,
                'registered_name'=>$request->company,
                'return_address'=>$request->address.' '.$request->address_2,
                'return_pin'=>$request->pincode,
                'return_city'=>$request->city,
                'return_state'=>$request->state,
                'return_country'=>$country['name'],

            ),true
        );

        $warehouse_d =  Integration::create_warehouse($warehouse_array_air,'a');
        $dat_creted =json_decode($warehouse_d,true);

        if(isset($dat_creted['success']) && $dat_creted['success']){

        }else{
            if(!isset($dat_creted['error'])){
                $dat_creted['error'] = array();
            }
            for($i=0;$i<count($dat_creted['error']);$i++){
                if(str_contains($dat_creted['error'][$i],'already exists')){
                    $warehouse_edit =  Integration::edit_warehouse($warehouse_array_air,'a');
                    $dat_edited =json_decode($warehouse_edit,true);
                }
            }
        }
        $warehouse_array_surface = json_encode(
            array(
                'phone'=>$request->phone,
                'city'=>$request->city,
                'name'=>$request->company.'s',
                'pin'=>$request->pincode,
                'address'=>$request->address.' '.$request->address_2,
                'country'=>$country['name'],
                'email'=>$request->email,
                'registered_name'=>$request->company,
                'return_address'=>$request->address.' '.$request->address_2,
                'return_pin'=>$request->pincode,
                'return_city'=>$request->city,
                'return_state'=>$request->state,
                'return_country'=>$country['name'],

            ),true
        );
        $warehouse_d =  Integration::create_warehouse($warehouse_array_surface,'s');
        $dat_creted =json_decode($warehouse_d,true);

        if(isset($dat_creted['success']) && $dat_creted['success']){

        }else{
            if(!isset($dat_creted['error'])){
                $dat_creted['error'] = array();
            }
            for($i=0;$i<count($dat_creted['error']);$i++){
                if(str_contains($dat_creted['error'][$i],'already exists')){
                    $warehouse_edit =  Integration::edit_warehouse($warehouse_array_surface,'s');
                    $dat_edited =json_decode($warehouse_edit,true);
                }
            }
        }
        if($request->default == 1)
        {
            $default = 1;
        }
        else{
            $default = 0;
        }

        $warehouse->name = $request->name;
        $warehouse->contact_name = $request->contact_name;
        $warehouse->company = $request->company;
        $warehouse->email = $request->email;
        $warehouse->phone = $request->phone;
        $warehouse->address = $request->address;
        $warehouse->address_2 = $request->address_2;
        $warehouse->city = $request->city;
        $warehouse->state = $request->state;
        $warehouse->country_id = $request->country_id;
        $warehouse->pincode = $request->pincode;
        $warehouse->latitude = $request->latitude;
        $warehouse->longitude = $request->longitude;
        $warehouse->gst_no = $request->gst_no;
        $warehouse->fssai_licence = $request->fssai_licence;
        $warehouse->note = $request->note;
        $warehouse->default = $default;
        $warehouse->created_at = now();
        $warehouse->updated_at = now();
        $warehouse->save();


        if ($id) {
            $changedFields = array_diff_assoc($warehouse->getAttributes(), $orginalwarehouse);
            unset($changedFields['created_at'], $changedFields['updated_at'], $changedFields['bd_id']);
            $oldValues = [];
            foreach ($changedFields as $key => $newValue) {
                $oldValues[$key] = $orginalwarehouse[$key] ?? 'N/A';
            }
            createlogs('updated', 'warehouse', $id, $changedFields, $oldValues);
            $wr_id = $id;
        }
        else{

            createlogs('created','warehouse',$warehouse->id);
            $wr_id = $warehouse->id;
        }
        $returnmsg[0]=$wr_id;
        $returnmsg[1]=$message;
        return $returnmsg;

    }


    public static function deleteware($id,$user_id){
    $warehouse = Warehouse::where('id', $id)->where('user_id',$user_id)->first();
    if ($warehouse) {
        createlogs('deleted','warehouse',$id);
        $warehouse->delete();
        return "Warehouse deleted successfully";
    } else {
       
        return "Warehouse not found";
    }
}

    public static function getstatusvalue($status_Array,$courier_id,$typet = 'forward'){ 
        $kimistatus = '';
        if($courier_id =='1'){
            if(in_array($status_Array['reason_code_number'],array('999','315'))){
                return 'DELIVERED';
            }
            if(in_array($status_Array['reason_code_number'],array('88807','88806','88805','888'))){
                return 'Destroyed';
            }
            if(in_array($status_Array['reason_code_number'],array('30204','30203','30202','30201','314'))){
                return 'Damaged';
            }
            if(in_array($status_Array['reason_code_number'],array('34806','34805','34804','34803','34802','34801','33308','348','33305','33304','33303','33302','33301','333'))){
                return 'Lost';
            }
            if(in_array($status_Array['reason_code_number'],array('35006','35004','35003','35002','35001','350','20003','22703','31501','20002','20001','421','401','415','006','82','83'))){
                return 'Out for Delivery';
            }
            if(in_array($status_Array['reason_code_number'],array('207','34302','30414','30415','37212','37211','37210','37209','37208','37207','37206','37205','37204','37203','37202','37201','30413','33202','33201','347','34602','34601','32502','30101','22903','32104','32105','21603','21602','30505','21601','30412','30411','30410','30409','30408','30407','34501','34401','34301','34003','34002','34001','32501','32103','32101','32001','31701','31101','30503','30502','30501','24003','24002','24001','23801','23701','22901','20701','88803','88802','30406','30405','30404','30403','30402','30401','34003','34002','334','236','428','235','1225','450','316','332','400','416','1260','002','127','003','004','005','0011'))){
                return 'In Transit';
            }
            if(in_array($status_Array['reason_code_number'],array('37215','24455','37214','37213','77','80','777'))){
                return 'RTO In Transit';
            }
            if(in_array($status_Array['reason_code_number'],array('34304','34303','426','425','424','405','407','408','409','411','414','417','418','419','420','310','230','211','001','013','1210','1220','1230','1310','1320','1340','1350','1360','1370','1380','1390','1400','1410','1420','1430','014'))){
                return 'Pickup Pending';
            }
            if(in_array($status_Array['reason_code_number'],array('227','331','2405','210','1224','231','22705','22704','23405','23404','23403','22111','22109','23202','23201','22108','24204','24202','24201','242','241','12247','12246','12245','12244','12243','12242','12241','23402','23401','23103','23102','23101','22801','22702','22303','22301','22107','22106','22105','22104','22103','22102','22101','21701','21503','21502','21501','21004','21003','21002','21001','22701','423','402','218','219','222','223','224'))){
                return 'NDR';
            }
            if(in_array($status_Array['reason_code_number'],array('33401','313','312','430'))){
                return 'RTO';
            }
            if($status_Array['reason_code_number'] =='' && $status_Array['status'] =='In-Transit'){
                return 'In Transit';
            }
        }
        if($courier_id =='2'){
            if(in_array($status_Array['Scan'],array('Delivered','DTO')) && in_array($status_Array['ScanType'],array('DL'))){
                return 'DELIVERED';
            }
            if(in_array($status_Array['Scan'],array('Canceled','Closed')) && in_array($status_Array['ScanType'],array('CN'))){
                return 'Canceled';
            }
            if(in_array($status_Array['Scan'],array('In Transit')) && in_array($status_Array['ScanType'],array('RT'))){
                return 'RTO';
            }
            if(in_array($status_Array['Scan'],array('RTO')) && in_array($status_Array['ScanType'],array('DL'))){
                return 'RTO Delivered';
            }
            if(($status_Array['Scan'] =='Manifested' && $status_Array['ScanType'] =='UD') ||
            ($status_Array['Scan'] =='Not Picked' && $status_Array['ScanType'] =='UD') ||
            ($status_Array['Scan'] =='Open' && $status_Array['ScanType'] =='PP') ||
            ($status_Array['Scan'] =='Scheduled' && $status_Array['ScanType'] =='PP') ){
                return 'Pickup Pending';
            }
            if(in_array($status_Array['Scan'],array('Pending','Dispatched','In Transit')) && in_array($status_Array['ScanType'],array('RT'))){
                return 'RTO In Transit';
            }
            if(($status_Array['Scan'] =='In Transit' && $status_Array['ScanType'] =='UD') ||
            ($status_Array['Scan'] =='Pending' && $status_Array['ScanType'] =='UD')  ||
            ($status_Array['Scan'] =='In Transit' && $status_Array['ScanType'] =='PU')  ||
            ($status_Array['Scan'] =='Pending' && $status_Array['ScanType'] =='PU') ){
                return 'In Transit';
            }
            if(in_array($status_Array['Scan'],array('Dispatched')) && in_array($status_Array['ScanType'],array('UD','PU'))){
                return 'Out for Delivery';
            }
            if(in_array($status_Array['Scan'],array('LOST')) && in_array($status_Array['ScanType'],array('UD','PU'))){
                return 'Lost';
            }
        }
        if($courier_id =='3'){
            if (in_array($status_Array['status_code'], ['227'])) {
                return 'Canceled';
            } elseif (in_array($status_Array['status_code'], ['226'])) {
                return 'DELIVERED';
            } elseif (in_array($status_Array['status_code'], ['225', '235'])) {
                return 'RTO'; // RTO
            } elseif (in_array($status_Array['status_code'], ['224'])) {
                return 'RTO Received'; // RTO Received
            } elseif (in_array($status_Array['status_code'], ['220', '221', '222', '231', '232','238'])) {
                return 'Pickup Pending';
            } elseif (in_array($status_Array['status_code'], ['223', '230', '531', '532'])) {
                return 'In Transit'; // In Transit
            } elseif (in_array($status_Array['status_code'], ['237'])) {
                return 'LOST'; // Lost
            } elseif (in_array($status_Array['status_code'], ['236', '234'])) {
                return 'RTO In Transit'; // RTO In Transit
            } elseif (in_array($status_Array['status_code'], ['228'])) {
                return 'Out for Delivery'; // Out for Delivery
            } elseif (in_array($status_Array['status_code'], ['233'])) {
                return 'NDR'; // NDR
            }
        }
        if($courier_id =='4'){
            $currentStatus = $status_Array['ShipmentStatus'];
            if(in_array($currentStatus,array('RAD',"IT","PKD","PUD"))){
                return 'In Transit';
            }
            if(in_array($currentStatus,array('RTO-IT','RTO-OFD','RAO'))){
                return 'RTO In Transit';
            }
            if(in_array($currentStatus,array('RTO','RTON'))){
                return 'RTO';
            }
            if(in_array($currentStatus,array('RTD'))){
                return 'RTO Delivered';
            }
            if(in_array($currentStatus,array('OFD'))){
                return 'Out for Delivery';
            }
            if(in_array($currentStatus,array('OFP',"DRC"))){
                return 'Pickup Pending';
            }
            if(in_array($currentStatus,array('UD'))){
                return 'NDR';
            }
            if(in_array($currentStatus,array('DLVD'))){
                return 'DELIVERED';
            }
        }
        if($courier_id =='5'){
            if(in_array(strtolower($status_Array['strAction']),array('delivered','otp based delivered'))){
                return 'DELIVERED';
            }
            if(in_array(strtolower($status_Array['strAction']),array('canceled','pickup cancelled'))){
                return 'Canceled';
            }
            if(in_array(strtolower($status_Array['strAction']),array('rto received','rto processed & forwarded','rto booked'))){
                return 'RTO';
            }
            if(in_array(strtolower($status_Array['strAction']),array('rto delivered'))){
                return 'RTO Delivered';
            }
            if(in_array(strtolower($status_Array['strAction']),array('not delivered','wrong pincode'))){
                return 'NDR';
            }
            if(in_array(strtolower($status_Array['strAction']),array('pickup scheduled','not picked','pickup awaited','archived','pickup reassigned','softdata upload'))){
                return 'Pickup Pending';
            }
            if(in_array(strtolower($status_Array['strAction']),array('rto in transit','rto out for delivery','rto returned/rto out for delivery'))){
                return 'RTO In Transit';
            }
            if(in_array(strtolower($status_Array['strAction']),array('picked up','in transit','arrived at airport','not received','customs cleared','customs heldup','held up','mis route','booked','reached at destination'))){
                return 'In Transit';
            }
            if(in_array(strtolower($status_Array['strAction']),array('out for delivery','fdm prepared'))){
                return 'Out for Delivery';
            }
            if(in_array(strtolower($status_Array['strAction']),array('damaged shipment received box opened from hub'))){
                return 'Damaged';
            }
        }
        if($courier_id =='6'){
            if(in_array($status_Array['statusCode'],array('MAN','OFP','PKF'))){
                return 'Pickup Pending';
            }
            if(in_array($status_Array['statusCode'],array('CAN'))){
                return 'Canceled';
            }
            if(in_array($status_Array['statusCode'],array('DDL'))){
                return 'DELIVERED';
            }
            if(in_array($status_Array['statusCode'],array('RTL'))){
                return 'RTO';
            }
            if(in_array($status_Array['statusCode'],array('SUD'))){
                return 'NDR';
            }
            if(in_array($status_Array['statusCode'],array('RTS'))){
                return 'RTO Delivered';
            }
            if(in_array($status_Array['statusCode'],array('PKD','IND','DPD','ARD','RDC'))){
                return 'In Transit';
            }
            if(in_array($status_Array['statusCode'],array('OFD'))){
                return 'Out for Delivery';
            }
            if(in_array($status_Array['statusCode'],array('LST'))){
                return 'Lost';
            }
            if(in_array($status_Array['statusCode'],array('DSD'))){
                return 'Damaged';
            }
        }
        if($courier_id =='7'){
            if(in_array($status_Array['status'],array('shipment_created','pickup_scheduled','out_for_pickup','pickup_reattempt'))){
                return 'Pickup Pending';
            }
            if(in_array($status_Array['status'],array('pickup_cancelled'))){
                return 'Canceled';
            }
            if(in_array($status_Array['status'],array('rto_created'))){
                return 'RTO';
            }
            if(in_array($status_Array['status'],array('rto_completed'))){
                return 'RTO Delivered';
            }
            if(in_array($status_Array['status'],array('rto_received','rto_in_transit','rto_cancelled'))){
                return 'RTO In Transit';
            }
            if(in_array($status_Array['status'],array('unsuccessful_delivery_attempt_due_to_serviceability_issues','unsuccessful_delivery_attempt_due_to_address_issues','undelivered','undelivered_due_to_customer_unavailability','undelivered_due_to_rejection_by_customer','undelivered_due_to_cash_unavailability','undelivered_due_to_request_for_reschedule'))){
                return 'NDR';
            }
            if(in_array($status_Array['status'],array('pickup_complete','in_transit','untraceable','shipment_expected'))){
                return 'In Transit';
            }
            if(in_array($status_Array['status'],array('out_for_delivery'))){
                return 'Out for Delivery';
            }
            if(in_array($status_Array['status'],array('lost'))){
                return 'Lost';
            }
            if(in_array($status_Array['status'],array('damaged'))){
                return 'Damaged';
            }
            if(in_array($status_Array['status'],array('delivered'))){
                return 'DELIVERED';
            }
        }
        if($courier_id =='8'){
            
            if($typet == 'forward'){
                $status = $status_Array['status_id'] ?? '';
                if (in_array($status, ['cancelled_by_seller'])) {
                    return'Canceled';
                }
                if (in_array($status, ['delivered'])) {
                    return'DELIVERED';
                }
                if (in_array($status, ['cancelled_by_customer','rts'])) {
                    return'RTO';
                }
                if (in_array($status, ['rts_nd'])) {
                    return 'RTO Received';
                }
                if (in_array($status, ['cid','seller_initiated_delay','seller_not_contactable','nc','pickup_not_attempted'])) {
                    return 'NDR';
                }
                if (in_array($status, ['new','assigned_for_seller_pickup','ofp'])) {
                    return 'Pickup Pending';//manifested
                }
                if (in_array($status, ['rts_d','rts_in_process','rts_ofd','recd_at_dc_rts','received_at_rts_hub'])) {
                    return 'RTO In Transit';
                }
                if (in_array($status, ['picked','recd_at_rev_hub','item_manifested','recd_at_fwd_hub','recd_at_fwd_dc','assigned_for_delivery','na','on_hold','pickup_on_hold','in_transit_return','bag_received','bag_received_at_via','bag_in_transit','pincode_updated','item_misrouted'])) {
                    return 'In Transit';
                }
                if (in_array($status, ['ofd','reopen_ndr'])) {
                    return 'Out for Delivery';
                }
                if (in_array($status, ['lost'])) {
                    return 'Lost';
                }
            }else{
                $status = $status_Array['state'] ?? '';
                if (in_array($status, ['Cancelled'])) {
                    return'Canceled';
                } elseif (in_array($status, ['delivered'])) {
                    return 'DELIVERED';
                } elseif (in_array($status, ['QC Failed', 'Return to Seller initiated'])) {
                    return 'RTO';
                } elseif (in_array($status, ['Returned To Client'])) {
                    return 'RTO Received';
                } elseif (in_array($status, ['New', 'Assigned', 'Out For Pickup', 'On Hold','Not Contactable'])) {
                    return 'Pickup Pending';//manifested
                } elseif (in_array($status, ['Undelivered', 'Bag In Transit for Return'])) {
                    return 'RTO In Transit';
                } elseif (in_array($status, ['Received at RTS destination hub', 'Pincode Updated', 'Item Misrouted', 'Bag Received', 'Bag Received at Via', 'Bag In Transit', 'Item added to Bag', 'Picked', 'Received', 'Received at Return DC', 'Not Attempted', 'Returned To Client'])) {
                    return 'In Transit';
                } elseif (in_array($status, ['Cid',  'Return Shipment Out for Delivery'])) {
                    return 'Out for Delivery';
                } elseif (in_array($status, ['Undelivered'])) {
                    return 'NDR';
                }
            }
        }
        if($courier_id == '9'){
            $status = $status_Array['eventCode'];
            if (in_array($status, ['PickupCancelled'])) {
                return'Canceled';
            } elseif (in_array($status, ['PreTransit','ReadyForReceive'])) {
                return'Pickup Pending'; // or '12'
            } elseif (in_array($status, ['InTransit','PickupDone','ArrivedAtCarrierFacility','Departed','AwaitingCustomerPickup'])) {
                if($status_Array['shipmentType'] =='FORWARD')
                {
                    return'In Transit'; // or '14'
                }else if($status_Array['shipmentType'] =='RETURNS'){
                    return 'RTO In Transit';
                }
            } elseif (in_array($status, ['Delivered'])) {
                if($status_Array['shipmentType'] =='FORWARD')
                {
                    return'Delivered'; 
                }else if($status_Array['shipmentType'] =='RETURNS'){
                    return'RTO Delivered'; 
                }
            } elseif (in_array($status, ['Lost'])) {
                return'Lost'; // or '16'
            }elseif (in_array($status, ['ReturnInitiated'])) {
                return'RTO'; // or '5'
            } elseif (in_array($status, ['OutForDelivery'])) {
                if($status_Array['shipmentType'] =='FORWARD')
                {
                    return'Out for Delivery'; // or '15'
                }else if($status_Array['shipmentType'] =='RETURNS'){
                    return 'RTO In Transit';
                }
                
            } elseif (in_array($status, ['Rejected', 'Undeliverable', 'DeliveryAttempted'])) {
                return'NDR'; // or '10'
            }
        }
        return $kimistatus;
    }

     public static function ordermanifest($order_id,$user_id){
        $ordarray = explode(',', $order_id);
        $manifest_order_array = array();
        foreach($ordarray as $id){
            $id = ltrim(rtrim($id));
            $order = Order::where('id', $id)->where('user_id', $user_id)->where('ship_courier_id','!=',0)->where('ship_courier_id','!=',null)->first();
            if($order){
                if($order->tracking_info !='' && ($order->manifest_id ==0 || $order->manifest_id =='') && (strip_tags($order->status) =='Shipped' || strip_tags($order->status) =='Pickup Pending')){
                    $manifest_order_array[$order->ship_courier_id][] = $id;
                }
                
            }
        }
        $msg ='';
        if(!empty($manifest_order_array)){
            foreach($manifest_order_array as $m_key => $m_order){
                    $manifest_new = new Manifest();
                    $manifest_new->created_by = $user_id;
                    $manifest_new->courier_id = $m_key;
                    $manifest_new->updated_at = now();
                    $manifest_new->created_at = now();
                    $manifest_new->save();
                    $manf_id = $manifest_new->id;
                for($i=0;$i<count($m_order);$i++){
                    $order_mani = Order::find($m_order[$i]);
                    $old_status = strip_tags($order_mani->status);
                    $order_mani->manifest_id = $manf_id;
                    $order_mani->status = '12';
                    $order_mani->manifest_date = now();
                    $order_mani->save();
                    Status::orderstatuslog($m_order[$i],$old_status,'Pickup Pending',now());
                    $msg .= $m_order[$i].',';
                }
            }
        }
        if($msg ==''){
            $returnmsg[0]='error';
            $returnmsg[1]='No Order found for manifest, please chk order id';
        }else{
            $returnmsg[0]='success';
            $returnmsg[1]=rtrim($msg,',').' successfully manifested';
        }
            return $returnmsg;
        
     }

}