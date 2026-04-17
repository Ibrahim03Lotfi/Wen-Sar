<?php $__env->startSection('title', __('Pending Approvals')); ?>
<?php $__env->startSection('page-title', __('Pending Business Approvals')); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <p class="text-gray-500"><?php echo e(__('Review and approve new business registrations')); ?></p>
    </div>

    <?php if($pendingBusinesses->count() > 0): ?>
        <div class="divide-y divide-gray-100">
            <?php $__currentLoopData = $pendingBusinesses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $business): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Image -->
                    <div class="w-full lg:w-48 h-32 bg-gray-100 rounded-xl flex-shrink-0 overflow-hidden">
                        <?php if($business->logo): ?>
                            <img src="<?php echo e(asset('storage/' . $business->logo)); ?>" class="w-full h-full object-cover">
                        <?php elseif($business->images && count($business->images) > 0): ?>
                            <img src="<?php echo e(asset('storage/' . $business->images[0])); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Details -->
                    <div class="flex-1">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800"><?php echo e($business->name); ?></h3>
                                <p class="text-sm text-gray-500 mt-1"><?php echo e($business->english_name); ?></p>
                                
                                <div class="flex flex-wrap gap-2 mt-3">
                                    <span class="bg-brand-green/10 text-brand-green text-xs font-bold px-3 py-1 rounded-lg"><?php echo e($business->category?->name ?? '-'); ?></span>
                                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-lg"><?php echo e($business->district?->name ?? '-'); ?></span>
                                    <?php if($business->subArea): ?>
                                        <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-lg"><?php echo e($business->subArea->name); ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="mt-4 space-y-1 text-sm text-gray-600">
                                    <p><span class="font-bold"><?php echo e(__('Owner')); ?>:</span> <?php echo e($business->owner?->name ?? __('Unknown')); ?></p>
                                    <p><span class="font-bold"><?php echo e(__('Phone')); ?>:</span> <?php echo e($business->phone); ?></p>
                                    <?php if($business->address): ?>
                                        <p><span class="font-bold"><?php echo e(__('Address')); ?>:</span> <?php echo e($business->address); ?></p>
                                    <?php endif; ?>
                                    <?php if($business->opening_time && $business->closing_time): ?>
                                        <p><span class="font-bold"><?php echo e(__('Working Hours')); ?>:</span> <?php echo e(substr($business->opening_time, 0, 5)); ?> - <?php echo e(substr($business->closing_time, 0, 5)); ?></p>
                                    <?php endif; ?>
                                </div>

                                <?php if($business->description): ?>
                                    <p class="mt-3 text-sm text-gray-600 line-clamp-2"><?php echo e($business->description); ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col gap-3 min-w-[200px]">
                                <a href="<?php echo e(route('manager.approvals.show', $business)); ?>" class="text-center bg-gray-100 text-gray-700 font-bold py-2.5 rounded-xl hover:bg-gray-200 transition-all">
                                    <?php echo e(__('View Details')); ?>

                                </a>

                                <form method="POST" action="<?php echo e(route('manager.approvals.approve', $business)); ?>" class="space-y-3">
                                    <?php echo csrf_field(); ?>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1"><?php echo e(__('Contract Duration')); ?></label>
                                        <select name="contract_duration" required class="w-full border-2 border-gray-200 rounded-xl px-3 py-2 text-sm focus:border-brand-green focus:outline-none">
                                            <option value="30">30 <?php echo e(__('days')); ?></option>
                                            <option value="90">90 <?php echo e(__('days')); ?></option>
                                            <option value="180">180 <?php echo e(__('days')); ?></option>
                                            <option value="365">1 <?php echo e(__('year')); ?></option>
                                        </select>
                                    </div>
                                    <button type="submit" class="w-full bg-green-500 text-white font-bold py-2.5 rounded-xl hover:bg-green-600 transition-all flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <?php echo e(__('Approve')); ?>

                                    </button>
                                </form>

                                <form method="POST" action="<?php echo e(route('manager.approvals.reject', $business)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="w-full bg-red-500 text-white font-bold py-2.5 rounded-xl hover:bg-red-600 transition-all flex items-center justify-center gap-2" onclick="return confirm('<?php echo e(__('Are you sure you want to reject this business?')); ?>')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        <?php echo e(__('Reject')); ?>

                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="p-4 border-t border-gray-100">
            <?php echo e($pendingBusinesses->links()); ?>

        </div>
    <?php else: ?>
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-600 mb-2"><?php echo e(__('No Pending Approvals')); ?></h3>
            <p class="text-gray-500"><?php echo e(__('All businesses have been reviewed')); ?></p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views/manager/approvals/pending.blade.php ENDPATH**/ ?>