@php
    $user_data = auth()->guard('admin')->user();
    $general_setting = DB::table('general_settings')
        ->where('company_id', $user_data->company_id)
        ->first();
// echo '<pre>';print_R($user_data->company_id);die;
    $role = DB::table('roles')
        ->where(
            'id',
            $user_data->role_id,
        )
        ->first();
        $userId = $user_data->id;
        $today = date('Y-m-d'); // Assuming the date format in your database is 'Y-m-d'
        $broadcast = DB::table('broadcasts')
        ->whereRaw("FIND_IN_SET(?, user_id) > 0", [$userId])
        ->where('active','1')
         ->where(function($query) use ($today) {
        $query->whereDate('from_date', '<=', $today)
              ->whereDate('to_date', '>=', $today);
    })
        ->first();
$broadcast =true
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>
        @if(isset($general_setting))
                    {{$general_setting->name}}
        @else
            HYLO
        @endif
    </title>

    @include('admin.includes.styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    @php
        if(isset($general_setting)){
    @endphp
        <link href="{{ asset('public/uploads/' . $general_setting->favicon) }}" rel="shortcut icon" type="image/png">
    @php
        }
    @endphp

</head>
@php
    $g_setting = $general_setting;
    // echo '<pre>';print_R($g_setting);die;
    if(!isset($general_setting)) {
        $g_setting->theme_color = '#000';
    }
@endphp

@php
    $url = Request::path();
    $conName = explode('/', $url);
    if (!isset($conName[3])) {
        $conName[3] = '';
    }
    if (!isset($conName[2])) {
        $conName[2] = '';
    }

@endphp

<style>/* ================= GLOBAL THEME ================= */
::selection{
    color:#fff;
    background: {{$g_setting->theme_color}};
}


 option:checked{
        background: {{$g_setting->theme_color}} !important;
        color: #fff;
    }
    .sidebar .metismenu a.menu.active{
        background: {{$g_setting->theme_color}} !important;
        color: #fff !important;
    }
    .sidebar .metismenu a.menu.active::before {
        background: #fff;
    }
    thead{
        background: {{$g_setting->theme_color}} !important;
        color: #fff;
    }
    thead a{
        color: #fff;
        text-decoration: underline;
    }
    tbody a{
        color: #000;
        text-decoration: underline;
    }
    option:hover{
        background: {{$g_setting->theme_color}} !important;
    
    }
    .sidebar .metismenu a{
        color:{{$g_setting->theme_color}} !important;
    }
    .display-none {
        display: none !important;
    }

    #getDataBtn{
        background: #e2e222;
        border: 1px solid #e2e222;
        padding:  10px 20px;
    }
body{
    margin:0;
    padding:0;
}

/* ================= TOP MARQUEE ================= */
.fixed-marquee{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:60px;
    background:#fff;
    display:flex;
    align-items:center;
    z-index:1050;
    box-shadow:0 2px 6px rgba(0,0,0,.1);
    overflow:hidden;
    white-space:nowrap;
}
#wrapper{ padding-top:60px; }

.marquee-wrapper{
    display:inline-flex;
    animation:marquee 25s linear infinite;
}
@keyframes marquee{
    0%{transform:translateX(0)}
    100%{transform:translateX(-50%)}
}

/* ================= NAVBAR BASE ================= */
.main-navbar{
    position:fixed;
    left:0;
    right:0;
    height:64px;
    background: {{$g_setting->theme_color}} !important;
    z-index:1040;
    border:none;
    padding:0 20px;
}

/* FLEX CONTAINER */
.navbar-flex{
    display:flex;
    align-items:center;
    width:100%;
    height:64px;
}

/* LEFT LOGO */
.navbar-left{
    flex:0 0 auto;
    display:flex;
    align-items:center;
}

/* CENTER MENU (MOST IMPORTANT FIX) */
.navbar-center{
    flex:1 1 auto;
    display:flex;
    align-items:center;
    margin-left:30px;
    min-width:0;
}

