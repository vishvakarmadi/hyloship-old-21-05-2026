<?php $attributes = $attributes->exceptProps(['type' => '','size','class','label' => '','required' => '','name' => '','value' => '','id' => '','options' => '','placeholder' => '','store' => '', 'print' => '','index' => '','src' => '']); ?>
<?php foreach (array_filter((['type' => '','size','class','label' => '','required' => '','name' => '','value' => '','id' => '','options' => '','placeholder' => '','store' => '', 'print' => '','index' => '','src' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php if($type=='text' || $type=='number' || $type=='email' || $type=='file' || $type=='password' || $type=='date' || $type=='time' || $type=='color' || $type=='hidden' || $type=='search' || $type=='url' || $type=='tel' || $type=='datetime-local' || $type=='month' || $type=='week' || $type=='datetime' || $type=='datetime-local' || $type=='range'): ?>

<div class="form-group  <?php echo e(@$size ?: 'col-md-4'); ?> ">
    <label><?php echo e(@$label); ?></label><?php if(@$required): ?><span class="required"> *</span><?php endif; ?>
    <input class="form-control <?php echo e(@$class); ?>" placeholder="<?php echo e(@$placeholder); ?>" type="<?php echo e(@$type); ?>" name="<?php echo e(@$name); ?>" <?php if(@$required): ?> required <?php endif; ?> value="<?php echo e(@$value); ?>" id="<?php echo e(@$id); ?>">
</div>
<?php endif; ?>

<?php if($type == 'tags'): ?>

<div class="form-group <?php echo e(@$size ?? 'col-md-4'); ?>">
    <label><?php echo e(@$label); ?></label><?php if(@$required): ?><span class="required"> *</span><?php endif; ?>
    <input type="text" class="inputTag <?php echo e(@$class); ?>" placeholder="<?php echo e(@$placeholder); ?>" name="<?php echo e(@$name); ?>" value="<?php echo e(@$value); ?>" data-role="tagsinput" <?php if(@$required): ?> required <?php endif; ?>>
</div>
<?php endif; ?>

<?php if($type=='date-range'): ?>
<?php ($date = explode('-',$name)); ?>
<?php ($v = explode('/',$value)); ?>
<?php ($clas = explode('-',@$class)); ?>

<div class="form-group <?php echo e(@$size ?? 'col-md-4'); ?>">
    <label><?php echo e(@$label); ?></label><?php if(@$required): ?><span class="required"> *</span><?php endif; ?>
    <div class="row">
        <?php $__currentLoopData = $date; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6">
            <input class="form-control <?php echo e(@$clas[$index]); ?>" type="date" name="<?php echo e(@$row); ?>" <?php if(@$required): ?> required <?php endif; ?> value="<?php echo e(@$v[$index]); ?>" id="<?php echo e($id); ?>_<?php echo e($loop->iteration); ?>">
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>

<?php if($type == 'textarea'): ?>

<div class="form-group <?php echo e(@$size ?? 'col-md-4'); ?>">
    <label><?php echo e(@$label); ?></label><?php if(@$required): ?><span class="required"> *</span><?php endif; ?>
    <textarea class="form-control <?php echo e(@$class); ?>" placeholder="<?php echo e(@$placeholder); ?>" name="<?php echo e(@$name); ?>" <?php if(@$required): ?> required <?php endif; ?> id="<?php echo e(@$id); ?>"><?php echo e(@$value); ?></textarea>
</div>
<?php endif; ?>


<?php if($type == 'radio'): ?>

<div class="form-group <?php echo e(@$size ?? 'col-md-4'); ?>">
    <label for="text-input1"><?php echo e(@$label); ?></label><br>
    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php ($radio = explode(':', $option)); ?>
    <label class="fancy-radio custom-color-green"><input class="<?php echo e(@$class); ?>" name="<?php echo e(@$name); ?>"
            value="<?php echo e(@$radio[1]); ?>" type="<?php echo e(@$type); ?>" <?php if(@$value == $radio[1] || $key == 0): ?> checked <?php endif; ?> id="<?php echo e(@$id); ?>_<?php echo e(@$key); ?>"><span><i></i><?php echo e($radio[0]); ?></span></label>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>


<?php if($type == 'checkbox'): ?>

<div class="form-group clearfix" style="align-items: unset;display: flex;">
    <label class="element-left" style="padding: 34px 0px 0px 27px;">
        <input type="<?php echo e(@$type); ?>" name="<?php echo e(@$name); ?>" class="<?php echo e(@$class); ?>" id="<?php echo e(@$id); ?>" value="<?php echo e(@$store); ?>" <?php if(@$value == @$store): ?> checked <?php endif; ?>>
        <span for="<?php echo e(@$name); ?>"><?php echo e(@$label); ?></span>
    </label>								
</div>
<?php endif; ?>


<?php if($type == 'select'): ?>

<div class="form-group <?php echo e(@$size ?? 'col-md-4'); ?>">
    <label class="form-control-label"><?php echo e(@$label); ?></label><?php if(@$required): ?><span class="required"> *</span><?php endif; ?>
    <select class="form-control <?php echo e(@$class); ?>" name="<?php echo e(@$name); ?>" id="<?php echo e(@$id); ?>" <?php if(@$required): ?> required <?php endif; ?>>
        <option value="">Select <?php echo e(@$label); ?></option>
        <?php $__currentLoopData = @$options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($option[$store]); ?>" <?php if(@$option[$store] == @$value): ?> Selected <?php endif; ?>><?php echo e($option[$print]); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>
<?php endif; ?>


<?php if($type == 'submit' || $type == 'reset'): ?>

<div class="form-group">
    <input type="<?php echo e(@$type); ?>" class="form-control btn <?php echo e(@$class); ?>" id="<?php echo e(@$id); ?>" value="<?php echo e(@$value); ?>">
</div>
<?php endif; ?>

<?php if($type == 'img_preview'): ?>

<div class="form-group <?php echo e(@$size ?? 'col-md-4'); ?>">
    <div style="max-width:200px;">
        <label for="<?php echo e(@$name); ?>_image"><?php echo e(@$label); ?></label>
        <div id="<?php echo e(@$name); ?>_image_container_<?php echo e($index); ?>">
            <img id="<?php echo e(@$name); ?>_image_preview_<?php echo e($index); ?>" class="preview" src="<?php echo e($src); ?>" alt="" onclick="image('<?php echo e(@$name); ?>',<?php echo e(@$index); ?>)">
        </div>
        <input type="file" id="<?php echo e(@$name); ?>_image_<?php echo e($index); ?>" name="<?php echo e(@$name); ?>" style="display: none;" accept="image/*" onchange="preview_Image('<?php echo e(@$name); ?>',<?php echo e(@$index); ?>)" <?php if(@$required): ?> required <?php endif; ?>>
    </div>
</div>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\laravel\admin\resources\views/components/field.blade.php ENDPATH**/ ?>