<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale() == 'ar' ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo e(__('Manager Login')); ?> - <?php echo e(__('Wen Sar')); ?></title>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800"><?php echo e(__('Manager Login')); ?></h1>
                <p class="text-gray-500 mt-1"><?php echo e(__('Secure access for authorized personnel only')); ?></p>
            </div>

            <?php if($errors->any()): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('manager.login')); ?>" class="space-y-5">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Username')); ?></label>
                    <input type="text" name="username" required
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors"
                           placeholder="<?php echo e(__('Enter your username')); ?>">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Password 1')); ?></label>
                    <input type="password" name="password_1" required
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors"
                           placeholder="<?php echo e(__('Enter first password')); ?>">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Password 2')); ?></label>
                    <input type="password" name="password_2" required
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors"
                           placeholder="<?php echo e(__('Enter second password')); ?>">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-brand-green border-gray-300 rounded focus:ring-brand-green">
                    <label for="remember" class="mr-2 text-sm text-gray-600"><?php echo e(__('Remember me')); ?></label>
                </div>

                <button type="submit" class="w-full bg-brand-green text-white font-bold py-3.5 rounded-xl hover:opacity-90 transition-all shadow-lg">
                    <?php echo e(__('Secure Login')); ?>

                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="<?php echo e(url('/')); ?>" class="text-sm text-gray-500 hover:text-brand-green">
                    ← <?php echo e(__('Back to Website')); ?>

                </a>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views/manager/auth/login.blade.php ENDPATH**/ ?>