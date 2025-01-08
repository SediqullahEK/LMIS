<div class="relative bg-white shadow-md sm:rounded-lg mx-4 my-4">
    <x-topHeader pageTitle='اشخاص' />
    <div class="bg-white w-full flex flex-col gap-5 px-2 py-2 md:px-16 lg:px-6 md:flex-row text-[#161931]" dir='rtl'>

        <div class="w-full px-6 pb-8 mt-2">
            {{-- alerts section --}}
            <div wire:loading wire:target="openForm, toggleConfirm">
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

            <div class="flex flex-wrap items-center justify-between mb-2">

                <!-- Search Input -->
                <div class="relative flex items-center mt-4  group text-left {{ $currentPage > 1 ? 'hidden' : '' }}">
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

                            <span>جستجو به اساس نام کاربری، آی آدرس، نوعیت دستگاه</span>
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
                                wire:click="sortBy('users.full_name')">
                                <div class="flex justify-center">
                                    <span>نام کاربر</span>
                                    @if ($sortField === 'users.full_name')
                                        <span
                                            class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                    @else
                                        <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200">
                                <div class="flex justify-center">
                                    <span>اعمال</span>
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer"
                                wire:click="sortBy('activity_logs.model')">
                                <div class="flex justify-center">
                                    <span>مودَل</span>
                                    @if ($sortField === 'activity_logs.model')
                                        <span
                                            class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                    @else
                                        <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200">
                                <div class="flex justify-center">
                                    <span>آی پی آدرس</span>
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer"
                                wire:click="sortBy('activity_logs.os')">
                                <div class="flex justify-center">
                                    <span>نوعیت OS</span>
                                    @if ($sortField === 'activity_logs.os')
                                        <span
                                            class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                    @else
                                        <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer"
                                wire:click="sortBy('activity_logs.browser')">
                                <div class="flex justify-center">
                                    <span>نوعیت مرورگر</span>
                                    @if ($sortField === 'activity_logs.browser')
                                        <span
                                            class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                    @else
                                        <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer"
                                wire:click="sortBy('activity_logs.device_type')">
                                <div class="flex justify-center">
                                    <span>نوعیت دستگاه</span>
                                    @if ($sortField === 'activity_logs.device_type')
                                        <span
                                            class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                    @else
                                        <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer "
                                wire:click="sortBy('activity_logs.created_at')">
                                <div class="flex justify-center">
                                    <span>تاریخ</span>
                                    @if ($sortField === 'activity_logs.created_at')
                                        <span
                                            class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                    @else
                                        <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                    @endif
                                </div>
                            </th>

                            <th scope="col" class="px-3 py-2 border border-slate-200">
                                <div class="flex justify-center">
                                    <span>توضیحات</span>
                                </div>
                            </th>
                            <th scope="col" class="px-3 py-2 border border-slate-200">
                                <div class="flex justify-center">
                                    <span>بیشتر</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody wire:init="loadTableData">
                        @if ($logs && count($logs))
                            @foreach ($logs as $index => $log)
                                <tr class="border-b hover:bg-warning-400">
                                    <td class="px-3 py-2 border border-slate-200">
                                        @if ($perPage)
                                            {{ $logs->firstItem() + $index }}
                                        @else
                                            {{ ++$index }}
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $log->full_name ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $log->action ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $log->model ?? '' }}
                                    </td>
                                    {{-- <td class="px-3 py-2 border border-slate-200">
                                        {{ $log->changes ?? '' }}
                                    </td> --}}
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $log->ip_address ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $log->os ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $log->browser ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $log->device_type ?? '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">
                                        {{ $log->created_at ?? '' }}
                                    </td>
                                    <td class="px-2 py-2 border border-slate-200 ">
                                        {{ $log->description }}
                                    </td>
                                    <td>
                                        <button @click=" @this.call('toggleMore', {{ $log->id }})"
                                            {{ $log->changes ? '' : 'disabled' }}
                                            class="flex items-center justify-center w-8 h-4 text-gray-100 {{ $log->changes ? 'bg-[#D4AF37] hover:bg-sky-900' : 'bg-gray-300' }}  rounded-full mr-2">
                                            <span class="text-xl">
                                                <i class="fa fa-ellipsis-h"></i>
                                            </span>
                                        </button>
                                    </td>

                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                {{-- detials modal --}}
                <div x-data="{ more: @entangle('more') }">
                    <!-- Modal Overlay -->
                    <div x-show="more" id="moreModal" x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
                        <!-- Modal Container -->
                        <div id="modal" x-show="more" x-cloak
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90"
                            class="bg-white p-6 rounded-lg shadow-2xl max-w-lg w-full mx-4">
                            @if ($details)
                                <!-- Modal Header -->
                                <div class="flex justify-between items-center pb-4 border-b w-full">
                                    <h2 class="text-xl font-semibold text-gray-800">
                                        جزئیات لاگ
                                    </h2>
                                    <button @click="more = false"
                                        class="text-gray-500 hover:text-gray-700 text-2xl focus:outline-none">&times;</button>
                                </div>

                                <!-- Modal Body -->
                                <div class="mt-4">
                                    <h3 class="font-medium text-lg text-gray-700">تغییرات:</h3>
                                    <div
                                        class="mt-2 bg-gray-100 p-4 rounded-md text-sm text-gray-600 leading-relaxed overflow-x-auto">
                                        <pre class="whitespace-pre-wrap break-words">
                                            {{ json_encode(json_decode($details->changes), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                                        </pre>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="mt-6 text-right">
                                    <button @click="more = false"
                                        class="px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800 focus:outline-none focus:ring focus:ring-gray-300">
                                        بستن
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

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
                    @if ($perPage && $logs->isNotEmpty())
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <span>{{ $logs->links('vendor.pagination.tailwind') }}</span>
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