/* MENU UL (BOOTSTRAP BREAKER FIX) */
.top-menu{
    display:flex;
    align-items:center;
    gap:6px;
    margin:0;
    padding:0;
    list-style:none;
    flex:1 1 auto;      /* MENU WILL TAKE FULL SPACE */
}

/* REMOVE DEFAULT UL WIDTH BEHAVIOUR */
.top-menu > li{
    flex:0 0 auto;
    list-style:none;
}

/* RIGHT SIDE (PUSH TO EXTREME RIGHT) */
.navbar-right{
    flex:0 0 auto;
    display:flex;
    align-items:center;
    gap:14px;
    margin-left:auto;   /* KEY LINE */
}

/* ================= LOGO ================= */
.company-logo{
    height:38px;
    object-fit:contain;
}

/* ================= MENU LINKS ================= */
.top-menu > li > a{
    color:#fff !important;
    padding:10px 12px;
    font-size:13px;
    font-weight:500;
    white-space:nowrap;
    display:block;
}
.top-menu > li.active > a{
    border-bottom:3px solid #fff;
}


/* ===== DROPDOWN SPACING FIX ===== */

.dropdown-menu{
    border-radius:10px;
    padding:10px 0;
    min-width:220px;
    box-shadow:0 12px 30px rgba(0,0,0,.18);
}

/* Each item proper height */
.dropdown-menu > li > a{
    padding:12px 20px !important;
    font-size:14px;
    line-height:22px;
    display:block;
    color:#333 !important;
    transition:all .2s ease;
}

/* Space between items */
.dropdown-menu > li{
    margin:2px 0;
}

/* Hover effect */
.dropdown-menu > li > a:hover{
    background:#f5f5f5;
    padding-left:24px !important;
}

/* Remove bootstrap tightness */
.dropdown-menu > li > a:focus{
    background:#f5f5f5;
}


/* ================= USER / WALLET ================= */
.user-btn{
    display:flex;
    align-items:center;
    gap:8px;
    color:#fff !important;
}
.wallet-pill{
    background:rgba(255,255,255,.18);
    padding:6px 12px;
    border-radius:8px;
    color:#fff;
}

.logout-icon{
    color:#e74c3c !important;
    font-weight:600;
}
.logout-icon i{
    margin-right:6px;
}


/* ================= REMOVE SIDEBAR ================= */
#leftsidebar{ display:none !important; }
#main-content{
    margin-left:0 !important;
    width:100% !important;
}

/* ================= PREVENT WRAP ================= */
.navbar ul, .navbar li{
    white-space:nowrap;
}

/* ================= TABLE HEADER ================= */
thead{
    background: {{$g_setting->theme_color}} !important;
    color:#fff;
}

/* ================= BUTTON ================= */
.btn-success{
    background: {{$g_setting->theme_color}} !important;
    border:none;
}

/* ================= BOOTSTRAP NAV FIX ================= */
.navbar-nav{
    float:none !important;
    display:flex !important;
}

/* ================= CONTENT OFFSET ================= */
/*#main-content{
    padding-top:60px;
}*/
.modal {
    top: 125px !important;
    
    }
    
    
   /* ===== Rate Page Tabs ===== */

