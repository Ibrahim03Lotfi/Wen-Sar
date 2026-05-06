<?php $__env->startSection('title', $business->name); ?>
<?php $__env->startSection('page-title', __('Business Details')); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <a href="<?php echo e(route('manager.businesses.index')); ?>" class="text-gray-500 hover:text-brand-green flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <?php echo e(__('Back to Businesses')); ?>

    </a>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Business Card -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="h-48 bg-gray-100 relative">
                <?php if($business->logo): ?>
                    <img src="<?php echo e(asset('storage/' . $business->logo)); ?>" class="w-full h-full object-cover">
                <?php elseif($business->images && count($business->images) > 0): ?>
                    <img src="<?php echo e(asset('storage/' . $business->images[0])); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                <?php endif; ?>
                <?php if($business->is_featured): ?>
                    <div class="absolute top-4 right-4 bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-lg">
                        <?php echo e(__('Featured')); ?>

                    </div>
                <?php endif; ?>
            </div>
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2"><?php echo e($business->name); ?></h2>
                <p class="text-gray-500"><?php echo e($business->english_name); ?></p>
                
                <div class="flex flex-wrap gap-2 mt-4">
                    <span class="bg-brand-green/10 text-brand-green text-sm font-bold px-3 py-1 rounded-lg"><?php echo e($business->category->name); ?></span>
                    <?php if($business->status === 'approved'): ?>
                        <span class="bg-green-100 text-green-700 text-sm font-bold px-3 py-1 rounded-lg"><?php echo e(__('Approved')); ?></span>
                    <?php elseif($business->status === 'pending'): ?>
                        <span class="bg-yellow-100 text-yellow-700 text-sm font-bold px-3 py-1 rounded-lg"><?php echo e(__('Pending')); ?></span>
                    <?php endif; ?>
                </div>

                <?php if($business->description): ?>
                    <div class="mt-6">
                        <h4 class="font-bold text-gray-700 mb-2"><?php echo e(__('Description')); ?></h4>
                        <p class="text-gray-600"><?php echo e($business->description); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Images -->
        <?php if($business->images && count($business->images) > 0): ?>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="font-bold text-gray-700 mb-4"><?php echo e(__('Gallery')); ?></h4>
            <div class="grid grid-cols-4 gap-4">
                <?php $__currentLoopData = $business->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="aspect-square rounded-lg overflow-hidden">
                        <img src="<?php echo e(asset('storage/' . $image)); ?>" class="w-full h-full object-cover">
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-6">
        <!-- Status Card -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="font-bold text-gray-700 mb-4"><?php echo e(__('Status')); ?></h4>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-500"><?php echo e(__('Current Status')); ?></span>
                    <span class="font-bold <?php echo e($business->status === 'approved' ? 'text-green-600' : ($business->status === 'pending' ? 'text-yellow-600' : 'text-red-600')); ?>">
                        <?php echo e(__(ucfirst($business->status))); ?>

                    </span>
                </div>
                <?php if($business->approved_at): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-500"><?php echo e(__('Approved On')); ?></span>
                        <span class="font-bold"><?php echo e($business->approved_at->format('Y-m-d')); ?></span>
                    </div>
                <?php endif; ?>
                <?php if($business->approvedBy): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-500"><?php echo e(__('Approved By')); ?></span>
                        <span class="font-bold"><?php echo e($business->approvedBy->name); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="font-bold text-gray-700 mb-4"><?php echo e(__('Contact Info')); ?></h4>
            <div class="space-y-3">
                <?php $phones = $business->allPhones(); ?>
                <?php if(count($phones) > 0): ?>
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <div class="space-y-1">
                        <?php $__currentLoopData = $phones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="block"><?php echo e($phone); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if($business->address): ?>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        <span><?php echo e($business->address); ?></span>
                    </div>
                <?php endif; ?>
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <span><?php echo e($business->district->name); ?><?php echo e($business->subArea ? ' - ' . $business->subArea->name : ''); ?></span>
                </div>
            </div>
        </div>

        <!-- Owner Info -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="font-bold text-gray-700 mb-4"><?php echo e(__('Owner')); ?></h4>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-brand-green/10 rounded-full flex items-center justify-center">
                    <span class="text-brand-green font-bold"><?php echo e(substr($business->owner->name, 0, 1)); ?></span>
                </div>
                <div>
                    <p class="font-bold text-gray-800"><?php echo e($business->owner->name); ?></p>
                    <p class="text-sm text-gray-500"><?php echo e($business->owner->email); ?></p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
            <a href="<?php echo e(route('manager.businesses.edit', $business)); ?>" class="flex-1 bg-brand-green text-white font-bold py-3 rounded-xl hover:opacity-90 transition-all text-center">
                <?php echo e(__('Edit')); ?>

            </a>
            <form method="POST" action="<?php echo e(route('manager.businesses.destroy', $business)); ?>" class="flex-1" onsubmit="return confirm('<?php echo e(__('Are you sure you want to delete this business?')); ?>')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="w-full bg-red-500 text-white font-bold py-3 rounded-xl hover:bg-red-600 transition-all">
                    <?php echo e(__('Delete')); ?>

                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views\manager\businesses\show.blade.php ENDPATH**/ ?>