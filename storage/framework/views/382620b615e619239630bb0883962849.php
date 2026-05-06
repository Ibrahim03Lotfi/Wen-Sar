<?php $__env->startSection('title', __('Add Manager')); ?>
<?php $__env->startSection('page-title', __('Create New Manager Account')); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <p class="text-gray-500"><?php echo e(__('Create a new manager account with secure dual-password authentication.')); ?></p>
        </div>

        <form method="POST" action="<?php echo e(route('manager.managers.store')); ?>" class="p-6 space-y-6">
            <?php echo csrf_field(); ?>

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Full Name')); ?> <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 outline-none transition-all"
                    placeholder="<?php echo e(__('Enter full name')); ?>">
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Username')); ?> <span class="text-red-500">*</span></label>
                <input type="text" id="username" name="username" value="<?php echo e(old('username')); ?>" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 outline-none transition-all font-mono"
                    placeholder="<?php echo e(__('Enter username (letters, numbers, underscore)')); ?>"
                    pattern="[a-zA-Z0-9_]+">
                <p class="text-xs text-gray-500 mt-1"><?php echo e(__('Only letters, numbers, and underscores allowed. Cannot be changed later.')); ?></p>
                <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Password 1 -->
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                <h4 class="font-bold text-blue-800 mb-3"><?php echo e(__('First Password')); ?></h4>
                
                <div class="mb-4">
                    <label for="password_1" class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Password')); ?> <span class="text-red-500">*</span></label>
                    <input type="password" id="password_1" name="password_1" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 outline-none transition-all"
                        placeholder="<?php echo e(__('Enter first password')); ?>">
                    <p class="text-xs text-gray-500 mt-1"><?php echo e(__('Min 12 characters, must include uppercase, lowercase, number, and symbol.')); ?></p>
                    <?php $__errorArgs = ['password_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="password_1_confirmation" class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Confirm Password')); ?> <span class="text-red-500">*</span></label>
                    <input type="password" id="password_1_confirmation" name="password_1_confirmation" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 outline-none transition-all"
                        placeholder="<?php echo e(__('Confirm first password')); ?>">
                </div>
            </div>

            <!-- Password 2 -->
            <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                <h4 class="font-bold text-green-800 mb-3"><?php echo e(__('Second Password')); ?></h4>
                
                <div class="mb-4">
                    <label for="password_2" class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Password')); ?> <span class="text-red-500">*</span></label>
                    <input type="password" id="password_2" name="password_2" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 outline-none transition-all"
                        placeholder="<?php echo e(__('Enter second password')); ?>">
                    <p class="text-xs text-gray-500 mt-1"><?php echo e(__('Min 12 characters, must include uppercase, lowercase, number, and symbol. Must be different from first password.')); ?></p>
                    <?php $__errorArgs = ['password_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="password_2_confirmation" class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Confirm Password')); ?> <span class="text-red-500">*</span></label>
                    <input type="password" id="password_2_confirmation" name="password_2_confirmation" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 outline-none transition-all"
                        placeholder="<?php echo e(__('Confirm second password')); ?>">
                </div>
            </div>

            <!-- Active Status -->
            <div class="flex items-center gap-3">
                <input type="checkbox" id="is_active" name="is_active" value="1" checked
                    class="w-5 h-5 text-brand-green rounded border-gray-300 focus:ring-brand-green">
                <label for="is_active" class="text-gray-700 font-medium"><?php echo e(__('Active (can login immediately)')); ?></label>
            </div>

            <!-- Security Notice -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="text-sm text-yellow-800">
                        <p class="font-bold mb-1"><?php echo e(__('Security Notice')); ?></p>
                        <ul class="list-disc list-inside space-y-1">
                            <li><?php echo e(__('Both passwords must be different for security')); ?></li>
                            <li><?php echo e(__('Passwords must be at least 12 characters with mixed case, numbers, and symbols')); ?></li>
                            <li><?php echo e(__('This action will be logged for security audit')); ?></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                <a href="<?php echo e(route('manager.managers.index')); ?>" class="px-6 py-3 text-gray-600 font-bold hover:text-gray-800 transition-colors">
                    <?php echo e(__('Cancel')); ?>

                </a>
                <button type="submit" class="bg-brand-green text-white font-bold py-3 px-8 rounded-xl hover:opacity-90 transition-all">
                    <?php echo e(__('Create Manager')); ?>

                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views\manager\managers\create.blade.php ENDPATH**/ ?>