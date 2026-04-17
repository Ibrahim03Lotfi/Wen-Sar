<?php $__env->startSection('title', __('Businesses')); ?>
<?php $__env->startSection('page-title', __('All Approved Businesses')); ?>

<?php $__env->startSection('content'); ?>
<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" action="<?php echo e(route('manager.businesses.index')); ?>" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('Search businesses...')); ?>"
                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-brand-green focus:outline-none transition-colors">
        </div>
        <div class="w-48">
            <select name="district_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-brand-green focus:outline-none transition-colors bg-white">
                <option value=""><?php echo e(__('All Districts')); ?></option>
                <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($district->id); ?>" <?php echo e(request('district_id') == $district->id ? 'selected' : ''); ?>><?php echo e($district->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="w-48">
            <select name="category_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-brand-green focus:outline-none transition-colors bg-white">
                <option value=""><?php echo e(__('All Categories')); ?></option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>" <?php echo e(request('category_id') == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="w-48">
            <select name="contract_status" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-brand-green focus:outline-none transition-colors bg-white">
                <option value=""><?php echo e(__('All Contracts')); ?></option>
                <option value="active" <?php echo e(request('contract_status') == 'active' ? 'selected' : ''); ?>><?php echo e(__('Active')); ?></option>
                <option value="expiring_soon" <?php echo e(request('contract_status') == 'expiring_soon' ? 'selected' : ''); ?>><?php echo e(__('Expiring Soon')); ?></option>
                <option value="expired" <?php echo e(request('contract_status') == 'expired' ? 'selected' : ''); ?>><?php echo e(__('Expired')); ?></option>
            </select>
        </div>
        <button type="submit" class="bg-brand-green text-white font-bold py-2.5 px-6 rounded-xl hover:opacity-90 transition-all">
            <?php echo e(__('Filter')); ?>

        </button>
        <a href="<?php echo e(route('manager.businesses.index')); ?>" class="bg-gray-100 text-gray-700 font-bold py-2.5 px-6 rounded-xl hover:bg-gray-200 transition-all">
            <?php echo e(__('Reset')); ?>

        </a>
    </form>
</div>

<!-- Businesses List -->
<div class="bg-white rounded-xl shadow-sm">
    <?php if($businesses->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-600"><?php echo e(__('Business')); ?></th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-600"><?php echo e(__('Owner')); ?></th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-600"><?php echo e(__('Location')); ?></th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600"><?php echo e(__('Contract')); ?></th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $businesses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $business): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden">
                                    <?php if($business->logo): ?>
                                        <img src="<?php echo e(asset('storage/' . $business->logo)); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800"><?php echo e($business->name); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e($business->category->name); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-600"><?php echo e($business->owner?->name ?? __('Unknown')); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600"><?php echo e($business->district?->name ?? '-'); ?></span>
                            <?php if($business->subArea): ?>
                                <p class="text-xs text-gray-400"><?php echo e($business->subArea->name); ?></p>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if($business->contract_ends_at): ?>
                                <?php
                                    $daysLeft = $business->daysUntilExpiry();
                                ?>
                                <div class="text-sm">
                                    <p class="font-bold <?php echo e($daysLeft <= 3 ? 'text-red-600' : 'text-green-600'); ?>">
                                        <?php echo e($daysLeft > 0 ? $daysLeft . ' ' . __('days') : __('Expired')); ?>

                                    </p>
                                    <p class="text-xs text-gray-400"><?php echo e($business->contract_ends_at->format('Y-m-d')); ?></p>
                                </div>
                            <?php else: ?>
                                <span class="text-gray-400 text-sm">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('manager.businesses.show', $business)); ?>" class="bg-brand-green/10 text-brand-green p-2 rounded-lg hover:bg-brand-green hover:text-white transition-all" title="<?php echo e(__('View')); ?>">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="<?php echo e(route('manager.businesses.edit', $business)); ?>" class="bg-blue-100 text-blue-600 p-2 rounded-lg hover:bg-blue-500 hover:text-white transition-all" title="<?php echo e(__('Edit')); ?>">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="<?php echo e(route('manager.businesses.destroy', $business)); ?>" onsubmit="return confirm('<?php echo e(__('Are you sure you want to delete this business?')); ?>')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="bg-red-100 text-red-600 p-2 rounded-lg hover:bg-red-500 hover:text-white transition-all" title="<?php echo e(__('Delete')); ?>">
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
            <?php echo e($businesses->links()); ?>

        </div>
    <?php else: ?>
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-600 mb-2"><?php echo e(__('No Businesses Found')); ?></h3>
            <p class="text-gray-500"><?php echo e(__('Try adjusting your filters')); ?></p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views/manager/businesses/index.blade.php ENDPATH**/ ?>