<div class="w-full px-6 pb-8 mt-2">
    {{-- loader --}}
    <div wire:loading wire:target="openForm, updateRole, addRole, toggleConfirm, openPermissions">
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
            <div x-show="confirm" class="fixed inset-0 z-50 flex items-start justify-center bg-gray-900 bg-opacity-50">
                <!-- Modal Container -->
                <div id="modal" x-show="confirm" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-90"
                    class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full mx-auto mt-12">

                    <!-- Modal Header -->
                    <div class="flex justify-between items-center pb-4 border-b w-full max-w-3xl">
                        <h2 class="text-xl font-semibold">
                            آیا مطمعن هستید وظیفه ذیل را حذف کنید؟
                        </h2>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-center gap-4 mt-6">
                        <button id="cancelButton" @click=" @this.call('toggleConfirm', 0)"
                            class="bg-red-600 text-white px-5 py-2 rounded-md hover:bg-red-500 transition">
                            لغو
                        </button>
                        <button id="confirmButton" @click=" @this.call('deleteRole')"
                            class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-500 transition">
                            تایید
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    {{-- Add permission to role section --}}
    @can('دادن صلاحیت')
        <div x-data="{ showPermissions: @entangle('showPermissions') }" dir="rtl">
            <div x-show="showPermissions" style="display: none;"
                class="fixed inset-0 z-50 flex items-start justify-center bg-gray-900 bg-opacity-50 overflow-y-auto">
                <div wire:loading wire:target="toggleSelectAllPermissions">
                    <x-loader />
                </div>
                <!-- Modal Structure -->
                <div x-show="showPermissions" x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-90"
                    class="bg-white p-6 rounded-lg shadow-lg w-full max-w-3xl mt-12">

                    <!-- Modal Content -->
                    <div>
                        <!-- Modal Header -->
                        <div class="flex justify-between items-center pb-4 border-b w-full max-w-3xl">
                            <h2 class="text-xl font-semibold"> صلاحیت های {{ $role }}</h2>

                            <button @click="showPermissions = false; searchedPermission ='' ;"
                                class="text-gray-500 hover:text-gray-700 text-4xl p-2">&times;</button>
                        </div>
                        <input type="text"
                            class="border border-gray-300 w-full rounded-lg py-2 px-4 my-2 shadow-sm focus:ring-2 focus:ring-sky-100 focus:outline-none focus:border-blue-500 hover:border-gray-400 transition-all duration-150"
                            placeholder="جستجو" wire:model.live="searchedPermission">


                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 ">
                            <div class="flex items-center mb-2">
                                <label class="text-gray-700 cursor-pointer">
                                    <input type="checkbox" wire:model="selectAllPermissions"
                                        wire:change="toggleSelectAllPermissions" class="ml-2 rounded" />
                                    انتخاب همه
                                </label>
                            </div>
                            @if ($allPermissions)
                                @foreach ($allPermissions as $pr)
                                    <div class="flex items-center">
                                        <label class="text-gray-700 cursor-pointer">
                                            <input type="checkbox" wire:model="permissions" value="{{ $pr->name }}"
                                                @if (in_array($pr->name, $rolePermissions)) checked @endif class="ml-2 rounded" />
                                            {{ $pr->name }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif

                        </div>

                        <div class="mt-4">
                            <button wire:click="addPermissionsToRole"
                                class="text-sm h-10 px-8 bg-[#189197]  rounded-lg text-white hover:bg-[#189179] focus:outline-none focus:ring-2 focus:ring-blue-600"
                                title="ذخیره">
                                ذخیره
                            </button>

                            <button type="button"
                                class="text-sm h-10 px-8 bg-red-800 rounded-lg text-white hover:bg-red-700 hover:text-black focus:outline-none focus:ring-2 focus:ring-red-600"
                                x-on:click="showPermissions = false; searchedPermissions ='';" title="لفو">
                                لفو
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endcan

    {{-- Add and Edit Role form section --}}
    @can('ایجاد وظیفه جدید')
        <button @click=" @this.call('resetForm'); @this.call('openForm',1)"
            class="px-4 py-1 mb-1 bg-[#189197] rounded-lg text-2xl text-white"><i class="fa fa-plus"></i></button>
    @endcan

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
                                {{ $isEditing ? 'ویرایش وظیفه' : 'افزودن وظیفه جدید' }}
                            </h2>
                            <button @click="isOpen = false; @this.call('resetForm');"
                                class="text-gray-500 hover:text-gray-700 text-4xl p-2">&times;</button>
                        </div>

                        <!-- Form -->
                        <form wire:submit.prevent="{{ $isEditing ? 'updateRole' : 'addRole' }}"
                            class="grid lg:grid-cols-1 md:grid-cols-1 sm:grid-cols-1 gap-4">
                            @csrf
                            @if ($isEditing)
                                <input type="hidden" wire:model.live="roleId" />
                            @endif

                            <!-- Scale Selection -->
                            <span class="col-span-1 text-right">
                                <label class="font-bold text-sm">وظیفه</label>
                                <span class="text-red-700">*</span>

                                <input type="text" wire:model.live="role"
                                    class="mt-1 px-4 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    autocomplete="off" dir="rtl">
                                @error('role')
                                    <p class="text-red-500">{{ $message }}</p>
                                @enderror
                            </span>

                            <!-- Buttons (Below Input) -->
                            <div class="">
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
                    <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                        <div class="flex justify-center">
                            <span>وظیفه</span>
                        </div>
                    </th>
                    @can('دادن صلاحیت')
                        <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                            <div class="flex justify-center">
                                <span>صلاحیت ها</span>
                            </div>
                        </th>
                    @endcan
                    @if (auth()->user()->can('ایجاد وظیفه جدید') || auth()->user()->can('ویرایش وظیفه'))
                        <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                            <div class="flex justify-center">
                                <span>اعمال</span>
                            </div>
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody wire:init="loadTableData">
                @foreach ($roles as $index => $rl)
                    <tr class="border-b hover:bg-warning-400">
                        <td class="px-3 py-2 border border-slate-200">
                            @if ($perPage)
                                {{ $roles->firstItem() + $index }}
                            @else
                                {{ ++$index }}
                            @endif
                        </td>
                        <td class="px-3 py-2 border border-slate-200">
                            {{ $rl->name ?? '' }}
                        </td>
                        @can('دادن صلاحیت')
                            <td class="px-3 py-2 border border-slate-200">
                                <span wire:click="openPermissions({{ $rl->id }})"
                                    class="text-lg px-3 pt-5 cursor-pointer"><i
                                        class="fa  fa-eye text-yellow-500  hover:text-sky-800"></i></span>
                            </td>
                        @endcan

                        <td class="px-2 py-2 border border-slate-200 dark:text-white">
                            @can('ویرایش وظیفه')
                                <button @click=" @this.call('editRole', {{ $rl->id }}); @this.call('openForm',0) "
                                    class=" text-gray-900 px-2 py-2 rounded">
                                    <span class="text-xl px-3 pt-5"><i class="fa  fa-edit text-sky-600"></i></span>
                                </button>
                            @endcan
                            @can('حذف وظیفه')
                                <button @click=" @this.call('toggleConfirm', {{ $rl->id }})"
                                    class=" text-gray-900 px-2 py-2 rounded">
                                    <span class="text-xl px-3 pt-5"><i class="fa  fa-trash text-red-600"></i></span>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <span wire:loading wire:target="loadTableData,perPage">
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
            @if ($perPage && $roles->isNotEmpty())
                <div class="flex items-center space-x-2 space-x-reverse">
                    <span>{{ $roles->links('vendor.pagination.tailwind') }}</span>
                    <span wire:loading wire:target="previousPage, nextPage, gotoPage">
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
                </div>
            @endif
        </nav>
    </div>

</div>
