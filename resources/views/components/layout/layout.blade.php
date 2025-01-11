<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LMIS</title>

    @livewireStyles
    @stack('customCss')
    <link rel="stylesheet" href="{{ asset('assets/Latest_Persian_Datepicker/persian-datepicker.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-...." crossorigin="anonymous" />


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Style the restore prompt */
        #restoreFullscreenPrompt {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px;
            border-radius: 10px;
            z-index: 9999;
            text-align: center;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* General button styles */
        .prompt-button {
            padding: 10px 20px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        /* Style for "Yes" button */
        .yes-button {
            background-color: #28a745;
            /* Green */
            color: white;
        }

        .yes-button:hover {
            background-color: #218838;
            /* Darker green */
        }

        /* Style for "No" button */
        .no-button {
            background-color: #dc3545;
            /* Red */
            color: white;
        }

        .no-button:hover {
            background-color: #c82333;
            /* Darker red */
        }
    </style>

</head>

<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="fixed top-0 z-50 w-full bg-[#2C3E50] border-b border-gray-200">
        <div class="flex items-center justify-between px-4 py-3">
            <!-- Logo Section -->
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <img src="/storage/system_images/logo.webp" class="h-10" alt="Logo">
                </div>
                <button id="fullscreenButton" class="text-gray-500 hover:text-gray-800">
                    <i class="fa fa-expand" aria-hidden="true"></i>
                </button>
                <h1 class="text-xl text-white font-bold sm:hidden">LMIS</h1>
            </div>
            <h1 class="text-xl text-white font-bold hidden sm:block">سیستم مدیریت جواز ها</h1>

            <!-- Right Section: Toggle and User Info -->
            <div class="flex items-center space-x-4">
                <!-- Sidebar Toggle Button -->

                <livewire:user.profile-image />
                <button id="sidebarToggle" class="p-2 mr-4 text-gray-500 rounded-lg hover:bg-[#D4AF37] ">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2 4h16v2H2V4zm0 5h16v2H2V9zm0 5h16v2H2v-2z">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

    </nav>


    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed top-0 right-0 z-40 w-22 h-full pt-16 transition-transform translate-x-full bg-[#2C3E50] border-l border-gray-200 sm:translate-x-0"
        dir="rtl">

        <ul class="space-y-2 p-2">
            <li class="{{ request()->routeIs('dashboard') ? '!bg-[#D4AF37] text-gray-900  rounded-lg' : '' }}">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center p-2 hover:bg-[#D4AF37] hover:text-gray-900 rounded-lg {{ request()->routeIs('dashboard') ? 'text-gray-900' : 'text-white' }}">
                    <i class="fa fa-home"></i>
                    <p class="mr-3 hidden">داشبورد سیستم</p>

                </a>
            </li>
            <li x-data="{ open: {{ request()->routeIs('individuals') || request()->routeIs('companies') ? 'true' : 'false' }} }" class="group">
                <a href="#" @click.prevent="open = !open"
                    class="flex items-center p-2 text-sm hover:bg-[#D4AF37] hover:text-gray-900 rounded-lg {{ request()->routeIs('individuals') || request()->routeIs('companies') ? '!bg-[#D4AF37] text-gray-900' : 'text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                        <path d="M4.5 3.75a3 3 0 0 0-3 3v.75h21v-.75a3 3 0 0 0-3-3h-15Z" />
                        <path fill-rule="evenodd"
                            d="M22.5 9.75h-21v7.5a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3v-7.5Zm-18 3.75a.75.75 0 0 1 .75-.75h6a.75.75 0 0 1 0 1.5h-6a.75.75 0 0 1-.75-.75Zm.75 2.25a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="mr-3 hidden">متقاضیان</p>
                    <i class="mr-2 mt-1 fa {{ !(request()->routeIs('individuals') || request()->routeIs('companies')) ? 'text-gray-400' : '' }} "
                        :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </a>
                <!-- Submenu -->
                <ul x-show="open" x-transition class="mt-2 space-y-1 pl-6">
                    <li
                        class="group {{ request()->routeIs('individuals') ? '!bg-[#D4AF37] text-gray-900 rounded-lg' : '' }}">
                        <a href="{{ route('individuals') }}"
                            class="flex items-center p-2 text-sm hover:bg-[#D4AF37] hover:text-gray-900 rounded-lg {{ request()->routeIs('individuals') ? 'text-gray-900' : 'text-white' }}">
                            <i class="fa fa-user"></i>
                            <p class="mr-3 hidden">اشخاص</p>
                        </a>
                    </li>
                    <li
                        class="group {{ request()->routeIs('companies') ? '!bg-[#D4AF37] text-gray-900 rounded-lg' : '' }}">
                        <a href="{{ route('companies') }}"
                            class="flex items-center p-2 text-sm hover:bg-[#D4AF37] hover:text-gray-900 rounded-lg {{ request()->routeIs('companies') ? 'text-gray-900' : 'text-white' }}">
                            <i class="fa fa-building"></i>
                            <p class="mr-3 hidden">شرکت ها</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li x-data="{ psl: {{ request()->routeIs('ps_licenses') || request()->routeIs('ps_maktoobs') || request()->routeIs('ps_stones') ? 'true' : 'false' }} }" class="group">
                <a href="#" @click.prevent="psl = !psl"
                    class="flex items-center p-2 hover:bg-[#D4AF37] hover:text-gray-900 rounded-lg {{ request()->routeIs('ps_licenses') || request()->routeIs('ps_maktoobs') || request()->routeIs('ps_stones') ? '!bg-[#D4AF37] text-gray-900' : 'text-white' }}">
                    <i class="fa fa-gem ml-1"></i> / <i class="fa fa-diamond mr-1"></i>
                    <p class="mr-3 hidden">جواز سنگ های قیمتی/نیمه قیمتی</p>
                    <i class="ml-auto mr-2 fa" :class="psl ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </a>

                <ul x-show="psl" x-transition class="mt-2 space-y-1 pl-6">
                    <li
                        class="group {{ request()->routeIs('ps_maktoobs') ? '!bg-[#D4AF37] text-gray-900 rounded-lg' : '' }}">
                        <a href="{{ route('ps_maktoobs') }}"
                            class="flex items-center p-2 text-sm hover:bg-[#D4AF37] hover:text-gray-900 rounded-lg {{ request()->routeIs('ps_maktoobs') ? 'text-gray-900' : 'text-white' }}">
                            <i class="fa fa-file"></i>
                            <p class="mr-3 hidden">مکاتیب</p>
                        </a>
                    </li>
                    <li
                        class="group {{ request()->routeIs('ps_licenses') ? '!bg-[#D4AF37] text-gray-900 rounded-lg' : '' }}">
                        <a href="{{ route('ps_licenses') }}"
                            class="flex items-center p-2 text-sm hover:bg-[#D4AF37] hover:text-gray-900 rounded-lg {{ request()->routeIs('ps_licenses') ? 'text-gray-900' : 'text-white' }}">
                            <i class="fa-solid fa-address-card "></i>
                            <p class="mr-3 hidden">جواز ها</p>
                        </a>
                    </li>
                    <li
                        class="group {{ request()->routeIs('ps_stones') ? '!bg-[#D4AF37] text-gray-900 rounded-lg' : '' }}">
                        <a href="{{ route('ps_stones') }}"
                            class="flex items-center p-2 text-sm hover:bg-[#D4AF37] hover:text-gray-900 rounded-lg {{ request()->routeIs('ps_stones') ? 'text-gray-900' : 'text-white' }}">
                            <i class="fa-solid fa-stroopwafel"></i>


                            <p class="mr-3 hidden">سنگ ها</p>
                        </a>
                    </li>
                </ul>
            </li>


            <li>
                {{-- href="http://172.20.3.17:3030" target="_blank" rel="noopener noreferrer" --}}
                <a class="flex items-center p-2 text-white text-sm hover:bg-[#D4AF37] hover:text-gray-900 rounded-lg">
                    <i class="fa fa-mountain"></i>
                    <p class="mr-4 hidden"> جواز های حرفه یی</p>
                </a>
            </li>


            <li class="group">
                <a href="#"
                    class="flex items-center p-2 text-white hover:bg-[#D4AF37] hover:text-gray-900 rounded-lg">
                    <svg class="text-gray-100 w-[1.25rem] h-[1.25rem]  fill-current group-hover:text-gray-900"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 576 512"><!--!Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                        <path
                            d="M208 64a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zM9.8 214.8c5.1-12.2 19.1-18 31.4-12.9L60.7 210l22.9-38.1C99.9 144.6 129.3 128 161 128c51.4 0 97 32.9 113.3 81.7l34.6 103.7 79.3 33.1 34.2-45.6c6.4-8.5 16.6-13.3 27.2-12.8s20.3 6.4 25.8 15.5l96 160c5.9 9.9 6.1 22.2 .4 32.2s-16.3 16.2-27.8 16.2l-256 0c-11.1 0-21.4-5.7-27.2-15.2s-6.4-21.2-1.4-31.1l16-32c5.4-10.8 16.5-17.7 28.6-17.7l32 0 22.5-30L22.8 246.2c-12.2-5.1-18-19.1-12.9-31.4zm82.8 91.8l112 48c11.8 5 19.4 16.6 19.4 29.4l0 96c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-74.9-60.6-26-37 111c-5.6 16.8-23.7 25.8-40.5 20.2S-3.9 486.6 1.6 469.9l48-144 11-33 32 13.7z" />
                    </svg>
                    <p class="mr-3 hidden">جواز های مواد ساختمانی</p>
                </a>
            </li>
            <li class="group">
                <a href="#"
                    class="flex items-center p-2 text-white hover:bg-[#D4AF37] hover:text-gray-900 rounded-lg">
                    <svg class="text-gray-100 w-[1.25rem] h-[1.25rem]  fill-current group-hover:text-gray-900"
                        xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24">
                        <path
                            d="m17,11.5v-6.304l-7,5.804v8h-2V2h1V0H0v2h1v19c0,1.654,1.346,3,3,3h17c1.654,0,3-1.346,3-3V5.196l-7,6.304Zm-1,7.5h-3v-3h3v3Zm5,0h-3v-3h3v3Z" />
                    </svg>

                    <p class="mr-3 hidden">جواز پروسس برای فابریکه ها</p>
                </a>
            </li>
            <li
                class="group {{ request()->routeIs('activity_logs') ? '!bg-[#D4AF37] text-gray-900  rounded-lg' : '' }}">
                <a href="{{ route('activity_logs') }}"
                    class="flex items-center p-2 hover:bg-[#D4AF37] hover:text-gray-900 rounded-lg {{ request()->routeIs('activity_logs') ? 'text-gray-900' : 'text-white' }}">
                    <i class="fas fa-clipboard-list"></i>
                    <p class="mr-4 hidden">فعالیت ها</p>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Content -->
    <main id="content" class="pt-16 transition-all lg:mr-24">
        {{ $slot }}
    </main>


    @livewireScripts


    <script src="{{ asset('assets/jquery/dist/jquery.min.js') }}"></script>

    <script src="{{ asset('assets/Latest_Persian_Datepicker/persian-date.min.js') }}"></script>
    <script src="{{ asset('assets/Latest_Persian_Datepicker/persain-datepicker.js') }}"></script>
    @stack('customJs')
    <script>
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const toggleBtn = document.getElementById('sidebarToggle');

        toggleBtn.addEventListener('click', () => {
            // Toggle the sidebar visibility
            sidebar.classList.toggle('translate-x-full');

            // Check if screen size is desktop
            if (window.innerWidth >= 1024) {
                sidebar.classList.toggle('w-22'); // Toggle collapsed width of sidebar

                const links = sidebar.querySelectorAll('p');
                links.forEach(link => {
                    link.classList.toggle('hidden'); // Toggle text visibility
                });

                // Adjust content margin-right only in desktop view
                if (sidebar.classList.contains('w-22')) {
                    content.style.marginRight = '6rem'; // Reduced margin-right for collapsed state
                } else {
                    content.style.marginRight = '21rem'; // Original margin-right for expanded state
                }
            } else {
                if (sidebar.classList.contains('translate-x-full')) {
                    content.style.marginRight = '0'; // No margin when sidebar is hidden
                } else {
                    sidebar.classList.remove('w-22');
                    const links = sidebar.querySelectorAll('p');
                    links.forEach(link => {
                        link.classList.remove('hidden');
                    });
                }
            }
        });

        // Reset margin-right when resizing window
        window.addEventListener('resize', () => {
            z
            if (window.innerWidth < 1024) {
                content.style.marginRight = '0'; // No margin in mobile view
            } else if (!sidebar.classList.contains('w-22')) {
                content.style.marginRight = '21rem'; // Expanded sidebar margin for desktop
            } else {
                content.style.marginRight = '6rem'; // Collapsed sidebar margin for desktop
            }
        });
    </script>


    <script>
        const userMenuToggle = document.getElementById('userMenuToggle');
        const userMenuDropdown = document.getElementById('userMenuDropdown');

        userMenuToggle.addEventListener('click', () => {
            userMenuDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!userMenuDropdown.contains(event.target) && !userMenuToggle.contains(event.target)) {
                userMenuDropdown.classList.add('hidden');
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const button = document.getElementById('fullscreenButton');

            const isFullscreen = localStorage.getItem('isFullscreen') === 'true';

            if (document.fullscreenElement || isFullscreen) {
                button.innerHTML = '<i class="fa fa-compress" aria-hidden="true"></i>';
            } else {
                button.innerHTML = '<i class="fa fa-expand" aria-hidden="true"></i>';
            }

            if (isFullscreen && !document.fullscreenElement) {
                const restorePrompt = document.createElement('div');
                restorePrompt.id = 'restoreFullscreenPrompt';
                restorePrompt.innerHTML = `
            <p>آیا میخواهد دوباره صفحه را بزرگ نمایی کنید؟</p>
            <button id="restoreFullscreenButton" class="prompt-button yes-button">بلی</button>
            <button id="cancelRestoreButton" class="prompt-button no-button">خیر</button>
        `;
                document.body.appendChild(restorePrompt);

                document.getElementById('restoreFullscreenButton').addEventListener('click', () => {
                    document.documentElement.requestFullscreen().then(() => {
                        localStorage.setItem('isFullscreen', 'true');
                        document.body.removeChild(restorePrompt);
                    }).catch(err => {
                        console.error(`Failed to restore fullscreen: ${err.message}`);
                    });
                });

                document.getElementById('cancelRestoreButton').addEventListener('click', () => {
                    document.body.removeChild(restorePrompt);
                    localStorage.setItem('isFullscreen', 'false');
                });
            }

            button.addEventListener('click', () => {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen().then(() => {
                        button.innerHTML = '<i class="fa fa-compress" aria-hidden="true"></i>';
                        localStorage.setItem('isFullscreen', 'true');
                    }).catch(err => {
                        console.error(`Error entering fullscreen: ${err.message}`);
                    });
                } else {
                    document.exitFullscreen().then(() => {
                        button.innerHTML = '<i class="fa fa-expand" aria-hidden="true"></i>';
                        localStorage.setItem('isFullscreen', 'false');
                    }).catch(err => {
                        console.error(`Error exiting fullscreen: ${err.message}`);
                    });
                }
            });

            document.addEventListener('fullscreenchange', () => {
                if (!document.fullscreenElement) {
                    button.innerHTML = '<i class="fa fa-expand" aria-hidden="true"></i>';
                    localStorage.setItem('isFullscreen', 'false');
                } else {
                    button.innerHTML = '<i class="fa fa-compress" aria-hidden="true"></i>';
                    localStorage.setItem('isFullscreen', 'true');
                }
            });
        });
    </script>

</body>


</html>
