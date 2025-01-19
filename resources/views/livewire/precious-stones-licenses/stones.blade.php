<div class="relative bg-white shadow-md sm:rounded-lg mx-4 my-4">
    <x-topHeader pageTitle='سنگ های قیمتی و نیمه قیمتی ' />
    <div class="bg-white w-full flex flex-col gap-5 px-2 py-2 md:px-16 lg:px-6 md:flex-row text-[#161931]" dir='rtl'>
        <div class="w-full  px-6 pb-8 mt-2">
            {{-- loader --}}
            <div wire:loading wire:target="openForm, updateRole, addStone, toggleConfirm">
                <x-loader />
            </div>

            {{-- Alerts section --}}
            @if (session()->has('message'))
                <div x-data="{ show: @json(session()->has('message')) }" x-init="if (show) { setTimeout(() => { show = false }, 3000); }" x-show="show"
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
                <div x-data="{ show: @json(session()->has('error')) }" x-init="if (show) { setTimeout(() => { show = false }, 3000); }" x-show="show"
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

            @can('حذف وظیفه')
                <div x-data="{ confirm: @entangle('confirm') }">
                    <!-- Modal Overlay -->
                    <div x-show="confirm"
                        class="fixed inset-0 z-50 flex items-start justify-center bg-gray-900 bg-opacity-50">
                        <!-- Modal Container -->
                        <div id="modal" x-show="confirm" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                            class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full mx-auto mt-12">

                            <!-- Modal Header -->
                            <div class="flex justify-between items-center pb-4 border-b w-full max-w-3xl">
                                <h2 class="text-xl font-semibold">
                                    آیا مطمعن هستید سنگ ذیل را حذف کنید؟
                                </h2>
                            </div>

                            <!-- Buttons -->
                            <div class="flex justify-center gap-4 mt-6">
                                <button id="cancelButton" @click=" @this.call('toggleConfirm', 0)"
                                    class="bg-red-600 text-white px-5 py-2 rounded-md hover:bg-red-500 transition">
                                    لغو
                                </button>
                                <button id="confirmButton" @click=" @this.call('deleteStone')"
                                    class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-500 transition">
                                    تایید
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan


            <div class="flex flex-wrap items-center justify-between mb-2">

                {{-- Add and Edit stone section --}}
                @can('ایجاد وظیفه جدید')
                    <div class="mt-2 sm:mt-0">
                        <button @click=" @this.call('resetForm'); @this.call('openForm',1)"
                            class="px-4 py-1 mb-1 bg-[#189197] rounded-lg text-2xl text-white">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                @endcan
                {{-- search section --}}
                <div class="relative flex items-center mt-4  group text-left ">
                    <span class="absolute left-3">
                        <svg aria-hidden="true" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            class="w-6 h-6 text-gray-300 mt-2">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input type="text" required wire:model.live.debounce.500ms="search" placeholder="جستجو"
                        class="mt-1 px-2 peer block h-10 w-64 max-w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] "
                        autocomplete="off" dir="rtl">
                    <div
                        class="absolute top-12 left-0 hidden group-hover:block bg-white border border-[#D4AF37] text-gray-700 px-4 py-1 rounded-lg shadow-md">
                        <div class="flex items-center">

                            <span> جستجو به اساس نام مروج و لاتین سنگ</span>
                        </div>
                    </div>
                    <!-- Disable on pages other than the first -->

                </div>

            </div>



            @if (auth()->user()->can('ایجاد وظیفه جدید') || auth()->user()->can('ویرایش وظیفه'))
                <div x-data="{ isOpen: @entangle('isOpen') }" dir="rtl">
                    <div x-show="isOpen" style="display: none;"
                        class="fixed inset-0 z-50 flex items-start justify-center bg-gray-900 bg-opacity-50">

                        <!-- Modal Structure -->
                        <div x-show="isOpen" x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                            class="bg-white p-6 rounded-lg shadow-lg w-full max-w-3xl mt-12">
                            <!-- Added mt-12 for margin from top -->

                            <!-- Modal Content -->
                            <div>
                                <!-- Modal Header -->
                                <div class="flex justify-between items-center pb-4 border-b w-full max-w-3xl">
                                    <h2 class="text-xl font-semibold">
                                        {{ $isEditing ? 'ویرایش سنگ' : 'افزودن سنگ جدید' }}
                                    </h2>
                                    <button @click="isOpen = false; @this.call('resetForm');"
                                        class="text-gray-500 hover:text-gray-700 text-4xl p-2">&times;</button>
                                </div>
                                @if (session()->has('error'))
                                    <div x-data="{ show: @json(session()->has('error')) }" x-init="if (show) { setTimeout(() => { show = false }, 3000); }" x-show="show"
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
                                <!-- Form -->
                                <div class="overflow-y-auto max-h-[70vh]">
                                    <form wire:submit.prevent="{{ $isEditing ? 'updateStone' : 'addStone' }}"
                                        class="grid lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-1 gap-4">
                                        {{-- General Name of ston --}}
                                        <input type="number" hidden wire:model.live='stonesId'>
                                        <span class="col-span-2 text-right">
                                            <label class="font-bold text-sm">نام مروج سنګ</label>
                                            <span class="text-red-700">*</span>
                                            <input type="text" wire:model.live ="name" name="name"
                                                class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                autocomplete="off" dir="rtl">
                                            @error('name')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </span>
                                        {{-- Name --}}
                                        <span class="col-span-2 text-right">
                                            <label class="font-bold text-sm">نام لاتین سنګ</label>
                                            <span class="text-red-700">*</span>
                                            <input type="text" wire:model.live ="latin_name" name="latin_name"
                                                class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                autocomplete="off" dir="rtl">
                                            @error('latin_name')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </span>
                                        {{-- Scal --}}
                                        <span class="col-span-2 text-right">
                                            <label class="font-bold text-sm">مقیاس </label>
                                            <span class="text-red-700">*</span>
                                            <select wire:model.live ="quantity"
                                                class="mt-1 px-2 peer block h-10 w-full bg-blue border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                                <option value="" disabled hidden selected>مقیاس را انتخاب کنید
                                                </option>
                                                <option value="گرام">گرام</option>
                                                <option value="کیلو گرام">کیلو گرام</option>
                                                <option value="تن">تن</option>
                                                <option value="قیراط">قیراط</option>

                                            </select>

                                            @error('quantity')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </span>
                                        {{-- is precious --}}
                                        <span class="col-span-2 text-right">
                                            <label class="font-bold text-sm">نوع سنګ </label>
                                            <span class="text-red-700">*</span>
                                            <select wire:model.live ="is_precious"
                                                class="mt-1 px-2 peer block h-10 w-full bg-blue border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                                <option value="" disabled hidden selected>نوع سنګ را انتخاب کنید
                                                </option>
                                                <option value="1">قیمتی</option>
                                                <option value="0">نیمه قیمتی </option>
                                            </select>

                                            @error('is_precious')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </span>
                                        {{-- estimated_extraction --}}
                                        <span class="col-span-2 text-right">
                                            <label class="font-bold text-sm">مقدار تخمینی استخراج</label>
                                            <span class="text-red-700">*</span>
                                            <input type="text" wire:model.live ="estimated_extraction"
                                                name="estimated_extraction"
                                                class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                autocomplete="off" dir="rtl">
                                            @error('estimated_extraction')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </span>
                                        {{-- estimated_price_from --}}
                                        <span class="col-span-2 text-right">
                                            <label class="font-bold text-sm">نرخ تخمینی حد اقل </label>
                                            <span class="text-red-700">*</span>
                                            <input type="text" wire:model.live ="estimated_price_from"
                                                name="estimated_price_from"
                                                class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                autocomplete="off" dir="rtl">
                                            @error('estimated_price_from')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </span>
                                        {{-- estimated_price_to --}}
                                        <span class="col-span-2 text-right">
                                            <label class="font-bold text-sm">نرخ تخمینی حداکثر </label>
                                            <span class="text-red-700">*</span>
                                            <input type="text" wire:model.live ="estimated_price_to"
                                                name="estimated_price_to"
                                                class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                autocomplete="off" dir="rtl">
                                            @error('estimated_price_to')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </span>
                                        {{-- offered_royality_by_private_sector --}}
                                        <span class="col-span-2 text-right">
                                            <label class="font-bold text-sm"> ریالیتی پیشنهادی </label>
                                            <span class="text-red-700">*</span>
                                            <input type="text" wire:model.live ="offered_royality_by_private_sector"
                                                name="offered_royality_by_private_sector"
                                                class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                autocomplete="off" dir="rtl">
                                            @error('offered_royality_by_private_sector')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </span>
                                        {{-- final_royality_after_negotiations --}}
                                        <span class="col-span-2 text-right">
                                            <label class="font-bold text-sm"> ریالیتی نهایی </label>
                                            <span class="text-red-700">*</span>
                                            <input type="text" wire:model.live ="final_royality_after_negotiations"
                                                name="final_royality_after_negotiations"
                                                class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                autocomplete="off" dir="rtl">
                                            @error('final_royality_after_negotiations')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </span>
                                        {{-- estimated_revenue_based_on_ORPS --}}
                                        <span class="col-span-2 text-right">
                                            <label class="font-bold text-sm">عواید تخمینی به اساس پیشنهاد سکتور خصوصی
                                            </label>
                                            <span class="text-red-700">*</span>
                                            <input type="text" wire:model.live ="estimated_revenue_based_on_ORPS"
                                                name="estimated_revenue_based_on_ORPS"
                                                class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                autocomplete="off" dir="rtl">
                                            @error('estimated_revenue_based_on_ORPS')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </span>
                                        {{-- estimated_revenue_based_on_FRAN --}}
                                        <span class="col-span-2 text-right">
                                            <label class="font-bold text-sm">عواید تخمینی به اساس مزاکره شده </label>
                                            <span class="text-red-700">*</span>
                                            <input type="text" wire:model.live ="estimated_revenue_based_on_FRAN"
                                                name="estimated_revenue_based_on_FRAN"
                                                class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                autocomplete="off" dir="rtl">
                                            @error('estimated_revenue_based_on_FRAN')
                                                <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </span>

                                        {{-- image --}}
                                        <div class="flex">
                                            <span class="w-1/2 text-right ">
                                                <label class="font-bold text-sm">عکس</label>
                                                <input type="file" wire:model="photo" id="file-upload" name="photo"
                                                    accept="image/*" class="hidden" />
                                                <label for="file-upload"
                                                    class="cursor-pointer mt-1 h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm flex items-center justify-center text-gray-700 hover:bg-gray-100 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                                    انتخاب عکس
                                                </label>
                                                @error('photo')
                                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                                @enderror
                                            </span>

                                            <!-- Image Preview -->
                                            <span class="w-1/2 flex items-center justify-center ">
                                                <span wire:loading wire:target="photo"
                                                    class="col-start-5 col-span-1 justify-self-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24">
                                                        <rect width="6" height="14" x="1" y="4" fill="black">
                                                            <animate id="svgSpinnersBarsFade0" fill="freeze"
                                                                attributeName="opacity"
                                                                begin="0;svgSpinnersBarsFade1.end-0.175s" dur="0.525s"
                                                                values="1;0.2" />
                                                        </rect>
                                                        <rect width="6" height="14" x="9" y="4" fill="black"
                                                            opacity="0.4">
                                                            <animate fill="freeze" attributeName="opacity"
                                                                begin="svgSpinnersBarsFade0.begin+0.105s" dur="0.525s"
                                                                values="1;0.2" />
                                                        </rect>
                                                        <rect width="6" height="14" x="17" y="4" fill="black"
                                                            opacity="0.3">
                                                            <animate id="svgSpinnersBarsFade1" fill="freeze"
                                                                attributeName="opacity"
                                                                begin="svgSpinnersBarsFade0.begin+0.21s" dur="0.525s"
                                                                values="1;0.2" />
                                                        </rect>
                                                    </svg>
                                                </span>
                                                @if ($photo)
                                                    <img src="{{ $photo->temporaryUrl() }}" width="100"
                                                        class="rounded mr-4" alt="Uploaded image">
                                                @elseif ($existing_photo_path)
                                                    <img src="{{ Storage::url($existing_photo_path) }}" width="100"
                                                        class="rounded mr-4" alt="Existing image">
                                                @endif

                                            </span>
                                        </div>

                                        <div class="col-span-full flex justify-start space-x-4 mt-4">
                                            <button type="submit"
                                                class="text-sm h-10 px-8 bg-[#189197]  rounded-lg text-white hover:bg-[#189179] focus:outline-none focus:ring-2 focus:ring-blue-600"
                                                title="{{ $isEditing ? 'به‌روزرسانی' : 'ذخیره' }}">
                                                {{ $isEditing ? 'به‌روزرسانی' : 'ذخیره' }}
                                            </button>

                                            <button type="button"
                                                class="text-sm h-10 px-8 bg-red-800 rounded-lg text-white hover:bg-red-700 hover:text-black focus:outline-none focus:ring-2 focus:ring-red-600"
                                                x-on:click="isOpen = false; $wire.call('resetForm')" title="لفو">
                                                لفو
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif



            {{-- Data Table section --}}
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
                                wire:click="sortBy('name')">
                                <div class="flex justify-center">
                                    <span>نام سنګ</span>
                                    @if ($sortField === 'name')
                                        <span
                                            class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                    @else
                                        <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer"
                                wire:click="sortBy('latin_name')">
                                <div class="flex justify-center">
                                    <span>نام لاتین سنګ</span>
                                    @if ($sortField === 'latin_name')
                                        <span
                                            class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                    @else
                                        <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                    @endif
                                </div>
                            </th>

                            <th scope="col" class="px-3 py-2 border border-slate-200">
                                <div class="flex justify-center">
                                    <span>مقیاس</span>
                                </div>
                            </th>

                            <th scope="col" class="px-3 py-2 border border-slate-200">
                                <div class="flex justify-center">
                                    <span>نوع سنګ</span>
                                    @if ($sortField === 'is_precious')
                                        <span
                                            class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                    @else
                                        <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                    @endif
                                </div>
                            </th>

                            <th scope="col" class="px-3 py-2 border border-slate-200">
                                <div class="flex justify-center">
                                    <span>مقدار تخمینی</span>
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200">
                                <div class="flex justify-center">
                                    <span>نرخ حداقل </span>
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200">
                                <div class="flex justify-center">
                                    <span>نرخ حداکثر </span>
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200">
                                <div class="flex justify-center">
                                    <span>رویالتی پیشنهاد شده </span>
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200">
                                <div class="flex justify-center">
                                    <span>رویالتی نهایی </span>
                                </div>
                            </th>

                            <th scope="col" class="px-3 py-2 border border-slate-200 ">
                                <div class="flex justify-center">
                                    <span>عواید پیشنهادی </span>
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200 ">
                                <div class="flex justify-center">
                                    <span>عواید مزاکره </span>
                                </div>
                            </th>


                            @if (auth()->user()->can('ویرایش شخص') || auth()->user()->can('حذف شخص'))
                                <th scope="col" class="px-3 py-2 border border-slate-200">
                                    <div class="flex justify-center">
                                        <span>اعمال</span>
                                    </div>
                                </th>
                            @endif

                        </tr>
                    </thead>
                    <tbody wire:init="loadTableData">
                        @if ($stones && count($stones))
                            @foreach ($stones as $index => $stone)
                                <tr class="border-b hover:bg-warning-400">
                                    <td class="px-3 py-2 border border-slate-200">
                                        @if ($perPage)
                                            {{ $stones->firstItem() + $index }}
                                        @else
                                            {{ ++$index }}
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $stone->name ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $stone->latin_name ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $stone->quantity ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $stone->is_precious ? 'قیمتی' : 'نیمه قیمتی' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $stone->estimated_extraction ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $stone->estimated_price_from ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $stone->estimated_price_to ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $stone->offered_royality_by_private_sector ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $stone->final_royality_after_negotiations ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $stone->estimated_revenue_based_on_ORPS ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $stone->estimated_revenue_based_on_FRAN ?? '' }}
                                    </td>


                                    <td class="px-2 py-2 border border-slate-200 dark:text-white">
                                        @can('ویرایش شخص')
                                            <button
                                                @click=" @this.call('editStone', {{ $stone->id }}); @this.call('openForm',0) "
                                                class=" text-gray-900 px-2 py-2 rounded">
                                                <span class="text-xl px-3 pt-5"><i
                                                        class="fa  fa-edit text-sky-600"></i></span>
                                            </button>
                                        @endcan
                                        @can('حذف شخص')
                                            <button @click=" @this.call('toggleConfirm', {{ $stone->id }})"
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
                    @if ($perPage && $stones->isNotEmpty())
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <span>{{ $stones->links('vendor.pagination.tailwind') }}</span>
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
        </div>
    </div>
</div>
