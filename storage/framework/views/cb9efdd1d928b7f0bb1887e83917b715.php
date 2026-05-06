<?php $__env->startSection('content'); ?>
<div class="bg-brand-white min-h-screen pb-12">
    <div class="bg-brand-green py-12 shadow-inner relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">إدارة منشآتك</h1>
                    <p class="text-brand-white/70 font-bold">هنا يمكنك التحكم ببيانات محالك التجارية وتحديثها</p>
                </div>
                <a href="<?php echo e(route('owner.businesses.create')); ?>" class="bg-orange-500 text-white font-bold py-3.5 px-8 rounded-2xl hover:bg-orange-600 transition shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    إضافة منشأة جديدة
                </a>
            </div>
        </div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
        <?php if(session('success')): ?>
            <div class="bg-green-50 border border-green-100 text-green-700 px-6 py-4 rounded-2xl mb-8 font-bold flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php $__empty_1 = true; $__currentLoopData = $businesses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $business): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all border border-gray-100 group">
                <div class="h-48 bg-gray-100 relative">
                    <?php if($business->logo): ?>
                        <img src="<?php echo e(asset('storage/' . $business->logo)); ?>" alt="<?php echo e($business->name); ?>" class="w-full h-full object-cover">
                    <?php elseif($business->images && count($business->images) > 0): ?>
                        <img src="<?php echo e(asset('storage/' . $business->images[0])); ?>" alt="<?php echo e($business->name); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto=format&fit=crop" alt="<?php echo e($business->name); ?>" class="w-full h-full object-cover">
                    <?php endif; ?>
                    <div class="absolute top-4 right-4 bg-brand-green text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg">
                        <?php echo e($business->category->name); ?>

                    </div>
                    <!-- Status Badge -->
                    <div class="absolute top-4 left-4 shadow-lg">
                        <?php if($business->status === 'pending'): ?>
                            <span class="bg-yellow-500 text-white text-[10px] font-bold px-3 py-1 rounded-full"><?php echo e(__('Pending Approval')); ?></span>
                        <?php elseif($business->status === 'approved'): ?>
                            <span class="bg-green-500 text-white text-[10px] font-bold px-3 py-1 rounded-full"><?php echo e(__('Published')); ?></span>
                        <?php elseif($business->status === 'rejected'): ?>
                            <span class="bg-red-500 text-white text-[10px] font-bold px-3 py-1 rounded-full"><?php echo e(__('Rejected')); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4"><?php echo e($business->name); ?></h3>
                    <div class="flex items-center text-gray-400 text-sm gap-4 mb-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <?php echo e($business->subArea?->name ?? __('Not specified')); ?>

                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <?php echo e($business->views_count); ?>

                        </div>
                    </div>
                    
                    <div class="flex gap-2 pt-4 border-t border-gray-50">
                        <a href="<?php echo e(route('owner.businesses.edit', $business->id)); ?>" class="flex-1 bg-brand-green/5 text-brand-green font-bold text-center py-3 rounded-xl hover:bg-brand-green hover:text-white transition-all">
                            تعديل البيانات
                        </a>
                        <a href="<?php echo e(route('business.show', $business->id)); ?>" class="p-3 bg-gray-50 text-gray-400 rounded-xl hover:bg-brand-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full text-center py-24 bg-white rounded-[2.5rem] shadow-sm border border-dashed border-gray-200">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-400">لا تملك أي منشآت مسجلة حالياً</h3>
                <p class="text-gray-400 mt-2 mb-8">ابدأ بإضافة أول منشأة لك لتظهر في الدليل</p>
                <a href="<?php echo e(route('owner.businesses.create')); ?>" class="bg-brand-green text-white font-bold py-4 px-10 rounded-2xl hover:opacity-90 transition-all shadow-xl shadow-brand-green/20">
                    أضف منشأتك الآن
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views\owner\businesses\index.blade.php ENDPATH**/ ?>