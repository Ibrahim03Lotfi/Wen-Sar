<?php $__env->startSection('title', $user->name); ?>
<?php $__env->startSection('page-title', __('Owner Details')); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <a href="<?php echo e(route('manager.owners.index')); ?>" class="text-gray-500 hover:text-brand-green flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <?php echo e(__('Back to Owners')); ?>

    </a>
</div>

<!-- Owner Info -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-brand-green rounded-full flex items-center justify-center">
                <span class="text-white text-2xl font-bold"><?php echo e(substr($user->name, 0, 1)); ?></span>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800"><?php echo e($user->name); ?></h2>
                <p class="text-gray-500"><?php echo e($user->email); ?></p>
                <p class="text-sm text-gray-400"><?php echo e(__('Registered')); ?>: <?php echo e($user->created_at->format('Y-m-d')); ?></p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('manager.businesses.create-for-owner', $user)); ?>" class="bg-blue-100 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <?php echo e(__('Add Business')); ?>

            </a>
            <form method="POST" action="<?php echo e(route('manager.owners.destroy', $user)); ?>" onsubmit="return confirm('<?php echo e(__('Are you sure you want to delete :name account? All their :count businesses will be deleted!', ['name' => $user->name, 'count' => $user->businesses->count()])); ?>')" class="inline">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="bg-red-100 text-red-600 px-4 py-2 rounded-lg hover:bg-red-600 hover:text-white transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <?php echo e(__('Delete Owner')); ?>

            </button>
        </form>
    </div>
</div>

<!-- Change Password -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h3 class="font-bold text-gray-800 mb-4"><?php echo e(__('Change Password')); ?></h3>
    <?php if($errors->any()): ?>
        <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="mr-3">
                    <h3 class="text-sm font-medium text-red-800"><?php echo e(__('Error')); ?></h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <form method="POST" action="<?php echo e(route('manager.owners.update-password', $user)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('New Password')); ?> <span class="text-red-500">*</span></label>
                <input type="password" name="password" required minlength="8" class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="••••••••">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Confirm Password')); ?> <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" required minlength="8" class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="••••••••">
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="bg-brand-green text-white px-6 py-2 rounded-lg hover:opacity-90 transition-all font-bold">
                <?php echo e(__('Update Password')); ?>

            </button>
        </div>
    </form>
</div>

<!-- Owner's Businesses -->
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <h3 class="font-bold text-gray-800"><?php echo e(__('Businesses')); ?> (<?php echo e($user->businesses->count()); ?>)</h3>
    </div>

    <?php if($user->businesses->count() > 0): ?>
        <div class="divide-y divide-gray-100">
            <?php $__currentLoopData = $user->businesses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $business): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gray-100 rounded-lg overflow-hidden">
                            <?php if($business->logo): ?>
                                <img src="<?php echo e(asset('storage/' . $business->logo)); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800"><?php echo e($business->name); ?></h4>
                            <p class="text-sm text-gray-500"><?php echo e($business->category->name); ?> • <?php echo e($business->district->name); ?></p>
                            <div class="flex items-center gap-2 mt-1">
                                <?php if($business->status === 'pending'): ?>
                                    <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-2 py-0.5 rounded"><?php echo e(__('Pending')); ?></span>
                                <?php elseif($business->status === 'approved'): ?>
                                    <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded"><?php echo e(__('Approved')); ?></span>
                                <?php elseif($business->status === 'rejected'): ?>
                                    <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-0.5 rounded"><?php echo e(__('Rejected')); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo e(route('manager.businesses.show', $business)); ?>" class="bg-brand-green/10 text-brand-green px-4 py-2 rounded-lg text-sm font-bold hover:bg-brand-green hover:text-white transition-all">
                        <?php echo e(__('View')); ?>

                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="text-center py-8">
            <p class="text-gray-500"><?php echo e(__('No businesses registered yet')); ?></p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views\manager\owners\show.blade.php ENDPATH**/ ?>