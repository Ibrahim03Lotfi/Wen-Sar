<?php $__env->startSection('title', __('Sub Areas')); ?>
<?php $__env->startSection('page-title', __('Sub Areas Management')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Search Bar -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" action="<?php echo e(route('manager.sub-areas.index')); ?>" class="flex gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('Search by sub-area, district or governorate name...')); ?>" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-brand-green focus:ring-1 focus:ring-brand-green transition-all">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <button type="submit" class="bg-brand-green text-white font-bold py-2.5 px-6 rounded-xl hover:opacity-90 transition-all">
                <?php echo e(__('Search')); ?>

            </button>
            <?php if(request('search')): ?>
            <a href="<?php echo e(route('manager.sub-areas.index')); ?>" class="bg-gray-100 text-gray-600 font-bold py-2.5 px-4 rounded-xl hover:bg-gray-200 transition-all">
                <?php echo e(__('Clear')); ?>

            </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-6 flex items-center justify-between">
        <p class="text-gray-500"><?php echo e(__('Manage sub-areas (neighborhoods) within districts')); ?></p>
        <a href="<?php echo e(route('manager.sub-areas.create')); ?>" class="bg-brand-green text-white font-bold py-2.5 px-5 rounded-xl hover:opacity-90 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <?php echo e(__('Add Sub Area')); ?>

        </a>
    </div>

    <?php if($districts->count() > 0): ?>
        <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-xl shadow-sm">
            <!-- District Header -->
            <div class="p-4 bg-gray-50 border-b border-gray-100 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-brand-green rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800"><?php echo e($district->name); ?></h3>
                            <p class="text-sm text-gray-500"><?php echo e($district->governorate->name); ?> • <?php echo e($district->sub_areas_count); ?> <?php echo e(__('Sub Areas')); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sub Areas for this District -->
            <?php if($district->subAreas->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-3 text-right text-sm font-bold text-gray-600"><?php echo e(__('Name')); ?></th>
                                <th class="px-6 py-3 text-center text-sm font-bold text-gray-600"><?php echo e(__('Businesses')); ?></th>
                                <th class="px-6 py-3 text-center text-sm font-bold text-gray-600"><?php echo e(__('Actions')); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php $__currentLoopData = $district->subAreas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subArea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3">
                                    <span class="font-medium text-gray-800"><?php echo e($subArea->name); ?></span>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="bg-green-100 text-green-700 text-sm font-bold px-3 py-1 rounded-lg"><?php echo e($subArea->businesses_count); ?></span>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?php echo e(route('manager.sub-areas.edit', $subArea)); ?>" class="bg-brand-green/10 text-brand-green p-2 rounded-lg hover:bg-brand-green hover:text-white transition-all" title="<?php echo e(__('Edit')); ?>">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="<?php echo e(route('manager.sub-areas.destroy', $subArea)); ?>" onsubmit="return confirm('<?php echo e(__('Are you sure? Businesses must be moved or deleted first.')); ?>')" class="inline">
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
            <?php else: ?>
                <div class="p-4 text-center text-gray-500">
                    <?php echo e(__('No sub areas in this district')); ?>

                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow-sm text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-600 mb-2"><?php echo e(__('No Sub Areas')); ?></h3>
            <p class="text-gray-500 mb-4"><?php echo e(__('Start by adding your first sub area')); ?></p>
            <a href="<?php echo e(route('manager.sub-areas.create')); ?>" class="bg-brand-green text-white font-bold py-3 px-8 rounded-xl hover:opacity-90 transition-all">
                <?php echo e(__('Add Sub Area')); ?>

            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views\manager\sub-areas\index.blade.php ENDPATH**/ ?>