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

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Phone') }} <span class="text-red-500">*</span></label>
                <input type="text" name="phone" required value="{{ old('phone', $business->phone) }}"
                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-brand-green focus:outline-none transition-colors">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Landline') }}</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-brand-green font-extrabold text-lg select-none">011</span>
                    <input type="tel" name="landline_suffix" maxlength="7" value="{{ old('landline', $business->landline) }}"
                           class="w-full border-2 border-gray-200 rounded-xl pl-14 pr-4 py-3 focus:border-brand-green focus:outline-none transition-colors"
                           placeholder="1234567"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 7);">
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ __('Optional') }}</p>
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