.rate-tabs {
    background: #ffffff;
    border-radius: 10px;
    padding: 5px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.rate-tabs-menu {
    list-style: none;
    display: flex;
    padding: 0;
    margin: 0;
}

.rate-tabs-menu li {
    padding: 12px 25px;
    cursor: pointer;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
    color: #555;
}

/*.rate-tabs-menu li:hover {
    background: #f1f3f7;
}*/

.rate-tabs-menu li.active {
    background: {{$g_setting->theme_color}};
    color: #fff;
}
/* ===== WHATSAPP FLOAT BUTTON ===== */
.whatsapp-float{
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    background: #25D366;
    color: #fff !important;
    border-radius: 50%;
    text-align: center;
    font-size: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    z-index: 99999; /* very high to stay above everything */
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.whatsapp-float:hover{
    background: #1ebe5d;
    transform: scale(1.1);
}
 /* ===== CUT-OFF MARQUEE ===== */
.cutoff-marquee{
    position: fixed;   /* CHANGE from sticky → fixed */
    top: 60px;         /* BELOW first marquee */
    left: 0;
    width: 100%;
    z-index: 1049;
    background: #fff3cd;
    color: #000;
    font-size: 13px;
    font-weight: 500;
    padding: 8px 0;
    overflow: hidden;
    white-space: nowrap;
    border: 1px solid #ffeeba;
}
.cutoff-marquee-content{
    display: inline-block;
    padding-left: 100%;
    animation: cutoff-scroll 30s linear infinite;
}

@keyframes cutoff-scroll{
    0% { transform: translateX(0); }
    100% { transform: translateX(-100%); }
}

/* ===== TRACKING FLOAT BUTTON ===== */
.track-float{
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 60px;
    height: 60px;
    background: {{$g_setting->theme_color}};
    color: #fff !important;
    border-radius: 50%;
    text-align: center;
    font-size: 24px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
    animation: pulse-blue 2s infinite;
}

@keyframes pulse-blue {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(52, 152, 219, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(52, 152, 219, 0); }
}

.track-float:hover{
    background: {{$g_setting->theme_color}};
    transform: scale(1.1);
    animation: none;
}

.track-popup {
    position: fixed;
    bottom: 160px;
    right: 20px;
    width: 320px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.25);
    z-index: 99998;
    display: none;
    overflow: hidden;
    animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px) scale(0.9); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.track-popup-header {
    background: {{$g_setting->theme_color}};
    color: #fff;
    padding: 18px;
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 16px;
}

.track-popup-body {
    padding: 25px;
}

.track-popup-body .form-control {
    border-radius: 25px;
    padding: 12px 20px;
    margin-bottom: 15px;
    border: 1px solid #eee;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
}

.track-popup-body .form-control:focus {
    border-color: {{$g_setting->theme_color}};
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.track-popup-body .btn-track {
    width: 100%;
    border-radius: 25px;
    background: {{$g_setting->theme_color}};
    color: #fff;
    border: none;
    padding: 12px;
    font-weight: bold;
    font-size: 15px;
    transition: all 0.3s;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.track-popup-body .btn-track:hover {
    filter: brightness(1.1);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}
.main-navbar{
    top: 60px;
}

#wrapper{
    padding-top: 60px;
}

/* WHEN SECOND MARQUEE EXISTS */
.has-second-marquee .main-navbar{
    top: 100px;
}

.has-second-marquee #wrapper{
    padding-top: 100px;
}
</style>
<body class="theme-blue {{ $broadcast ? 'has-second-marquee' : '' }}">
    <div class="page-loader-wrapper">
        <div class="loader">
            <?php
            if(isset($general_setting)){ ?>
            <div class="m-t-30"><img src="{{ asset('public/uploads/' . $general_setting->preloader_photo) }}"
                    width="48" height="48" alt="{{$user_data->name}}"></div>
            <?php }else{ ?>
             <div class="m-t-30"><img src="{{ asset('public/uploads/preload.gif') }}"
                    width="48" height="48" alt=""></div>   
            <?php } ?>
            <p>Please wait...</p>
        </div>
    </div>

    <div id="loader" class="lds-dual-ring hidden overlay"></div>
    <div id="wrapper">
        
         <!-- ===== TOP OFFER MARQUEE (ADD HERE) ===== -->
    <!--<div class="fixed-marquee fw-bold">-->
    <!--    <div class="marquee-wrapper">-->
    <!--        <span class="marquee-content">-->
    <!--            🚚 Recharge Your Wallet for <strong >₹1000</strong> & Get <strong>₹1600</strong> Bonus 🎁 |-->
    <!--            Use Code: <strong>HYLO600</strong> | Limited Time Offer ⏳-->
    <!--        </span>-->
    <!--        <span class="marquee-content">-->
    <!--            🚚 Recharge Your Wallet for <strong>₹1000</strong> & Get <strong>₹1600</strong> Bonus 🎁 |-->
    <!--            Use Code: <strong>HYLO600</strong> | Limited Time Offer ⏳-->
    <!--        </span>-->
    <!--    </div>-->
    <!--</div>-->
    
    <div class="fixed-marquee fw-bold">
        <div class="marquee-wrapper">

            <span class="marquee-content">
                🚚 <strong>First Recharge Offer!</strong> 
                ₹1000 → <strong>₹1200</strong> (Code: <strong>HYLO200</strong>) | 
                ₹2000 → <strong>₹2500</strong> (Code: <strong>HYLO500</strong>) 🎁 | 
                Valid for <strong>New Users – First Recharge Only</strong> | 
                Limited Time ⏳
            </span>

            <span class="marquee-content">
                🚚 <strong>First Recharge Offer!</strong> 
                ₹1000 → <strong>₹1200</strong> (Code: <strong>HYLO200</strong>) | 
                ₹2000 → <strong>₹2500</strong> (Code: <strong>HYLO500</strong>) 🎁 | 
                Valid for <strong>New Users – First Recharge Only</strong> | 
                Limited Time ⏳
            </span>

        </div>
    </div>
    <?php if($broadcast) { ?>
        <div class="cutoff-marquee">
            <div class="cutoff-marquee-content">
                ⚠️ Weight Updated! Review Now & Raise Disputes Within TAT to Avoid Extra Charges
            </div>
        </div>
    <?php } ?>
    <!-- ===== END MARQUEE ===== -->
    
      
      <nav class="navbar main-navbar">
    <div class="navbar-flex">

        <!-- LEFT LOGO -->
        <div class="navbar-left">
            <a href="https://hyloship.com/admin/dashboard">
                <img src="{{ asset('public/uploads/'.$general_setting->logo) }}" class="company-logo">
            </a>
        </div>

        <!-- CENTER MENU -->
        <div class="navbar-center">
            <ul class="top-menu">

                 <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>

                        {{-- Orders --}}
                        <li class="dropdown {{ request()->routeIs(
                            'admin.order.all',
                            'admin.order.index',
                            'admin.order.return',
                            'admin.bulkorder.create'
                        ) ? 'active' : '' }}">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                Orders <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('admin.order.all') }}">All Orders</a></li>
                                <li><a href="{{ route('admin.order.index') }}">New Orders</a></li>
                                <li><a href="{{ route('admin.order.return') }}">RTO Orders</a></li>
                                <li><a href="{{ route('admin.bulkorder.create') }}">Bulk Order Import</a></li>
                                <li><a href="{{ route('admin.ndr.ndr') }}">NDR</a></li>
                            </ul>
                        </li>

                       {{-- Operations --}}
                <li class="dropdown {{ request()->routeIs(
                        'admin.order.shipped_order',
                        'admin.order.manifest',
                        'admin.payment.lostshipments'
                    ) ? 'active' : '' }}">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        Operations <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu">
                        <li><a href="{{ route('admin.order.shipped_order') }}">Shipped</a></li>
                        <li><a href="{{ route('admin.order.manifest') }}">Manifest</a></li>

                        {{-- ✅ Lost Shipments added --}}
                        <li>
                            <a href="{{ route('admin.payment.lostshipments') }}">
                                Lost Shipments
                            </a>
                        </li>
                    </ul>
                </li>

                        <!--<li class="{{ request()->routeIs('admin.ndr.ndr') ? 'active' : '' }}">-->
                        <!--    <a href="{{ route('admin.ndr.ndr') }}">NDR</a>-->
                        <!--</li>-->
                    {{-- Payments --}}
                    <li class="dropdown {{ request()->routeIs(
                            'admin.rate',
                            'admin.wallet_transaction',
                            'admin.invoices',
                            'admin.credit_notes',
                            'admin.order.remlist',
                            'admin.payment.walletreport'
                        ) ? 'active' : '' }}">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            Payments <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a href="{{ route('admin.order.remlist') }}">COD Remittance</a></li>
                            <li><a href="{{ route('admin.rate') }}">Rate Card</a></li>
                            <li><a href="{{ route('admin.wallet_transaction') }}">Shipping Charges</a></li>
                            <li><a href="{{ route('admin.weight') }}">Weight Reconciliation</a></li>
                            <li><a href="{{ route('admin.payment.walletreport') }}">Wallet Recharge</a></li>

                        </ul>
                    </li>



                        <li class="{{ request()->routeIs('admin.integration.channel') ? 'active' : '' }}">
                            <a href="{{ route('admin.integration.channel') }}">Integration</a>
                        </li>

                        <!--<li class="{{ request()->routeIs('admin.weight') ? 'active' : '' }}">-->
                        <!--    <a href="{{ route('admin.weight') }}">Weight Reconciliation</a>-->
                        <!--</li>-->
                        
                  


                        {{-- Courier --}}
                        <li class="dropdown {{ request()->routeIs(
                            'admin.integration.manage_courier',
                            'admin.integration.courier_serviceable',
                            'admin.integration.courier_priority'
                        ) ? 'active' : '' }}">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                Courier <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                @if(Auth::guard('admin')->user()->role_id == '1')
                                    <li><a href="{{ route('admin.integration.manage_courier') }}">Manage Courier</a></li>
                                    <li><a href="{{ route('admin.integration.courier_serviceable') }}">Courier Serviceable</a></li>
                                @endif
                                <li><a href="{{ route('admin.integration.courier_priority') }}">Courier Priority</a></li>
                            </ul>
                        </li>

                        <!--@if(Auth::guard('admin')->user()->role_id == '1')-->
                        <!--<li class="{{ request()->routeIs('admin.integration.pincode') ? 'active' : '' }}">-->
                        <!--    <a href="{{ route('admin.integration.pincode') }}">Pincodes</a>-->
                        <!--</li>-->
                        <!--@endif-->
                        

                        <li class="{{ request()->routeIs('admin.warehouse.list') ? 'active' : '' }}">
                            <a href="{{ route('admin.warehouse.list') }}">Warehouse</a>
                        </li>

                        <!--<li class="{{ request()->routeIs('admin.kyc') ? 'active' : '' }}">-->
                        <!--    <a href="{{ route('admin.kyc') }}">KYC</a>-->
                        <!--</li>-->

                        <!--@if(in_array(Auth::guard('admin')->user()->role_id, ['1','2']))-->
                        <!--<li class="{{ request()->routeIs('admin.role.user') ? 'active' : '' }}">-->
                        <!--    <a href="{{ route('admin.role.user') }}">Users</a>-->
                        <!--</li>-->
                        <!--@endif-->
                        
                       

                        {{-- Reports --}}
                        <li class="dropdown {{ request()->routeIs(
                            'admin.reports.mis',
                            'admin.reports.requestedreport',
                            'admin.order.shipment_report'
                        ) ? 'active' : '' }}">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                Reports <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('admin.reports.mis') }}">MIS Report</a></li>
                                <li><a href="{{ route('admin.reports.requestedreport') }}">Requested Report</a></li>
                                <li><a href="{{ route('admin.order.shipment_report') }}">Shipment Reports</a></li>
                            </ul>
                        </li>
                        
                        
                       

            </ul>
        </div>

        <!-- RIGHT SIDE -->
        <div class="navbar-right">

    <a href="{{ route('admin.settings.ticket') }}" class="user-btn">
        <i class="fa fa-ticket"></i> Ticket
    </a>

    <!-- WALLET CLICKABLE -->
    <a href="{{route('admin.payment.wallet')}}" class="wallet-pill">
        ₹ {{ number_format($user_data->wallet_blc,2) }}
    </a>

    <!-- USER DROPDOWN -->
    <div class="dropdown">
    <a href="#" class="user-btn" data-toggle="dropdown">
        {{ $user_data->name }} ▾
    </a>

    <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header">
            Seller ID: {{ $user_data->user_code }}
        </li>
        
         <!-- <li><a href="/profile-change">My Profile</a></li> -->
         
        <li><a href="{{ route('admin.kyc') }}">KYC</a></li>
        
        <li class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings') }}">Settings</a>
                        </li>
                        
        

        @if(in_array(Auth::guard('admin')->user()->role_id, ['1','2']))
            <li><a href="{{ route('admin.role.user') }}">Users</a></li>
            <li><a href="{{ route('admin.orders') }}">Order list</a></li>
        @endif

        @if(Auth::guard('admin')->user()->role_id == '1')
            <li><a href="{{ route('admin.integration.pincode') }}">Pincodes</a></li>
        @endif
        
        <!-- <li><a href="/password-change">Password</a></li>
        <li><a href="/photo-change">Photo</a></li> -->

        <li class="divider"></li>
        
        

        

        <li>
            <a href="{{ route('admin.logout') }}">
                Logout
            </a>
        </li>
        
        
    </ul>
