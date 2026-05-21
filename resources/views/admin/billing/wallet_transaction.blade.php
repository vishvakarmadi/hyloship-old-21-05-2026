@extends('admin.admin_layouts')
@section('admin_content')



<!--<div id="navbar-animmenu">
    <ul class="show-dropdown main-navbar">
        <div class="hori-selector" style="margin-left: 20px;">
            <div class="left"></div>
            <div class="right"></div>
        </div>
         <li class="{{ request()->routeIs('admin.shipping_charges') ? 'active' : '' }}">
            <a href="{{ route('admin.shipping_charges') }}" data-id="shipping_charges">Shipping charges</a>
        </li> 
        <li class="{{ request()->routeIs('admin.invoices') ? 'active' : '' }}">
            <a href="{{ route('admin.invoices') }}" data-id="invoices">Invoices</a>
        </li>
        <li class="{{ request()->routeIs('admin.credit_notes') ? 'active' : '' }}">
            <a href="{{ route('admin.credit_notes') }}" data-id="credit-notes">Credit Notes</a>
        </li>
        <li class="{{ request()->routeIs('admin.wallet_transaction') ? 'active' : '' }}">
            <a href="{{ route('admin.wallet_transaction') }}" data-id="wallet_transaction">Wallet Transaction</a>
        </li>
    </ul>
</div>-->

<div class="card mt-30 wallet_transaction">
    <div class="card-header">
        <h5 class="card-title mb-0">Wallet Transaction</h5>
    </div>
    <form action="{{ route('admin.wallet_transaction') }}" method="GET" style="padding: 16px;">
        <div class="row">
            <div class="form-group col-md-8">
                <label>Select Date Range</label><span class="required"> *</span>
                <div class="row">
                    <div class="col-md-6">
                        <input class="form-control" type="date" name="start_date" required="" value="{{ explode(' ', $re_data['start_date'])[0] }}" id="_1">
                    </div>
                    <div class="col-md-6">
                        <input class="form-control" type="date" name="end_date" required="" value="{{ explode(' ', $re_data['end_date'])[0] }}" id="_2">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <x-button size="col-lg-3" type="submit" name="Search" />
        </div>
    </form>  
    <hr>  
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover dataTable js-exportable sorttableexceldatesortsecond">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Tracking ID</th>
                        <th>AWB</th>
                        <th>Credit</th> 
                        <th>Debit</th>
                        <th>Closing Blc</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction as $row)
                    <tr>
                        <td class="date-column"><span class="fa fa-calendar"></span>&nbsp;
                            {{ \Carbon\Carbon::parse($row->updated_at)->format('d M, Y') }}<br>
                            <span class="fa fa-clock-o"></span>&nbsp;
                            {{ \Carbon\Carbon::parse($row->updated_at)->format('H:i') }}
                        </td>
                        <td class="text-center"> 
                            {{ $row->id }}
                        </td>
                        <td class="anchor-column">
                            @if ($row->order_id != 0) 
                            <a href="{{ route('admin.order.detail', $row->order_id) }}">
                                {{$row->awb}}
                            </a>
                            @endif
                        </td>
                        <td style="color:green">{{ $row->credit }}</td>
                        <td style="color:red">
                            @if ($row->debit != 0) 
                            - {{ $row->debit }}
                            @else
                            {{ $row->debit }}
                            @endif
                        </td>
                        <td>{{ $row->closing_blc }}</td>
                        <td>
                            @if (strlen($row->remarks) > 45)
                                {{ substr($row->remarks, 0, 44) }} ...
                            @else
                                {{ $row->remarks }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card mt-30 invoices hide"></div>
@endsection

