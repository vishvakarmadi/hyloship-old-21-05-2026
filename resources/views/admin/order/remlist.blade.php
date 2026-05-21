@php
use App\Models\Admin\Order;
@endphp
@extends('admin.admin_layouts')
@section('admin_content')
<style>
.card.pt-30 .card-title {
    font-size: 20px;
    font-family: 'FontAwesome';
}
.col-3 .card-body{
    padding: 8px;
    text-align: center;
}
.col-3 .card-header{
    text-align: center;
}
.btn-group{
        display: block;
    }
    .btn-group .multiselect{
        width:100%
    }
    .multiselect-container
    , .multiselect-container>li>a>label.checkbox {
        width: 100%;
    }
    </style>

    <div class="row clearfix">
             
        <div class="col-xl-12 mt-3">
            <div class="container-fluid">
        
        <div class="row clearfix">
            <div class="col-3">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Remittance till date</h5>
                    </div>
                    <div class="card-body">
                        <h5 class='remitilldata'>Loading..</h5>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Last Remittance</h5>
                    </div>
                    <div class="card-body">
                        <h5 class='lastremitance'>Loading..</h5>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card pt-30">
                    <div class="card-header">
                    <h5 class="card-title mb-0" style="font-size: 18px;">Next Remittance(Expected)</h5>
                    </div>
                    <div class="card-body">
                        <h5 class='nextremitance'>RS.   </h5>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Total Remittance Due</h5>
                    </div>
                    <div class="card-body">  
                        <h5 class='dueremitance'>RS. </h5>
                    </div>
                </div>
            </div>
        </div>
        </div>
        @if (in_array($user->role_id,array('1')))
        <div class="col-md-12 d-flex justify-content-end mb-3">
    <x-button type="import" route="{{ route('admin.order.bulkremittance') }}" name="Import" />
    <a href="{{ route('admin.order.remlist', ['intrsitdata' => '1']) }}" class="btn btn-secondary" title="View" style="margin-left: 10px;">Show complete data</a>
</div>
        @endif
    
        <div class="col-xl-12">
        <form id="myForm" action="{{ route('admin.order.action') }}" method="POST">
            @csrf
            <div class="card  new_orders">
                <div class="header d-flex justify-content-between" style="padding-bottom: 4px;">
                    <h2>Remittance List</h2>
                    @if (in_array($user->role_id,array('1')))
                    <!-- <div class="form-group col-2 mb-0">
                        <label class="mr-2">@lang('Action')</label>
                        <select class="form-control" name="status" id="myselect">
                            <option value="" selected disabled>@lang('Select One')</option>
                            <option value="remitance">Paid</option>
                            
                        </select>
                    </div> -->
                    @endif
                   <input type="hidden" name ='path' value="all">
                   <!-- <div class="form-group col-md-3">
                        <label class="form-control-label">Amount Recharge</label><span class="required"></span>:
                        <input type="number" name="recharge" value="" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="form-control-label">Paid Amaount</label><span class="required"> </span>:
                            <input type="number" name="paid" value="" required>
                    </div> -->
                </div>

                <div class="">
                        <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sorttablenew  table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <!-- <th class="text-center" data-field="hideexport">
                                            <label class="fancy-checkbox">
                                                <input class="select-all" type="checkbox" name="checkbox">
                                                <span></span>
                                            </label>
                                        </th> -->
                                        <th>R-Id</th>
                                        <th>Seller-id</th>
                                        <th>Seller</th>
                                        <th>StartDate</th>
                                        <th>EndDate</th>
                                        <th>Total Amount Collected</th>
                                        <th>Freight Charge</th>
                                        <th>Wallet Recharge</th>
                                        <th>Total Paid</th>
                                        <th>Pending Amount</th>
                                        <th>UTR</th>
                                        <th>Status</th>
                                        @if (in_array($user->role_id,array('1')) )
                                        @if($show_intrsit =='1')
                                        <th>Current Wallet</th>
                                        <th>Current Intransit Orders</th>
                                        <th>Current Intransit Amount</th>
                                        @endif
                                       <th data-field="hideexport">Action</th> 
                                       @endif
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php $total =$lastremitance=$nextremitance =$dueremitance=0;?>
                                        @foreach($remlist as $or)
                                        <?php 
                                        $or->start_date = explode(' ',$or->start_date)[0];
                                        $or->end_date = explode(' ',$or->end_date)[0];
                                        ?>
                                        <tr>
                                            <!-- <td class="text-center">
                                                <label class="fancy-checkbox">
                                                    <input class="checkbox-tick" type="checkbox" name="id[{{ $or->id }}]" value="{{ $or->id }}">
                                                    <span></span>
                                                </label>
                                            </td> -->
                                            <td><a href="{{ route('admin.order.remview',$or->id) }}">{{$or->id}}</a></td>
                                            <td>{{$or->user_id}}</td>
                                            <td>{{$or->name}}</td>
                                            <td>{{$or->start_date}}</td>
                                            <td>{{$or->end_date}}</td>
                                            <td>{{$or->cod_amount}}</td>
                                            <?php 
                                            $wllaetonlast =  Order::getwalleton($or->end_date,$or->user_id);
                                            ?>
                                            <td><a href="/admin/billing/billing-info?start_date={{$or->start_date}}&end_date={{$or->end_date}}" target="_blank">{{$wllaetonlast}}</a></td>
                                            <td>{{$or->recharge}}</td>
                                            <td>{{$or->paid}}</td>
                                            <?php 
                                            $pending = (float)($or->cod_amount) - ((float)$or->paid  + (float)$or->recharge);
                                            $dueremitance  = $dueremitance + ($or->cod_amount - $pending);
                                            if($or->status == 'in-progress'){
                                                $nextremitance += $or->cod_amount;
                                            }
                                            ?>
                                            <td>{{$pending}}</td>
                                            <td>{{$or->utr}}</td>
                                            
                                            <td>{{$or->status}}</td>
                                            @if (in_array($user->role_id,array('1')) )
                                            @if($show_intrsit =='1')
                                            <?php 
                                            $intransitdetails['current_wallet'] =0;
                                            $intransitdetails['count'] =0;
                                            $intransitdetails['total'] =0;
                                            $intransitdetails =  Order::getintransitdetails($or->user_id); 
                                            ?>
                                            <td>{{$intransitdetails['current_wallet']}}</td>
                                            <td>{{$intransitdetails['count']}}</td>
                                            <td>{{$intransitdetails['total']}}</td>
                                            @endif
                                               <td>
                                                   @if($or->status =='in-progress')
                                                   <button type="button" class="btn btn-sm edit-btn btn-success" data-title="Pay-Remittance"
                                                                   data-edit='@json($or)'>
                                                                   <i class="fa fa-money"></i>
                                                               </button>
                                                   @endif
                                               </td>
                                            @endif
                                         
                                        </tr>
                                            
                                        @endforeach

                                        
                                    
                                </tbody>
                            </table>        
                        </div>
                </div>
                </div>   
            </div>
        </form>         
        </div>  
        <div id="my-modal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog" role="document" style="max-width: 1000px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" class="myform">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="modal-body">
                                            <!-- <input class="hidden" id="rem_id" type="text" name="rem_id"  value=""> -->
                                            
                                                <div class="form-group">
                                                    <label class="st_url">AMount Received</label>
                                                    <input class="form-control" id="cod_amount" type="text" name="cod_amount" required readonly
                                                        value="{{ old('cod_amount') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="st_url">AMount Shipping</label>
                                                    <input class="form-control" id="shipping_amount" type="text" name="shipping_amount" required readonly
                                                        value="{{ old('shipping_amount') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="st_url">Amount Paid(in account)*</label>
                                                    <input class="form-control" id="paid" step="any" type="text" name="paid" required
                                                        value="{{ old('paid') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label class="st_url">Amount Recharge*</label>
                                                    <input class="form-control" id="recharge" step="any" type="text" name="recharge" required
                                                           value="" min="-999999">
                                                </div>
                                                <div class="form-group">
                                                    <label class="st_url">Utr*</label>
                                                    <input class="form-control" id="utr" type="text" name="utr" required
                                                        value="{{ old('utr') }}">
                                                </div>
                                                

                                              <div class="form-group"> <label for="">
                                                <input type="checkbox" name="term" required class="form-control" style="width: 22px;float: left; margin-top: 6px;"> I certify that the above information is true to the best of my knowledge</label> 
                                              </div> 
                                              
                                            </div>
                                        </div>

                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-secondary h-45 w-100">@lang('Submit')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>      
    </div>
