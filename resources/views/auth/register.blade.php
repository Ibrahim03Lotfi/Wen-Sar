@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative bg-brand-green min-h-[calc(100vh-80px)] flex items-center justify-center py-12">
    <div class="absolute inset-0 opacity-10">
        <img src="https://images.unsplash.com/photo-1549136365-5c1a1795f57a?q=80&w=2070&auto=format&fit=crop" alt="Background" class="w-full h-full object-cover">
    </div>

    <div class="relative w-full max-w-md mx-4">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">إنشاء حساب جديد</h1>
                <p class="text-gray-500">انضم إلى وين صار</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">الاسم الكامل</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="w-full border-gray-200 rounded-xl focus:ring-brand-green focus:border-brand-green bg-gray-50 py-3 px-4">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">البريد الإلكتروني</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                           class="w-full border-gray-200 rounded-xl focus:ring-brand-green focus:border-brand-green bg-gray-50 py-3 px-4">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-2">كلمة المرور</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="w-full border-gray-200 rounded-xl focus:ring-brand-green focus:border-brand-green bg-gray-50 py-3 px-4">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">تأكيد كلمة المرور</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="w-full border-gray-200 rounded-xl focus:ring-brand-green focus:border-brand-green bg-gray-50 py-3 px-4">
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-brand-green text-white font-bold py-3.5 rounded-xl hover:opacity-90 transition duration-300 shadow-lg">
                    إنشاء الحساب
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-500 text-sm">
                    لديك حساب بالفعل؟ 
                    <a href="{{ route('login') }}" class="text-brand-green font-bold hover:underline">تسجيل الدخول</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
