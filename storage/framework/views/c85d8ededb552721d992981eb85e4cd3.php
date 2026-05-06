<?php $__env->startSection('title', __('Category Rankings')); ?>
<?php $__env->startSection('page-title', __('Rankings for :category', ['category' => $category->name])); ?>

<?php $__env->startSection('content'); ?>
<?php
$rankingsData = collect(range(1,10))->mapWithKeys(fn($r) => [$r => (string)($rankings[$r]->business_id ?? '')])->all();
$businessesData = $businesses->map(fn($b) => ['id' => (string)$b->id, 'name' => $b->name])->values()->all();
?>
<script>
    window.rankingsApp = function(rankingsData, businessesData) {
        return {
            rankings: rankingsData,
            businesses: businessesData,
            get usedIds() {
                return Object.values(this.rankings)
                    .filter(function(v) { return v !== ''; })
                    .map(function(v) { return String(v); });
            },
            availableBusinesses: function(rank) {
                var currentId = this.rankings[rank] ? String(this.rankings[rank]) : '';

                return this.businesses.filter(function(business) {
                    var businessId = String(business.id);
                    return businessId === currentId || !this.usedIds.includes(businessId);
                }, this);
            },
            selectedName: function(rank) {
                var id = this.rankings[rank];
                if (!id) return null;
                var b = this.businesses.find(function(b) { return b.id === id; });
                return b ? b.name : null;
            }
        };
    };
