@extends('admin.admin_layouts')
@section('admin_content')
@php
$session = Auth::guard('admin')->user();
@endphp

<style>
label {
    display: inline-block;
    margin-bottom: 0px;
}
td.active_0 {
    background: #ff904f;
}
</style>
<!-- Main body part  -->
<div class="container-fluid">
    <!-- Page header section  -->

    <div class="row clearfix">
        <div class="col-xl-12">
            <div class="card bg-light mb-3">
                <div class="card-header" style="display:flex;flex-wrap: wrap;">
                    <!-- <h5 class="m-0 mt-2 font-weight-bold text-primary invoice-heading">Filter</h5> -->
                    <div class="col-md-9"> <a href="javascript:void(0)" class="expand">Filters <?php if(empty($re_data)){ echo '<<';}else{ echo '>>';} ?></a></div>
                    <div class="col-md-3">
                        <x-button type="import" route="{{ route('admin.integration.createserviceable') }}" name="Import"/>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.integration.courier_serviceable') }}" method="GET">
                        <div class="col-md-12">
                            <?php $pincodes='';
                            if(!empty($re_data)){
                              if(isset($re_data['pincodes']))
                                $pincodes = $re_data['pincodes'];
                              
                            }
                            ?>
                            <div class="show_more" style="width: 100%; ">
                                <div class="row">
                                    <x-field type="text" label="Pincodes" placeholder="Pincodes" name="pincodes" value="{{$pincodes}}" />
                                </div>
                                <div class="row">
                                    <x-button size="col-lg-3" type="submit" name="Search" />
                                </div>
                            </div>
                           
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-xl-12">
            <div class="card new_orders">
                 <div class="header d-flex justify-content-between" style="padding-bottom: 0;">
                        <h2 class="col-md-9">Serviceable Pincode</h2>
                        <div class="col-md-3">
                            <button id="btnExport" class="btn btn-primary" onclick="fnExcelReport();" style="height:30px;font-size:12px"><i class="fa fa-file-excel-o" style=""></i> EXPORT </button> 
                        </div>
                        
                   </div>
                  
                

                <div class="card-body">
                    <div class="table-responsive">
                <table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Pincode</th>
                            <th colspan ="27" class="text-center">Courier</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td colspan="3" class="text-center">Ecom Express</td>
                            <td colspan="3" class="text-center">Delhivery</td>
                            <td colspan="3" class="text-center">Bluedart</td>
                            <td colspan="3" class="text-center">XpressBees</td>
                            <td colspan="3" class="text-center">DTDC</td>
                            <td colspan="3" class="text-center">Smartr</td>
                            <td colspan="3" class="text-center">Ekart</td>
                            <td colspan="3" class="text-center">Shadowfax</td>
                            <td colspan="3" class="text-center">ATS</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Transfer</td>
                            <td>Payment</td>
                            <td>Mode</td>
                            <td>Transfer</td>
                            <td>Payment</td>
                            <td>Mode</td>
                            <td>Transfer</td>
                            <td>Payment</td>
                            <td>Mode</td>
                            <td>Transfer</td>
                            <td>Payment</td>
                            <td>Mode</td>
                            <td>Transfer</td>
                            <td>Payment</td>
                            <td>Mode</td>
                            <td>Transfer</td>
                            <td>Payment</td>
                            <td>Mode</td>
                            <td>Transfer</td>
                            <td>Payment</td>
                            <td>Mode</td>
                            <td>Transfer</td>
                            <td>Payment</td>
                            <td>Mode</td>
                            <td>Transfer</td>
                            <td>Payment</td>
                            <td>Mode</td>
                        </tr>
                        @foreach($pincodedata as $pc)
                        <tr>
                            <td>{{$pc['pincode']}}</td>
                            @for($i=1;$i<10;$i++)
                                @if(isset($pc[$i]))
                                    <td class="active_{{$pc[$i]['active']}}">{{$pc[$i]['mode']}}</td>
                                    <td class="active_{{$pc[$i]['active']}}">{{$pc[$i]['payment']}}</td>
                                    <td class="active_{{$pc[$i]['active']}}">{{$pc[$i]['type']}}</td>
                                @else
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
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.0/xlsx.full.min.js"></script>
<script>
    function fnExcelReport() {
    var tab = document.getElementById('dataTable');
    var wb = XLSX.utils.table_to_book(tab);
    XLSX.writeFile(wb, 'report.xlsx');
}
    (function($) {
        "use strict";
        
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


@endsection