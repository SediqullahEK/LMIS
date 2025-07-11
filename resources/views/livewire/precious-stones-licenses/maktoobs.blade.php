@push('customCss')
    <style>
        .skeleton {
            background: linear-gradient(90deg, #d1d5db 25%, #e5e7eb 50%, #d1d5db 75%);
            background-size: 200% 100%;
            animation: shimmer 1.2s infinite linear;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }
    </style>
@endpush
<div class="relative bg-white shadow-md sm:rounded-lg mx-4 my-4">
    <x-topHeader pageTitle='مکتوب ها' />
    <div wire:loading wire:target='generateMaktoobs'>
        <x-loader />
    </div>
    {{-- alerts section --}}

    @if (session()->has('message'))
        <div x-data="{ show: @json(session()->has('message')) }" x-init="if (show) { setTimeout(() => { show = false }, 3500); }" x-show="show"
            class="fixed top-16 left-1/2 transform -translate-x-1/2 bg-green-300 text-green-800 px-3 py-4 shadow-xl flex justify-between items-center rounded-lg w-auto">
            <button @click="show = false" class="text-gray-500 hover:text-gray-700 text-2xl ">&times;</button>
            {{ session('message') }}
            <svg class="h-5 w-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
            </svg>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: @json(session()->has('error')) }" x-init="if (show) { setTimeout(() => { show = false }, 3500); }" x-show="show"
            class="fixed top-16 left-1/2 transform -translate-x-1/2 bg-red-300 text-gray-800 px-3 py-4 shadow-xl flex justify-between items-center rounded-lg w-auto">
            <button @click="show = false" class="text-gray-500 hover:text-gray-700 text-2xl ">&times;</button>
            {{ session('error') }}
            <svg class="h-5 w-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
            </svg>
        </div>
    @endif
    <div class="flex justify-end items-center w-full p-4 mb-8">
        <div class="flex flex-col items-end space-y-2">
            <!-- Label -->
            <label class="font-bold text-sm">
                نمبر مسلسل <span class="text-red-700">*</span>
            </label>
            <!-- Input Field -->
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="serialNumber" autofocus name="serialNumber"
                    placeholder="نمبر مسلسل جواز را وارد کنید"
                    class="mt-1 px-2 peer block h-10 w-64 bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl" />
                <!-- Loading Animation -->
                {{-- <span wire:loading wire:target="serialNumber"
                    class="absolute left-2 top-1/2 transform -translate-y-1/2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="black">
                        <rect width="6" height="14" x="1" y="4">
                            <animate attributeName="opacity" begin="0s" dur="0.5s" values="1;0.2"
                                repeatCount="indefinite" />
                        </rect>
                        <rect width="6" height="14" x="9" y="4" opacity="0.3">
                            <animate attributeName="opacity" begin="0.2s" dur="0.5s" values="1;0.2"
                                repeatCount="indefinite" />
                        </rect>
                        <rect width="6" height="14" x="17" y="4" opacity="0.4">
                            <animate attributeName="opacity" begin="0.4s" dur="0.5s" values="1;0.2"
                                repeatCount="indefinite" />
                        </rect>
                    </svg>
                </span> --}}
            </div>
            <!-- Error Message -->
            @error('serialNumber')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="bg-white w-full flex flex-col gap-5 px-2 py-2 md:px-16 lg:px-6 md:flex-row text-[#161931]"
        dir='rtl'>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mx-auto">
            @if (!$serialNumber)
                <div class="w-96 h-96 bg-white rounded-lg shadow-lg p-4 mx-auto hover:shadow-2xl cursor-pointer">
                    <!-- Header -->
                    <div class="flex items-center space-x-4 mb-4">
                        <div wire:loading.class='skeleton' class="w-12 h-12 bg-gray-300 rounded-full"></div>
                        <div class="flex-1 space-y-2">
                            <div wire:loading.class='skeleton' class="w-3/4 mx-auto h-4 bg-gray-300 rounded"></div>
                            <div wire:loading.class='skeleton' class="w-1/2 mx-auto h-4 bg-gray-300 rounded"></div>
                        </div>
                        <div wire:loading.class='skeleton' class="w-12 h-12 bg-gray-300 rounded-full"></div>
                    </div>
                    <!-- Content -->
                    <div class="space-y-2">
                        <div wire:loading.class='skeleton' class="w-full h-3 bg-gray-300 rounded "></div>
                        <div wire:loading.class='skeleton' class="w-5/6 h-3 bg-gray-300 rounded "></div>
                        <div wire:loading.class='skeleton' class="w-2/3 h-3 bg-gray-300 rounded"></div>
                    </div>
                </div>
                <div class="w-96 h-96 bg-white rounded-lg shadow-lg p-4 mx-auto hover:shadow-2xl cursor-pointer">
                    <!-- Header -->
                    <div class="flex items-center space-x-4 mb-4">
                        <div wire:loading.class='skeleton' class="w-12 h-12 bg-gray-300 rounded-full "></div>
                        <div class="flex-1 space-y-2">
                            <div wire:loading.class='skeleton' class="w-3/4 mx-auto h-4 bg-gray-300 rounded "></div>
                            <div wire:loading.class='skeleton' class="w-1/2 mx-auto h-4 bg-gray-300 rounded "></div>
                        </div>
                        <div wire:loading.class='skeleton' class="w-12 h-12 bg-gray-300 rounded-full "></div>
                    </div>
                    <!-- Content -->
                    <div class="space-y-2">
                        <div wire:loading.class='skeleton' class="w-full h-3 bg-gray-300 rounded "></div>
                        <div wire:loading.class='skeleton' class="w-5/6 h-3 bg-gray-300 rounded "></div>
                        <div wire:loading.class='skeleton' class="w-2/3 h-3 bg-gray-300 rounded "></div>
                    </div>
                </div>
                <div class="w-96 h-96 bg-white rounded-lg shadow-lg p-4 mx-auto hover:shadow-2xl cursor-pointer">
                    <!-- Header -->
                    <div class="flex items-center space-x-4 mb-4">
                        <div wire:loading.class='skeleton' class="w-12 h-12 bg-gray-300 rounded-full "></div>
                        <div class="flex-1 space-y-2">
                            <div wire:loading.class='skeleton' class="w-3/4 mx-auto h-4 bg-gray-300 rounded "></div>
                            <div wire:loading.class='skeleton' class="w-1/2 mx-auto h-4 bg-gray-300 rounded "></div>
                        </div>
                        <div wire:loading.class='skeleton' class="w-12 h-12 bg-gray-300 rounded-full"></div>
                    </div>
                    <!-- Content -->
                    <div class="space-y-2">
                        <div wire:loading.class='skeleton' class="w-full h-3 bg-gray-300 rounded"></div>
                        <div wire:loading.class='skeleton' class="w-5/6 h-3 bg-gray-300 rounded"></div>
                        <div wire:loading.class='skeleton' class="w-2/3 h-3 bg-gray-300 rounded"></div>
                    </div>
                </div>
            @else
                <div>
                    <x-maktoobLayout>
                        <div>
                            <div id="editor" contenteditable="true"
                                class="p-2 w-full h-40 focus:outline-none overflow-auto"
                                style="font-family: Arial, sans-serif; font-size: 0.5rem;">
                                <strong>به وزارت محترم مالیه!</strong><br>
                                <strong>قابل توجه معینیت عواید و گمرکات!</strong><br>
                                <strong>موضوع:</strong> اجازه انتقال مقدار (2500) کیلو گرام سنگ ابرک به خارج از
                                کشور؛<br><br>
                                با ابراز تمنیات و آرزومند یهای نیک؛
                            </div>
                        </div>
                    </x-maktoobLayout>
                </div>
                <div>
                    <x-maktoobLayout>
                        <div>
                            <div id="editor" contenteditable="true"
                                class="p-2 w-full h-40 focus:outline-none overflow-auto"
                                style="font-family: Arial, sans-serif; font-size: 0.5rem;">
                                <strong>به وزارت محترم مالیه!</strong><br>
                                <strong>قابل توجه معینیت عواید و گمرکات!</strong><br>
                                <strong>موضوع:</strong> اجازه انتقال مقدار (2500) کیلو گرام سنگ ابرک به خارج از
                                کشور؛<br><br>
                                با ابراز تمنیات و آرزومند یهای نیک؛
                            </div>
                        </div>
                    </x-maktoobLayout>
                </div>
                <div>
                    <x-maktoobLayout>
                        <div>
                            <div id="editor" contenteditable="true"
                                class="p-2 w-full h-40 focus:outline-none overflow-auto"
                                style="font-family: Arial, sans-serif; font-size: 0.5rem;">
                                <strong>به وزارت محترم مالیه!</strong><br>
                                <strong>قابل توجه معینیت عواید و گمرکات!</strong><br>
                                <strong>موضوع:</strong> اجازه انتقال مقدار (2500) کیلو گرام سنگ ابرک به خارج از
                                کشور؛<br><br>
                                با ابراز تمنیات و آرزومند یهای نیک؛
                            </div>
                        </div>
                    </x-maktoobLayout>
                </div>
                <div>
                    <x-maktoobLayout>
                        <div>
                            <div id="editor" contenteditable="true"
                                class="p-2 w-full h-40 focus:outline-none overflow-auto"
                                style="font-family: Arial, sans-serif; font-size: 0.5rem;">
                                <strong>به وزارت محترم مالیه!</strong><br>
                                <strong>قابل توجه معینیت عواید و گمرکات!</strong><br>
                                <strong>موضوع:</strong> اجازه انتقال مقدار (2500) کیلو گرام سنگ ابرک به خارج از
                                کشور؛<br><br>
                                با ابراز تمنیات و آرزومند یهای نیک؛
                            </div>
                        </div>
                    </x-maktoobLayout>
                </div>
                <div>
                    <x-maktoobLayout>
                        <div>
                            <div id="editor" contenteditable="true"
                                class="p-2 w-full h-40 focus:outline-none overflow-auto"
                                style="font-family: Arial, sans-serif; font-size: 0.5rem;">
                                <strong>به وزارت محترم مالیه!</strong><br>
                                <strong>قابل توجه معینیت عواید و گمرکات!</strong><br>
                                <strong>موضوع:</strong> اجازه انتقال مقدار (2500) کیلو گرام سنگ ابرک به خارج از
                                کشور؛<br><br>
                                با ابراز تمنیات و آرزومند یهای نیک؛
                            </div>
                        </div>
                    </x-maktoobLayout>
                </div>
                <div>
                    <x-maktoobLayout>
                        <div>
                            <div id="editor" contenteditable="true"
                                class="p-2 w-full h-40 focus:outline-none overflow-auto"
                                style="font-family: Arial, sans-serif; font-size: 0.5rem;">
                                <strong>به وزارت محترم مالیه!</strong><br>
                                <strong>قابل توجه معینیت عواید و گمرکات!</strong><br>
                                <strong>موضوع:</strong> اجازه انتقال مقدار (2500) کیلو گرام سنگ ابرک به خارج از
                                کشور؛<br><br>
                                با ابراز تمنیات و آرزومند یهای نیک؛
                            </div>
                        </div>
                    </x-maktoobLayout>
                </div>
            @endif



            <!-- Repeat similar blocks for additional cards -->
        </div>



    </div>

    <button class="mx-6 my-8 p-3 text-white bg-sky-800 rounded-lg">
        چاپ
    </button>
