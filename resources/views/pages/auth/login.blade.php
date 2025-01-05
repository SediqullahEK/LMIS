<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css') <!-- Include compiled Tailwind CSS -->
</head>

<body class="relative flex items-start justify-start min-h-screen bg-gray-100" dir="rtl">
    <!-- Full Background Gradient -->
    <div class="fixed top-0 left-0 w-full h-full bg-gradient-to-b from-gray-900 via-gray-900 to-[#2C3E50] z-0"></div>

    <!-- Content Wrapper -->
    <div
        class="relative z-10 flex flex-col md:flex-row items-start justify-start max-w-7xl mx-auto p-8 space-y-4 md:space-y-0 md:space-x-8">
        <!-- Form Section -->
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-10 md:ml-8">
            <div class="text-center mb-6">
                <img src="/system_images/logo.webp" alt="Logo" class="w-24 h-24 mx-auto mb-4">
                <h1 class="text-2xl font-bold text-gray-700">امارت اسلامی افغانستان</h1>
                <h2 class="text-xl text-gray-600">وزارت معادن و پترولیم</h2>
                <h2 class="text-lg text-gray-600">معینیت سروی جیولوژی</h2>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-4">
                    <ul class="list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Username Field -->
                <div class="mb-6">
                    <label for="user_name" class="block text-sm font-medium text-gray-700 mb-2">نام کاربری</label>
                    <input id="user_name" name="user_name" type="text" required autofocus
                        class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">رمز عبور</label>
                    <input id="password" name="password" type="password" required
                        class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <!-- Forgot Password Link -->
                <div class="flex items-center justify-between mb-6">
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:underline">
                        آیا رمز تان را فراموش کرده اید؟
                    </a>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full px-4 py-3 text-white bg-[#D4AF37] hover:bg-[#F6BF0C] rounded-lg focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50">
                        ورود به سیستم
                    </button>
                </div>
            </form>
        </div>

        <!-- Image Section -->
        <div class="flex flex-col items-center w-full md:w-auto">
            <img src="/system_images/loginImage.png" alt="Login Illustration"
                class="object-contain w-300 h- max-w-full">
            <div class="text-center mt-4">
                <h2 class="text-xl text-gray-200">سیستم مدیریت جواز ها</h2>
                <h5 class="text-lg text-gray-400 mt-2">این سیستم برای مدیریت و صدور جواز های سنگ های قیمتی، نیمه قیمتی،
                    زر شویی، پروسس مواد ساختمانی
                    وایجاد فابریکه ها برای پروسس ایجاد شده است
                </h5>
            </div>
        </div>
    </div>
</body>

</html>
