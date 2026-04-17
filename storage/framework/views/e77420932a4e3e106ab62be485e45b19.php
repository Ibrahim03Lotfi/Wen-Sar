<?php $__env->startSection('title', __('Managers')); ?>
<?php $__env->startSection('page-title', __('Manager Accounts')); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <p class="text-gray-500"><?php echo e(__('Manage manager accounts and permissions')); ?></p>
        <a href="<?php echo e(route('manager.managers.create')); ?>" class="bg-brand-green text-white font-bold py-2.5 px-5 rounded-xl hover:opacity-90 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <?php echo e(__('Add Manager')); ?>

        </a>
    </div>

    <?php if($managers->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-600"><?php echo e(__('Name')); ?></th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-600"><?php echo e(__('Username')); ?></th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600"><?php echo e(__('Status')); ?></th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600"><?php echo e(__('Last Login')); ?></th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600"><?php echo e(__('Created')); ?></th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $managers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manager): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-800"><?php echo e($manager->name); ?></span>
                            <?php if($manager->id === Auth::guard('manager')->id()): ?>
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded mr-2"><?php echo e(__('You')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm text-gray-600"><?php echo e($manager->username); ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if($manager->is_active): ?>
                                <span class="bg-green-100 text-green-700 text-sm font-bold px-3 py-1 rounded-lg"><?php echo e(__('Active')); ?></span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-700 text-sm font-bold px-3 py-1 rounded-lg"><?php echo e(__('Inactive')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-gray-500">
                            <?php echo e($manager->last_login_at ? $manager->last_login_at->diffForHumans() : __('Never')); ?>

                        </td>
                        <td class="px-6 py-4 text-center text-sm text-gray-500">
                            <?php echo e($manager->created_at->format('Y-m-d')); ?>

                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <?php if($manager->id !== Auth::guard('manager')->id()): ?>
                                    <form method="POST" action="<?php echo e(route('manager.managers.destroy', $manager)); ?>" onsubmit="return confirm('<?php echo e(__('Are you sure you want to delete this manager? This action cannot be undone.')); ?>')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="bg-red-100 text-red-600 p-2 rounded-lg hover:bg-red-500 hover:text-white transition-all" title="<?php echo e(__('Delete')); ?>">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-gray-400 text-sm">-</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            <?php echo e($managers->links()); ?>

        </div>
    <?php else: ?>
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-600 mb-2"><?php echo e(__('No Managers')); ?></h3>
            <p class="text-gray-500 mb-4"><?php echo e(__('Start by adding your first manager account')); ?></p>
            <a href="<?php echo e(route('manager.managers.create')); ?>" class="bg-brand-green text-white font-bold py-3 px-8 rounded-xl hover:opacity-90 transition-all">
                <?php echo e(__('Add Manager')); ?>

            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views/manager/managers/index.blade.php ENDPATH**/ ?>