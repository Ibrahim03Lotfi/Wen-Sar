<?php $__env->startSection('title', __('Add Category')); ?>
<?php $__env->startSection('page-title', __('Add New Category')); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <a href="<?php echo e(route('manager.categories.index')); ?>" class="text-gray-500 hover:text-brand-green flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <?php echo e(__('Back to Categories')); ?>

            </a>
            <h2 class="text-xl font-bold text-gray-800"><?php echo e(__('Add New Category')); ?></h2>
            <p class="text-gray-500"><?php echo e(__('Create a new main category or subcategory')); ?></p>
        </div>

        <form method="POST" action="<?php echo e(route('manager.categories.store')); ?>" class="p-6 space-y-6">
            <?php echo csrf_field(); ?>

            <!-- Category Name -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Category Name')); ?> <span class="text-red-500">*</span></label>
                <input type="text" name="name" required 
                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors"
                       placeholder="<?php echo e(__('Example: Restaurants, Shops, Services...')); ?>"
                       value="<?php echo e(old('name')); ?>">
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

            <!-- Parent Category -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Parent Category')); ?></label>
                <select name="parent_id" 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors bg-white">
                    <option value=""><?php echo e(__('None - Create as Main Category')); ?></option>
                    <?php $__currentLoopData = $parentCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($parent->id); ?>" <?php echo e(old('parent_id') == $parent->id ? 'selected' : ''); ?>>
                            <?php echo e($parent->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <p class="text-sm text-gray-500 mt-2"><?php echo e(__('Select a parent to create this as a subcategory, or leave empty for a main category.')); ?></p>
                <?php $__errorArgs = ['parent_id'];
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

            <!-- Submit Button -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-brand-green text-white font-bold py-3 rounded-xl hover:opacity-90 transition-all">
                    <?php echo e(__('Create Category')); ?>

                </button>
                <a href="<?php echo e(route('manager.categories.index')); ?>" class="px-6 py-3 border-2 border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-all">
                    <?php echo e(__('Cancel')); ?>

                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views\manager\categories\create.blade.php ENDPATH**/ ?>