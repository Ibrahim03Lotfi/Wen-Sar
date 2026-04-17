<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo e(__('Manager Setup')); ?> - <?php echo e(__('Wen Sar')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        .manager-gradient {
            background: linear-gradient(135deg, #06402b 0%, #0d5c3d 100%);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center manager-gradient">
    <div class="w-full max-w-md px-4">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-brand-green rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800"><?php echo e(__('Initial Manager Setup')); ?></h1>
                <p class="text-gray-500 mt-1"><?php echo e(__('Create the first manager account')); ?></p>
                <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-sm text-yellow-700">
                    <?php echo e(__('This page is only available when no managers exist. Two different passwords are required for enhanced security.')); ?>

                </div>
            </div>

            <?php if($errors->any()): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('manager.setup')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Full Name')); ?></label>
                    <input type="text" name="name" required value="<?php echo e(old('name')); ?>"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors"
                           placeholder="<?php echo e(__('Enter your full name')); ?>">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Username')); ?></label>
                    <input type="text" name="username" required value="<?php echo e(old('username')); ?>"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors"
                           placeholder="<?php echo e(__('Choose a username')); ?>">
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Password 1')); ?></label>
                    <input type="password" name="password_1" required
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors mb-2"
                           placeholder="<?php echo e(__('Enter first password')); ?>">
                    <input type="password" name="password_1_confirmation" required
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors"
                           placeholder="<?php echo e(__('Confirm first password')); ?>">
                    <p class="text-xs text-gray-500 mt-1"><?php echo e(__('Minimum 8 characters')); ?></p>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Password 2')); ?> <span class="text-red-500">*</span></label>
                    <input type="password" name="password_2" required
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors mb-2"
                           placeholder="<?php echo e(__('Enter second password (must be different)')); ?>">
                    <input type="password" name="password_2_confirmation" required
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors"
                           placeholder="<?php echo e(__('Confirm second password')); ?>">
                    <p class="text-xs text-gray-500 mt-1"><?php echo e(__('Must be different from Password 1')); ?></p>
                </div>

                <button type="submit" class="w-full bg-brand-green text-white font-bold py-3.5 rounded-xl hover:opacity-90 transition-all shadow-lg mt-6">
                    <?php echo e(__('Create Manager Account')); ?>

                </button>
            </form>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views/manager/auth/setup.blade.php ENDPATH**/ ?>