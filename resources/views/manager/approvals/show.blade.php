@extends('layouts.manager')

@section('title', __('Review Business'))
@section('page-title', __('Review Business Application'))

@section('content')
<div class="mb-6">
    <a href="{{ route('manager.approvals.pending') }}" class="text-gray-500 hover:text-brand-green flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        {{ __('Back to Pending') }}
    </a>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Business Details -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="h-64 bg-gray-100 relative">
                @if($business->logo)
                    <img src="{{ asset('storage/' . $business->logo) }}" class="w-full h-full object-cover">
                @elseif($business->images && count($business->images) > 0)
                    <img src="{{ asset('storage/' . $business->images[0]) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                        <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                @endif
                <div class="absolute top-4 left-4 bg-yellow-500 text-white text-sm font-bold px-4 py-2 rounded-lg">
                    {{ __('Pending Approval') }}
                </div>
            </div>
            
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $business->name }}</h2>
                <p class="text-gray-500">{{ $business->english_name }}</p>

                <div class="flex flex-wrap gap-2 mt-4 mb-6">
                    <span class="bg-brand-green/10 text-brand-green text-sm font-bold px-3 py-1 rounded-lg">{{ $business->category->name }}</span>
                    <span class="bg-gray-100 text-gray-600 text-sm font-bold px-3 py-1 rounded-lg">{{ $business->district->name }}</span>
                    @if($business->subArea)
                        <span class="bg-gray-100 text-gray-600 text-sm font-bold px-3 py-1 rounded-lg">{{ $business->subArea->name }}</span>
                    @endif
                </div>

                @if($business->description)
                    <div class="mb-6">
                        <h4 class="font-bold text-gray-700 mb-2">{{ __('Description') }}</h4>
                        <p class="text-gray-600 bg-gray-50 rounded-xl p-4">{{ $business->description }}</p>
                    </div>
                @endif

                <!-- Contact Info -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h4 class="font-bold text-gray-700 mb-3">{{ __('Contact Information') }}</h4>
                        <div class="space-y-2 text-sm">
                            @php $phones = $business->allPhones(); @endphp
                            @if(count($phones) > 0)
                                <p><span class="text-gray-500">{{ __('Phone') }}:</span> <span class="font-bold">{{ implode(', ', $phones) }}</span></p>
                            @endif
                            @if($business->address)
                                <p><span class="text-gray-500">{{ __('Address') }}:</span> {{ $business->address }}</p>
                            @endif
                            @if($business->opening_time && $business->closing_time)
                                <p><span class="text-gray-500">{{ __('Working Hours') }}:</span> {{ substr($business->opening_time, 0, 5) }} - {{ substr($business->closing_time, 0, 5) }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4">
                        <h4 class="font-bold text-gray-700 mb-3">{{ __('Application Details') }}</h4>
                        <div class="space-y-2 text-sm">
                            <p><span class="text-gray-500">{{ __('Submitted On') }}:</span> <span class="font-bold">{{ $business->created_at->format('Y-m-d H:i') }}</span></p>
                            <p><span class="text-gray-500">{{ __('Category') }}:</span> {{ $business->category->name }}</p>
                            <p><span class="text-gray-500">{{ __('District') }}:</span> {{ $business->district->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery -->
        @if($business->images && count($business->images) > 0)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="font-bold text-gray-700 mb-4">{{ __('Image Gallery') }} ({{ count($business->images) }})</h4>
            <div class="grid grid-cols-4 gap-4">
                @foreach($business->images as $image)
                    <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                        <img src="{{ asset('storage/' . $image) }}" class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Owner Info -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="font-bold text-gray-700 mb-4">{{ __('Business Owner') }}</h4>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-brand-green rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-lg">{{ substr($business->owner->name, 0, 1) }}</span>
                </div>
                <div>
                    <p class="font-bold text-gray-800">{{ $business->owner->name }}</p>
                    <p class="text-sm text-gray-500">{{ $business->owner->email }}</p>
                    <p class="text-xs text-gray-400">{{ __('Member since') }} {{ $business->owner->created_at->format('Y-m-d') }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-sm text-gray-600">
                    <span class="font-bold">{{ __('Total Businesses') }}:</span> 
                    {{ $business->owner->businesses->count() }}
                </p>
            </div>
        </div>

        <!-- Approval Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="font-bold text-gray-700 mb-4">{{ __('Approval Decision') }}</h4>
            
            <form method="POST" action="{{ route('manager.approvals.approve', $business) }}" class="mb-4">
                @csrf
                <button type="submit" class="w-full bg-green-500 text-white font-bold py-3.5 rounded-xl hover:bg-green-600 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('Approve Business') }}
                </button>
            </form>

            <form method="POST" action="{{ route('manager.approvals.reject', $business) }}">
                @csrf
                <button type="submit" class="w-full bg-red-500 text-white font-bold py-3.5 rounded-xl hover:bg-red-600 transition-all flex items-center justify-center gap-2" onclick="return confirm('{{ __('Are you sure you want to reject this business?') }}')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ __('Reject Business') }}
                </button>
            </form>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="font-bold text-gray-700 mb-4">{{ __('Quick Stats') }}</h4>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('Current Status') }}</span>
                    <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-2 py-1 rounded">{{ __('Pending') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('Submitted') }}</span>
                    <span class="font-bold">{{ $business->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
