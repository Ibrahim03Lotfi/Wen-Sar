<?php $__env->startSection('title', __('Business Owners')); ?>
<?php $__env->startSection('page-title', __('Business Owners Management')); ?>

<?php $__env->startSection('content'); ?>
<!-- Search -->
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" action="<?php echo e(route('manager.owners.index')); ?>" class="flex gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('Search by name or email...')); ?>"
                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-brand-green focus:outline-none transition-colors">
        </div>
        <button type="submit" class="bg-brand-green text-white font-bold py-2.5 px-6 rounded-xl hover:opacity-90 transition-all">
            <?php echo e(__('Search')); ?>

        </button>
        <a href="<?php echo e(route('manager.owners.index')); ?>" class="bg-gray-100 text-gray-700 font-bold py-2.5 px-6 rounded-xl hover:bg-gray-200 transition-all">
            <?php echo e(__('Reset')); ?>

        </a>
    </form>
</div>

<!-- Owners List -->
<div class="bg-white rounded-xl shadow-sm">
    <?php if($owners->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-600"><?php echo e(__('Owner')); ?></th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-600"><?php echo e(__('Email')); ?></th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600"><?php echo e(__('Registered')); ?></th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600"><?php echo e(__('Businesses')); ?></th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $owners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $owner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-brand-green/10 rounded-full flex items-center justify-center">
                                    <span class="text-brand-green font-bold"><?php echo e(substr($owner->name, 0, 1)); ?></span>
                                </div>
                                <span class="font-bold text-gray-800"><?php echo e($owner->name); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-600"><?php echo e($owner->email); ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm text-gray-500"><?php echo e($owner->created_at->format('Y-m-d')); ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-brand-green/10 text-brand-green text-sm font-bold px-3 py-1 rounded-lg"><?php echo e($owner->businesses_count); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('manager.businesses.create-for-owner', $owner)); ?>" class="bg-blue-100 text-blue-600 p-2 rounded-lg hover:bg-blue-600 hover:text-white transition-all" title="<?php echo e(__('Add Business')); ?>">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </a>
                                <a href="<?php echo e(route('manager.owners.show', $owner)); ?>" class="bg-brand-green/10 text-brand-green p-2 rounded-lg hover:bg-brand-green hover:text-white transition-all" title="<?php echo e(__('View Details')); ?>">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="<?php echo e(route('manager.owners.destroy', $owner)); ?>" onsubmit="return confirm('<?php echo e(__('Are you sure you want to delete :name account? All their :count businesses will be deleted!', ['name' => $owner->name, 'count' => $owner->businesses_count])); ?>')" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="bg-red-100 text-red-600 p-2 rounded-lg hover:bg-red-600 hover:text-white transition-all" title="<?php echo e(__('Delete')); ?>">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            <?php echo e($owners->links()); ?>

        </div>
    <?php else: ?>
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-600 mb-2"><?php echo e(__('No Owners Found')); ?></h3>
            <p class="text-gray-500"><?php echo e(__('Try adjusting your search')); ?></p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views/manager/owners/index.blade.php ENDPATH**/ ?>