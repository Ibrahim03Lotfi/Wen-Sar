@extends('layouts.manager')

@section('title', __('Pending Approvals'))
@section('page-title', __('Pending Business Approvals'))

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <p class="text-gray-500">{{ __('Review and approve new business registrations') }}</p>
    </div>

    @if($pendingBusinesses->count() > 0)
        <div class="divide-y divide-gray-100">
            @foreach($pendingBusinesses as $business)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Image -->
                    <div class="w-full lg:w-48 h-32 bg-gray-100 rounded-xl flex-shrink-0 overflow-hidden">
                        @if($business->logo)
                            <img src="{{ asset('storage/' . $business->logo) }}" class="w-full h-full object-cover">
                        @elseif($business->images && count($business->images) > 0)
                            <img src="{{ asset('storage/' . $business->images[0]) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="flex-1">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $business->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $business->english_name }}</p>
                                
                                <div class="flex flex-wrap gap-2 mt-3">
                                    <span class="bg-brand-green/10 text-brand-green text-xs font-bold px-3 py-1 rounded-lg">{{ $business->category?->name ?? '-' }}</span>
                                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-lg">{{ $business->district?->name ?? '-' }}</span>
                                    @if($business->subArea)
                                        <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-lg">{{ $business->subArea->name }}</span>
                                    @endif
                                </div>

                                <div class="mt-4 space-y-1 text-sm text-gray-600">
                                    <p><span class="font-bold">{{ __('Owner') }}:</span> {{ $business->owner?->name ?? __('Unknown') }}</p>
                                    @php $phones = $business->allPhones(); @endphp
                                    @if(count($phones) > 0)
                                        <p><span class="font-bold">{{ __('Phone') }}:</span> {{ implode(', ', $phones) }}</p>
                                    @endif
                                    @if($business->address)
                                        <p><span class="font-bold">{{ __('Address') }}:</span> {{ $business->address }}</p>
                                    @endif
                                    @if($business->opening_time && $business->closing_time)
                                        <p><span class="font-bold">{{ __('Working Hours') }}:</span> {{ substr($business->opening_time, 0, 5) }} - {{ substr($business->closing_time, 0, 5) }}</p>
                                    @endif
                                </div>

                                @if($business->description)
                                    <p class="mt-3 text-sm text-gray-600 line-clamp-2">{{ $business->description }}</p>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col gap-3 min-w-[200px]">
                                <a href="{{ route('manager.approvals.show', $business) }}" class="text-center bg-gray-100 text-gray-700 font-bold py-2.5 rounded-xl hover:bg-gray-200 transition-all">
                                    {{ __('View Details') }}
                                </a>

                                <form method="POST" action="{{ route('manager.approvals.approve', $business) }}" class="space-y-3">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-500 text-white font-bold py-2.5 rounded-xl hover:bg-green-600 transition-all flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Approve') }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('manager.approvals.reject', $business) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-red-500 text-white font-bold py-2.5 rounded-xl hover:bg-red-600 transition-all flex items-center justify-center gap-2" onclick="return confirm('{{ __('Are you sure you want to reject this business?') }}')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        {{ __('Reject') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $pendingBusinesses->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-600 mb-2">{{ __('No Pending Approvals') }}</h3>
            <p class="text-gray-500">{{ __('All businesses have been reviewed') }}</p>
        </div>
    @endif
</div>
@endsection
