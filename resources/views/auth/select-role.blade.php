<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختر نوع الحساب - وين صار</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    @php
        $action = request('action', 'login');
        $route = $action === 'register' ? route('register') : route('login');
        $title = $action === 'register' ? 'إنشاء حساب جديد' : 'تسجيل الدخول';
    @endphp
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $title }}</h1>
                <p class="text-gray-500">اختر نوع الحساب للمتابعة</p>
            </div>

            <div class="space-y-4">
                <a href="{{ $route }}?role=user" 
                   class="flex items-center gap-4 p-6 border-2 border-gray-100 rounded-xl hover:border-brand-green hover:bg-brand-green/5 transition-all group">
                    <div class="w-14 h-14 bg-brand-green/10 rounded-full flex items-center justify-center group-hover:bg-brand-green/20">
                        <svg class="w-7 h-7 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800 text-lg">مستخدم عادي</h3>
                        <p class="text-sm text-gray-500">ابحث عن المحال واطلع على التقييمات</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <a href="{{ $route }}?role=owner" 
                   class="flex items-center gap-4 p-6 border-2 border-gray-100 rounded-xl hover:border-brand-green hover:bg-brand-green/5 transition-all group">
                    <div class="w-14 h-14 bg-brand-green/10 rounded-full flex items-center justify-center group-hover:bg-brand-green/20">
                        <svg class="w-7 h-7 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800 text-lg">صاحب نشاط تجاري</h3>
                        <p class="text-sm text-gray-500">أضف منشأتك وإدارة بياناتها</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="mt-6 text-center">
                @if($action === 'register')
                    <p class="text-gray-500 text-sm">
                        لديك حساب بالفعل؟ 
                        <a href="{{ route('select-role') }}" class="text-brand-green font-bold hover:underline">تسجيل الدخول</a>
                    </p>
                @else
                    <p class="text-gray-500 text-sm">
                        ليس لديك حساب؟ 
                        <a href="{{ route('select-role') }}?action=register" class="text-brand-green font-bold hover:underline">إنشاء حساب</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