</script>
<div class="space-y-6" x-data="rankingsApp(<?php echo \Illuminate\Support\Js::from($rankingsData)->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($businessesData)->toHtml() ?>)">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800"><?php echo e(__('Choose Top 10 Places')); ?></h3>
                <p class="text-gray-500 mt-1"><?php echo e(__('Select the businesses that will appear first in this category. The rest will be shown randomly.')); ?></p>
            </div>
            <a href="<?php echo e(route('manager.categories.index')); ?>" class="text-gray-500 hover:text-gray-700 font-bold text-sm">
                <?php echo e(__('Back to Categories')); ?>

            </a>
        </div>
    </div>

    <?php if($businesses->count() > 0): ?>
    <form method="POST" action="<?php echo e(route('manager.categories.rankings.update', $category)); ?>" class="space-y-6">
        <?php echo csrf_field(); ?>

        <!-- Rankings Grid -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h4 class="font-bold text-gray-700"><?php echo e(__('Ranked Places (1-10)')); ?></h4>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php for($rank = 1; $rank <= 10; $rank++): ?>
                <?php
                    $borderClass = match($rank) {
                        1 => 'border-yellow-400 bg-yellow-50',
                        2 => 'border-gray-300 bg-gray-50',
                        3 => 'border-orange-400 bg-orange-50',
                        4, 5 => 'border-blue-300 bg-blue-50',
                        6, 7 => 'border-purple-300 bg-purple-50',
                        default => 'border-teal-300 bg-teal-50',
                    };
                    $badgeClass = match($rank) {
                        1 => 'bg-yellow-500 text-white',
                        2 => 'bg-gray-500 text-white',
                        3 => 'bg-orange-500 text-white',
                        4, 5 => 'bg-blue-500 text-white',
                        6, 7 => 'bg-purple-500 text-white',
                        default => 'bg-teal-500 text-white',
                    };
                    $label = match($rank) {
                        1 => __('Gold'),
                        2 => __('Silver'),
                        3 => __('Bronze'),
                        4, 5 => __('Blue'),
                        6, 7 => __('Purple'),
                        default => __('Teal'),
                    };
                ?>
                <div class="border-2 rounded-xl p-4 <?php echo e($borderClass); ?>">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-8 h-8 rounded-full <?php echo e($badgeClass); ?> flex items-center justify-center text-sm font-bold"><?php echo e($rank); ?></span>
                        <span class="text-sm font-bold text-gray-700"><?php echo e($label); ?></span>
                    </div>
                    <select name="rankings[<?php echo e($rank); ?>]" x-model="rankings[<?php echo e($rank); ?>]" class="w-full border border-gray-200 rounded-lg py-2.5 px-3 text-sm focus:ring-brand-green focus:border-brand-green bg-white">
                        <option value=""><?php echo e(__('-- Select a place --')); ?></option>
                        <template x-for="business in availableBusinesses(<?php echo e($rank); ?>)" :key="business.id">
                            <option :value="business.id" x-text="business.name"></option>
                        </template>
                    </select>
                    <p x-show="selectedName(<?php echo e($rank); ?>)" class="text-xs text-gray-500 mt-2" x-cloak>
                        <?php echo e(__('Currently selected')); ?>: <span class="font-bold" x-text="selectedName(<?php echo e($rank); ?>)"></span>
                    </p>
                    <?php if(isset($rankings[$rank]) && $rankings[$rank]->expires_at): ?>
                        <?php
                            $isExpired = $rankings[$rank]->expires_at->isPast();
                            $daysLeft = max(0, now()->diffInDays($rankings[$rank]->expires_at, false));
                        ?>
                        <p class="text-xs mt-2 <?php echo e($isExpired ? 'text-red-600' : 'text-gray-500'); ?>">
                            <?php echo e(__('Ranking timer')); ?>:
                            <?php if($isExpired): ?>
                                <span class="font-bold"><?php echo e(__('Expired')); ?></span>
                            <?php else: ?>
                                <span class="font-bold"><?php echo e(__(':days days left', ['days' => $daysLeft])); ?></span>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>

                    <?php if(isset($rankings[$rank])): ?>
                        <div class="mt-3 flex items-center gap-2">
                            <button
                                type="button"
                                onclick="if(confirm('<?php echo e(__('Are you sure?')); ?>')) document.getElementById('remove-ranking-<?php echo e($rankings[$rank]->id); ?>').submit();"
                                class="bg-red-100 text-red-700 text-xs font-bold py-2 px-3 rounded-lg hover:bg-red-200 transition-all"
                            >
                                <?php echo e(__('Remove from rank')); ?>

                            </button>

                            <?php if($rankings[$rank]->expires_at && $rankings[$rank]->expires_at->isPast()): ?>
                                <button
                                    type="button"
                                    onclick="document.getElementById('extend-ranking-<?php echo e($rankings[$rank]->id); ?>').submit();"
                                    class="bg-brand-green text-white text-xs font-bold py-2 px-3 rounded-lg hover:opacity-90 transition-all"
                                >
                                    <?php echo e(__('Keep 14 more days')); ?>

                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-3">
            <button type="submit" class="bg-brand-green text-white font-bold py-3 px-8 rounded-xl hover:opacity-90 transition-all">
                <?php echo e(__('Save Rankings')); ?>

            </button>
            <a href="<?php echo e(route('manager.categories.index')); ?>" class="bg-gray-100 text-gray-700 font-bold py-3 px-6 rounded-xl hover:bg-gray-200 transition-all">
                <?php echo e(__('Cancel')); ?>

            </a>
        </div>
    </form>

    <?php $__currentLoopData = $rankings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ranking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <form id="remove-ranking-<?php echo e($ranking->id); ?>" method="POST" action="<?php echo e(route('manager.rankings.remove', $ranking)); ?>" class="hidden">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
        </form>

        <form id="extend-ranking-<?php echo e($ranking->id); ?>" method="POST" action="<?php echo e(route('manager.rankings.extend', $ranking)); ?>" class="hidden">
            <?php echo csrf_field(); ?>
        </form>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-600 mb-2"><?php echo e(__('No businesses found')); ?></h3>
        <p class="text-gray-500"><?php echo e(__('There are no approved businesses in this category yet.')); ?></p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manager', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views\manager\categories\rankings.blade.php ENDPATH**/ ?>