</div>
@push('customJs')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @this.call('loadMaktoobs');
        });
    </script>
    <script>
        function validateNumber(input) {
            if (input.value < 0) {
                input.value = 0;
            }
        }
    </script>
@endpush


{{-- <aside class="py-4 w-full md:w-1/3 lg:w-1/4">
            <div class="p-4 text-sm border-l border-gray-200">
                <a href="#" wire:click="toggle('user')"
                    class="flex items-center mb-2 px-3 py-2.5 font-bold border rounded-full transition
                            @if ($user) bg-[#D4AF37] text-gray-100 @else bg-white text-gray-900 @endif
                            hover:bg-[#D4AF37] hover:text-gray-100">
                    <i class="fa fa-dollar ml-2"></i>
                    به وزارت محترم مالیه
                </a>

                <a href="#" wire:click="toggle('role')"
                    class="flex items-center mb-2 px-3 py-2.5 font-bold border rounded-full transition
                            @if ($role) bg-[#D4AF37] text-gray-100 @else bg-white text-gray-900 @endif
                            hover:bg-[#D4AF37] hover:text-gray-100">
                    <i class="fa fa-dollar ml-2"></i>
                    به ریاست محترم عواید
                </a>

                <a href="#" wire:click="toggle('permission')"
                    class="flex items-center mb-2 px-3 py-2.5 font-bold border rounded-full transition
                            @if ($permission) bg-[#D4AF37] text-gray-100 @else bg-white text-gray-900 @endif
                            hover:bg-[#D4AF37] hover:text-gray-100">
                    <i class="fa fa-home ml-2"></i>
                    به ریاست محترم معادن ولایت
                </a>

            </div>
        </aside> --}}
{{-- <div class="justify-start py-1">
                        <button onclick="document.execCommand('justifyFull', false, null)"
                            class="text-2xl mr-2  border border-gray-200 rounded-lg px-2 py-1 hover:bg-gray-50 cursor pointer"
                            title="Justify"><strong>J</strong></button>
                        <button onclick="document.execCommand('justifyRight', false, null)"
                            class="text-2xl border border-gray-200 rounded-lg px-2 py-1 hover:bg-gray-50 cursor pointer"
                            title="Align Right"><strong>R</strong></button>
                        <button onclick="document.execCommand('justifyCenter', false, null)"
                            class="text-2xl border border-gray-200 rounded-lg px-2 py-1 hover:bg-gray-50 cursor pointer"
                            title="Align Center"><strong>C</strong></button>
                        <button onclick="document.execCommand('justifyLeft', false, null)"
                            class="text-2xl border border-gray-200 rounded-lg px-2 py-1 hover:bg-gray-50 cursor pointer"
                            title="Align Left"><strong>L</strong></button>

                        <button onclick="document.execCommand('strikeThrough', false, null)"
                            class="text-2xl border border-gray-200 rounded-lg px-2 py-1 hover:bg-gray-50 cursor pointer"
                            title="Strikethrough"><strong>S</strong></button>
                        <button onclick="document.execCommand('underline', false, null)"
                            class="text-2xl border border-gray-200 rounded-lg px-2 py-1 hover:bg-gray-50 cursor pointer"
                            title="Underline"><strong><u>U</u></strong></button>
                        <button onclick="document.execCommand('italic', false, null)"
                            class="text-2xl border border-gray-200 rounded-lg px-2 py-1 hover:bg-gray-50 cursor pointer"
                            title="Italic"><strong><em>I</em></strong></button>
                        <button onclick="document.execCommand('bold', false, null)"
                            class="text-2xl border border-gray-200 rounded-lg px-2 py-1 hover:bg-gray-50 cursor pointer"
                            title="Bold"><strong>B</strong></button>
                    </div>
                    <hr> --}}