</div>


</div>


    </div>
</nav>



        <div id="main-content">
            <div class="container-fluid">
                    @yield('admin_content')
                
            </div>
        </div>
    
    </div>

    @include('admin.includes.scripts-footer')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<a href="https://wa.me/919217856226" 
   class="whatsapp-float" 
   target="_blank">
    <i class="fa fa-whatsapp"></i>
</a>

<!-- Floating AWB Search -->
<a href="javascript:void(0)" class="track-float" id="trackBtn" title="Track AWB" onclick="$('#trackPopup').fadeToggle(300)">
    <i class="fa fa-search"></i>
</a>

<div class="track-popup" id="trackPopup">
    <div class="track-popup-header">
        <span>Track Your AWB</span>
        <i class="fa fa-times" style="cursor:pointer" id="closeTrack" onclick="$('#trackPopup').fadeOut(300)"></i>
    </div>
    <div class="track-popup-body">
        <input type="text" id="awb_number" class="form-control" placeholder="Enter AWB Number">
        <button class="btn-track" id="performTrack">Track Now</button>
    </div>
</div>

<script>
$(document).ready(function() {
    $(document).on('click', '#performTrack', function() {
        var awb = $('#awb_number').val().trim();
        if (awb !== '') {
            $.ajax({
                url: "{{ route('admin.tracking.check') }}",
                type: 'GET',
                data: { awb: awb },
                beforeSend: function() {
                    $('#performTrack').prop('disabled', true).text('Checking...');
                },
                success: function(response) {
                    if (response.success) {
                        var url = "{{ route('admin.tracking', ['awb' => ':awb']) }}";
                        url = url.replace(':awb', awb);
                        window.location.href = url;
                    } else {
                        toastr.error('AWB not found! Please check again.');
                        $('#performTrack').prop('disabled', false).text('Track Now');
                    }
                },
                error: function() {
                    toastr.error('Something went wrong. Please try again.');
                    $('#performTrack').prop('disabled', false).text('Track Now');
                }
            });
        } else {
            toastr.warning('Please enter an AWB number');
        }
    });

    $(document).on('keypress', '#awb_number', function(e) {
        if(e.which == 13) {
            $('#performTrack').click();
        }
    });

    $(document).click(function(e) {
        if (!$(e.target).closest('#trackPopup, #trackBtn').length) {
            $('#trackPopup').fadeOut(300);
        }
    });
});
</script>
</body>


