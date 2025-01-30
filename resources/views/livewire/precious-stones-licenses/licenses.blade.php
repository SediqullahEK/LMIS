<div class="relative bg-white shadow-md sm:rounded-lg mx-4 my-4">
    <x-topHeader pageTitle='جواز ها' />
    <div class="bg-white px-2 py-2 md:px-16 lg:px-6 md:flex-row text-[#161931]" dir='rtl'>

        {{-- alerts section --}}
        <div wire:loading wire:target="openMaktoobsModal,openForm, toggleConfirm, navigateToMaktoobs,deleteLicense">
            <x-loader />
        </div>
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

        @can('حذف شخص')
            <div x-data="{ confirm: @entangle('confirm') }">
                <!-- Modal Overlay -->
                <div x-show="confirm" id="confirmModal" x-cloak
                    class="fixed inset-0 z-50 flex items-start justify-center bg-gray-900 bg-opacity-50">
                    <!-- Modal Container -->
                    <div id="modal" x-show="confirm" x-cloak x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                        class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full mx-auto mt-12">

                        <!-- Modal Header -->
                        <div class="flex justify-between items-center pb-4 border-b w-full max-w-4xl">
                            <h2 class="text-xl font-semibold">
                                آیا مطمعن هستید شخص ذیل را حذف کنید؟
                            </h2>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-center gap-4 mt-6">
                            <button id="cancelButton" @click=" @this.call('toggleConfirm', 0)"
                                class="bg-red-600 text-white px-5 py-2 rounded-md hover:bg-red-500 transition">
                                لغو
                            </button>
                            <button id="confirmButton" @click=" @this.call('deleteLicense')"
                                class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-500 transition">
                                تایید
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        {{-- Add/update License section --}}
        <div class="flex flex-wrap items-center justify-between mb-2">
            <!-- Add Button -->
            @can('ایجاد شخص جدید')
                <div class="mt-2 sm:mt-0">
                    <button @click=" @this.call('resetForm'); @this.call('openForm',1)"
                        class="px-4 py-2 bg-[#189197] rounded-lg text-2xl text-white flex items-center justify-center">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            @endcan
            <!-- Search Input -->
            <div class="relative flex items-center mt-4  group text-left ">
                <span class="absolute left-3">
                    <svg aria-hidden="true" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="w-6 h-6 text-gray-300 mt-2">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="جستجو"
                    class="mt-1 px-2 peer block h-10 w-64 max-w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] "
                    autocomplete="off" dir="rtl">
                <div
                    class="absolute top-12 left-0 hidden group-hover:block bg-white border border-[#D4AF37] text-gray-700 px-4 py-1 rounded-lg shadow-md">
                    <div class="flex items-center">

                        <span> جستجو به اساس متقاضی، نمبر تذکره/جواز/عریضه/مسلسل و سنگ</span>
                    </div>
                </div>
                <!-- Disable on pages other than the first -->
                @error('name')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Add/update License modal --}}
        @if (auth()->user()->can('ویرایش شخص') || auth()->user()->can('ایجاد شخص جدید'))
            <div x-data="{ isOpen: @entangle('isOpen') }" dir="rtl">
                <div x-show="isOpen" style="display: none;"
                    class="fixed inset-0 z-50 flex items-start justify-center bg-gray-900 bg-opacity-50">
                    <!-- Modal Structure -->
                    <div x-show="isOpen" x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                        class="bg-white p-4 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg lg:max-w-4xl mt-12 mx-4 relative">
                        {{-- alerts section --}}
                        @if (session()->has('error'))
                            <div x-data="{ show: @json(session()->has('error')) }" x-init="if (show) { setTimeout(() => { show = false }, 5000); }" x-show="show"
                                class="fixed top-16 left-1/2 transform -translate-x-1/2 bg-red-300 text-gray-800 px-3 py-4 shadow-xl flex justify-between items-center rounded-lg w-auto">
                                <button @click="show = false"
                                    class="text-gray-500 hover:text-gray-700 text-2xl ">&times;</button>
                                {{ session('error') }}
                                <svg class="h-5 w-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @endif
                        <div wire:loading wire:target="generateMaktoobs">
                            <x-loader />
                        </div>
                        <!-- Modal Header -->
                        <div class="flex justify-between items-center pb-4 border-b w-full max-w-4xl">
                            <h2 class="text-xl font-semibold">
                                {{ $isEditing ? 'ویرایش جواز' : 'افزودن جواز' }}
                            </h2>
                            <button @click="isOpen = false; @this.call('resetForm');"
                                class="text-gray-500 hover:text-gray-700 text-4xl p-2">&times;</button>
                        </div>
                        <!-- Scrollable Modal Content -->
                        <div class="overflow-y-auto max-h-[70vh] my-8">
                            <form wire:submit.prevent="{{ $isEditing ? 'updateLicense' : 'addLicense' }}"
                                class="grid md:grid-cols-10 sm:grid-cols-1 gap-4 ">

                                <span class="md:col-span-3 sm:col-span-4 text-right w-full">
                                    <label class="font-bold text-sm">نمبر عریضه</label>
                                    <span class="text-red-700">*</span>
                                    <input type="number" wire:model.live.debounce.500ms="letterNumber" autofocus
                                        oninput="validateNumber(this)" name="letterNumber"
                                        placeholder="نمبر عریضه متقاضی را وارد کنید"
                                        class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        autocomplete="off" dir="rtl">
                                    @error('letterNumber')
                                        <p class="text-red-500">{{ $message }}</p>
                                    @enderror
                                    @if ($letterNumberError != '')
                                        <p class="text-red-500">{{ $letterNumberError }}</p>
                                    @endif
                                    <span wire:loading wire:target="letterNumber">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="black">
                                            <rect width="6" height="14" x="1" y="4">
                                                <animate attributeName="opacity" begin="0s" dur="0.5s"
                                                    values="1;0.2" repeatCount="indefinite" />
                                            </rect>
                                            <rect width="6" height="14" x="9" y="4" opacity="0.3">
                                                <animate attributeName="opacity" begin="0.2s" dur="0.5s"
                                                    values="1;0.2" repeatCount="indefinite" />
                                            </rect>
                                            <rect width="6" height="14" x="17" y="4" opacity="0.4">
                                                <animate attributeName="opacity" begin="0.4s" dur="0.5s"
                                                    values="1;0.2" repeatCount="indefinite" />
                                            </rect>
                                        </svg>
                                    </span>
                                </span>
                                <span class="md:col-span-4 sm:col-span-4 text-right w-full">
                                    <label class="font-bold text-sm">موضوع عریضه</label>
                                    <span class="text-red-700">*</span>
                                    <input type="text" wire:model.live="letterSubject" disabled
                                        placeholder="موضوع عریضه متقاضی "
                                        class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        autocomplete="off" dir="rtl">
                                    @error('letterSubject')
                                        <p class="text-red-500">{{ $message }}</p>
                                    @enderror
                                </span>
                                <span class="md:col-span-3 sm:col-span-4 text-right w-full">
                                    <label class="font-bold text-sm">نوع سنگ</label>
                                    <span class="text-red-700">*</span>
                                    <select wire:model.live="stone" name='stone' wire:change='loadStoneDetails'
                                        class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        autocomplete="off" dir="rtl">
                                        <option value="0" disabled hidden selected>سنگ مورد نظر را انتخاب کنید
                                        </option>
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
                                <span class="md:col-span-3 sm:col-span-2 text-right w-full">
                                    <label class="font-bold text-sm">مقیاس</label>
                                    <span class="text-red-700">*</span>
                                    <input type="text" wire:model.live="quantity" name="quantity" disabled
                                        readonly
                                        class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        autocomplete="off" dir="rtl">
                                    @error('quantity')
                                        <p class="text-red-500">{{ $message }}</p>
                                    @enderror
                                </span>
                                <span class="md:col-span-3 sm:col-span-2 text-right w-full">
                                    <label class="font-bold text-sm">رویالیتی فی واحد مقیاس</label>
                                    <span class="text-red-700">*</span>
                                    <input type="text" wire:model.live="finalRoyalityPerQuantity"
                                        name="finalRoyalityPerQuantity" disabled readonly
                                        class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        autocomplete="off" dir="rtl">
                                    @error('finalRoyalityPerQuantity')
                                        <p class="text-red-500">{{ $message }}</p>
                                    @enderror
                                </span>
                                <span class="md:col-span-2 sm:col-span-2 text-right w-full">
                                    <label class="font-bold text-sm">رنگ سنگ به دری</label>
                                    <span class="text-red-700">*</span>
                                    <input type="text" wire:model.live="stoneColorDr" name="stoneColorDr"
                                        class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        autocomplete="off" dir="rtl">
                                    @error('stoneColorDr')
                                        <p class="text-red-500">{{ $message }}</p>
                                    @enderror
                                </span>
                                <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                                    <label class="font-bold text-sm">رنگ سنگ به انگلیسی</label>
                                    <span class="text-red-700">*</span>
                                    <input type="text" wire:model.live="stoneColorEn" name="stoneColorEn"
                                        class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        autocomplete="off" dir="rtl">
                                    @error('stoneColorEn')
                                        <p class="text-red-500">{{ $message }}</p>
                                    @enderror
                                </span>
                                <span class="md:col-span-3 sm:col-span-1 text-right w-full">
                                    <label class="font-bold text-sm">مقدار سنگ</label>
                                    <span class="text-red-700">*</span>
                                    <input type="number" wire:model.live="stoneAmount" name="stoneAmount"
                                        oninput="validateNumber(this)" placeholder="مقدار سنگ را وارد کنید "
                                        class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        autocomplete="off" dir="rtl">
                                    @error('stoneAmount')
                                        <p class="text-red-500">{{ $message }}</p>
                                    @enderror
                                </span>
                                <span class="md:col-span-3 sm:col-span-2 text-right w-full">
                                    <label class="font-bold text-sm">مجموع پول قابل تادیه</label>
                                    <span class="text-red-700">*</span>
                                    <input type="text" wire:model.live="finalRoyalityPerQuantity"
                                        name="finalRoyalityPerQuantity" disabled readonly
                                        class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                        autocomplete="off" dir="rtl">
                                    @error('finalRoyalityPerQuantity')
                                        <p class="text-red-500">{{ $message }}</p>
                                    @enderror
                                </span>

                                <span class="flex justify-start items-end md:col-span-10 text-right my-2 w-full ">

                                    <label class="inline-flex items-center me-5 cursor-pointer">
                                        <input type="checkbox" wire:model.live="individualDetails"
                                            name="individualDetails" wire:change='resetIndividualData(1)'
                                            class="sr-only peer" checked>
                                        <div
                                            class="relative w-11 h-6 bg-gray-200 rounded-full peer   peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600">
                                        </div>
                                        <span class="ms-3 text-sm font-medium text-gray-900 ">جزئیات شخص</span>
                                    </label>

                                </span>
                                @error('individualDetails')
                                    <span class="flex justify-start items-end md:col-span-10 text-right my-2 w-full ">
                                        <p class="text-red-500">{{ $message }}</p>
                                    </span>
                                @enderror
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
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="black">
                                                <rect width="6" height="14" x="1" y="4">
                                                    <animate attributeName="opacity" begin="0s" dur="0.5s"
                                                        values="1;0.2" repeatCount="indefinite" />
                                                </rect>
                                                <rect width="6" height="14" x="9" y="4" opacity="0.3">
                                                    <animate attributeName="opacity" begin="0.2s" dur="0.5s"
                                                        values="1;0.2" repeatCount="indefinite" />
                                                </rect>
                                                <rect width="6" height="14" x="17" y="4" opacity="0.4">
                                                    <animate attributeName="opacity" begin="0.4s" dur="0.5s"
                                                        values="1;0.2" repeatCount="indefinite" />
                                                </rect>
                                            </svg>
                                        </span>
                                    </span>
                                    <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                                        <label class="font-bold text-sm">نمبر تشخیصیه</label>
                                        <span class="text-red-700">*</span>
                                        <input type="number" wire:model.live="tinNumber" disabled name="tinNumber"
                                            class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            autocomplete="off" dir="rtl">
                                        @error('tinNumber')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </span>
                                    <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                                        <label class="font-bold text-sm">نام</label>
                                        <span class="text-red-700">*</span>
                                        <input type="text" wire:model.live="name" disabled name="name"
                                            class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            autocomplete="off" dir="rtl">
                                        @error('name')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </span>
                                    <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                                        <label class="font-bold text-sm">نام پدر</label>
                                        <span class="text-red-700">*</span>
                                        <input type="text" wire:model.live="fathersName" disabled
                                            name="fathersName"
                                            class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            autocomplete="off" dir="rtl">
                                        @error('fathersName')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </span>
                                    <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                                        <label class="font-bold text-sm">ولایت</label>
                                        <span class="text-red-700">*</span>
                                        <input type="text" wire:model.live="province" disabled name="province"
                                            class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            autocomplete="off" dir="rtl">
                                        @error('province')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </span>
                                @else
                                    <span wire:loading wire:target="individualDetails">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="black">
                                            <rect width="6" height="14" x="1" y="4">
                                                <animate attributeName="opacity" begin="0s" dur="0.5s"
                                                    values="1;0.2" repeatCount="indefinite" />
                                            </rect>
                                            <rect width="6" height="14" x="9" y="4" opacity="0.3">
                                                <animate attributeName="opacity" begin="0.2s" dur="0.5s"
                                                    values="1;0.2" repeatCount="indefinite" />
                                            </rect>
                                            <rect width="6" height="14" x="17" y="4" opacity="0.4">
                                                <animate attributeName="opacity" begin="0.4s" dur="0.5s"
                                                    values="1;0.2" repeatCount="indefinite" />
                                            </rect>
                                        </svg>
                                    </span>
                                @endif

                                <span class="flex justify-start items-end md:col-span-10 text-right my-2 w-full">
                                    <label class="inline-flex items-center me-5 cursor-pointer">
                                        <input type="checkbox" wire:model.live="companyDetails"
                                            wire:change='resetCompanyData(1)' class="sr-only peer" checked>
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
                                        <input type="number" wire:model.live="licenseNumber" name="licenseNumber"
                                            oninput="validateNumber(this)" placeholder="نمبر جواز شرکت را وارد کنید"
                                            class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            autocomplete="off" dir="rtl">
                                        @error('licenseNumber')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                        <span wire:loading wire:target="licenseNumber">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="black">
                                                <rect width="6" height="14" x="1" y="4">
                                                    <animate attributeName="opacity" begin="0s" dur="0.5s"
                                                        values="1;0.2" repeatCount="indefinite" />
                                                </rect>
                                                <rect width="6" height="14" x="9" y="4" opacity="0.3">
                                                    <animate attributeName="opacity" begin="0.2s" dur="0.5s"
                                                        values="1;0.2" repeatCount="indefinite" />
                                                </rect>
                                                <rect width="6" height="14" x="17" y="4" opacity="0.4">
                                                    <animate attributeName="opacity" begin="0.4s" dur="0.5s"
                                                        values="1;0.2" repeatCount="indefinite" />
                                                </rect>
                                            </svg>
                                        </span>
                                    </span>
                                    <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                                        <label class="font-bold text-sm">نمبر تشخیصیه</label>
                                        <span class="text-red-700">*</span>
                                        <input type="number" wire:model.live.debounce.500ms="companyTINNumber"
                                            name="companyTINNumber" disabled
                                            class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            autocomplete="off" dir="rtl">
                                        @error('tinNumber')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </span>
                                    <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                                        <label class="font-bold text-sm">نام</label>
                                        <span class="text-red-700">*</span>
                                        <input type="text" wire:model.live="companyName" disabled
                                            name="companyName"
                                            class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            autocomplete="off" dir="rtl">
                                        @error('companyName')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </span>
                                    <span class="md:col-span-2 sm:col-span-1 text-right w-full">
                                        <label class="font-bold text-sm">آدرس</label>
                                        <span class="text-red-700">*</span>
                                        <input type="text" wire:model.live="address" disabled name="address"
                                            placeholder="موقعیت دقیق فعلی شرکت"
                                            class="mt-1 px-2 peer block h-10 w-full bg-gray-100 border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            autocomplete="off" dir="rtl">
                                        @error('address')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </span>
                                @else
                                    <span wire:loading wire:target="companyDetails">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="black">
                                            <rect width="6" height="14" x="1" y="4">
                                                <animate attributeName="opacity" begin="0s" dur="0.5s"
                                                    values="1;0.2" repeatCount="indefinite" />
                                            </rect>
                                            <rect width="6" height="14" x="9" y="4" opacity="0.3">
                                                <animate attributeName="opacity" begin="0.2s" dur="0.5s"
                                                    values="1;0.2" repeatCount="indefinite" />
                                            </rect>
                                            <rect width="6" height="14" x="17" y="4" opacity="0.4">
                                                <animate attributeName="opacity" begin="0.4s" dur="0.5s"
                                                    values="1;0.2" repeatCount="indefinite" />
                                            </rect>
                                        </svg>
                                    </span>
                                @endif

                                <span class="md:col-span-4 sm:col-span-1 text-right w-full">
                                    <button type="submit"
                                        class="text-sm h-10 px-8 bg-[#189197]  rounded-lg text-white hover:bg-[#189179] focus:outline-none focus:ring-2 focus:ring-blue-600"
                                        title="ایجاد جواز"wire:loading.attr="disabled">
                                        ایجاد جواز
                                    </button>

                                    <button type="button"
                                        class="text-sm h-10 px-8 bg-red-800 rounded-lg text-white hover:bg-red-700 hover:text-black focus:outline-none focus:ring-2 focus:ring-red-600"
                                        x-on:click="isOpen = false; @this.call('resetForm');" title="لفو">
                                        لفو
                                    </button>
                                </span>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Maktoobs section --}}
        <div x-data="{ maktoobModal: @entangle('maktoobModal') }" dir="rtl">
            <div x-show="maktoobModal" style="display: none;"
                class="fixed inset-0 z-50 flex items-start justify-center bg-gray-900 bg-opacity-50">
                <!-- Modal Structure -->
                <div x-show="maktoobModal" x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                    class="bg-white p-4 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg lg:max-w-5xl mt-12 mx-4 relative">

                    {{-- loader section --}}
                    <div wire:loading wire:target='addMaktoobsToLicenses'>
                        <x-loader />
                    </div>
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center pb-4 border-b w-full max-w-5xl">
                        <h2 class="text-xl font-semibold">
                            {{ 'انتخاب مکاتیب' }}
                        </h2>
                        <button @click="maktoobModal = false; @this.call('resetForm');"
                            class="text-gray-500 hover:text-gray-700 text-4xl p-2">&times;</button>
                    </div>
                    {{-- search input --}}
                    <input type="text"
                        class="border border-gray-300 w-full rounded-lg py-2 px-4 mt-2 shadow-sm focus:ring-2 focus:ring-yellow-100 focus:outline-none focus:border-yellow-500 hover:border-yellow-400 transition-all duration-150"
                        placeholder="جستجو" wire:model.live.debounce.400ms="searchedMaktoob">
                    <!-- Scrollable Modal Content -->
                    <div class="overflow-y-auto overflow-x-auto  max-h-[70vh] my-2">

                        <table dir="rtl"
                            class="w-full table-auto mb-4 text-sm text-center text-gray-900 border border-slate-100">
                            <thead class="text-xs text-gray-50 bg-[#2C3E50] uppercase">
                                <tr>
                                    <th scope="col" class="py-2 border border-slate-200">
                                        <div class="flex items-center justify-center">
                                            <select id="perPage" wire:model.live="modalPerPage"
                                                class="text-xs text-gray-100 bg-[#2C3E50] border  cursor-pointer rounded-md px-1 py-1 focus:outline-none">
                                                <option value="5" selected>5</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="0">همه</option>
                                            </select>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-3 py-2 border border-slate-200">
                                        <div class="flex justify-center">
                                            <span>نوعیت</span>

                                        </div>
                                    </th>
                                    <th scope="col" class="px-3 py-2 border border-slate-200">
                                        <div class="flex justify-center">
                                            <span>موضوع</span>

                                        </div>
                                    </th>
                                    <th scope="col" class="px-3 py-2 border border-slate-200">
                                        <div class="flex justify-center">
                                            <span>مرجع</span>

                                        </div>
                                    </th>
                                    <th scope="col" class="px-3 py-2 border border-slate-200">
                                        <div class="flex justify-center">
                                            <span>موقعیت فزیکی</span>

                                        </div>
                                    </th>
                                    <th scope="col" class="px-3 py-2 border border-slate-200">
                                        <div class="flex justify-center">
                                            <span>تاریخ</span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-3 py-2 border border-slate-200">
                                        <div class="flex justify-center">
                                            <span>سکن</span>
                                        </div>
                                    </th>

                                    @can('اضافه نمودن سهام دار')
                                        <th scope="col" class="px-3 py-2 border border-slate-200">
                                            <div class="flex justify-center">
                                                <span>انتخاب</span>
                                            </div>
                                        </th>
                                    @endcan

                                </tr>
                            </thead>
                            <tbody>
                                @if ($maktoobs && count($maktoobs))
                                    @foreach ($maktoobs as $index => $maktoob)
                                        <tr class="border-b hover:bg-warning-400">
                                            <td class="px-3 py-2 border border-slate-200">
                                                @if ($modalPerPage)
                                                    {{ $maktoobs->firstItem() + $index }}
                                                @else
                                                    {{ ++$index }}
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 border border-slate-200">
                                                {{ $maktoob->type ?? '' }}
                                            </td>
                                            <td class="px-3 py-2 border border-slate-200">
                                                {{ $maktoob->subject ?? '' }}
                                            </td>
                                            <td class="px-3 py-2 border border-slate-200">
                                                {{ $maktoob->source ?? '' }}
                                            </td>
                                            <td class="px-3 py-2 border border-slate-200">
                                                {{ $maktoob->file_location ?? '' }}
                                            </td>
                                            <td class="px-3 py-2 border border-slate-200">
                                                {{ $maktoob->date ?? '' }}
                                            </td>
                                            <td class="px-3 py-2 border border-slate-200">
                                                {{-- {{ $maktoob->maktob_scan ?? '' }} --}}

                                                <i id="icon-{{ $maktoob->id }}"
                                                    class="fa fa-eye text-[#D4AF37] cursor-pointer"
                                                    wire:loading.attr="hidden"
                                                    wire:target="popMaktoobScan({{ $maktoob->id }})"
                                                    wire:click="popMaktoobScan({{ $maktoob->id }})">
                                                </i>

                                                <span wire:loading wire:target="popMaktoobScan({{ $maktoob->id }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="black">
                                                        <rect width="6" height="14" x="1" y="4">
                                                            <animate attributeName="opacity" begin="0s"
                                                                dur="0.5s" values="1;0.2"
                                                                repeatCount="indefinite" />
                                                        </rect>
                                                        <rect width="6" height="14" x="9" y="4"
                                                            opacity="0.3">
                                                            <animate attributeName="opacity" begin="0.2s"
                                                                dur="0.5s" values="1;0.2"
                                                                repeatCount="indefinite" />
                                                        </rect>
                                                        <rect width="6" height="14" x="17" y="4"
                                                            opacity="0.4">
                                                            <animate attributeName="opacity" begin="0.4s"
                                                                dur="0.5s" values="1;0.2"
                                                                repeatCount="indefinite" />
                                                        </rect>
                                                    </svg>
                                                </span>

                                            </td>

                                            <td class="px-2 py-2 border border-slate-200 cursor-pointer">
                                                <input id="{{ $maktoob->id }}" type="checkbox"
                                                    @if ($maktoobModalState) disabled readonly @endif
                                                    class="text-2xl cursor-pointer rounded"
                                                    wire:model="selectedMaktoobs"
                                                    onclick="toggleInputDisable({{ $maktoob->id }})"
                                                    value="{{ $maktoob->id }}" {{-- disabled --}}>

                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        @if ($noData)
                            <h1 class="font-bold text-xl text-red-900">معلومات موجود نمیباشد! </h1>
                        @endif
                        <span wire:loading wire:target="searchedMaktoob,maktoobsData,modalPerPage">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="black">
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
                        <nav class="flex justify-between items-center mt-4">
                            @if ($modalPerPage && $maktoobs->isNotEmpty())
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <span>{{ $maktoobs->links('vendor.pagination.tailwind') }}</span>
                                    <span wire:loading wire:target="previousPage, nextPage, gotoPage">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="black">
                                            <rect width="6" height="14" x="1" y="4">
                                                <animate attributeName="opacity" begin="0s" dur="0.5s"
                                                    values="1;0.2" repeatCount="indefinite" />
                                            </rect>
                                            <rect width="6" height="14" x="9" y="4" opacity="0.3">
                                                <animate attributeName="opacity" begin="0.2s" dur="0.5s"
                                                    values="1;0.2" repeatCount="indefinite" />
                                            </rect>
                                            <rect width="6" height="14" x="17" y="4" opacity="0.4">
                                                <animate attributeName="opacity" begin="0.4s" dur="0.5s"
                                                    values="1;0.2" repeatCount="indefinite" />
                                            </rect>
                                        </svg>
                                    </span>
                                </div>
                            @endif
                        </nav>
                    </div>
                    @if (!$maktoobModalState)
                        <div class="mt-4">
                            @if ($error)
                                <div class="text-red-400 my-2">
                                    {{ $error }}
                                </div>
                            @endif

                            <button wire:click="addMaktoobsToLicenses"
                                class="text-sm h-10 px-8 rounded-lg text-white  @if ($maktoobModalState) bg-gray-500 @else bg-[#189197]   hover:bg-[#189179] focus:outline-none focus:ring-2 focus:ring-blue-600 @endif"
                                title="ذخیره"wire:loading.attr="disabled" {{-- disabled --}}>

                                ذخیره
                            </button>

                            <button type="button"
                                class="text-sm h-10 px-8 bg-red-800 rounded-lg text-white hover:bg-red-700 hover:text-black focus:outline-none focus:ring-2 focus:ring-red-600"
                                @click="maktoobModal = false; @this.call('resetForm');" title="لفو">
                                لفو
                            </button>
                        </div>
                    @endif

                    {{-- maktoob scale preview modal --}}
                    <div x-data="{ maktoobScan: @entangle('maktoobScan') }" dir="rtl">
                        <div x-show="maktoobScan" style="display: none;"
                            class="fixed inset-0 z-50 flex items-start justify-center bg-gray-900 bg-opacity-50 ">
                            <!-- Modal Structure -->
                            <div x-show="maktoobScan" x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-90"
                                class="bg-gray-900/50 p-4 rounded-lg shadow-lg w-full max-w-sm sm:max-w-lg lg:max-w-2xl mt-12 mb-4 mx-4 relative">


                                <!-- Modal Header -->
                                <div class="flex justify-between items-center pb-4 border-b w-full max-w-2xl">
                                    <h2 class="text-xl text-white font-semibold">
                                        {{ 'سکن مکتوب' }}
                                    </h2>
                                    <button @click="maktoobScan = false; @this.call('resetForm');"
                                        class="text-gray-100 hover:text-white text-4xl p-2">&times;</button>
                                </div>
                                <div class="flex justify-center items-center mx-auto w-full pt-4">
                                    <img src="{{ asset('storage/system_images/maktoob.jpg') }}"
                                        class="w-[500px] h-[550px] rounded-lg ">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data table section --}}
        <div class="overflow-x-auto">
            <table dir="rtl"
                class="w-full table-auto mb-4 text-sm text-center text-gray-900 border border-slate-100">
                <thead class="text-xs text-gray-50 bg-[#2C3E50] uppercase">
                    <tr>
                        <th scope="col" class="py-2 border border-slate-200">
                            <div class="flex items-center justify-center">
                                <select id="perPage" wire:model.live="perPage"
                                    class="text-xs text-gray-100 bg-[#2C3E50] border rounded-md px-1 py-1 focus:outline-none">
                                    <option value="5" selected>5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="0">همه</option>
                                </select>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer"
                            wire:click="sortBy('individuals.name_dr')">
                            <div class="flex justify-center">
                                <span>نام متقاضی</span>
                                @if ($sortField === 'individuals.name_dr')
                                    <span
                                        class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                @else
                                    <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer"
                            wire:click="sortBy('companies.name_dr')">
                            <div class="flex justify-center">
                                <span>شرکت</span>
                                @if ($sortField === 'companies.name_dr')
                                    <span
                                        class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                @else
                                    <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200">
                            <div class="flex justify-center">
                                <span>نمبر تذکره</span>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200">
                            <div class="flex justify-center">
                                <span>نمبر جواز</span>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200">
                            <div class="flex justify-center">
                                <span>نمبر عریضه</span>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer"
                            wire:click="sortBy('stone')">
                            <div class="flex justify-center">
                                <span>سنگ</span>
                                @if ($sortField === 'stone')
                                    <span
                                        class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                @else
                                    <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200">
                            <div class="flex justify-center">
                                <span>مقدار سنگ</span>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200">
                            <div class="flex justify-center">
                                <span>رنگ سنگ</span>
                            </div>
                        </th>

                        <th scope="col" class="px-3 py-2 border border-slate-200">
                            <div class="flex justify-center">
                                <span>نمبر مسلسل</span>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer"
                            wire:click="sortBy('status')">
                            <div class="flex justify-center">
                                <span>حالت</span>
                                @if ($sortField === 'status')
                                    <span
                                        class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                @else
                                    <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                @endif
                            </div>

                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200">
                            <div class="flex justify-center">
                                <span>مکاتیب</span>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200">
                            <div class="flex justify-center">
                                <span>چاپ جواز</span>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200">
                            <div class="flex justify-center">
                                <span>اعمال</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody wire:init="loadTableData">
                    @if ($licenses && count($licenses))
                        @foreach ($licenses as $index => $license)
                            <tr class="border-b hover:bg-warning-400" wire:key="license-{{ $license->id }}">
                                {{-- {{ dd($license) }} --}}
                                <td class="px-3 py-2 border border-slate-200">
                                    @if ($perPage)
                                        {{ $licenses->firstItem() + $index }}
                                    @else
                                        {{ ++$index }}
                                    @endif

                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->individual_name ?? '' }}
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->company_name ?? 'ندارد' }}
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->tazkira_num ?? '' }}
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->license_num ?? 'ندارد' }}
                                </td>

                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->letter_id ?? '' }}
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->stone ?? '' }}
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->stone_amount ?? '' }}
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->stone_color_dr ?? '' }}
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->serial_number ?? '' }}
                                </td>
                                <td class="px-8 py-2 border border-slate-200">
                                    @if ($license->status == 'in_process')
                                        <p class="pb-1 px-1 text-white bg-[#D4AF37] rounded-full text-sm">در حال
                                            پروسس</p>
                                    @elseif ($license->status == 'printed')
                                        <p class="py-1 px-1 text-white bg-green-500 rounded-full text-sm">چاپ شده
                                        </p>
                                    @elseif ($license->status == 'expired')
                                        <p class="py-1 px-1 text-white bg-red-900 rounded-full text-sm">منقضی شده
                                        </p>
                                    @endif
                                </td>


                                <td class="px-2 py-2 border border-slate-200">

                                    <button class=" text-gray-900 px-2 py-2 rounded"
                                        wire:click="openMaktoobsModal('{{ $license->id }}',{{ $license->status == 'in_process' ? 0 : 1 }})">
                                        @if ($license->status == 'in_process')
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="@if ($license->maktoobs_count > 0) fill-[#189197]@else
                                                    fill-[#043234] @endif w-6 h-6"
                                                viewBox="0 0 576 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                                <path
                                                    @if ($license->maktoobs_count > 0) fill-[#189197]@else
                                                    fill-[#043234] @endif
                                                    d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7L64 512c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128zM288 368a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm211.3-43.3c-6.2-6.2-16.4-6.2-22.6 0L416 385.4l-28.7-28.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6l40 40c6.2 6.2 16.4 6.2 22.6 0l72-72c6.2-6.2 6.2-16.4 0-22.6z" />
                                            </svg>
                                        @else
                                            <i class="fa fa-eye text-lg text-sky-800"></i>
                                        @endif

                                    </button>
                                    @if ($license->status == 'in_process')
                                        <button wire:click="navigateToMaktoobs({{ $license->id }})"
                                            class=" text-gray-900 px-2 py-2 rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="@if ($license->status == 'in_process') fill-[#366089] @else fill-gray-300 @endif   w-6 h-6"
                                                viewBox="0 0 576 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                                <path
                                                    d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7L64 512c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128zm48 96a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm16 80c0-8.8-7.2-16-16-16s-16 7.2-16 16l0 48-48 0c-8.8 0-16 7.2-16 16s7.2 16 16 16l48 0 0 48c0 8.8 7.2 16 16 16s16-7.2 16-16l0-48 48 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-48 0 0-48z" />
                                            </svg>
                                        </button>
                                    @endif
                                </td>

                                <td class="px-2 py-2 border border-slate-200 ">

                                    @if ($license->maktoobs_count == 0 && $license->status == 'in_process')
                                        <p class="text-[#D4AF37]">نخست مکاتیب را اپلود
                                            کنید!
                                        </p>
                                    @elseif ($license->maktoobs_count > 0 && $license->status == 'in_process')
                                        <button
                                            @click=" @this.call('editLicense', {{ $license->id }}); @this.call('openForm',0) "
                                            class=" text-gray-900 px-2 py-2 rounded">
                                            <span class="text-xl px-3 pt-5"><i
                                                    class="fa  fa-print text-sky-800"></i></span>
                                        </button>
                                    @elseif($license->status == 'printed')
                                        <p class="text-green-500">چاپ شده</p>
                                    @elseif($license->status == 'expired')
                                        <p class="text-red-500">منقضی</p>
                                    @endif

                                </td>

                                <td class="px-2 py-2 border border-slate-200 ">
                                    @can('ویرایش شخص')
                                        <button
                                            @click=" @this.call('editLicense', {{ $license->id }}); @this.call('openForm',0) "
                                            class=" text-gray-900 px-2 py-2 rounded">
                                            <span class="text-xl px-3 pt-5"><i
                                                    class="fa  fa-edit text-sky-600"></i></span>
                                        </button>
                                    @endcan
                                    @can('حذف شخص')
                                        <button @click=" @this.call('toggleConfirm', {{ $license->id }})"
                                            class=" text-gray-900 px-2 py-2 rounded">
                                            <span class="text-xl px-3 pt-5"><i
                                                    class="fa  fa-trash text-red-600"></i></span>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            @if ($noData)
                <h1 class="font-bold text-xl text-red-900">معلومات موجود نمیباشد! </h1>
            @endif
            <span wire:loading wire:target="loadTableData,search,perPage,sortBy">
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
            <nav class="flex justify-between items-center mt-4">
                @if ($perPage && $licenses->isNotEmpty())
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <span>{{ $licenses->links('vendor.pagination.tailwind') }}</span>
                        <span wire:loading wire:target="previousPage, nextPage, gotoPage">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="black">
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
                    </div>
                @endif
            </nav>
        </div>

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
