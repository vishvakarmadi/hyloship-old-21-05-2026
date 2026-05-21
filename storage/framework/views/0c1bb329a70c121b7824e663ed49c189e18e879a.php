<?php $attributes = $attributes->exceptProps(['route' => '','type' => '','name' => '','size' =>'']); ?>
<?php foreach (array_filter((['route' => '','type' => '','name' => '','size' =>'']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>


<?php if($type == 'create'): ?>
<a href="<?php echo e($route); ?>" class="btn btn-dark">
    <i class="fa fa-plus"></i> <?php echo e(@$name); ?>

</a>
<?php endif; ?>

<?php if($type == 'import'): ?>
<a href="<?php echo e($route); ?>" class="btn btn-primary">
    <i class="fa fa-upload"></i> <?php echo e(@$name); ?>

</a>
<?php endif; ?>


<?php if($type == 'submit'): ?>
<div class="<?php echo e($size ?? 'col-md-12'); ?>">
    <button type="submit" class="btn btn-success" style="width:100%"><?php echo e(@$name); ?></button>
</div>
<?php endif; ?>

<?php /**PATH C:\xampp\htdocs\laravel\admin\resources\views/components/button.blade.php ENDPATH**/ ?>