<script>
     $(document).ready(function() {
       
        $('.sorttable').DataTable(
        {
            paging: false,
            dom: 'Bfrtip',
            buttons: [
                {
                extend: 'csv',
                text: 'Export CSV',
                filename: function() {
                    // Get the table name
                    var tableName = $('.sorttable').attr('name');
                    return tableName;
                },
                exportOptions: {
                    columns: ':not([data-field="hideexport"])'
                }
                },
            ]
        });
        $('.sorttableexcel').DataTable(
        {
            pageLength: 100,  // Set the default page length to 10
            dom: 'Bfrtip',
            buttons: [
                {
                extend: 'excel',
                text: 'Export excel',
                filename: function() {
                    // Get the table name
                    var tableName = $('.sorttable').attr('name');
                    return tableName;
                },
                exportOptions: {
                    columns: ':not([data-field="hideexport"])'
                }
                },
            ]
        });
        $('.sorttableexceldate').DataTable(
        {

            pageLength: 250,  // Set the default page length to 10
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: 'Export excel',
                    filename: function() {
                        // Get the table name
                        var tableName = $('.sorttableexceldate').attr('name');
                        return tableName;
                    },
                    exportOptions: {
                        columns: ':not([data-field="hideexport"])',   
                        format: {
                            body: function(data, row, column, node) {
                                // If the column has the date-column class, replace data with 'ritesh'
                                if ($(node).hasClass('date-column')) {
                                    var datedata = $(data).text();
                                    datedata = datedata.replace(/^\s+|\s+$/gm,'');
                                    if(datedata != ''){
                                    console.log(datedata);
                                    myArray = datedata.split(" ");
                                    mon  = myArray[1].replace(',','');
                                    word = myArray[2]+'-'+parseMonth(mon)+'-'+myArray[0];
                                    console.log(word);
                                    return word;
                                    }else{
                                        return '';
                                    }
                                }
                                if ($(node).hasClass('anchor-column')) {
                                    return $(data).text();
                                }
                                if ($(node).hasClass('special-column')) {
                                    var datedata = $(data).text();
                                    datedata = datedata.replace(/^\s+|\s+$/gm,'');
                                    if(datedata != ''){
                                        return datedata;
                                    }else{
                                        return '';
                                    }
                                }
                                // For other columns, return the original data
                                // return data;
                                  return $(node).text().replace(/\s+/g, ' ').trim();
                            }
                        }
                    }
                }
            ]
        });
        $('.sorttableexceldatesortsecond').DataTable(
        {
            pageLength: 100,  // Set the default page length to 10
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: 'Export excel',
                    filename: function() {
                        // Get the table name
                        var tableName = $('.sorttableexceldatesortsecond').attr('name');
                        return tableName;
                    },
                    exportOptions: {
                        columns: ':not([data-field="hideexport"])',   
                        format: {
                            body: function(data, row, column, node) {
                                // If the column has the date-column class, replace data with 'ritesh'
                                if ($(node).hasClass('date-column')) {
                                    var datedata = $(data).text();
                                    datedata = datedata.replace(/^\s+|\s+$/gm,'');
                                    if(datedata != ''){
                                    console.log(datedata);
                                    myArray = datedata.split(" ");
                                    mon  = myArray[1].replace(',','');
                                    word = myArray[2]+'-'+parseMonth(mon)+'-'+myArray[0];
                                    console.log(word);
                                    return word;
                                    }else{
                                        return '';
                                    }
                                }
                                if ($(node).hasClass('anchor-column')) {
                                    return $(data).text();
                                }
                                if ($(node).hasClass('special-column')) {
                                    var datedata = $(data).text();
                                    datedata = datedata.replace(/^\s+|\s+$/gm,'');
                                    if(datedata != ''){
                                        return datedata;
                                    }else{
                                        return '';
                                    }
                                }
                                // For other columns, return the original data
                               return $(node).text().replace(/\s+/g, ' ').trim();
                            }
                        }
                    }
                }
            ]
        });
         $('.sorttableexceldatesortsecond').DataTable().order([1, 'desc']).draw();
              
    });
      // Function to parse month abbreviation to a number
        function parseMonth(month) {
            var months = {
                Jan: '01',
                Feb: '02',
                Mar: '03',
                Apr: '04',
                May: '05',
                Jun: '06',
                Jul: '07',
                Aug: '08',
                Sep: '09',
                Oct: '10',
                Nov: '11',
                Dec: '12'
            };
            return months[month];
        }
        function broadcastclose(broadcast_id){
            $.get({
                url: "{{ route('admin.broadcast.hideuser') }}",
                data: { 
                    b_id: broadcast_id
                   
                },
                beforeSend: function() {
                    $('#loader').removeClass('hidden')
                },
                success: function(data) {
                    $('.broadcast-conatiner').animate({
                    opacity: 0
                    }, 1000, function() {
                        $(this).hide(); // Ensure the element is hidden after the animation
                    });
                },
                complete: function(){
                    $('#loader').addClass('hidden')
                },
            });
            
        }
