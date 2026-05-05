@extends('layouts.manager')

@section('title', __('Edit Business'))
@section('page-title', __('Edit Business'))

@section('content')
<div class="mb-6">
    <a href="{{ route('manager.businesses.index') }}" class="text-gray-500 hover:text-brand-green flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        {{ __('Back to Businesses') }}
    </a>
</div>

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('manager.businesses.update', $business) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Business Name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="{{ old('name', $business->name) }}"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('English Name') }}</label>
                    <input type="text" name="english_name" value="{{ old('english_name', $business->english_name) }}"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Description') }}</label>
                <textarea name="description" rows="4"
                          class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors">{{ old('description', $business->description) }}</textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Category') }} <span class="text-red-500">*</span></label>
                    <select name="category_id" required
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors bg-white">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (old('category_id', $business->category_id) == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('District') }} <span class="text-red-500">*</span></label>
                    <select name="district_id" required
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors bg-white">
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ (old('district_id', $business->district_id) == $district->id) ? 'selected' : '' }}>{{ $district->name }} ({{ $district->governorate->name }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Sub Area') }}</label>
                <select name="sub_area_id"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors bg-white">
                    <option value="">{{ __('Select Sub Area') }}</option>
                    @foreach($subAreas as $subArea)
                        <option value="{{ $subArea->id }}" {{ (old('sub_area_id', $business->sub_area_id) == $subArea->id) ? 'selected' : '' }}>{{ $subArea->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Phone Numbers -->
            <div class="mb-6" x-data="{ phones: editPhones() }">
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Phone Numbers') }}</label>
                <template x-for="(phone, index) in phones" :key="index">
                    <div class="relative flex items-center gap-2 mb-2">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-brand-green font-extrabold text-lg select-none">09</span>
                        <input type="tel" :name="`phones[${index}]`" x-model="phone.value" maxlength="8"
                               class="w-full border-2 border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:border-brand-green focus:outline-none transition-colors bg-white text-gray-800 font-bold tracking-wider"
                               placeholder="12345678"
                               @input="phone.value = phone.value.replace(/[^0-9]/g, '').slice(0, 8);">
                        <button type="button" @click="phones.splice(index, 1)" class="shrink-0 bg-red-100 text-red-600 hover:bg-red-200 w-10 h-10 rounded-lg flex items-center justify-center transition" title="{{ __('Remove') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
                <button type="button" @click="phones.push({ value: '' })" class="mt-2 inline-flex items-center gap-2 text-sm font-bold text-brand-green hover:text-brand-green/80 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    {{ __('Add Phone Number') }}
                </button>
                <p class="text-xs text-gray-400 mt-2">{{ __('Optional') }}</p>
                <script>
                    function editPhones() {
                        const oldPhones = @json(old('phones', $business->phones ?? []));
                        if (oldPhones && oldPhones.length > 0) {
                            return oldPhones.map(v => ({ value: v.startsWith('09') ? v.substring(2) : v }));
                        }
                        const oldPhone = @json(old('phone', $business->phone));
                        if (oldPhone) {
                            return [{ value: oldPhone.startsWith('09') ? oldPhone.substring(2) : oldPhone }];
                        }
                        return [];
                    }
                </script>
            </div>

            <!-- Landlines -->
            <div class="mb-6" x-data="{ landlines: editLandlines() }">
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Landlines') }}</label>
                <template x-for="(landline, index) in landlines" :key="index">
                    <div class="relative flex items-center gap-2 mb-2">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-brand-green font-extrabold text-lg select-none">011</span>
                        <input type="tel" :name="`landlines[${index}]`" x-model="landline.value" maxlength="7"
                               class="w-full border-2 border-gray-200 rounded-xl py-3 pl-14 pr-4 focus:border-brand-green focus:outline-none transition-colors bg-white text-gray-800 font-bold tracking-wider"
                               placeholder="1234567"
                               @input="landline.value = landline.value.replace(/[^0-9]/g, '').slice(0, 7);">
                        <button type="button" @click="landlines.splice(index, 1)" class="shrink-0 bg-red-100 text-red-600 hover:bg-red-200 w-10 h-10 rounded-lg flex items-center justify-center transition" title="{{ __('Remove') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
                <button type="button" @click="landlines.push({ value: '' })" class="mt-2 inline-flex items-center gap-2 text-sm font-bold text-brand-green hover:text-brand-green/80 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    {{ __('Add Landline') }}
                </button>
                <p class="text-xs text-gray-400 mt-2">{{ __('Optional') }}</p>
                <script>
                    function editLandlines() {
                        const oldLandlines = @json(old('landlines', $business->landlines ?? []));
                        if (oldLandlines && oldLandlines.length > 0) {
                            return oldLandlines.map(v => ({ value: v.startsWith('011') ? v.substring(3) : v }));
                        }
                        const oldLandline = @json(old('landline', $business->landline));
                        if (oldLandline) {
                            return [{ value: oldLandline.startsWith('011') ? oldLandline.substring(3) : oldLandline }];
                        }
                        return [];
                    }
                </script>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Opening Time') }}</label>
                    <input type="time" name="opening_time" value="{{ old('opening_time', $business->opening_time ? substr($business->opening_time, 0, 5) : '') }}"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Closing Time') }}</label>
                    <input type="time" name="closing_time" value="{{ old('closing_time', $business->closing_time ? substr($business->closing_time, 0, 5) : '') }}"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Address') }}</label>
                <input type="text" name="address" value="{{ old('address', $business->address) }}"
                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors">
            </div>

            <!-- Business Hours -->
            <x-business-hours :business="$business" />

            <div class="mb-6">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $business->is_featured) ? 'checked' : '' }}
                           class="w-5 h-5 text-brand-green border-gray-300 rounded focus:ring-brand-green">
                    <span class="font-bold text-gray-700">{{ __('Featured Business') }}</span>
                </label>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-brand-green text-white font-bold py-3.5 rounded-xl hover:opacity-90 transition-all">
                    {{ __('Update Business') }}
                </button>
                <a href="{{ route('manager.businesses.index') }}" class="flex-1 bg-gray-100 text-gray-700 font-bold py-3.5 rounded-xl hover:bg-gray-200 transition-all text-center">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
