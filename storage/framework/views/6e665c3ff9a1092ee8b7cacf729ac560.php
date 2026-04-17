<?php $__empty_1 = true; $__currentLoopData = $businesses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $business): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all border-2 border-gray-200 overflow-hidden group">
    <div class="flex flex-col md:flex-row">
        <!-- Image Section -->
        <div class="w-full md:w-72 h-56 md:h-auto flex-shrink-0 bg-gray-100 relative overflow-hidden border-b-2 md:border-b-0 md:border-l-2 border-gray-200">
            <?php if($business->logo): ?>
                <img src="<?php echo e(asset('storage/' . $business->logo)); ?>" alt="<?php echo e($business->name); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            <?php elseif($business->images && count($business->images) > 0): ?>
                <img src="<?php echo e(asset('storage/' . $business->images[0])); ?>" alt="<?php echo e($business->name); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            <?php else: ?>
                <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto=format&fit=crop" alt="<?php echo e($business->name); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            <?php endif; ?>
            <?php if($business->is_featured): ?>
                <div class="absolute top-3 right-3 bg-orange-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg border-2 border-orange-600"><?php echo e(__('Featured')); ?></div>
            <?php endif; ?>
        </div>

        <!-- Content Section -->
        <div class="flex-1 p-5 flex flex-col justify-between">
            <div>
                <!-- Title & Rating -->
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-brand-green transition-colors">
                        <?php echo e($business->name); ?>

                    </h3>
                    <div class="flex items-center bg-yellow-50 border border-yellow-200 px-2 py-1 rounded-lg">
                        <span class="text-sm font-bold text-yellow-700 ml-1"><?php echo e(number_format($business->averageRating(), 1)); ?></span>
                        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                    </div>
                </div>

                <!-- Location & Category Tags -->
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <div class="flex items-center bg-brand-green/10 text-brand-green px-3 py-1.5 rounded-lg border border-brand-green/20 font-medium text-sm">
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        </svg>
                        <?php echo e($business->subArea?->name ?? __('Not specified')); ?>

                    </div>
                    <div class="flex items-center bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg border border-gray-200 font-medium text-sm">
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <?php echo e($business->category->name); ?>

                    </div>
                </div>

                <!-- Description -->
                <p class="text-gray-600 text-sm leading-relaxed line-clamp-2 border-l-4 border-brand-green pl-3">
                    <?php echo e(Str::limit($business->description, 150)); ?>

                </p>
            </div>

            <!-- Footer -->
            <div class="flex flex-col sm:flex-row justify-between items-center mt-5 pt-4 border-t-2 border-gray-100 gap-3 sm:gap-0">
                <div class="flex items-center gap-4">
                    <?php if($business->opening_time || $business->closing_time): ?>
                    <span class="text-xs text-brand-green font-bold flex items-center uppercase tracking-wide bg-brand-green/5 px-3 py-1.5 rounded-lg border border-brand-green/20">
                        <span class="w-2 h-2 bg-brand-green rounded-full ml-2 animate-pulse"></span>
                        <?php echo e($business->opening_time ? substr($business->opening_time, 0, 5) : '--:--'); ?> - <?php echo e($business->closing_time ? substr($business->closing_time, 0, 5) : '--:--'); ?>

                    </span>
                    <?php else: ?>
                    <span class="text-xs text-gray-500 font-bold flex items-center uppercase tracking-wide bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200">
                        <?php echo e(__('Working hours not specified')); ?>

                    </span>
                    <?php endif; ?>
                    <span class="text-xs text-gray-500 font-medium bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200">
                        <?php echo e($business->views_count); ?> <?php echo e(__('views')); ?>

                    </span>
                </div>
                <a href="<?php echo e(route('business.show', $business->id)); ?>" class="text-brand-green font-bold text-sm bg-white border-2 border-brand-green px-6 py-2 rounded-lg hover:bg-brand-green hover:text-white hover:shadow-lg transition-all duration-300 text-center w-full sm:w-auto">
                    <?php echo e(__('View Details')); ?>

                </a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<!-- Empty State -->
<div class="text-center py-16 bg-white rounded-xl shadow-md border-2 border-dashed border-gray-300">
    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-gray-200">
        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>
    <h3 class="text-xl font-bold text-gray-600 mb-2"><?php echo e(__('No results found')); ?></h3>
    <p class="text-gray-500"><?php echo e(__('Try different search terms or another area')); ?></p>
</div>
<?php endif; ?>
<?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views/businesses/_results.blade.php ENDPATH**/ ?>