</script>
<script>
    $(document).ready(function() {

        $(document).on('input','.maxlenght',function(){
            let maxLength = $(this).attr('max');
            var value = $(this).val();
            if (value.length > maxLength) {
                $(this).val(value.slice(0, maxLength));
            }
        });


        $('.toggle-icon').click(function() {
            let i = $(this).closest('.card').find('i');
            if (i.attr("class") == 'fa fa-minus') {
                i.removeClass('fa-minus').addClass('fa-plus');
            } else {
                i.removeClass('fa-plus').addClass('fa-minus');
            }
            $(this).closest('.card').find('.card-body').slideToggle();
        });

        //tab Script
        var tabsNewAnim = $('#navbar-animmenu');
        var selectorNewAnim = $('#navbar-animmenu').find('li').length;
        var activeItemNewAnim = tabsNewAnim.find('.active');
        var activeWidthNewAnimWidth = activeItemNewAnim.innerWidth();
        var itemPosNewAnimLeft = activeItemNewAnim.position();
        $("#navbar-animmenu").on("click", "li", function(e) {
            $('#navbar-animmenu ul li').removeClass("active");
            $(this).addClass('active');
            var get = $(this).find('a').data('id');
            $('.card').addClass('hide');
            $('.' + get).removeClass('hide');
            var activeWidthNewAnimWidth = $(this).innerWidth();
            var itemPosNewAnimLeft = $(this).position();
            $(".hori-selector").css({
                "left": itemPosNewAnimLeft.left + "px",
                "width": activeWidthNewAnimWidth + "px"
            });
        });
    });


    
    function image(name,id) {
        document.getElementById(name +'_image_'+ id).click();
    }

    function preview_Image(name,id) {
        const input = document.getElementById(name +'_image_'+ id);
        const imagePreview = document.getElementById(name +'_image_preview_'+ id);
        const imageContainer = document.getElementById(name +'_image_container_'+ id);
        const allowedExtensions = ['jpeg', 'jpg', 'png', 'gif'];
        const maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileExtension = file.name.split('.').pop().toLowerCase();

            if (allowedExtensions.includes(fileExtension) && file.size <= maxFileSize) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
                imageContainer.style.display = 'block';
            } else {
                toastr.error('Invalid file type or file size exceeds 2MB');
                input.value = '';
                imageContainer.style.display = 'block';
            }
        }
    }

    var numberInputs = document.querySelectorAll('input[type="number"]');
    numberInputs.forEach(function(input) {
      input.addEventListener('change', function() {
        if (parseInt(input.value) < 0) {
          input.value = 0;
        }
      });

      input.addEventListener('wheel', function(event) {
                if (document.activeElement === this) {
                    event.preventDefault();
                }
            });
    });

    document.addEventListener('DOMContentLoaded', function () {
            var phoneInputs = document.querySelectorAll('.phone-number');
            phoneInputs.forEach(function (input) {
                input.addEventListener('input', function () {
                    // Remove non-numeric characters
                    var phoneNumber = this.value.replace(/\D/g, '');
                    if (phoneNumber.length > 10) {
                        this.value = phoneNumber.slice(0, 10);
                    }
                });
            });
        });

    

</script>
</script>
</html>
