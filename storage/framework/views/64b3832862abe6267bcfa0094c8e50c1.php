<?php $__env->startSection('title', __('Edit Business')); ?>
<?php $__env->startSection('page-title', __('Edit Business')); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <a href="<?php echo e(route('manager.businesses.index')); ?>" class="text-gray-500 hover:text-brand-green flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <?php echo e(__('Back to Businesses')); ?>

    </a>
</div>

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="<?php echo e(route('manager.businesses.update', $business)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Business Name')); ?> <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="<?php echo e(old('name', $business->name)); ?>"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors">
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

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('English Name')); ?></label>
                    <input type="text" name="english_name" value="<?php echo e(old('english_name', $business->english_name)); ?>"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Description')); ?></label>
                <textarea name="description" rows="4"
                          class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors"><?php echo e(old('description', $business->description)); ?></textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Category')); ?> <span class="text-red-500">*</span></label>
                    <select name="category_id" required
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors bg-white">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e((old('category_id', $business->category_id) == $category->id) ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('District')); ?> <span class="text-red-500">*</span></label>
                    <select name="district_id" required
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors bg-white">
                        <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($district->id); ?>" <?php echo e((old('district_id', $business->district_id) == $district->id) ? 'selected' : ''); ?>><?php echo e($district->name); ?> (<?php echo e($district->governorate->name); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Sub Area')); ?></label>
                <select name="sub_area_id"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors bg-white">
                    <option value=""><?php echo e(__('Select Sub Area')); ?></option>
                    <?php $__currentLoopData = $subAreas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subArea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($subArea->id); ?>" <?php echo e((old('sub_area_id', $business->sub_area_id) == $subArea->id) ? 'selected' : ''); ?>><?php echo e($subArea->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Phone Numbers -->
            <div class="mb-6" x-data="{ phones: editPhones() }">
                <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Phone Numbers')); ?></label>
                <template x-for="(phone, index) in phones" :key="index">
                    <div class="relative flex items-center gap-2 mb-2">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-brand-green font-extrabold text-lg select-none">09</span>
                        <input type="tel" :name="`phones[${index}]`" x-model="phone.value" maxlength="8"
                               class="w-full border-2 border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:border-brand-green focus:outline-none transition-colors bg-white text-gray-800 font-bold tracking-wider"
                               placeholder="12345678"
                               @input="phone.value = phone.value.replace(/[^0-9]/g, '').slice(0, 8);">
                        <button type="button" @click="phones.splice(index, 1)" class="shrink-0 bg-red-100 text-red-600 hover:bg-red-200 w-10 h-10 rounded-lg flex items-center justify-center transition" title="<?php echo e(__('Remove')); ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
                <button type="button" @click="phones.push({ value: '' })" class="mt-2 inline-flex items-center gap-2 text-sm font-bold text-brand-green hover:text-brand-green/80 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <?php echo e(__('Add Phone Number')); ?>

                </button>
                <p class="text-xs text-gray-400 mt-2"><?php echo e(__('Optional')); ?></p>
                <script>
                    function editPhones() {
                        const oldPhones = <?php echo json_encode(old('phones', $business->phones ?? []), 512) ?>;
                        if (oldPhones && oldPhones.length > 0) {
                            return oldPhones.map(v => ({ value: v.startsWith('09') ? v.substring(2) : v }));
                        }
                        const oldPhone = <?php echo json_encode(old('phone', $business->phone), 512) ?>;
                        if (oldPhone) {
                            return [{ value: oldPhone.startsWith('09') ? oldPhone.substring(2) : oldPhone }];
                        }
                        return [];
                    }
                </script>
            </div>

            <!-- Landlines -->
            <div class="mb-6" x-data="{ landlines: editLandlines() }">
                <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Landlines')); ?></label>
                <template x-for="(landline, index) in landlines" :key="index">
                    <div class="relative flex items-center gap-2 mb-2">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-brand-green font-extrabold text-lg select-none">011</span>
                        <input type="tel" :name="`landlines[${index}]`" x-model="landline.value" maxlength="7"
                               class="w-full border-2 border-gray-200 rounded-xl py-3 pl-14 pr-4 focus:border-brand-green focus:outline-none transition-colors bg-white text-gray-800 font-bold tracking-wider"
                               placeholder="1234567"
                               @input="landline.value = landline.value.replace(/[^0-9]/g, '').slice(0, 7);">
                        <button type="button" @click="landlines.splice(index, 1)" class="shrink-0 bg-red-100 text-red-600 hover:bg-red-200 w-10 h-10 rounded-lg flex items-center justify-center transition" title="<?php echo e(__('Remove')); ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
                <button type="button" @click="landlines.push({ value: '' })" class="mt-2 inline-flex items-center gap-2 text-sm font-bold text-brand-green hover:text-brand-green/80 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <?php echo e(__('Add Landline')); ?>

                </button>
                <p class="text-xs text-gray-400 mt-2"><?php echo e(__('Optional')); ?></p>
                <script>
                    function editLandlines() {
                        const oldLandlines = <?php echo json_encode(old('landlines', $business->landlines ?? []), 512) ?>;
                        if (oldLandlines && oldLandlines.length > 0) {
                            return oldLandlines.map(v => ({ value: v.startsWith('011') ? v.substring(3) : v }));
                        }
                        const oldLandline = <?php echo json_encode(old('landline', $business->landline), 512) ?>;
                        if (oldLandline) {
                            return [{ value: oldLandline.startsWith('011') ? oldLandline.substring(3) : oldLandline }];
                        }
                        return [];
                    }
                </script>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Opening Time')); ?></label>
                    <input type="time" name="opening_time" value="<?php echo e(old('opening_time', $business->opening_time ? substr($business->opening_time, 0, 5) : '')); ?>"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Closing Time')); ?></label>
                    <input type="time" name="closing_time" value="<?php echo e(old('closing_time', $business->closing_time ? substr($business->closing_time, 0, 5) : '')); ?>"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2"><?php echo e(__('Address')); ?></label>
                <input type="text" name="address" value="<?php echo e(old('address', $business->address)); ?>"
                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors">
            </div>

            <!-- Business Hours -->
            <?php if (isset($component)) { $__componentOriginal87b3f5244414598c0c7127e965720234 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87b3f5244414598c0c7127e965720234 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.business-hours','data' => ['business' => $business]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('business-hours'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['business' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($business)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal87b3f5244414598c0c7127e965720234)): ?>
<?php $attributes = $__attributesOriginal87b3f5244414598c0c7127e965720234; ?>
<?php unset($__attributesOriginal87b3f5244414598c0c7127e965720234); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal87b3f5244414598c0c7127e965720234)): ?>
<?php $component = $__componentOriginal87b3f5244414598c0c7127e965720234; ?>
<?php unset($__componentOriginal87b3f5244414598c0c7127e965720234); ?>
<?php endif; ?>

            <div class="mb-6">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_featured" value="1" <?php echo e(old('is_featured', $business->is_featured) ? 'checked' : ''); ?>

                           class="w-5 h-5 text-brand-green border-gray-300 rounded focus:ring-brand-green">
                    <span class="font-bold text-gray-700"><?php echo e(__('Featured Business')); ?></span>
                </label>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-brand-green text-white font-bold py-3.5 rounded-xl hover:opacity-90 transition-all">
                    <?php echo e(__('Update Business')); ?>

                </button>
                <a href="<?php echo e(route('manager.businesses.index')); ?>" class="flex-1 bg-gray-100 text-gray-700 font-bold py-3.5 rounded-xl hover:bg-gray-200 transition-all text-center">
                    <?php echo e(__('Cancel')); ?>

                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views\manager\businesses\edit.blade.php ENDPATH**/ ?>