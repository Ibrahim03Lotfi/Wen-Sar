<?php $__env->startSection('title', __('Ads Management')); ?>
<?php $__env->startSection('page-title', __('Ads Management')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800"><?php echo e(__('Homepage Ads Carousel')); ?></h3>
                <p class="text-sm text-gray-500 mt-1"><?php echo e(__('You can upload up to 6 ads. They appear automatically on the homepage slider.')); ?></p>
            </div>
            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold <?php echo e($ads->count() >= 6 ? 'bg-red-100 text-red-700' : 'bg-brand-green/10 text-brand-green'); ?>">
                <?php echo e(__(':count / 6 ads', ['count' => $ads->count()])); ?>

            </span>
        </div>
    </div>

    <?php if($ads->count() < 6): ?>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h4 class="font-bold text-gray-800 mb-4"><?php echo e(__('Add New Ad')); ?></h4>
        <form action="<?php echo e(route('manager.ads.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Ad Image')); ?></label>
                <input type="file" name="image" accept="image/*" required class="w-full border border-gray-200 rounded-lg py-2.5 px-3 text-sm focus:ring-brand-green focus:border-brand-green">
                <p class="text-xs text-gray-500 mt-2"><?php echo e(__('Recommended size: 1600 x 600. Max file size: 5MB.')); ?></p>
            </div>
            <button type="submit" class="bg-brand-green text-white font-bold py-2.5 px-6 rounded-lg hover:opacity-90 transition-all">
                <?php echo e(__('Upload Ad')); ?>

            </button>
        </form>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        <?php $__empty_1 = true; $__currentLoopData = $ads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <img src="<?php echo e(asset('storage/' . $ad->image_path)); ?>" alt="Ad <?php echo e($ad->id); ?>" class="w-full h-44 object-cover">
            <div class="p-4 space-y-3">
                <div class="text-xs text-gray-500"><?php echo e(__('Ad #:id', ['id' => $ad->id])); ?></div>

                <form action="<?php echo e(route('manager.ads.update', $ad)); ?>" method="POST" enctype="multipart/form-data" class="space-y-3">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1"><?php echo e(__('Replace Image')); ?></label>
                        <input type="file" name="image" accept="image/*" required class="w-full border border-gray-200 rounded-lg py-2 px-3 text-xs focus:ring-brand-green focus:border-brand-green">
                    </div>
                    <button type="submit" class="w-full bg-blue-50 text-blue-700 font-bold py-2 rounded-lg hover:bg-blue-100 transition-all text-sm">
                        <?php echo e(__('Update Ad')); ?>

                    </button>
                </form>

                <form action="<?php echo e(route('manager.ads.destroy', $ad)); ?>" method="POST" onsubmit="return confirm('<?php echo e(__('Are you sure?')); ?>');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="w-full bg-red-50 text-red-700 font-bold py-2 rounded-lg hover:bg-red-100 transition-all text-sm">
                        <?php echo e(__('Delete Ad')); ?>

                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full bg-white rounded-xl shadow-sm p-10 border border-dashed border-gray-300 text-center">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553a1 1 0 010 1.414L15 16m-6-6L4.447 14.586a1 1 0 000 1.414L9 20m5-16v16"/>
                </svg>
            </div>
            <h4 class="font-bold text-gray-700 mb-1"><?php echo e(__('No ads yet')); ?></h4>
            <p class="text-sm text-gray-500"><?php echo e(__('Add your first ad to appear in the homepage slider.')); ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views\manager\ads\index.blade.php ENDPATH**/ ?>