</div>

<script type="text/javascript">
    (function($) {
        "use strict";
        $('select[name=status]').change(function() {
            if ($('input[name^="id"]:checked').length > 0) {
                var action_type = $('select[name=status]').val();
                
                $('#myForm').submit();
            } else {
                toastr.error('Select atleast one seller');
                $('select[name=status]').val('');
            }
        });


        
        $('.expand').on('click',function() {
            if ($(this).text() == 'Filters>>') {
                $(this).text('Filters<<');
            } else {
                $(this).text('Filters>>');
            }
            $('.show_more').slideToggle('fast');
        });
    })(jQuery);


</script>
<script>
    $(document).ready(function() {
    // Destroy existing DataTable instance, if it exists
    if ($.fn.DataTable.isDataTable('.sorttablenew')) {
        $('.sorttablenew').DataTable().destroy();
    }

    // Initialize DataTable with your options
    $('.sorttablenew').DataTable({
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'csv',
                text: 'Export CSV',
                filename: function() {
                    // Get the table name
                    var tableName = $('.sorttablenew').attr('name');
                    return tableName;
                },
                exportOptions: {
                    columns: ':not([data-field="hideexport"])'
                }
            }
        ]
    });

    // Sorting the DataTable by a specific column in descending order
    $('.sorttablenew').DataTable().order([1, 'desc']).draw();
    });
    "use strict";
        (function($) {
            $(document).ready(function() {
                let myModal = new bootstrap.Modal(document.getElementById('my-modal'));
                let action = `{{ route('admin.order.saverem') }}`

                
                $('.edit-btn').on('click', function(e) {
                let action = `{{ route('admin.order.saverem', ':id') }}`;
                let data = $(this).data('edit');
                $('.modal-title').text("@lang('Pay Remittance')");
                $("input[name=cod_amount]").val(data.cod_amount);
                $("input[name=shipping_amount]").val(data.shipping_amount);
                $("select[name=rem_id]").val(data.id);
                
                
                // }
                // $("select[name=country_id]").val(data.country_id);

                $('form').attr("action", action.replace(":id", data.id));
                myModal.show();
                
                });


            });
        })(jQuery);
        window.onload = function() {
        $(".remitilldata").text("Rs. <?php echo $totalrem; ?>");
        $(".dueremitance").text("Rs. <?php echo $duerem; ?>");
        $(".nextremitance").text("Rs. <?php echo $nextremitance; ?>");
        $(".lastremitance").text("Rs. <?php echo $lastrem; ?>");
        }
</script>
@endsection