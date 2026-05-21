<?php $__env->startSection('admin_content'); ?>
<?php
$session = Auth::guard('admin')->user();
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/public/admin/semantic.min.js"></script>
<link rel="stylesheet" href="/public/admin/semantic.min.css">



<!-- Main body part  -->
<div class="container-fluid">
    <!-- Page header section  -->
    
    <div class="row clearfix">
        
        <div class="col-xl-12">


        <form id="myForm" action="" method="POST">
            <?php echo csrf_field(); ?>
            <div class="card mt-30 new_orders">
                <div class="header">
                    <h2>RTO Orders<small>
                </div>
                <div class="body row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sorttable table-striped table-hover" id="sorttable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        
                                        <th>SL</th>
                                        <th>Seller</th>
                                        <!-- <th>Channel</th> -->
                                        <th>Order Number</th>
                                        <th>AWB</th>
                                        <th>Courier</th>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Payment</th>
                                        <th>Dim. & Wt. <a title="Default Settings" data-toggle="popover"
                                                data-placement="bottom" data-trigger="hover"
                                                data-content="L: 10cm , B: 10cm , H: 10cm , Weight : 50gm "><span
                                                    class="fa fa-info-circle"></span></a>
                                        </th>
                                        <th>Status</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $order; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                    

                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($row->user_id); ?></td>
                                        <!-- <td><img src="<?php echo e(asset('public/favicon.svg')); ?>" style="width:70px"
                                                alt="Channel Logo"></td> -->
                                        <td class="text-center"> <a
                                                href="<?php echo e(route('admin.order.detail',$row->id)); ?>"><?php echo e($row->order_id); ?></a>
                                        </td>
                                        <td><?php echo e($row->tracking_info); ?></td>
                                        <td class="text-center"><?php echo e(@$couriers[$row->ship_courier_id]['name']); ?></td>
                                        <td><span class="fa fa-calendar"></span>&nbsp;
                                            <?php echo e(\Carbon\Carbon::parse($row->created_at)->format('d M, Y')); ?><br>
                                            <span class="fa fa-clock-o"></span>&nbsp;
                                            <?php echo e(\Carbon\Carbon::parse($row->created_at)->format('H:i')); ?>

                                        </td>
                                        <td><?php echo e($row->detail[0]->name); ?></td>
                                        <td> <?php echo e($row->total); ?> .00<br> <?php echo $row->payment_mode; ?></td>
                                        <td><b>Dim :</b> <?php echo e($row->length); ?>x<?php echo e($row->breadth); ?>x<?php echo e($row->height); ?>

                                            cm<br><b>Wt :</b> <?php echo e($row->weight); ?> gm</td>
                                        <td><?php echo $row->status; ?></td>
                                        
                                    </tr>
                             
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php echo e($order->links()); ?>

            </div>

         
        </form>
    </div>
    </div>
</div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.admin_layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\admin\resources\views/admin/order/return.blade.php ENDPATH**/ ?>