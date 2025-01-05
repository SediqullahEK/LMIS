<div class="relative bg-white shadow-md sm:rounded-lg mx-4 my-4">
    <x-topHeader pageTitle='مدیریت کاربران' />
    <div class="bg-white w-full flex flex-col gap-5 px-2 py-2 md:px-16 lg:px-6 md:flex-row text-[#161931]" dir='rtl'>

        <!-- Loader -->
        <div wire:loading wire:target="addUser, updateUser, deleteUser, toggle">
            <x-loader />
        </div>

        <!-- Sidebar -->
        <aside class="py-4 w-full md:w-1/3 lg:w-1/4">
            <div class="p-4 text-sm border-l border-gray-200">
                <a href="#" wire:click="toggle('user')"
                    class="flex items-center mb-2 px-3 py-2.5 font-bold border rounded-full transition
                            @if ($user) bg-[#D4AF37] text-gray-100 @else bg-white text-gray-900 @endif
                            hover:bg-[#D4AF37] hover:text-gray-100">
                    <i class="fa fa-user ml-2"></i>
                    کاربران
                </a>

                <a href="#" wire:click="toggle('role')"
                    class="flex items-center mb-2 px-3 py-2.5 font-bold border rounded-full transition
                            @if ($role) bg-[#D4AF37] text-gray-100 @else bg-white text-gray-900 @endif
                            hover:bg-[#D4AF37] hover:text-gray-100">
                    <i class="fa fa-user-shield ml-2"></i>
                    وظایف
                </a>

                <a href="#" wire:click="toggle('permission')"
                    class="flex items-center mb-2 px-3 py-2.5 font-bold border rounded-full transition
                            @if ($permission) bg-[#D4AF37] text-gray-100 @else bg-white text-gray-900 @endif
                            hover:bg-[#D4AF37] hover:text-gray-100">
                    <i class="fa fa-key ml-2"></i>
                    صلاحیت ها
                </a>

            </div>
        </aside>

        <!-- Main Content -->
        <main class="w-full min-h-screen py-4 mx-0">
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

            {{-- components section --}}
            @if ($user)

                <div class="w-full px-6 pb-8 mt-2">
                    {{-- alerts section --}}
                    <div wire:loading wire:target="openForm, updateUser, createUser, toggleConfirm, openRoles">
                        <x-loader />
                    </div>
                    @can('حذف کاربر')
                        <div x-data="{ confirm: @entangle('confirm') }">
                            <!-- Modal Overlay -->
                            <div x-show="confirm" x-cloak
                                class="fixed inset-0 z-50 flex items-start justify-center bg-gray-900 bg-opacity-50">
                                <!-- Modal Container -->
                                <div id="modal" x-show="confirm" x-cloak
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-90"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-90"
                                    class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full mx-auto mt-12">

                                    <!-- Modal Header -->
                                    <div class="flex justify-between items-center pb-4 border-b w-full max-w-4xl">
                                        <h2 class="text-xl font-semibold">
                                            آیا مطمعن هستید کاربر ذیل را حذف کنید؟
                                        </h2>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="flex justify-center gap-4 mt-6">
                                        <button id="cancelButton" @click=" @this.call('toggleConfirm', 0)"
                                            class="bg-red-600 text-white px-5 py-2 rounded-md hover:bg-red-500 transition">
                                            لغو
                                        </button>
                                        <button id="confirmButton" @click=" @this.call('deleteUser')"
                                            class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-500 transition">
                                            تایید
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan



                    {{-- Add/Edit user section --}}
                    @can('ایجاد کاربر جدید')
                        <button @click=" @this.call('resetForm'); @this.call('openForm',1)"
                            class="px-4 py-1 mb-1 bg-[#189197] rounded-lg text-2xl text-white"><i
                                class="fa fa-plus"></i></button>
                    @endcan
                    @if (auth()->user()->can('ویرایش کاربر') || auth()->user()->can('ایجاد کاربر جدید'))
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

                                    <!-- Scrollable Modal Content -->
                                    <div class="overflow-y-auto max-h-[80vh]">
                                        <!-- Modal Header -->
                                        <div class="flex justify-between items-center pb-4 border-b w-full">
                                            <h2 class="text-xl font-semibold">
                                                {{ $isEditing ? 'ویرایش کاربر' : 'افزودن کاربر جدید' }}
                                            </h2>
                                            <button @click="isOpen = false; @this.call('resetForm');"
                                                class="text-gray-500 hover:text-gray-700 text-4xl p-2">&times;</button>
                                        </div>

                                        <!-- Form -->
                                        <form wire:submit.prevent="{{ $isEditing ? 'updateUser' : 'addUser' }}"
                                            class="grid lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-1 gap-4">
                                            <!-- Full Name -->
                                            <input type="number" hidden wire:model.live='userId'>
                                            <span class="col-span-2 text-right">
                                                <label class="font-bold text-sm">نام مکمل</label>
                                                <span class="text-red-700">*</span>
                                                <input type="text" required wire:model.live="full_name"
                                                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                    autocomplete="off" dir="rtl">
                                                @error('full_name')
                                                    <p class="text-red-500">{{ $message }}</p>
                                                @enderror
                                            </span>

                                            <!-- User Name -->
                                            <span class="col-span-2 text-right">
                                                <label class="font-bold text-sm">نام کاربری</label>
                                                <span class="text-red-700">*</span>
                                                <input type="text" required wire:model.live="user_name"
                                                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                    autocomplete="off" dir="rtl">
                                                @error('user_name')
                                                    <p class="text-red-500">{{ $message }}</p>
                                                @enderror
                                            </span>

                                            <!-- Email -->
                                            <span class="col-span-2 text-right">
                                                <label class="font-bold text-sm">ایمیل</label>
                                                <span class="text-red-700">*</span>
                                                <input type="email" required wire:model.live="email"
                                                    class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                    autocomplete="off" dir="rtl">
                                                @error('email')
                                                    <p class="text-red-500">{{ $message }}</p>
                                                @enderror
                                            </span>



                                            <span class="col-span-2 text-right">
                                                <label class="font-bold text-sm">ریاست</label>
                                                <span class="text-red-700">*</span>
                                                <select required wire:model.live="department"
                                                    class="mt-1 px-2 peer block h-10 w-full bg-blue border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                                    <option value="0" disabled hidden selected>ریاست کاربر را
                                                        انتخاب
                                                        کنید</option>
                                                    @if ($departments && count($departments))
                                                        @foreach ($departments as $dp)
                                                            <option value="{{ $dp->id }}">{{ $dp->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif

                                                </select>
                                                @error('department')
                                                    <p class="text-red-500">{{ $message }}</p>
                                                @enderror
                                            </span>

                                            @if (!$isEditing)
                                                <span class="col-span-2 text-right">
                                                    <label class="font-bold text-sm">رمز عبور</label>
                                                    <span class="text-red-700">*</span>
                                                    <input type="password" required wire:model.live="password"
                                                        class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                        autocomplete="off" dir="rtl">
                                                    @error('password')
                                                        <p class="text-red-500">{{ $message }}</p>
                                                    @enderror
                                                </span>

                                                <span class="col-span-2 text-right">
                                                    <label class="font-bold text-sm">تایید رمز عبور</label>
                                                    <span class="text-red-700">*</span>
                                                    <input type="password" required
                                                        wire:model.live="password_confirmation"
                                                        class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                        autocomplete="off" dir="rtl">
                                                    @error('password_confirmation')
                                                        <p class="text-red-500">{{ $message }}</p>
                                                    @enderror
                                                </span>
                                            @endif

                                            <div class="flex ">
                                                <span class="w-1/2 text-right ">
                                                    <label class="font-bold text-sm">عکس پروفایل</label>
                                                    <input type="file" wire:model.live="profile_image"
                                                        id="file-upload" accept="image/*" class="hidden" />
                                                    <label for="file-upload"
                                                        class="cursor-pointer mt-1 block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm flex items-center justify-center text-gray-700 hover:bg-gray-100 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                                        انتخاب عکس
                                                    </label>
                                                    @error('profile_image')
                                                        <p class="text-red-500">{{ $message }}</p>
                                                    @enderror
                                                </span>

                                                <!-- Image Preview -->
                                                <span class="w-1/2 flex items-center justify-center ">
                                                    <span wire:loading wire:target="profile_image"
                                                        class="col-start-5 col-span-1 justify-self-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24">
                                                            <rect width="6" height="14" x="1" y="4"
                                                                fill="black">
                                                                <animate id="svgSpinnersBarsFade0" fill="freeze"
                                                                    attributeName="opacity"
                                                                    begin="0;svgSpinnersBarsFade1.end-0.175s"
                                                                    dur="0.525s" values="1;0.2" />
                                                            </rect>
                                                            <rect width="6" height="14" x="9" y="4"
                                                                fill="black" opacity="0.4">
                                                                <animate fill="freeze" attributeName="opacity"
                                                                    begin="svgSpinnersBarsFade0.begin+0.105s"
                                                                    dur="0.525s" values="1;0.2" />
                                                            </rect>
                                                            <rect width="6" height="14" x="17" y="4"
                                                                fill="black" opacity="0.3">
                                                                <animate id="svgSpinnersBarsFade1" fill="freeze"
                                                                    attributeName="opacity"
                                                                    begin="svgSpinnersBarsFade0.begin+0.21s"
                                                                    dur="0.525s" values="1;0.2" />
                                                            </rect>
                                                        </svg>
                                                    </span>
                                                    @if ($profile_image)
                                                        <img src="{{ $profile_image->temporaryUrl() }}"
                                                            width="100" class="rounded mr-4" alt="Uploaded image">
                                                    @elseif ($existing_image_path)
                                                        <img src="{{ Storage::url($existing_image_path) }}"
                                                            width="100" class="rounded mr-4" alt="Existing image">
                                                    @endif

                                                </span>
                                            </div>


                                            @if ($isEditing)
                                                <span class="col-span-3 text-right mt-4">

                                                    <label class="inline-flex items-center me-5 cursor-pointer">
                                                        <input type="checkbox" wire:model.live="passwordUpdate"
                                                            class="sr-only peer" checked>
                                                        <div
                                                            class="relative w-11 h-6 bg-gray-200 rounded-full peer   peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600">
                                                        </div>
                                                        <span class="ms-3 text-sm font-medium text-gray-900 ">تغییر
                                                            رمز
                                                            کاربری</span>
                                                    </label>
                                                </span>
                                            @endif

                                            @if ($passwordUpdate)
                                                <span class="col-span-2 text-right">
                                                    <label class="font-bold text-sm">رمز عبور</label>
                                                    <span class="text-red-700">*</span>
                                                    <input type="password" required wire:model.live="password"
                                                        class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                        autocomplete="off" dir="rtl">
                                                    @error('password')
                                                        <p class="text-red-500">{{ $message }}</p>
                                                    @enderror
                                                </span>

                                                <span class="col-span-2 text-right">
                                                    <label class="font-bold text-sm">تایید رمز عبور</label>
                                                    <span class="text-red-700">*</span>
                                                    <input type="password" required
                                                        wire:model.live="password_confirmation"
                                                        class="mt-1 px-2 peer block h-10 w-full bg-white border border-slate-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                        autocomplete="off" dir="rtl">
                                                    @error('password_confirmation')
                                                        <p class="text-red-500">{{ $message }}</p>
                                                    @enderror
                                                </span>
                                            @endif

                                            <div class="col-span-full flex justify-start space-x-4">
                                                <button type="submit"
                                                    class="text-sm h-10 ml-2 px-8 bg-[#189197] rounded-lg text-white hover:bg-[#189179] focus:outline-none focus:ring-2 focus:ring-blue-600"
                                                    title="{{ $isEditing ? 'به‌روزرسانی' : 'ذخیره' }}">
                                                    {{ $isEditing ? 'به‌روزرسانی' : 'ذخیره' }}
                                                </button>
                                                <button type="button"
                                                    class="text-sm h-10 px-8 bg-red-800 rounded-lg text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                                                    x-on:click="isOpen = false; $wire.call('resetForm')"
                                                    title="لفو">
                                                    لفو
                                                </button>

                                            </div>
                                        </form>
                                    </div>

                                    <!-- Modal Footer (Optional) -->

                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Add Roles to user section --}}
                    <div x-data="{ showRoles: @entangle('showRoles') }" dir="rtl">
                        <div x-show="showRoles" style="display: none;"
                            class="fixed inset-0 z-50 flex items-start justify-center bg-gray-900 bg-opacity-50">
                            <div wire:loading wire:target="toggleSelectAllRoles, addRolesToUser">
                                <x-loader />
                            </div>
                            <!-- Modal Structure -->
                            <div x-show="showRoles" x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-90"
                                class="bg-white p-6 rounded-lg shadow-lg w-full max-w-3xl mt-12">

                                <!-- Modal Content -->
                                <div>
                                    <!-- Modal Header -->
                                    <div class="flex justify-between items-center pb-4 border-b w-full max-w-3xl">
                                        <h2 class="text-xl font-semibold"> وظایف {{ $selectedUser }}</h2>

                                        <button @click="showRoles = false;"
                                            class="text-gray-500 hover:text-gray-700 text-4xl p-2">&times;</button>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                        <div class="flex items-center mb-2">
                                            <label class="text-gray-700 cursor-pointer">
                                                <input type="checkbox" wire:model="selectAllRoles"
                                                    wire:change="toggleSelectAllRoles" class="ml-2 rounded" />
                                                انتخاب همه
                                            </label>
                                        </div>

                                        @if ($allRoles)
                                            @foreach ($allRoles as $rl)
                                                <div class="flex items-center">
                                                    <label class="text-gray-700 cursor-pointer">
                                                        <input type="checkbox" wire:model="roles"
                                                            value="{{ $rl->name }}"
                                                            @if (in_array($rl->name, $userRoles)) checked @endif
                                                            class="ml-2 rounded" />
                                                        {{ $rl->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="mt-4">
                                        <button wire:click="addRolesToUser"
                                            class="text-sm h-10 px-8 bg-[#189197]  rounded-lg text-white hover:bg-[#189179] focus:outline-none focus:ring-2 focus:ring-blue-600"
                                            title="ذخیره">
                                            ذخیره
                                        </button>

                                        <button type="button"
                                            class="text-sm h-10 px-8 bg-red-800 rounded-lg text-white hover:bg-red-700 hover:text-black focus:outline-none focus:ring-2 focus:ring-red-600"
                                            x-on:click="showRoles = false;" title="لفو">
                                            لفو
                                        </button>
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
                                    <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                                        <div class="flex justify-center">
                                            <span>نام مکمل</span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                                        <div class="flex justify-center">
                                            <span>نام کاربری</span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                                        <div class="flex justify-center">
                                            <span>ایمیل</span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                                        <div class="flex justify-center">
                                            <span>ریاست</span>
                                        </div>
                                    </th>
                                    @can('دادن وظیفه')
                                        <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                                            <div class="flex justify-center">
                                                <span>وظایف</span>
                                            </div>
                                        </th>
                                    @endcan

                                    @if (auth()->user()->can('ویرایش کاربر') || auth()->user()->can('حذف کاربر'))
                                        <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer">
                                            <div class="flex justify-center">
                                                <span>اعمال</span>
                                            </div>
                                        </th>
                                    @endif

                                </tr>
                            </thead>
                            <tbody wire:init="loadTableData">
                                @if ($users && count($users))
                                    @foreach ($users as $index => $user)
                                        <tr class="border-b hover:bg-warning-400">
                                            <td class="px-3 py-2 border border-slate-200">
                                                @if ($perPage)
                                                    {{ $users->firstItem() + $index }}
                                                @else
                                                    {{ ++$index }}
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 border border-slate-200">
                                                {{ $user->full_name ?? '' }}
                                            </td>
                                            <td class="px-3 py-2 border border-slate-200">
                                                {{ $user->user_name ?? '' }}
                                            </td>
                                            <td class="px-3 py-2 border border-slate-200">
                                                {{ $user->email ?? '' }}
                                            </td>
                                            <td class="px-3 py-2 border border-slate-200">
                                                @if ($departments && count($departments))
                                                    @foreach ($departments as $dp)
                                                        @if ($user->department_id == $dp->id)
                                                            {{ $dp->name ?? '' }}
                                                        @endif
                                                    @endforeach
                                                @endif


                                            </td>
                                            @can('دادن وظیفه')
                                                <td class="px-3 py-2 border border-slate-200">
                                                    <span wire:click="openRoles({{ $user->id }})"
                                                        class="text-lg px-3 pt-5 cursor-pointer"><i
                                                            class="fa  fa-eye text-yellow-500  hover:text-sky-800"></i></span>
                                                </td>
                                            @endcan

                                            <td class="px-2 py-2 border border-slate-200 dark:text-white">
                                                @can('ویرایش کاربر')
                                                    <button
                                                        @click=" @this.call('editUser', {{ $user->id }}); @this.call('openForm',0) "
                                                        class=" text-gray-900 px-2 py-2 rounded">
                                                        <span class="text-xl px-3 pt-5"><i
                                                                class="fa  fa-edit text-sky-600"></i></span>
                                                    </button>
                                                @endcan
                                                @can('حذف کاربر')
                                                    <button @click=" @this.call('toggleConfirm', {{ $user->id }})"
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
                        <span wire:loading wire:target="loadTableData,perPage">
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
                </div>

                {{-- Role component render section --}}
            @elseif ($role)
                <livewire:auth.role />
                {{-- Permission component render section --}}
            @elseif ($permission)
                <livewire:auth.permission />
            @endif


        </main>

    </div>
</div>




@push('customJs')
    <script>
        function openModal() {
            const confirmButton = document.getElementById("confirmButton");
            @this.isOpen = true;
            confirmButton.onclick = function() {
                @this.deleteProfileImage();
                closeModal();
            };
        }

        function closeModal() {
            @this.isOpen = false;
        }
    </script>
@endpush
