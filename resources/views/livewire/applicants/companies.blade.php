<div class="relative bg-white shadow-md sm:rounded-lg mx-4 my-4">
    <x-topHeader pageTitle='شرکت ها' />
    <div class="bg-white w-full flex flex-col gap-5 px-2 py-2 md:px-16 lg:px-6 md:flex-row text-[#161931]" dir='rtl'>

        <div class="w-full px-6 pb-8 mt-2">
            {{-- alerts section --}}
            <div wire:loading wire:target="openForm, toggleConfirm,openShareholders">
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

            {{-- Delete Company Modal --}}
            @can('حذف شرکت')
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
                                    آیا مطمعن هستید شرکت ذیل را حذف کنید؟
                                </h2>
                            </div>

                            <!-- Buttons -->
                            <div class="flex justify-center gap-4 mt-6">
                                <button id="cancelButton" @click=" @this.call('toggleConfirm', 0)"
                                    class="bg-red-600 text-white px-5 py-2 rounded-md hover:bg-red-500 transition">
                                    لغو
                                </button>
                                <button id="confirmButton" @click=" @this.call('deleteCompany')"
                                    class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-500 transition">
                                    تایید
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            {{-- Add shareholders to company section --}}
            @can('اضافه نمودن سهام دار')
                <div x-data="{ showShareholders: @entangle('showShareholders') }" dir="rtl">
                    <div x-show="showShareholders" style="display: none;"
                        class="fixed inset-0 z-50 flex items-start justify-center bg-gray-900 bg-opacity-40 overflow-y-auto">
                        <div wire:loading wire:target="addShareholdersToCompany">
                            <x-loader />
                        </div>
                        @if (session()->has('message'))
                            <div x-data="{ show: @json(session()->has('message')) }" x-init="if (show) { setTimeout(() => { show = false }, 3500); }" x-show="show"
                                class="fixed top-16 left-1/2 transform -translate-x-1/2 bg-green-300 text-green-800 px-3 py-4 shadow-xl flex justify-between items-center rounded-lg w-auto">
                                <button @click="show = false"
                                    class="text-gray-500 hover:text-gray-700 text-2xl ">&times;</button>
                                {{ session('message') }}
                                <svg class="h-5 w-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @endif

                        @if ($errorMessage)
                            <div
                                class="fixed top-16 left-1/2 transform -translate-x-1/2 bg-red-300 text-gray-800 px-3 py-4 shadow-xl rounded-lg w-auto">
                                <button wire:click="$set('errorMessage', null)"
                                    class="text-gray-500 hover:text-gray-700 text-2xl ">&times;</button>
                                {{ $errorMessage }}
                            </div>
                        @endif


                        <!-- Modal Structure -->
                        <div x-show="showShareholders" x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                            class="bg-white p-6 rounded-lg shadow-lg w-full max-w-4xl mt-12">

                            <!-- Modal Content -->
                            <div>
                                <!-- Modal Header -->
                                <div class="flex justify-between items-center pb-4 border-b w-full max-w-4xl">
                                    <h2 class="text-xl font-semibold"> سهامداران {{ $company }}</h2>

                                    <button @click="showShareholders = false; @this.call('resetForm');"
                                        class="text-gray-500 hover:text-gray-700 text-4xl p-2">&times;</button>
                                </div>
                                <input type="text"
                                    class="border border-gray-300 w-full rounded-lg py-2 px-4 my-2 shadow-sm focus:ring-2 focus:ring-[#D4AF37] focus:outline-none focus:border-[#D4AF37] hover:border-[#D4AF37] transition-all duration-150"
                                    placeholder="جستجو به اساس نام، نمبر تشخیصیه و نمبر تذکره"
                                    wire:model.live.debounce.400ms="searchedIndividual">


                                <div class="overflow-x-auto ">

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
                                                        <span>نام</span>

                                                    </div>
                                                </th>
                                                <th scope="col" class="px-3 py-2 border border-slate-200">
                                                    <div class="flex justify-center">
                                                        <span>ولایت</span>
                                                    </div>
                                                </th>
                                                <th scope="col" class="px-3 py-2 border border-slate-200">
                                                    <div class="flex justify-center">
                                                        <span>تابعیت</span>

                                                    </div>
                                                </th>
                                                <th scope="col" class="px-3 py-2 border border-slate-200">
                                                    <div class="flex justify-center">
                                                        <span>نمبر تشخیصیه</span>

                                                    </div>
                                                </th>
                                                <th scope="col" class="px-3 py-2 border border-slate-200">
                                                    <div class="flex justify-center">
                                                        <span>نمبر تذکره</span>

                                                    </div>
                                                </th>

                                                @can('اضافه نمودن سهام دار')
                                                    <th scope="col" class="px-3 py-2 border border-slate-200">
                                                        <div class="flex justify-center">
                                                            <span>مقدار سهام</span>
                                                        </div>
                                                    </th>
                                                @endcan

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($allIndividuals && count($allIndividuals))
                                                @foreach ($allIndividuals as $index => $shareholder)
                                                    <tr class="border-b hover:bg-warning-400">
                                                        <td class="px-3 py-2 border border-slate-200">
                                                            @if ($modalPerPage)
                                                                {{ $allIndividuals->firstItem() + $index }}
                                                            @else
                                                                {{ ++$index }}
                                                            @endif
                                                        </td>
                                                        <td class="px-3 py-2 border border-slate-200">
                                                            {{ $shareholder->name_dr ?? '' }}
                                                        </td>

                                                        <td class="px-3 py-2 border border-slate-200">
                                                            @if ($provinces && count($provinces))
                                                                @foreach ($provinces as $pr)
                                                                    @if ($shareholder->province_id == $pr->id)
                                                                        {{ $pr->name ?? '' }}
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td class="px-3 py-2 border border-slate-200">
                                                            {{ $shareholder->nationality ?? '' }}
                                                        </td>
                                                        <td class="px-3 py-2 border border-slate-200">
                                                            {{ $shareholder->tin_num ?? '' }}
                                                        </td>
                                                        <td class="px-3 py-2 border border-slate-200">
                                                            {{ $shareholder->tazkira_num ?? '' }}
                                                        </td>

                                                        <td class="px-2 py-2 border border-slate-200 cursor-pointer">
                                                            <input id="{{ $shareholder->id }}" type="checkbox"
                                                                class="text-2xl cursor-pointer rounded"
                                                                wire:model="shareholders"
                                                                onclick="toggleInputDisable({{ $shareholder->id }})"
                                                                value="{{ $shareholder->id }}">

                                                            <input type="number"
                                                                id="sharePercentageInput_{{ $shareholder->id }}"
                                                                @disabled(!in_array($shareholder->id, $shareholders))
                                                                wire:model.live="sharePercentages.{{ $shareholder->id }}"
                                                                value="{{ collect($companyShareholders)->firstWhere('id', $shareholder->id)['shares_in_percentage'] ?? '' }}"
                                                                min="0" max="100"
                                                                class="w-12 border border-gray-200 py-2 px-1 focus:outline-none rounded-lg"
                                                                oninput="validatePercentage(this)">

                                                        </td>


                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    @if ($noData)
                                        <h1 class="font-bold text-xl text-red-900">معلومات موجود نمیباشد! </h1>
                                    @endif
                                    <span wire:loading wire:target="searchedIndividual,modalSearch,modalPerPage">
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
                                    <nav class="flex justify-between items-center mt-4">
                                        @if ($modalPerPage && $allIndividuals->isNotEmpty())
                                            <div class="flex items-center space-x-2 space-x-reverse">
                                                <span>{{ $allIndividuals->links('vendor.pagination.tailwind') }}</span>
                                                <span wire:loading wire:target="previousPage, nextPage, gotoPage">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="black">
                                                        <rect width="6" height="14" x="1" y="4">
                                                            <animate attributeName="opacity" begin="0s"
                                                                dur="0.5s" values="1;0.2" repeatCount="indefinite" />
                                                        </rect>
                                                        <rect width="6" height="14" x="9" y="4" opacity="0.3">
                                                            <animate attributeName="opacity" begin="0.2s"
                                                                dur="0.5s" values="1;0.2" repeatCount="indefinite" />
                                                        </rect>
                                                        <rect width="6" height="14" x="17" y="4" opacity="0.4">
                                                            <animate attributeName="opacity" begin="0.4s"
                                                                dur="0.5s" values="1;0.2" repeatCount="indefinite" />
                                                        </rect>
                                                    </svg>
                                                </span>
                                            </div>
                                        @endif
                                    </nav>

                                </div>

                                <div class="mt-4">
                                    <button wire:click="addShareholdersToCompany"
                                        class="text-sm h-10 px-8 bg-[#189197]  rounded-lg text-white hover:bg-[#189179] focus:outline-none focus:ring-2 focus:ring-blue-600"
                                        title="ذخیره"wire:loading.attr="disabled">
                                        ذخیره
                                    </button>

                                    <button type="button"
                                        class="text-sm h-10 px-8 bg-red-800 rounded-lg text-white hover:bg-red-700 hover:text-black focus:outline-none focus:ring-2 focus:ring-red-600"
                                        x-on:click="showShareholders = false; searchedPermissions =''; @this.call('resetForm');"
                                        title="لفو">
                                        لفو
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            {{-- Add/Edit Company section --}}
            <div class="flex flex-wrap items-center justify-between mb-2">
                <!-- Add Button -->
                @can('ایجاد شرکت جدید')
                    <div class="mt-2 sm:mt-0">
                        <button @click=" @this.call('resetForm'); @this.call('openForm',1)"
                            class="px-4 py-2 bg-[#189197] rounded-lg text-2xl text-white flex items-center justify-center">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                @endcan
                <!-- Search Input -->
                <div class="relative flex items-center mt-4 mb-3 group text-left ">
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

                            <span> جستجو به اساس نام، نمبر جواز و نمبر تشخیصیه</span>
                        </div>
                    </div>
                    <!-- Disable on pages other than the first -->
                    @error('name')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            @if (auth()->user()->can('ویرایش شرکت') || auth()->user()->can('ایجاد شرکت جدید'))
                <div x-data="{ isOpen: @entangle('isOpen') }" dir="rtl">
                    <div x-show="isOpen" style="display: none;"
                        class="fixed inset-0 z-50 flex items-start justify-center bg-gray-900 bg-opacity-50">

                        <!-- Modal Structure -->
                        <div x-show="isOpen" x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90"
                            class="bg-white p-4 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg lg:max-w-3xl mt-12 mx-4 relative">
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
                            <div wire:loading wire:target="updateCompany,addCompany">
                                <x-loader />
                            </div>

                            <!-- Scrollable Modal Content -->
                            <div class="overflow-y-auto max-h-[80vh]">
                                <!-- Modal Header -->
                                <div class="flex justify-between items-center pb-4 border-b w-full">
                                    <h2 class="text-xl font-semibold">
                                        {{ $isEditing ? 'ویرایش شرکت' : 'افزودن شرکت' }}
                                    </h2>
                                    <button @click="isOpen = false; @this.call('resetForm');"
                                        class="text-gray-500 hover:text-gray-700 text-4xl p-2">&times;</button>
                                </div>

                                <!-- Form -->
                                <form wire:submit.prevent="{{ $isEditing ? 'updateCompany' : 'addCompany' }}"
                                    class="grid lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-1 gap-4">
                                    <input type="number" hidden wire:model.live='companyId'>
                                    <span class="col-span-2 text-right">
                                        <label class="font-bold text-sm">نام دری</label>
                                        <span class="text-red-700">*</span>
                                        <input type="text" required wire:model.live="name_dr"
                                            class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            autocomplete="off" dir="rtl">
                                        @error('name_dr')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </span>
                                    <span class="col-span-2 text-right">
                                        <label class="font-bold text-sm">نام انگلیسی</label>
                                        <span class="text-red-700">*</span>
                                        <input type="text" required wire:model.live="name_en"
                                            class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            autocomplete="off" dir="rtl">
                                        @error('name_en')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </span>
                                    <span class="col-span-2 text-right">
                                        <label class="font-bold text-sm">نمبر تشخیصیه</label>
                                        <span class="text-red-700">*</span>
                                        <input type="number" required wire:model.live="tin_num"
                                            class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            autocomplete="off" dir="rtl">
                                        @error('tin_num')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </span>
                                    <span class="col-span-2 text-right">
                                        <label class="font-bold text-sm">نمبر جواز</label>
                                        <span class="text-red-700">*</span>
                                        <input type="number" required wire:model.live="license_num"
                                            class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            autocomplete="off" dir="rtl">
                                        @error('license_num')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </span>
                                    <span class="col-span-2 text-right">
                                        <label class="font-bold text-sm">ولایت</label>
                                        <span class="text-red-700">*</span>
                                        <select required wire:model.live="province"
                                            class="mt-1 px-2 peer block h-10 w-full bg-blue border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                            <option value="0" disabled hidden selected>ولایت شرکت را
                                                انتخاب
                                                کنید</option>
                                            @if ($provinces && count($provinces))
                                                @foreach ($provinces as $pr)
                                                    <option value="{{ $pr->id }}">{{ $pr->name }}
                                                    </option>
                                                @endforeach
                                            @endif

                                        </select>
                                        @error('province')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </span>
                                    <span class="col-span-2 text-right">
                                        <label class="font-bold text-sm">آدرس</label>
                                        <span class="text-red-700">*</span>
                                        <input type="text" required wire:model.live="address"
                                            placeholder="موقعیت دقیق فعلی شرکت"
                                            class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            autocomplete="off" dir="rtl">
                                        @error('address')
                                            <p class="text-red-500">{{ $message }}</p>
                                        @enderror
                                    </span>
                                    <div class="col-span-full flex justify-start space-x-4 mt-4">
                                        <button type="submit" wire:loading.attr="disabled"
                                            class="text-sm h-10 ml-2 px-8 bg-[#189197] rounded-lg text-white hover:bg-[#189179] focus:outline-none focus:ring-2 focus:ring-blue-600"
                                            title="{{ $isEditing ? 'به‌روزرسانی' : 'ذخیره' }}">
                                            {{ $isEditing ? 'به‌روزرسانی' : 'ذخیره' }}
                                        </button>
                                        <button type="button"
                                            class="text-sm h-10 px-8 bg-red-800 rounded-lg text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                                            x-on:click="isOpen = false; $wire.call('resetForm')" title="لفو">
                                            لفو
                                        </button>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Data table section --}}
            <div class="overflow-x-auto">
                <table dir="rtl"
                    class="w-full table-auto mb-4 text-sm text-center text-gray-900 border border-slate-100">
                    <thead class="text-xs text-gray-50 bg-[#2C3E50] uppercase">
                        <tr>
                            <th scope="col" class="py-2 border border-slate-200">
                                <div class="flex items-center justify-center">
                                    <select id="perPage" wire:model.live="perPage"
                                        class="text-xs text-gray-100 bg-[#2C3E50] cursor-pointer border rounded-md px-1 py-1 focus:outline-none">
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
                                wire:click="sortBy('name_dr')">
                                <div class="flex justify-center">
                                    <span>نام دری</span>
                                    @if ($sortField === 'name_dr')
                                        <span
                                            class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                    @else
                                        <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer"
                                wire:click="sortBy('name_en')">
                                <div class="flex justify-center">
                                    <span>نام انگلیسی</span>
                                    @if ($sortField === 'name_en')
                                        <span
                                            class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                    @else
                                        <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer"
                                wire:click="sortBy('license_num')">
                                <div class="flex justify-center">
                                    <span>نمبر جواز</span>
                                    @if ($sortField === 'license_num')
                                        <span
                                            class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                    @else
                                        <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer"
                                wire:click="sortBy('tin_num')">
                                <div class="flex justify-center">
                                    <span>نمبر تشخیصیه</span>
                                    @if ($sortField === 'tin_num')
                                        <span
                                            class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                    @else
                                        <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                    @endif
                                </div>
                            </th>

                            <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer"
                                wire:click="sortBy('province_id')">
                                <div class="flex justify-center">
                                    <span>ولایت</span>
                                    @if ($sortField === 'province_id')
                                        <span
                                            class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                    @else
                                        <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200">
                                <div class="flex justify-center">
                                    <span>آدرس</span>
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200">
                                <div class="flex justify-center">
                                    <span>سهام داران</span>
                                </div>
                            </th>




                            @if (auth()->user()->can('ویرایش شرکت') || auth()->user()->can('حذف شرکت'))
                                <th scope="col" class="px-3 py-2 border border-slate-200">
                                    <div class="flex justify-center">
                                        <span>اعمال</span>
                                    </div>
                                </th>
                            @endif

                        </tr>
                    </thead>
                    <tbody wire:init="loadTableData">
                        @if ($companies && count($companies))
                            @foreach ($companies as $index => $company)
                                <tr class="border-b hover:bg-warning-400">
                                    <td class="px-3 py-2 border border-slate-200">
                                        @if ($perPage)
                                            {{ $companies->firstItem() + $index }}
                                        @else
                                            {{ ++$index }}
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $company->name_dr ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $company->name_en ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $company->license_num ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $company->tin_num ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        @if ($provinces && count($provinces))
                                            @foreach ($provinces as $pr)
                                                @if ($company->province_id == $pr->id)
                                                    {{ $pr->name ?? '' }}
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $company->address ?? '' }}
                                    </td>
                                    @can('اضافه نمودن سهام دار')
                                        <td class="px-3 py-2 border border-slate-200">
                                            <span wire:click="openShareholders({{ $company->id }})"
                                                class="text-lg px-3 pt-5 cursor-pointer"><i
                                                    class="fa  fa-eye text-yellow-500  hover:text-sky-800"></i></span>
                                        </td>
                                    @endcan

                                    <td class="px-2 py-2 border border-slate-200 dark:text-white">
                                        @can('ویرایش شرکت')
                                            <button
                                                @click=" @this.call('editCompany', {{ $company->id }}); @this.call('openForm',0) "
                                                class=" text-gray-900 px-2 py-2 rounded">
                                                <span class="text-xl px-3 pt-5"><i
                                                        class="fa  fa-edit text-sky-600"></i></span>
                                            </button>
                                        @endcan
                                        @can('حذف شرکت')
                                            <button @click=" @this.call('toggleConfirm', {{ $company->id }})"
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
                    @if ($perPage && $companies->isNotEmpty())
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <span>{{ $companies->links('vendor.pagination.tailwind') }}</span>
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
@push('customJs')
    <script>
        function toggleInputDisable(shareholderId) {
            var checkbox = document.querySelector(`input[type="checkbox"][value="${shareholderId}"]`);
            var input = document.getElementById(`sharePercentageInput_${shareholderId}`);

            if (checkbox.checked) {
                input.disabled = false; // Enable the input field
            } else {
                input.disabled = true; // Disable the input field
                input.value = null;
                @this.dispatchSelf('inputUpdated', {
                    shareholderId: shareholderId,
                    value: null
                });
            }
        }

        function validatePercentage(input) {
            if (input.value < 0) {
                input.value = 0;
            } else if (input.value > 100) {
                input.value = 100;
            }
        }
    </script>
@endpush
