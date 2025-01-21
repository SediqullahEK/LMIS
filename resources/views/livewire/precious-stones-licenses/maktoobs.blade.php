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

    <div class=" px-4 pb-12 " dir="rtl">

        <form wire:submit.prevent="generateMaktoobs" class="grid md:grid-cols-10 sm:grid-cols-1 gap-4">

            <span class="md:col-span-2 sm:col-span-4 text-right w-full">
                <label class="font-bold text-sm">نمبر عریضه</label>
                <span class="text-red-700">*</span>
                <input type="number" required wire:model.live.debounce.500ms="letterNumber" autofocus
                    oninput="validateNumber(this)" name="letterNumber" placeholder="نمبر عریضه متقاضی را وارد کنید"
                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('letterNumber')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
                <span wire:loading wire:target="letterNumber">
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
                </span>
            </span>
            <span class="md:col-span-2 sm:col-span-4 text-right w-full">
                <label class="font-bold text-sm">موضوع عریضه</label>
                <span class="text-red-700">*</span>
                <input type="text" required wire:model.live="letterSubject" disabled
                    placeholder="موضوع عریضه متقاضی "
                    class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('letterSubject')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>
            <span class="md:col-span-2 sm:col-span-4 text-right w-full">
                <label class="font-bold text-sm">نوع سنگ</label>
                <span class="text-red-700">*</span>
                <select required wire:model.live="stone" name='stone' wire:change='loadQauntity'
                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                    <option value="0" disabled hidden selected>سنگ مورد نظر را انتخاب کنید</option>
                    @if ($stones)
                        @foreach ($stones as $st)
                            <option value="{{ $st->id }}">{{ $st->name }}</option>
                        @endforeach
                    @endif

                </select>
                @error('stone')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>
            <span class="md:col-span-2 sm:col-span-2 text-right w-full">
                <label class="font-bold text-sm">مقیاس</label>
                <span class="text-red-700">*</span>
                <input type="text" required wire:model.live="quantity" name="quantity" disabled readonly
                    class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('quantity')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>
            <span class="md:col-span-2 sm:col-span-2 text-right w-full">
                <label class="font-bold text-sm">رنگ سنگ به دری</label>
                <span class="text-red-700">*</span>
                <input type="text" required wire:model.live="stoneColorDr" name="stoneColorDr"
                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('stoneColorDr')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>
            <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                <label class="font-bold text-sm">رنگ سنگ به انگلیسی</label>
                <span class="text-red-700">*</span>
                <input type="text" required wire:model.live="stoneColorEn" name="stoneColorEn"
                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('stoneColorEn')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>
            <span class="md:col-span-8 sm:col-span-1 text-right w-full">
                <label class="font-bold text-sm">مقدار سنگ</label>
                <span class="text-red-700">*</span>
                <input type="number" required wire:model.live="stoneAmount" name="stoneAmount"
                    oninput="validateNumber(this)" placeholder="مقدار سنگ را وارد کنید "
                    class="mt-1 px-2 peer block h-10 w-full md:w-[260px] bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                    autocomplete="off" dir="rtl">
                @error('stoneAmount')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </span>

            <span class="flex justify-start items-end md:col-span-10 text-right my-2 w-full ">

                <label class="inline-flex items-center me-5 cursor-pointer">
                    <input type="checkbox" wire:model.live="individualDetails" wire:change='resetIndividualData(1)'
                        class="sr-only peer" checked>
                    <div
                        class="relative w-11 h-6 bg-gray-200 rounded-full peer   peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600">
                    </div>
                    <span class="ms-3 text-sm font-medium text-gray-900 ">جزئیات شخص</span>
                </label>


            </span>
            @error('tazkiraNumber')
                <span class="flex justify-start items-end md:col-span-10 text-right my-2 w-full ">
                    <p class="text-red-500">{{ $message }}</p>
                </span>
            @enderror
            {{-- Individual Data section --}}
            @if ($individualDetails)
                <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                    <label class="font-bold text-sm">نمبر تذکره</label>
                    <span class="text-red-700">*</span>
                    <input type="number" wire:model.live.debounce.500ms="tazkiraNumber"
                        oninput="validateNumber(this)" name="tazkiraNumber" name="tazkiraNumber"
                        placeholder="نمبر تذکره متقاضی را وارد کنید"
                        class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        autocomplete="off" dir="rtl">
                    @error('tazkiraNumber')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                    <span wire:loading wire:target="tazkiraNumber">
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
                    </span>
                </span>
                <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                    <label class="font-bold text-sm">نمبر تشخیصیه</label>
                    <span class="text-red-700">*</span>
                    <input type="number" required wire:model.live="tinNumber" disabled
                        class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        autocomplete="off" dir="rtl">
                    @error('tinNumber')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </span>
                <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                    <label class="font-bold text-sm">نام</label>
                    <span class="text-red-700">*</span>
                    <input type="text" required wire:model.live="name" disabled
                        class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        autocomplete="off" dir="rtl">
                    @error('name')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </span>
                <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                    <label class="font-bold text-sm">نام پدر</label>
                    <span class="text-red-700">*</span>
                    <input type="text" required wire:model.live="fathersName"disabled
                        class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        autocomplete="off" dir="rtl">
                    @error('fathersName')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </span>
                <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                    <label class="font-bold text-sm">ولایت</label>
                    <span class="text-red-700">*</span>
                    <input type="text" required wire:model.live="province" disabled
                        class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        autocomplete="off" dir="rtl">
                    @error('province')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </span>
            @else
                <span wire:loading wire:target="individualDetails">
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
                </span>
            @endif

            <span class="flex justify-start items-end md:col-span-10 text-right my-2 w-full">
                <label class="inline-flex items-center me-5 cursor-pointer">
                    <input type="checkbox" wire:model.live="companyDetails" wire:change='resetCompanyData(1)'
                        class="sr-only peer" checked>
                    <div
                        class="relative w-11 h-6 bg-gray-200 rounded-full peer   peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600">
                    </div>
                    <span class="ms-3 text-sm font-medium text-gray-900 ">جزئیات شرکت</span>
                </label>
            </span>

            @if ($companyDetails)
                <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                    <label class="font-bold text-sm">نمبر جواز</label>
                    <span class="text-red-700">*</span>
                    <input type="number" required wire:model.live="licenseNumber" name="licenseNumber"
                        oninput="validateNumber(this)" placeholder="نمبر جواز شرکت را وارد کنید"
                        class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        autocomplete="off" dir="rtl">
                    @error('licenseNumber')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                    <span wire:loading wire:target="licenseNumber">
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
                    </span>
                </span>
                <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                    <label class="font-bold text-sm">نمبر تشخیصیه</label>
                    <span class="text-red-700">*</span>
                    <input type="number" required wire:model.live.debounce.500ms="companyTINNumber" disabled
                        class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        autocomplete="off" dir="rtl">
                    @error('tinNumber')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </span>
                <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                    <label class="font-bold text-sm">نام</label>
                    <span class="text-red-700">*</span>
                    <input type="text" required wire:model.live="companyName" disabled
                        class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        autocomplete="off" dir="rtl">
                    @error('companyName')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </span>
                <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                    <label class="font-bold text-sm">آدرس</label>
                    <span class="text-red-700">*</span>
                    <input type="text" required wire:model.live="address" disabled
                        placeholder="موقعیت دقیق فعلی شرکت"
                        class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        autocomplete="off" dir="rtl">
                    @error('address')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </span>
            @else
                <span wire:loading wire:target="companyDetails">
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
                </span>
            @endif

            <!-- Adjust Button -->
            <span class="col-span-1 sm:col-span-2 md:col-span-1 text-right mt-1">
                <button type="submit"
                    class="px-4 py-2 mt-6 bg-[#189197] rounded-lg text-md text-white flex items-center justify-center">
                    ایجاد مکاتیب
                </button>
            </span>
        </form>
    </div>


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
@push('customJs')
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
