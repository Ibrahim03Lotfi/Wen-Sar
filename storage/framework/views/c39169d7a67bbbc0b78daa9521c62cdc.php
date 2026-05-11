<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<div class="bg-brand-green py-10 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-2xl md:text-4xl font-bold text-white mb-2">المفضلة</h1>
        <p class="text-white/80 text-sm md:text-base">الأماكن التي أضفتها إلى مفضلتك</p>
    </div>
</div>

<!-- Favorites Grid -->
<div class="max-w-7xl mx-auto px-4 py-8 md:py-12 sm:px-6 lg:px-8">
    <?php if($favorites->count() > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            <?php $__currentLoopData = $favorites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $business): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl md:rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow border border-gray-100 group">
                <div class="h-40 md:h-48 bg-gray-200 relative">
                    <?php if($business->logo): ?>
                        <img src="<?php echo e(asset('storage/' . $business->logo)); ?>" alt="<?php echo e($business->name); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php elseif($business->images && count($business->images) > 0): ?>
                        <img src="<?php echo e(asset('storage/' . $business->images[0])); ?>" alt="<?php echo e($business->name); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                        <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto=format&fit=crop" alt="<?php echo e($business->name); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php endif; ?>
                    <!-- Remove from favorites button -->
                    <form action="<?php echo e(route('favorites.destroy', $business)); ?>" method="POST" class="absolute top-3 left-3">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-lg transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </form>
                </div>
                <div class="p-4 md:p-6">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-bold text-brand-green px-2 py-1 bg-brand-green/5 rounded truncate max-w-[50%]"><?php echo e($business->category->name); ?></span>
                        <div class="flex items-center text-yellow-400">
                            <span class="text-xs font-bold text-gray-700 ml-1"><?php echo e(number_format($business->averageRating(), 1)); ?></span>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-1 md:mb-2 truncate"><?php echo e($business->name); ?></h3>
                    <div class="flex items-center text-gray-500 text-xs md:text-sm mb-3 md:mb-4">
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="truncate"><?php echo e($business->subArea?->name ?? 'غير محدد'); ?></span>
                    </div>
                    <a href="<?php echo e(route('business.show', $business)); ?>" class="block w-full text-center bg-brand-green text-white font-bold py-2 rounded-lg hover:opacity-90 transition-all">
                        عرض التفاصيل
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="text-center py-16 md:py-24">
            <div class="w-24 h-24 md:w-32 md:h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 md:w-16 md:h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">لا توجد مفضلات</h2>
            <p class="text-gray-500 mb-6">لم تقم بإضافة أي أماكن إلى مفضلاتك بعد</p>
            <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center gap-2 bg-brand-green text-white font-bold px-6 py-3 rounded-xl hover:opacity-90 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                استكشف الأماكن
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views/favorites/index.blade.php ENDPATH**/ ?>