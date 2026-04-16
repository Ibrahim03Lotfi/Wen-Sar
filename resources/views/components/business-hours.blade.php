@php
$days = [
    'saturday' => 'السبت',
    'sunday' => 'الأحد',
    'monday' => 'الاثنين',
    'tuesday' => 'الثلاثاء',
    'wednesday' => 'الأربعاء',
    'thursday' => 'الخميس',
    'friday' => 'الجمعة',
];

// Get existing values if editing
$businessHours = $business->business_hours ?? old('business_hours', []);
$regularOpen = $businessHours['regular']['open'] ?? old('opening_time', '09:00');
$regularClose = $businessHours['regular']['close'] ?? old('closing_time', '19:00');
$closedDays = $businessHours['closed_days'] ?? old('closed_days', []);
$overrides = $businessHours['overrides'] ?? old('overrides', []);
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" x-data="{
    hasOverrides: {{ !empty($overrides) ? 'true' : 'false' }},
    overrides: {{ json_encode($overrides) }},
    addOverride(day) {
        if (!this.overrides[day]) {
            this.overrides[day] = { open: '09:00', close: '22:00' };
        }
    },
    removeOverride(day) {
        delete this.overrides[day];
        this.overrides = {...this.overrides};
    }
}">
    <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
        <h2 class="font-bold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            أوقات العمل
        </h2>
    </div>
    <div class="p-6 space-y-6">
        <!-- Regular Hours -->
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">أوقات العمل الاعتيادية</label>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">وقت الفتح</label>
                    <input type="time" name="opening_time" value="{{ $regularOpen }}" required
                           class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">وقت الإغلاق</label>
                    <input type="time" name="closing_time" value="{{ $regularClose }}" required
                           class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800">
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-1">هذه هي أوقات العمل الافتراضية لجميع الأيام</p>
        </div>

        <!-- Closed Days -->
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-3">أيام الإغلاق (اختياري)</label>
            <p class="text-xs text-gray-500 mb-3">اختر الأيام التي يكون فيها المحل مغلقاً</p>
            <div class="flex flex-wrap gap-3">
                @foreach($days as $key => $label)
                    <label class="inline-flex items-center gap-2 cursor-pointer bg-gray-50 px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                        <input type="checkbox" name="closed_days[]" value="{{ $key }}"
                               {{ in_array($key, (array)$closedDays) ? 'checked' : '' }}
                               class="w-4 h-4 text-brand-green border-gray-300 rounded focus:ring-brand-green">
                        <span class="text-sm text-gray-700">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Day-specific Overrides -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <label class="block text-sm font-bold text-gray-700">أوقات خاصة لأيام محددة (اختياري)</label>
                <button type="button" @click="hasOverrides = !hasOverrides"
                        class="text-sm text-brand-green hover:text-brand-dark font-medium">
                    <span x-show="!hasOverrides">+ إضافة أوقات خاصة</span>
                    <span x-show="hasOverrides">- إخفاء الأوقات الخاصة</span>
                </button>
            </div>

            <div x-show="hasOverrides" x-transition class="space-y-3">
                <p class="text-xs text-gray-500 mb-3">اختر أياماً معينة لها أوقات عمل مختلفة</p>

                <!-- Day selector for adding new override -->
                <div class="flex gap-2 items-end">
                    <div class="flex-1">
                        <label class="block text-xs text-gray-500 mb-1">اختر يوماً</label>
                        <select x-model="selectedDay" @change="if($event.target.value) { addOverride($event.target.value); $event.target.value = ''; }"
                                class="w-full border-gray-300 rounded-lg py-2 px-3 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-sm">
                            <option value="">اختر يوماً لإضافة أوقات خاصة...</option>
                            @foreach($days as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Override entries -->
                <template x-for="(times, day) in overrides" :key="day">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-medium text-gray-800" x-text="{
                                'saturday': 'السبت',
                                'sunday': 'الأحد',
                                'monday': 'الاثنين',
                                'tuesday': 'الثلاثاء',
                                'wednesday': 'الأربعاء',
                                'thursday': 'الخميس',
                                'friday': 'الجمعة'
                            }[day]"></span>
                            <button type="button" @click="removeOverride(day)"
                                    class="text-red-500 hover:text-red-700 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">وقت الفتح</label>
                                <input type="time" :name="`overrides[${day}][open]`" x-model="times.open"
                                       class="w-full border-gray-300 rounded-lg py-2 px-3 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">وقت الإغلاق</label>
                                <input type="time" :name="`overrides[${day}][close]`" x-model="times.close"
                                       class="w-full border-gray-300 rounded-lg py-2 px-3 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-sm">
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
