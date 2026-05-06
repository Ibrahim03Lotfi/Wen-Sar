<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<div class="relative bg-brand-green min-h-[calc(100vh-80px)] flex items-center justify-center py-12">
    <div class="absolute inset-0 opacity-10">
        <img src="https://images.unsplash.com/photo-1549136365-5c1a1795f57a?q=80&w=2070&auto=format&fit=crop" alt="Background" class="w-full h-full object-cover">
    </div>

    <div class="relative w-full max-w-md mx-4">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">تسجيل الدخول</h1>
                <p class="text-gray-500">أهلاً بك في وين صار</p>
            </div>

            <!-- Session Status -->
            <?php if(session('status')): ?>
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg text-sm">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">البريد الإلكتروني</label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus autocomplete="username"
                           class="w-full border-gray-200 rounded-xl focus:ring-brand-green focus:border-brand-green bg-gray-50 py-3 px-4">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-2">كلمة المرور</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="w-full border-gray-200 rounded-xl focus:ring-brand-green focus:border-brand-green bg-gray-50 py-3 px-4">
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-brand-green shadow-sm focus:ring-brand-green" name="remember">
                        <span class="mr-2 text-sm text-gray-600">تذكرني</span>
                    </label>
                    <?php if(Route::has('password.request')): ?>
                        <a href="<?php echo e(route('password.request')); ?>" class="text-sm text-brand-green hover:underline font-medium">
                            نسيت كلمة المرور؟
                        </a>
                    <?php endif; ?>
                </div>

                <button type="submit" class="w-full bg-brand-green text-white font-bold py-3.5 rounded-xl hover:opacity-90 transition duration-300 shadow-lg">
                    دخول
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-500 text-sm">
                    ليس لديك حساب؟ 
                    <a href="<?php echo e(route('register')); ?>" class="text-brand-green font-bold hover:underline">سجل الآن</a>
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views\auth\login.blade.php ENDPATH**/ ?>