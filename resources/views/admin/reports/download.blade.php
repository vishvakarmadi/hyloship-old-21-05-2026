@extends('admin.admin_layouts')
@section('admin_content')

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 mt-2 font-weight-bold text-primary">Report</h6>
            <button id="btnExport" class="btn btn-primary" onclick="fnExcelReport();" style="height:30px;font-size:12px"><i class="fa fa-file-excel-o" style=""></i> EXPORT </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Order id</th>
                        <th>Order No.</th>
                        
                        <th>Seller Name</th>
                        <th>Courier Name</th>
                        <th>Channel</th>
                        <th>Created</th>
                        <th>Status</th>
                        <th>PaymentMode</th>
                        <th>Total Amount</th>
                        <th>Shipping date</th>
                        <th>Awb</th>
                        <th>Manifest id</th>
                        <th>Shipment Status</th>
                        <th>Total Attempt Count</th>
                        <th>Customer Name</th>
                        <th>Customer Phone</th>
                        <th>Customer Email</th>
                        <th>Pincode</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Dimension(CM)</th>
                        <th>Weight(kg)</th>
                        <th>Vol.Weight(kg)</th>
                        <th>Shiping Charge</th>
                        <th>Zone</th>
                        <th>Order tag</th>
                        <th>Product 1</th>
                        <th>SKU 1</th>
                        <th>QTY 1</th>
                        <th>Price 1</th>
                        <th>Discount type 1</th>
                        <th>Discount amount 1</th>
                        <th>Tax amount 1</th>
                        <th>T. amount 1</th>
                        <th>Product 2</th>
                        <th>SKU 2</th>
                        <th>QTY 2</th>
                        <th>Price 2</th>
                        <th>Discount type 2</th>
                        <th>Discount amount 2</th>
                        <th>Tax amount 2</th>
                        <th>T. amount 2</th>
                        <th>Product 3</th>
                        <th>SKU 3</th>
                        <th>QTY 3</th>
                        <th>Price 3</th>
                        <th>Discount type 3</th>
                        <th>Discount amount 3</th>
                        <th>Tax amount 3</th>
                        <th>T. amount 3</th>
                        <th>Product 4</th>
                        <th>SKU 4</th>
                        <th>QTY 4</th>
                        <th>Price 4</th>
                        <th>Discount type 4</th>
                        <th>Discount amount 4</th>
                        <th>Tax amount 4</th>
                        <th>T. amount 4</th>
                        <th>Product 5</th>
                        <th>SKU 5</th>
                        <th>QTY 5</th>
                        <th>Price 5</th>
                        <th>Discount type 5</th>
                        <th>Discount amount 5</th>
                        <th>Tax amount 5</th>
                        <th>T. amount 5</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach($orders as $order)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$order->order_id}}</td>
                            <td>{{$order->vendor_order_id}}</td>
                            <td>{{$order->user_id}}</td>
                            <td>{{ isset($couriers[$order->ship_courier_id]) ? $couriers[$order->ship_courier_id]['name'] : ($order->ship_courier_id ? 'Courier ID: '.$order->ship_courier_id : 'N/A') }}</td>
                            <td>{{$order->channel}}</td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i:s') }}</td>
                            <td>
                                @if($order->manifest_id && strip_tags($order->status) == 'Shipped')
                                    Manifested
                                @else
                                    {{ strip_tags($order->status) }}
                                @endif
                            </td>
                            <td>{{ strip_tags($order->payment_mode) }}</td>
                            <td>{{ $order->total }}.00</td>
                            <td>{{ $order->shipped_date ? \Carbon\Carbon::parse($order->shipped_date)->format('Y-m-d H:i:s') : '' }}</td>
                            <td>{{$order->tracking_info}}</td>
                            <td>{{$order->manifest_id}}</td>
                            <td></td>
                            <td>0</td>
                            <td>{{$order->ship_fname.' '.$order->ship_lname}}</td>
                            <td>{{$order->ship_phone}}</td>
                            <td>{{$order->ship_email}}</td>
                            <td>{{$order->ship_pincode}}</td>
                            <td>{{$order->ship_city}}</td>
                            <td>{{$order->ship_state}}</td>
                            <td>{{$order->length.'*'.$order->breadth.'*'.$order->height}}</td>
                            <td>{{$order->weight/1000}}</td>
                            <td>
                                @if($order->ship_courier_id == '2' || $order->ship_courier_id == '5')
                                    @if($order->ship_courier_id == '2')
                                        {{($order->length*$order->breadth*$order->height)/4000}}
                                    @else
                                        {{($order->length*$order->breadth*$order->height)/4750}}
                                    @endif    
                                @else
                                        {{($order->length*$order->breadth*$order->height)/5000}}
                                @endif
                            </td>
                            <td>
                                {{$order->shipping_courier_cost}}
                            </td>
                            <td>{{$order->zone}}</td>
                            <td>{{$order->tags}}</td>
                            @for($j=0;$j<5;$j++)
                                @if(isset($order->detail[$j]))
                                    <?php $detail = $order->detail[$j];?>
                                    <td>{{$detail->name}}</td>
                                    <td>{{$detail->code}}</td>
                                    <td>{{$detail->qty}}</td>
                                    <td>{{$detail->price}}</td>
                                    <td>
                                        @if($detail->discount_type =='p')
                                            Percentage
                                        @else
                                            Flat
                                        @endif
                                    </td>
                                    <td>{{$detail->discount}}</td>
                                    <td>{{$detail->tax_amount}}</td>
                                    <td>{{$detail->total_price}}</td>
                                @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endif    
                            @endfor
                            
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
</div>
<script>
   function fnExcelReport() {
       var path = "<?php  echo $path ?>"
    var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
    var j = 0;
    var tab = document.getElementById('dataTable'); // id of table

    for (j = 0; j < tab.rows.length; j++) {
        tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
        //tab_text=tab_text+"</tr>";
    }

    tab_text = tab_text + "</table>";
    tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
    tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var msie = window.navigator.userAgent.indexOf("MSIE ");

    // If Internet Explorer
    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
        txtArea1.document.open("txt/html", "replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus();

        sa = txtArea1.document.execCommand("SaveAs", true, "download.xls");
     } else {
        // Modern approach using Blob to handle larger data sets and avoid browser blocking
        var blob = new Blob(['\uFEFF' + tab_text], { type: 'application/vnd.ms-excel' });
        var url = window.URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'order_report.xls';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }
    return true;

};

</script>
@endsection