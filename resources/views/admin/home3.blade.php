@extends('admin.admin_layouts') @section('admin_content') @php $session = Auth::guard('admin')->user(); @endphp

<style>
    .bg-white-50 {
        background-color: rgba(255, 255, 255, 0.5);
    }

    .text-warning {
        --bs-text-opacity: 1;
        color: rgba(var(--bs-warning-rgb), var(--bs-text-opacity)) !important;
    }

    .top_report .body2 {
        padding: 10px;
    }

    .card {
        margin-bottom: 0px;
    }

    .col-lg-3.col-md-6.col-sm-6,
    .col-lg-6.col-md-6.col-sm-6 {
        padding: 0;
    }

    .card .card-title {
        font-size: 1.25rem;
        line-height: 2rem;
        font-weight: 500;
        letter-spacing: 0.0125em;
        margin-bottom: 0;
    }

    .card .card-subtitle {
        font-size: 0.875rem;
        line-height: 1.375rem;
        font-weight: 500;
        letter-spacing: 0.0071428571em;
        margin-bottom: 0;
        opacity: 0.6;
    }

    .card-subtitle {
        margin-top: calc(-0.5* var(--bs-card-title-spacer-y));
        margin-bottom: 0;
    }

    #navbar-animmenus {
        background: transparent !important;
        float: left;
        overflow: hidden;
        position: relative;
        padding: 5px 0px;
        width: 100%;
        border-radius: 10px;
    }

    #navbar-animmenus ul li a {
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        line-height: 30px;
        display: block;
        padding: 0px 10px;
        transition-duration: 0.6s;
        transition-timing-function: cubic-bezier(0.68, -0.55, 0.265, 1.55);
        position: relative;
    }

    #navbar-animmenus ul {
        padding: 0px;
        margin: 0px;
    }

    .hori-selector {
        display: inline-block;
        position: absolute;
        height: 100%;
        top: 10px;
        left: 0px;
        transition-duration: 0.6s;
        transition-timing-function: cubic-bezier(0.68, -0.55, 0.265, 1.55);
        background-color: #fff;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    #navbar-animmenus li {
        list-style-type: none;
        float: left;
        margin-left: 20px;
        margin-top: 13px;
    }

    .hori-selector .right,
    .hori-selector .left {
        position: absolute;
        width: 25px;
        height: 25px;
        background-color: #fff;
        bottom: 10px;
    }

    #navbar-animmenus>ul>li.active>a {
        color: #fff;
        background-color: transparent;
        transition: all 0.7s;
        border-bottom: 3px solid #fff !important;
    }

    .display-5.text-white {
        /* font-size: 20px; */
        line-height: 2rem;
        font-size: 1.5rem;
    }

    .text-white a {
        color: #fff !important;
        /*text-decoration: underline;*/
    }

    .canvasjs-chart-credit {
        display: none !important;
    }

    .font-size1em {
        font-size: 1.19em !important;
    }

    .me-2 .card-text {
        font-size: 13px;
    }

    .d-flex {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .spinner {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        padding: 20px;
    }

    @media (min-width: 992px) {
        .col-lg-2 {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 19.666667%;
            max-width: 20.666667%;
        }
    }
</style>

<script>
    function myFunction() {
        //    console.log('Starting AJAX request...');
        document.querySelectorAll('.spinner').forEach(function(spinner) {
            spinner.style.display = 'inline-block'; // Show the spinner
        });
        const contentElements = document.querySelectorAll('.content');

        function hideContent() {
            contentElements.forEach((element) => {
                element.style.display = 'none';
            });
        }
        hideContent();
        $.ajax({
            url: "{{ route('admin.load') }}",
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('AJAX request successful:', data);

                // Assigning values and calculating percentages
                let totalShipments = data.tshipment || 0;
                let deliveredOrder = data.delivredorder || 0;
                let orderCount = data.orderall ? data.orderall.length : 0;
                let rtoorder = data.rtoorder || 0;
                let revenue = data.revenue || 0;
                let intransit = data.intransit || 0;
                revenue = parseFloat(revenue.toFixed(2));
                //    alert(intransit);
                let deliveredPercentage = (totalShipments !== 0) ? round((deliveredOrder / totalShipments) * 100, 2) : 0;
                let transitPercentage = (totalShipments !== 0) ? round((intransit / totalShipments) * 100, 2) : 0;
                let rtopercentage = (totalShipments !== 0) ? round((rtoorder / totalShipments) * 100, 2) : 0;
                let percentage = (orderCount > 0) ? round((totalShipments / orderCount) * 100, 2) : 0;
                let percentage2 = (orderCount > 0) ? round((deliveredOrder / orderCount) * 100, 2) : 0;
                let percentage3 = (orderCount > 0) ? round((rtoorder / orderCount) * 100, 2) : 0;
                let percentage4 = (orderCount > 0) ? round((intransit / orderCount) * 100, 2) : 0;
                let avgrevenue = (deliveredOrder !== 0) ? round((revenue / deliveredOrder), 2) : 0;

                // Updating HTML elements with the calculated values
                $('#tshipment').text(totalShipments);
                $('#delshipment').text(deliveredOrder);
                $('#trasitshipment').text(intransit);
                $('#delshipmentperce').text(deliveredPercentage + '%');
                $('#trasitshipmentperce').text(transitPercentage + '%');
                $('#rto').text(rtoorder);
                $('#revenue').text(revenue);
                $('#percentage').text(percentage);
                $('#percentage2').text(percentage2);
                $('#rtopercent').text(rtopercentage + '%');
                $('#percentage3').text(percentage3);
                $('#percentage4').text(percentage4);
                $('#avgrevenue').text(avgrevenue);

                $('#courier-status-table').empty();
                for (let key in data.statusdata) {
                    if (data.statusdata.hasOwnProperty(key)) {
                        let sd = data.statusdata[key];
                        //    console.log(key);
                        let total = sd.New + sd.Shipped + sd.Delivered + sd.RTO +
                            sd["RTO Delivered"] + sd.NDR + sd["Pickup Pending"] +
                            sd["RTO In Transit"] + sd["In Transit"] +
                            sd["Out for Delivery"] + sd.Lost + sd.Damaged;

                        let rto = sd.RTO + sd["RTO Delivered"] + sd["RTO In Transit"];

                        let intransit = sd.NDR + sd["In Transit"] + sd["RTO In Transit"] +
                            sd.Damaged + sd.Lost + sd["Out for Delivery"];
                        //            console.log(key); 
                        // Update spans with calculated values
                        $('#courier-status-table').append(`
                <tr>
                    <td>${key}</td>
                    <td>${total}</td>
                    <td>${(sd["Pickup Pending"] || 0)} ${total > 0 ? `(${((sd["Pickup Pending"] / total) * 100).toFixed(2)}%)` : ''}</td>
                    <td>${intransit} ${total > 0 ? `(${((intransit / total) * 100).toFixed(2)}%)` : ''}</td>
                    <td>${(sd.Delivered || 0)} ${total > 0 ? `(${((sd.Delivered / total) * 100).toFixed(2)}%)` : ''}</td>
                    <td>${rto} ${total > 0 ? `(${((rto / total) * 100).toFixed(2)}%)` : ''}</td>
                </tr>
            `);
                    }
                }

                let pieDataPoints = [];
                for (let key in data.courierdata) {
                    if (data.courierdata.hasOwnProperty(key)) {
                        if (data.courierdata[key] != 0) {
                            let yValue = data.courierdata[key];
                            let percentage = (totalShipments !== 0) ? Math.round((yValue / totalShipments) * 100, 2) : 0;

                            pieDataPoints.push({
                                value: yValue, // Ensure this matches the series value field
                                category: capitalizeFirstLetter(key.replace(/_/g, ' ')), // Match the category field
                                percentage: percentage // You can store this if needed, but it's not required for series
                            });
                        }
                    }
                }
                let dataPoints = [];

                for (let key in data.zonedaat) {
                    if (data.zonedaat.hasOwnProperty(key)) {
                        if (data.zonedaat[key] != 0) {
                            dataPoints.push({
                                value: data.zonedaat[key], // Use 'value' to match your series configuration
                                category: capitalizeFirstLetter(key.replace(/_/g, ' ')) // Format the category
                            });
                        }
                    }
                }
                am5.ready(function() {

                    var root = am5.Root.new("chartdiv1");

                    root.setThemes([
                        am5themes_Animated.new(root)
                    ]);

                    var chart = root.container.children.push(am5percent.PieChart.new(root, {
                        startAngle: 0,
                        endAngle: 360,
                        layout: root.verticalLayout,
                        innerRadius: am5.percent(50),
                        radius: am5.percent(66)
                     }));

                    var series = chart.series.push(am5percent.PieSeries.new(root, {
                        startAngle: 0,
                        endAngle: 360,
                        valueField: "value",
                        categoryField: "category",
                        alignLabels: false
                    }));

                    // series.states.create("hidden", {
                    // startAngle: 180,
                    // endAngle: 180
                    // });

                    series.slices.template.setAll({
                        strokeWidth: 2,
                        stroke: am5.color(0xffffff),
                        shadowOpacity: 0.1,
                        shadowOffsetX: 2,
                        shadowOffsetY: 2,
                        shadowColor: am5.color(0x000000),
                        cornerRadius: 10
                    });
                    series.slices.template.set(
                        "fillGradient",
                        am5.RadialGradient.new(root, {
                            stops: [
                            { brighten: -0.8 },
                            { brighten: -0.8 },
                            { brighten: -0.5 },
                            { brighten: 0 },
                            { brighten: -0.5 }
                            ]
                        })
                        );
                    series.labels.template.set('text', '{category}\n {value}');
                    series.ticks.template.setAll({
                        forceHidden: false
                    });
                    series.set("colors", am5.ColorSet.new(root, {
                        colors: [
                            am5.color(0x73556E),
                            am5.color(0x9FA1A6),
                            am5.color(0xF2AA6B),
                            am5.color(0xF28F6B),
                            am5.color(0xA95A52),
                            am5.color(0xE35B5D),
                            am5.color(0xFFA446)
                        ]
                    }))

                    // Set data
                    // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
                    series.data.setAll(dataPoints);
                    var legend = chart.children.push(am5.Legend.new(root, {
                        centerX: am5.percent(50),
                        x: am5.percent(50),
                        marginTop: 15,
                        marginBottom: 15,
                    }));
                    legend.markerRectangles.template.adapters.add("fillGradient", function() {
                        return undefined;
                    })
                    legend.data.setAll(series.dataItems);
                    series.appear(1000, 100);

                    // Create root for Bar Chart
                    var root2 = am5.Root.new("chartdiv2");
                    root2.setThemes([am5themes_Animated.new(root2)]);

                    // Create XY Chart
                    var barChart = root2.container.children.push(am5xy.XYChart.new(root2, {

                        paddingLeft: 0,
                        paddingRight: 1
                    }));

                    barChart.set("colors", am5.ColorSet.new(root, {
                        colors: [
                            am5.color(0x73556E),
                            am5.color(0x9FA1A6),
                            am5.color(0xF2AA6B),
                            am5.color(0xF28F6B),
                            am5.color(0xA95A52),
                            am5.color(0xE35B5D),
                            am5.color(0xFFA446)

                        ]
                    }))

                    // Add cursor
                    var cursor = barChart.set("cursor", am5xy.XYCursor.new(root2, {}));
                    cursor.lineY.set("visible", false);
                    cursor.lineX.set("visible", false);

                    // Create axes
                    var xRenderer = am5xy.AxisRendererX.new(root2, {
                        minGridDistance: 30,
                        minorGridEnabled: true
                    });
                    xRenderer.labels.template.setAll({
                        rotation: -90,
                        centerY: am5.p50,
                        centerX: am5.p100,
                        paddingRight: 15
                    });
                    xRenderer.grid.template.setAll({
                        location: 1
                    });

                    var xAxis = barChart.xAxes.push(am5xy.CategoryAxis.new(root2, {
                        categoryField: "category",
                        renderer: xRenderer
                    }));

                    var yRenderer = am5xy.AxisRendererY.new(root2, {
                        strokeOpacity: 0.1
                    });
                    var yAxis = barChart.yAxes.push(am5xy.ValueAxis.new(root2, {
                        renderer: yRenderer
                    }));

                    // Create series for Bar Chart
                    var barSeries = barChart.series.push(am5xy.ColumnSeries.new(root2, {
                        name: "Series 1",
                        xAxis: xAxis,
                        yAxis: yAxis,
                        valueYField: "value",
                        categoryXField: "category",
                        tooltip: am5.Tooltip.new(root2, {
                            labelText: "{categoryX}: {valueY}"
                        })
                    }));

                    barSeries.columns.template.setAll({
                        tooltipY: 0,
                        tooltipText: "{categoryX}: {valueY}",
                        shadowOpacity: .5,
                        shadowOffsetX: 2,
                        shadowOffsetY: 2,
                        shadowBlur: 20,
                        strokeWidth: 2,
                        stroke: am5.color(0xffffff),
                        shadowColor: am5.color(0x000000),
                        cornerRadiusTL: 50,
                        cornerRadiusTR: 50,
                    });
                    barSeries.columns.template.adapters.add("fill", function(fill, target) {
                        return barChart.get("colors").getIndex(barSeries.columns.indexOf(target));
                    });
                    barSeries.columns.template.states.create("hover", {
                        shadowOpacity: 1,
                        shadowBlur: 10,
                        cornerRadiusTL: 10,
                        cornerRadiusTR: 10
                    })
                    xAxis.data.setAll(pieDataPoints);
                    barSeries.data.setAll(pieDataPoints);

                    // Animate the charts
                    barSeries.appear(1000);
                    barChart.appear(1000, 100);
                });

                // Helper function to capitalize the first letter of each word
                function capitalizeFirstLetter(string) {
                    return string.charAt(0).toUpperCase() + string.slice(1);
                }


                document.querySelectorAll('.spinner').forEach(function(spinner) {
                    spinner.style.display = 'none'; // Hide the spinner
                });
                // Show all content again
                contentElements.forEach((element) => {
                    element.style.display = 'block'; // Show the content
                });

            },
            error: function(xhr, status, error) {
                console.error('Error loading dashboard data:', error);
            }
        });
    }

    function round(value, decimals) {
        return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    window.addEventListener('load', myFunction);
</script>
<!-- <script>
    $('form select[name="role_id"]').on('change', function() {
        $(this).closest('form').submit();
    });
</script> -->

<!--<div id="loading-indicator" style="">Loading...</div>-->
<!-- Main body part  -->
<div class="container-fluid">
    <!-- Page header section  -->



    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-12">
<!--                <div id="navbar-animmenus">
                    <ul class="show-dropdown main-navbar">
                        <div class="hori-selector" style="margin-left: 20px;    left: 0px;    width: 106.137px;">
                            <div class="left"></div>
                            <div class="right"></div>
                        </div>
                        <li class="active"><a href="javascript:void(0);" data-id="Overview">Overview</a></li>
                        <li><a href="{{route('admin.dashboard.shipment')}}" data-id="channel">Shipment Status</a></li>
                         <li><a href="{{route('admin.dashboard.top')}}" data-id="top10">Top 10</a></li> 
                         <li><a href="javascript:void(0);" data-id="ndr">NDR</a></li> 
                    </ul>
                </div>-->
                <div class="card top_report card mt-30 Overview">
                    <div class="row" style="margin:0">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6" style="padding: 0px">
                                    <div class="body2">
                                        <div class="card card-raised loader-hidden">
                                            <div class="d-flex justify-content-center align-items-center" style="height: 100%; position: relative;">
                                                <div class="spinner" style="display: none;">
                                                    <img src="{{ asset('public/loader.gif') }}" style="width: 50px;">
                                                </div>
                                            </div>
                                            <div class="card-body px-4 content">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="me-2">
                                                        <div class="display-5 font-size1em">
                                                            <a href="{{ route('admin.order.all', ['exceptnewcancel' => true, 'role_id' => $role_id]) }}" target="_blank">
                                                                <span id="tshipment"></span>
                                                            </a>
                                                        </div>
                                                        <div class="card-text">Total Shipments</div>
                                                    </div>
                                                    <div class="icon-circle bg-white-50 text-warning">
                                                        <i class="fa fa-2x fa-briefcase text-col-yellow"></i>
                                                    </div>
                                                </div>
                                                <div class="card-text">
                                                    <a>
                                                        <div class="d-inline-flex align-items-center">
                                                            <div class="caption fw-500 me-2">
                                                                <span id="percentage">

                                                                </span>
                                                            </div>
                                                            <div class="caption">% of total orders</div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6" style="padding: 0px">
                                    <div class="body2">
                                        <div class="card card-raised loader-hidden">
                                            <div class="d-flex justify-content-center align-items-center" style="height: 100%; position: relative;">
                                                <div class="spinner" style="display: none;">
                                                    <img src="{{ asset('public/loader.gif') }}" style="width: 50px;">
                                                </div>
                                            </div>
                                            <div class="card-body px-4 content">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="me-2">
                                                        <div class="display-5 font-size1em">
                                                            <a href="{{ route('admin.order.all', ['delivered' => true, 'role_id' => $role_id]) }}" target="_blank">
                                                                <span id="delshipment"></span> (
                                                                <span id="delshipmentperce">

                                                                </span>)
                                                            </a>
                                                        </div>
                                                        <div class="card-text">Delivered Shipments</div>
                                                    </div>
                                                    <div class="icon-circle bg-white-50 text-warning">
                                                        <i class="fa fa-2x fa-plane text-col-green"></i>
                                                    </div>
                                                </div>
                                                <div class="card-text">
                                                    <div class="d-inline-flex align-items-center">
                                                        <div class="caption fw-500 me-2">
                                                            <span id="percentage2">

                                                            </span>
                                                        </div>
                                                        <div class="caption">% of total orders</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6" style="padding: 0px">
                                    <div class="body2">
                                        <div class="card card-raised  loader-hidden">
                                            <div class="d-flex justify-content-center align-items-center" style="height: 100%; position: relative;">
                                                <div class="spinner" style="display: none;">
                                                    <img src="{{ asset('public/loader.gif') }}" style="width: 50px;">
                                                </div>
                                            </div>
                                            <div class="card-body px-4 content">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="me-2">
                                                        <div class="display-5 font-size1em">
                                                            <a href="{{ route('admin.order.all', ['intrait' => true, 'role_id' => $role_id]) }}" target="_blank">
                                                                <span id="trasitshipment"></span> (
                                                                <span id="trasitshipmentperce">

                                                                </span>)
                                                            </a>
                                                        </div>
                                                        <div class="card-text">In Transit</div>
                                                    </div>
                                                    <div class="icon-circle bg-white-50 text-warning">
                                                        <i class="fa fa-2x fa-car text-muted"></i>
                                                    </div>
                                                </div>
                                                <div class="card-text">
                                                    <div class="d-inline-flex align-items-center">
                                                        <div class="caption fw-500 me-2">
                                                            <span id="percentage4">

                                                            </span>
                                                        </div>
                                                        <div class="caption">% of total orders</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6" style="padding: 0px">
                                    <div class="body2">
                                        <div class="card card-raised  loader-hidden">
                                            <div class="d-flex justify-content-center align-items-center" style="height: 100%; position: relative;">
                                                <div class="spinner" style="display: none;">
                                                    <img src="{{ asset('public/loader.gif') }}" style="width: 50px;">
                                                </div>
                                            </div>
                                            <div class="card-body px-4 content">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="me-2">
                                                        <div class="display-5 font-size1em">
                                                            <a href="{{ route('admin.order.all', ['rto' => true, 'role_id' => $role_id]) }}" target="_blank">
                                                                <span id="rto"></span> (
                                                                <span id="rtopercent">

                                                                </span>)
                                                            </a>
                                                        </div>
                                                        <div class="card-text">Total RTO</div>
                                                    </div>
                                                    <div class="icon-circle bg-white-50 text-warning"> <i class="fa fa-2x fa-reply text-col-red"></i></div>
                                                </div>
                                                <div class="card-text">
                                                    <div class="d-inline-flex align-items-center">
                                                        <!-- <i class="material-icons icon-xs">arrow_upward</i> -->
                                                        <div class="caption fw-500 me-2">
                                                            <span id="percentage3">

                                                            </span>
                                                        </div>
                                                        <div class="caption">% of total orders</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-6 col-md-6 col-sm-6" style="padding: 0px">
                                    <div class="body2">
                                        <div class="card card-raised  loader-hidden">
                                        <div class="d-flex justify-content-center align-items-center" style="height: 100%; position: relative;">
                                                            <div class="spinner" style="display: none;">
                                                                <img src="{{ asset('public/loader.gif') }}" style="width: 50px;">
                                                            </div>
                                                        </div>
                                            <div class="card-body px-4 content">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="me-2">
                                                        <div class="display-5 font-size1em">
                                                            <a href="{{ route('admin.order.all', ['delivered' => true, 'role_id' => $role_id]) }}" target="_blank" id="revenue">{{$revenue ?? ' '}}</a>
                                                        </div>
                                                        <div class="card-text">Total Revenue (₹)</div>
                                                    </div>
                                                    <div class="icon-circle bg-white-50 text-warning"> <i class="fa fa-2x fa-balance-scale text-col-blue"></i></div>
                                                </div>
                                                <div class="card-text">
                                                    <div class="d-inline-flex align-items-center">
                                                        <div class="caption fw-500 me-2">
                                                            <div class="caption fw-500 me-2">
                                                                <span id="avgrevenue"> </span>
                                                            </div>
                                                        </div>
                                                        <div class="caption"> &nbsp;Avg. Revenue (₹)</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>

                    </div>
                    <div class="row" style="margin:0;">

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="col-12" style="margin-top:10px">
                                <div class="card card-raised h-100" style="border:1px solid">
                                    <div class="card-header bg-primary text-white px-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-4">
                                                <h2 class="card-title text-white mb-0">Courier wise shipment</h2>
                                                <!-- <div class="card-subtitle">Revenue sources</div> -->
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="d-flex h-100 w-100 align-items-center justify-content-center">
                                            <div id="chartdiv2" style="height: 370px; width: 100%;"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="col-12" style="margin-top:10px">
                                <div class="card card-raised h-100" style="border:1px solid">
                                    <div class="card-header bg-primary text-white px-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-4">
                                                <h2 class="card-title text-white mb-0">Zone wise shipment</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="row gx-4">
                                            <div id="chartdiv1" style="height: 370px; width: 100%;"></div>
                                            <!-- <script src="/kimi/admin//js/index_amchart.js"></script> -->
                                            <script src="{{ asset('/admin/assets/js/index_amchart.js') }}"></script>
                                            <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
                                            <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
                                            <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>Courier</th>
                                            <th>Total</th>
                                            <th>Pending Pickup</th>
                                            <th>In Transit</th>
                                            <th>Delivered</th>
                                            <th>RTO</th>
                                        </tr>
                                    </thead>
                                    <tbody id="courier-status-table">

                                        <tr>
                                            <td><span id="tablec_name"></span></td>
                                            <td><span id="tabletotal"></span></td>
                                            <td><span id="tablepending"></span></td>
                                            <td><span id="tableintransit"></span></td>
                                            <td><span id="tabledelivered"></span></td>
                                            <td><span id="tablerto"></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
@endsection