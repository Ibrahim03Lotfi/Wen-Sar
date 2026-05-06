<?php $__env->startSection('title', __('Categories')); ?>
<?php $__env->startSection('page-title', __('Categories Management')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Search Bar -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" action="<?php echo e(route('manager.categories.index')); ?>" class="flex gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('Search by main or subcategory name...')); ?>" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green transition-all">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <button type="submit" class="bg-brand-green text-white font-bold py-2.5 px-6 rounded-xl hover:opacity-90 transition-all">
                <?php echo e(__('Search')); ?>

            </button>
            <?php if(request('search')): ?>
            <a href="<?php echo e(route('manager.categories.index')); ?>" class="bg-gray-100 text-gray-600 font-bold py-2.5 px-4 rounded-xl hover:bg-gray-200 transition-all">
                <?php echo e(__('Clear')); ?>

            </a>
            <?php endif; ?>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <p class="text-gray-500"><?php echo e(__('Manage main categories and their subcategories')); ?></p>
            <a href="<?php echo e(route('manager.categories.create')); ?>" class="bg-brand-green text-white font-bold py-2.5 px-5 rounded-xl hover:opacity-90 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <?php echo e(__('Add Category')); ?>

            </a>
        </div>

    <?php if($mainCategories->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-600"><?php echo e(__('Category')); ?></th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600"><?php echo e(__('Subcategories')); ?></th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600"><?php echo e(__('Total Businesses')); ?></th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $mainCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mainCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <!-- Main Category Row -->
                    <tr class="bg-blue-50/50 hover:bg-blue-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded"><?php echo e(__('Main')); ?></span>
                                <span class="font-bold text-gray-800 text-lg"><?php echo e($mainCategory->name); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-purple-100 text-purple-700 text-sm font-bold px-3 py-1 rounded-lg"><?php echo e($mainCategory->subcategories_count); ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php
                                $totalBusinesses = $mainCategory->businesses_count;
                                foreach($mainCategory->subcategories as $sub) {
                                    $totalBusinesses += $sub->businesses_count;
                                }
                            ?>
                            <span class="bg-green-100 text-green-700 text-sm font-bold px-3 py-1 rounded-lg"><?php echo e($totalBusinesses); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('manager.categories.rankings', $mainCategory)); ?>" class="bg-yellow-100 text-yellow-700 p-2 rounded-lg hover:bg-yellow-500 hover:text-white transition-all" title="<?php echo e(__('Manage Rankings')); ?>">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </a>
                                <a href="<?php echo e(route('manager.categories.edit', $mainCategory)); ?>" class="bg-brand-green/10 text-brand-green p-2 rounded-lg hover:bg-brand-green hover:text-white transition-all" title="<?php echo e(__('Edit Main Category')); ?>">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="<?php echo e(route('manager.categories.destroy', $mainCategory)); ?>" onsubmit="return confirm('<?php echo e(__('Are you sure? Subcategories and businesses must be deleted first.')); ?>')">
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
                    
                    <!-- Subcategories Rows -->
                    <?php $__currentLoopData = $mainCategory->subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 transition-colors bg-gray-50/30">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3 pr-8">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2 py-1 rounded"><?php echo e(__('Sub')); ?></span>
                                <span class="font-medium text-gray-700"><?php echo e($subcategory->name); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <span class="text-gray-400">-</span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <span class="bg-green-50 text-green-600 text-sm font-bold px-3 py-1 rounded-lg"><?php echo e($subcategory->businesses_count); ?></span>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('manager.categories.edit', $subcategory)); ?>" class="bg-brand-green/10 text-brand-green p-2 rounded-lg hover:bg-brand-green hover:text-white transition-all" title="<?php echo e(__('Edit Subcategory')); ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="<?php echo e(route('manager.categories.destroy', $subcategory)); ?>" onsubmit="return confirm('<?php echo e(__('Are you sure? Businesses must be moved or deleted first.')); ?>')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="bg-red-100 text-red-600 p-2 rounded-lg hover:bg-red-500 hover:text-white transition-all" title="<?php echo e(__('Delete')); ?>">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            <?php echo e($mainCategories->links()); ?>

        </div>
    <?php else: ?>
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-600 mb-2"><?php echo e(__('No Categories')); ?></h3>
            <p class="text-gray-500 mb-4"><?php echo e(__('Start by adding your first category')); ?></p>
            <a href="<?php echo e(route('manager.categories.create')); ?>" class="bg-brand-green text-white font-bold py-3 px-8 rounded-xl hover:opacity-90 transition-all">
                <?php echo e(__('Add Category')); ?>

            </a>
        </div>
    <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views\manager\categories\index.blade.php ENDPATH**/ ?>