<?php $__env->startSection('title', __('Governorates')); ?>
<?php $__env->startSection('page-title', __('Governorates Management')); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <p class="text-gray-500"><?php echo e(__('Manage governorates and their statistics')); ?></p>
        <a href="<?php echo e(route('manager.governorates.create')); ?>" class="bg-brand-green text-white font-bold py-2.5 px-5 rounded-xl hover:opacity-90 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <?php echo e(__('Add Governorate')); ?>

        </a>
    </div>

    <?php if($governorates->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-600"><?php echo e(__('Name')); ?></th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600"><?php echo e(__('Districts')); ?></th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $governorates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $governorate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-800"><?php echo e($governorate->name); ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-blue-100 text-blue-700 text-sm font-bold px-3 py-1 rounded-lg"><?php echo e($governorate->districts_count); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('manager.governorates.edit', $governorate)); ?>" class="bg-brand-green/10 text-brand-green p-2 rounded-lg hover:bg-brand-green hover:text-white transition-all" title="<?php echo e(__('Edit')); ?>">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="<?php echo e(route('manager.governorates.destroy', $governorate)); ?>" onsubmit="return confirm('<?php echo e(__('Are you sure? All districts under this governorate must be deleted first.')); ?>')">
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
            <?php echo e($governorates->links()); ?>

        </div>
    <?php else: ?>
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-600 mb-2"><?php echo e(__('No Governorates')); ?></h3>
            <p class="text-gray-500 mb-4"><?php echo e(__('Start by adding your first governorate')); ?></p>
            <a href="<?php echo e(route('manager.governorates.create')); ?>" class="bg-brand-green text-white font-bold py-3 px-8 rounded-xl hover:opacity-90 transition-all">
                <?php echo e(__('Add Governorate')); ?>

            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views\manager\governorates\index.blade.php ENDPATH**/ ?>