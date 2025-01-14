<div class="relative bg-white shadow-md sm:rounded-lg mx-4 my-4">
    <x-topHeader pageTitle='مکتوب ها' />
    {{-- <div wire:loading wire:target='requestId'>
        <x-loader />
    </div> --}}
    <span class="flex justify-end items-end col-span-3 text-right mt-4">

        <label class="inline-flex items-center me-5 cursor-pointer">
            <input type="checkbox" wire:model.live="passwordUpdate" class="sr-only peer" checked>
            <div
                class="relative w-11 h-6 bg-gray-200 rounded-full peer   peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600">
            </div>
            <span class="ms-3 text-sm font-medium text-gray-900 ">شرکت</span>
        </label>

        <label class="inline-flex items-center me-5 cursor-pointer">
            <input type="checkbox" wire:model.live="passwordUpdate" class="sr-only peer" checked>
            <div
                class="relative w-11 h-6 bg-gray-200 rounded-full peer   peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600">
            </div>
            <span class="ms-3 text-sm font-medium text-gray-900 ">شخص</span>
        </label>
    </span>
    <fieldset class=" shadow-md ring-2 ring-gray-100 p-4 rounded-lg px-4 pb-12 m-5" dir="rtl">
        <span class="text-lg font-semibold text-right">جزئیات شرکت</span>

        <form wire:submit.prevent="generateMaktoobs"
            class="grid lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-1 gap-4 mt-4">
            <!--Name -->
            <input type="number" hidden wire:model.live='companyId'>
            <span class="col-span-1 text-right">
                <label class="font-bold text-sm">نام</label>
                <span class="text-red-700">*</span>
                <input type="text" required wire:model.live="name"
                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('name')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>


            <!-- tin_num -->
            <span class="col-span-1 text-right">
                <label class="font-bold text-sm">نمبر تشخیصیه</label>
                <span class="text-red-700">*</span>
                <input type="number" required wire:model.live="tin_num"
                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('tin_num')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>

            <!-- license_num -->
            <span class="col-span-1 text-right">
                <label class="font-bold text-sm">نمبر جواز</label>
                <span class="text-red-700">*</span>
                <input type="number" required wire:model.live="license_num"
                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('license_num')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>

            <span class="col-span-1 text-right">
                <label class="font-bold text-sm">ولایت</label>
                <span class="text-red-700">*</span>
                <input type="text" required wire:model.live="address" placeholder="موقعیت دقیق فعلی شرکت"
                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('province')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>

            <span class="col-span-1 text-right">
                <label class="font-bold text-sm">آدرس</label>
                <span class="text-red-700">*</span>
                <input type="text" required wire:model.live="address" placeholder="موقعیت دقیق فعلی شرکت"
                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('address')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>

        </form>

    </fieldset>
    <fieldset class=" shadow-md ring-2 ring-gray-100 p-4 rounded-lg px-4 pb-12 m-5" dir="rtl">
        <span class="text-lg font-semibold text-right">جزئیات شخص</span>

        <form wire:submit.prevent="generateMaktoobs"
            class="grid lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-1 gap-4 mt-4">
            <!--Name -->
            <input type="number" hidden wire:model.live='companyId'>
            <span class="col-span-1 text-right">
                <label class="font-bold text-sm">نام</label>
                <span class="text-red-700">*</span>
                <input type="text" required wire:model.live="name"
                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('name')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>


            <!-- tin_num -->
            <span class="col-span-1 text-right">
                <label class="font-bold text-sm">نمبر تشخیصیه</label>
                <span class="text-red-700">*</span>
                <input type="number" required wire:model.live="tin_num"
                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('tin_num')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>

            <!-- license_num -->
            <span class="col-span-1 text-right">
                <label class="font-bold text-sm">نمبر جواز</label>
                <span class="text-red-700">*</span>
                <input type="number" required wire:model.live="license_num"
                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('license_num')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>

            <span class="col-span-1 text-right">
                <label class="font-bold text-sm">ولایت</label>
                <span class="text-red-700">*</span>
                <input type="text" required wire:model.live="address" placeholder="موقعیت دقیق فعلی شرکت"
                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('province')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>

            <span class="col-span-1 text-right">
                <label class="font-bold text-sm">آدرس</label>
                <span class="text-red-700">*</span>
                <input type="text" required wire:model.live="address" placeholder="موقعیت دقیق فعلی شرکت"
                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('address')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>
            {{-- <input type="number" required wire:model.live.debounce.400ms="requestId"
                    placeholder="نمبر عریضه را وارد کنید"
                    class="mt-1 peer block h-10 w-64 max-w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] px-4"
                    autocomplete="off" dir="rtl">
                <span class="absolute right-56 mt-2">
                    <svg aria-hidden="true" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="w-6 h-6 text-gray-300">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </span> --}}
            <span class="col-span-1 text-right mt-6">
                <button type="submit" wire:loading.attr="disabled"
                    class="text-sm mt-1 h-10 ml-2 px-8 bg-[#189197] rounded-lg text-white hover:bg-[#189179] focus:outline-none focus:ring-2 focus:ring-blue-600"
                    title="ایجاد مکاتیب">
                    ایجاد مکاتیب
                </button>
            </span>
        </form>

    </fieldset>


    <div class="bg-white w-full flex flex-col gap-5 px-2 py-2 md:px-16 lg:px-6 md:flex-row text-[#161931]"
        dir='rtl'>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mx-auto">
            @if (!$requestId)
                <div
                    class="w-96 h-96 bg-white rounded-lg shadow-lg p-4  mx-auto animate-pulse hover:shadow-2xl hover:w- cursor-pointer ">
                    <!-- Header -->
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 bg-gray-300 rounded-full"></div>
                        <div class="flex-1 space-y-2">
                            <div class="w-3/4 mx-auto h-4 bg-gray-300 rounded"></div>
                            <div class="w-1/2 mx-auto h-4 bg-gray-300 rounded"></div>
                        </div>
                        <div class="w-12 h-12 bg-gray-300 rounded-full"></div>
                    </div>
                    <!-- Content -->
                    <div class="space-y-2">
                        <div class="w-full h-3 bg-gray-300 rounded"></div>
                        <div class="w-5/6 h-3 bg-gray-300 rounded"></div>
                        <div class="w-2/3 h-3 bg-gray-300 rounded"></div>
                    </div>
                </div>
                <div class="w-96 h-96 bg-white rounded-lg shadow-lg p-4  mx-auto animate-pulse ">
                    <!-- Header -->
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 bg-gray-300 rounded-full"></div>
                        <div class="flex-1 space-y-2">
                            <div class="w-3/4 mx-auto h-4 bg-gray-300 rounded"></div>
                            <div class="w-1/2 mx-auto h-4 bg-gray-300 rounded"></div>
                        </div>
                        <div class="w-12 h-12 bg-gray-300 rounded-full"></div>
                    </div>
                    <!-- Content -->
                    <div class="space-y-2">
                        <div class="w-full h-3 bg-gray-300 rounded"></div>
                        <div class="w-5/6 h-3 bg-gray-300 rounded"></div>
                        <div class="w-2/3 h-3 bg-gray-300 rounded"></div>
                    </div>
                </div>
                <div class="w-96 h-96 bg-white rounded-lg shadow-lg p-4  mx-auto animate-pulse ">
                    <!-- Header -->
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 bg-gray-300 rounded-full"></div>
                        <div class="flex-1 space-y-2">
                            <div class="w-3/4 mx-auto h-4 bg-gray-300 rounded"></div>
                            <div class="w-1/2 mx-auto h-4 bg-gray-300 rounded"></div>
                        </div>
                        <div class="w-12 h-12 bg-gray-300 rounded-full"></div>
                    </div>
                    <!-- Content -->
                    <div class="space-y-2">
                        <div class="w-full h-3 bg-gray-300 rounded"></div>
                        <div class="w-5/6 h-3 bg-gray-300 rounded"></div>
                        <div class="w-2/3 h-3 bg-gray-300 rounded"></div>
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
