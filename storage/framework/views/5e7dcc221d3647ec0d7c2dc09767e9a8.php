<?php $__env->startSection('title', __('Review Business')); ?>
<?php $__env->startSection('page-title', __('Review Business Application')); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <a href="<?php echo e(route('manager.approvals.pending')); ?>" class="text-gray-500 hover:text-brand-green flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <?php echo e(__('Back to Pending')); ?>

    </a>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Business Details -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="h-64 bg-gray-100 relative">
                <?php if($business->logo): ?>
                    <img src="<?php echo e(asset('storage/' . $business->logo)); ?>" class="w-full h-full object-cover">
                <?php elseif($business->images && count($business->images) > 0): ?>
                    <img src="<?php echo e(asset('storage/' . $business->images[0])); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                        <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                <?php endif; ?>
                <div class="absolute top-4 left-4 bg-yellow-500 text-white text-sm font-bold px-4 py-2 rounded-lg">
                    <?php echo e(__('Pending Approval')); ?>

                </div>
            </div>
            
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2"><?php echo e($business->name); ?></h2>
                <p class="text-gray-500"><?php echo e($business->english_name); ?></p>

                <div class="flex flex-wrap gap-2 mt-4 mb-6">
                    <span class="bg-brand-green/10 text-brand-green text-sm font-bold px-3 py-1 rounded-lg"><?php echo e($business->category->name); ?></span>
                    <span class="bg-gray-100 text-gray-600 text-sm font-bold px-3 py-1 rounded-lg"><?php echo e($business->district->name); ?></span>
                    <?php if($business->subArea): ?>
                        <span class="bg-gray-100 text-gray-600 text-sm font-bold px-3 py-1 rounded-lg"><?php echo e($business->subArea->name); ?></span>
                    <?php endif; ?>
                </div>

                <?php if($business->description): ?>
                    <div class="mb-6">
                        <h4 class="font-bold text-gray-700 mb-2"><?php echo e(__('Description')); ?></h4>
                        <p class="text-gray-600 bg-gray-50 rounded-xl p-4"><?php echo e($business->description); ?></p>
                    </div>
                <?php endif; ?>

                <!-- Contact Info -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h4 class="font-bold text-gray-700 mb-3"><?php echo e(__('Contact Information')); ?></h4>
                        <div class="space-y-2 text-sm">
                            <?php $phones = $business->allPhones(); ?>
                            <?php if(count($phones) > 0): ?>
                                <p><span class="text-gray-500"><?php echo e(__('Phone')); ?>:</span> <span class="font-bold"><?php echo e(implode(', ', $phones)); ?></span></p>
                            <?php endif; ?>
                            <?php if($business->address): ?>
                                <p><span class="text-gray-500"><?php echo e(__('Address')); ?>:</span> <?php echo e($business->address); ?></p>
                            <?php endif; ?>
                            <?php if($business->opening_time && $business->closing_time): ?>
                                <p><span class="text-gray-500"><?php echo e(__('Working Hours')); ?>:</span> <?php echo e(substr($business->opening_time, 0, 5)); ?> - <?php echo e(substr($business->closing_time, 0, 5)); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4">
                        <h4 class="font-bold text-gray-700 mb-3"><?php echo e(__('Application Details')); ?></h4>
                        <div class="space-y-2 text-sm">
                            <p><span class="text-gray-500"><?php echo e(__('Submitted On')); ?>:</span> <span class="font-bold"><?php echo e($business->created_at->format('Y-m-d H:i')); ?></span></p>
                            <p><span class="text-gray-500"><?php echo e(__('Category')); ?>:</span> <?php echo e($business->category->name); ?></p>
                            <p><span class="text-gray-500"><?php echo e(__('District')); ?>:</span> <?php echo e($business->district->name); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery -->
        <?php if($business->images && count($business->images) > 0): ?>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="font-bold text-gray-700 mb-4"><?php echo e(__('Image Gallery')); ?> (<?php echo e(count($business->images)); ?>)</h4>
            <div class="grid grid-cols-4 gap-4">
                <?php $__currentLoopData = $business->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                        <img src="<?php echo e(asset('storage/' . $image)); ?>" class="w-full h-full object-cover">
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Owner Info -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="font-bold text-gray-700 mb-4"><?php echo e(__('Business Owner')); ?></h4>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-brand-green rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-lg"><?php echo e(substr($business->owner->name, 0, 1)); ?></span>
                </div>
                <div>
                    <p class="font-bold text-gray-800"><?php echo e($business->owner->name); ?></p>
                    <p class="text-sm text-gray-500"><?php echo e($business->owner->email); ?></p>
                    <p class="text-xs text-gray-400"><?php echo e(__('Member since')); ?> <?php echo e($business->owner->created_at->format('Y-m-d')); ?></p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-sm text-gray-600">
                    <span class="font-bold"><?php echo e(__('Total Businesses')); ?>:</span> 
                    <?php echo e($business->owner->businesses->count()); ?>

                </p>
            </div>
        </div>

        <!-- Approval Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="font-bold text-gray-700 mb-4"><?php echo e(__('Approval Decision')); ?></h4>
            
            <form method="POST" action="<?php echo e(route('manager.approvals.approve', $business)); ?>" class="mb-4">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full bg-green-500 text-white font-bold py-3.5 rounded-xl hover:bg-green-600 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <?php echo e(__('Approve Business')); ?>

                </button>
            </form>

            <form method="POST" action="<?php echo e(route('manager.approvals.reject', $business)); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full bg-red-500 text-white font-bold py-3.5 rounded-xl hover:bg-red-600 transition-all flex items-center justify-center gap-2" onclick="return confirm('<?php echo e(__('Are you sure you want to reject this business?')); ?>')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <?php echo e(__('Reject Business')); ?>

                </button>
            </form>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="font-bold text-gray-700 mb-4"><?php echo e(__('Quick Stats')); ?></h4>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-500"><?php echo e(__('Current Status')); ?></span>
                    <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-2 py-1 rounded"><?php echo e(__('Pending')); ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500"><?php echo e(__('Submitted')); ?></span>
                    <span class="font-bold"><?php echo e($business->created_at->diffForHumans()); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views\manager\approvals\show.blade.php ENDPATH**/ ?>