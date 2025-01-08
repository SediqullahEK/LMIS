<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-...." crossorigin="anonymous" />

    <title>Login</title>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100" dir="rtl">
    <!-- Full Background Gradient -->
    <div class="fixed inset-0 bg-gradient-to-b from-gray-900 via-gray-900 to-[#2C3E50] z-0"></div>

    <!-- Content Wrapper -->
    <div
        class="relative z-10 flex flex-col md:flex-row items-center justify-between max-w-7xl mx-auto px-4 md:px-8 py-8 space-y-8 md:space-y-0">
        <div id="loader" class="hidden">
            <x-loader />
        </div>
        <!-- Form Section -->
        <div class="w-full md:w-1/3 bg-white rounded-lg shadow-lg m-8 p-8 md:p-8">
            <div class="text-center mb-6">
                <img src="{{ asset('storage/system_images/logo.webp') }}" alt="Logo"
                    class="w-16 h-16 mx-auto mb-4 md:w-20 md:h-20">
                <h1 class="text-lg md:text-2xl font-bold text-gray-700">امارت اسلامی افغانستان</h1>
                <h2 class="text-sm md:text-xl text-gray-600">وزارت معادن و پترولیم</h2>
                <h2 class="text-sm md:text-lg text-gray-600">معینیت سروی جیولوژی</h2>
            </div>

            <!-- Session Status and Validation Errors -->
            @if (session('status'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif
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
                <div class="mb-6">
                    <label for="user_name" class="block text-sm font-medium text-gray-700 mb-2">نام کاربری</label>
                    <input id="user_name" name="user_name" type="text" placeholder="نام کاربری تان را وارد کنید"
                        class="bg-[rgba(246,191,12,0.2)] border border-[rgba(246,191,12,0.8)] text-sky-900 text-sm rounded-lg  block w-full px-2.5 py-3 transition duration-200 ease-in-out hover:bg-[rgba(246,191,12,0.3)] focus:outline-none"
                        required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-sky-900 mb-2">رمز
                        عبور</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="رمز عبور تان را وارد کنید"
                            class="bg-[rgba(246,191,12,0.2)] border border-[rgba(246,191,12,0.8)] text-sky-900 text-sm rounded-lg  block w-full px-2.5 py-3 transition duration-200 ease-in-out hover:bg-[rgba(246,191,12,0.3)] focus:outline-none"
                            required>
                        <button id="PasswordToggleBtn" type="button" aria-label="Toggle password visibility"
                            class="hidden absolute inset-y-0 left-4 flex items-center text-gray-600 hover:text-sky-500 transition duration-200 ease-in-out">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-6 mt-2">
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:underline">
                        آیا رمز تان را فراموش کرده اید؟
                    </a>
                </div>
                <div>
                    <button id="submitBtn" type="submit"
                        class="w-full px-4 py-3 mt-2 mb-4 text-white bg-[#D4AF37] hover:bg-[#F6BF0C] rounded-lg focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50">
                        ورود به سیستم
                    </button>
                </div>
            </form>
        </div>

        <!-- Image Section -->
        <div class="flex flex-col items-center w-full md:w-2/3 mr-4">
            <img src="{{ asset('storage/system_images/loginImage.png') }}" alt="Login Image"
                class="w-full md:w-[80%] h-auto mx-auto mb-4">
            <div class="text-center mt-4">
                <h2 class="text-lg md:text-xl text-gray-200">سیستم مدیریت جواز ها</h2>
                <h5 class="text-sm md:text-lg text-gray-400 mt-2 leading-relaxed">
                    این سیستم برای مدیریت و صدور جواز های سنگ های قیمتی، نیمه قیمتی، زر شویی، پروسس مواد ساختمانی و
                    ایجاد فابریکه ها برای پروسس ایجاد شده است.
                </h5>
            </div>
        </div>
    </div>
    <script>
        const submitBtn = document.querySelector('#submitBtn');
        const loader = document.querySelector('#loader');
        submitBtn.addEventListener('click', function() {
            loader.classList.remove('hidden');
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const field = document.querySelector('#password');
            const toggleBtn = document.querySelector('#PasswordToggleBtn');

            function updateToggleButtonVisibility() {
                if (field.value) {
                    toggleBtn.classList.remove('hidden');
                } else {
                    toggleBtn.classList.add('hidden');
                }
            }

            toggleBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const isPassword = field.getAttribute('type') === 'password';
                field.setAttribute('type', isPassword ? 'text' : 'password');

                // Toggle FontAwesome icon
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');

                // Update accessibility attributes
                this.setAttribute('aria-pressed', !isPassword);
                this.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
            });

            field.addEventListener('input', updateToggleButtonVisibility);

            // Initial visibility setup
            updateToggleButtonVisibility();
        });
    </script>
</body>

</html>
