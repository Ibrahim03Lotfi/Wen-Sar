@extends('layouts.manager')

@section('title', __('Dashboard'))
@section('page-title', __('Dashboard'))

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-8">
    <a href="{{ route('manager.approvals.pending') }}" class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-red-500 hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">{{ __('Pending') }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_businesses'] }}</p>
            </div>
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </a>

    <a href="{{ route('manager.businesses.index') }}" class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500 hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">{{ __('Approved') }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['approved_businesses'] }}</p>
            </div>
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </a>

    <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">{{ __('Owners') }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_owners'] }}</p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">{{ __('Governorates') }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_governorates'] }}</p>
            </div>
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-indigo-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">{{ __('Districts') }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_districts'] }}</p>
            </div>
            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-pink-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">{{ __('Sub Areas') }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_sub_areas'] }}</p>
            </div>
            <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Recent Pending & Expiring -->
<div class="grid lg:grid-cols-2 gap-6">
    <!-- Recent Pending -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ __('Recent Pending Approvals') }}
            </h3>
            <a href="{{ route('manager.approvals.pending') }}" class="text-sm text-brand-green hover:underline">{{ __('View All') }}</a>
        </div>
        <div class="p-4">
            @forelse($recentPending as $business)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            @if($business->logo)
                                <img src="{{ asset('storage/' . $business->logo) }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm">{{ $business->name }}</p>
                            <p class="text-xs text-gray-500">{{ $business->category->name }} • {{ $business->district->name }}</p>
                        </div>
                    </div>
                    <a href="{{ route('manager.approvals.show', $business) }}" class="text-sm text-brand-green hover:underline">{{ __('Review') }}</a>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">{{ __('No pending approvals') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
