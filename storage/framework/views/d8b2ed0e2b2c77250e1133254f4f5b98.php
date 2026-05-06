<?php $__env->startSection('title', __('Add Sub Area')); ?>
<?php $__env->startSection('page-title', __('Add New Sub Area')); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <a href="<?php echo e(route('manager.sub-areas.index')); ?>" class="text-gray-500 hover:text-brand-green flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <?php echo e(__('Back to Sub Areas')); ?>

            </a>
        </div>

        <?php if(!$governorate_id): ?>
            <!-- Step 1: Select Governorate -->
            <form method="GET" action="<?php echo e(route('manager.sub-areas.create')); ?>" class="p-6">
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Select Governorate')); ?> <span class="text-red-500">*</span></label>
                    <select name="governorate_id" required
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors bg-white"
                            onchange="this.form.submit()">
                        <option value=""><?php echo e(__('Choose a governorate...')); ?></option>
                        <?php $__currentLoopData = $governorates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $governorate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($governorate->id); ?>">
                                <?php echo e($governorate->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <p class="text-gray-500 text-sm"><?php echo e(__('First, select the governorate where the sub-area is located')); ?></p>
            </form>
        <?php else: ?>
            <!-- Step 2: Select District and Enter Sub Area Name -->
            <form method="POST" action="<?php echo e(route('manager.sub-areas.store')); ?>" class="p-6">
                <?php echo csrf_field(); ?>

                <!-- Selected Governorate (Read Only) -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Governorate')); ?></label>
                    <div class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 bg-gray-50 text-gray-700 flex items-center justify-between">
                        <span><?php echo e($governorates->firstWhere('id', $governorate_id)->name ?? '-'); ?></span>
                        <a href="<?php echo e(route('manager.sub-areas.create')); ?>" class="text-brand-green text-sm hover:underline"><?php echo e(__('Change')); ?></a>
                    </div>
                </div>

                <!-- Select District -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('District')); ?> <span class="text-red-500">*</span></label>
                    <select name="district_id" required
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors bg-white">
                        <option value=""><?php echo e(__('Select District')); ?></option>
                        <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($district->id); ?>" <?php echo e(old('district_id') == $district->id ? 'selected' : ''); ?>>
                                <?php echo e($district->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['district_id'];
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

                <!-- Sub Area Name -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Sub Area Name')); ?> <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="<?php echo e(old('name')); ?>"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors"
                           placeholder="<?php echo e(__('Enter sub area name')); ?>">
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

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-brand-green text-white font-bold py-3.5 rounded-xl hover:opacity-90 transition-all">
                        <?php echo e(__('Create Sub Area')); ?>

                    </button>
                    <a href="<?php echo e(route('manager.sub-areas.index')); ?>" class="flex-1 bg-gray-100 text-gray-700 font-bold py-3.5 rounded-xl hover:bg-gray-200 transition-all text-center">
                        <?php echo e(__('Cancel')); ?>

                    </a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views\manager\sub-areas\create.blade.php ENDPATH**/ ?>