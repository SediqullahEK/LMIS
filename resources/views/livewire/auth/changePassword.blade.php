<div>
    {{-- This section is shown when the user attempts to login for the very first time --}}
    @if (!auth()->user()->first_logged_in)
        <div class="relative bg-white shadow-md sm:rounded-lg mx-4 my-4">
            <x-topHeader pageTitle="تغییر رمز عبور" />
            <div class="bg-white w-full flex flex-col gap-5 px-4 py-6 md:px-16 lg:px-6 text-[#161931]" dir="rtl">
                <div wire:loading wire:target ="updatePassword">
                    <x-loader />
                </div>
                <div class="w-full sm:max-w-xl mx-auto">
                    {{-- Alerts section --}}
                    @if (session()->has('message'))
                        <div x-data="{ show: @json(session()->has('message')) }" x-init="setTimeout(() => show = false, 3500)" x-show="show"
                            class="fixed top-16 left-1/2 transform -translate-x-1/2 bg-green-300 text-green-800 px-4 py-3 shadow-lg rounded-lg flex items-center gap-3 w-auto">
                            <button @click="show = false"
                                class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>

                            <span>{{ session('message') }}</span>

                            <svg class="h-6 w-6 text-green-700" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 4.293a1 1 0 00-1.414 0L8 11.586 4.707 8.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>

                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div x-data="{ show: @json(session()->has('error')) }" x-init="setTimeout(() => show = false, 3500)" x-show="show"
                            class="fixed top-16 left-1/2 transform -translate-x-1/2 bg-red-300 text-red-800 px-4 py-3 shadow-lg rounded-lg flex items-center gap-3 w-auto">

                            <button @click="show = false"
                                class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>

                            <span>{{ session('error') }}</span>

                            <svg class="h-6 w-6 text-red-700" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.779-1.36 3.544 0l6.518 11.6c.777 1.384-.19 3.1-1.772 3.1H3.511c-1.582 0-2.549-1.716-1.772-3.1l6.518-11.6zM11 13a1 1 0 10-2 0 1 1 0 002 0zM10 9a1 1 0 00-.993.883L9 10v1a1 1 0 001.993.117L11 11V10a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    @endif

                    {{-- Change Password Form --}}
                    <form wire:submit.prevent="updatePassword" class="space-y-4 mb-12">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-sky-900 mb-2">رمز
                                فعلی</label>
                            <div class="relative">
                                <input type="password" id="current_password" wire:model="current_password"
                                    class="bg-[rgba(246,191,12,0.2)] border border-[rgba(246,191,12,0.5)] text-sky-900 text-sm rounded-lg  block w-full p-2.5 transition duration-200 ease-in-out hover:bg-[rgba(246,191,12,0.3)] focus:outline-none"
                                    placeholder="رمز فعلی تان را وارد کنید" required>
                                <button id="currentPasswordToggleBtn" type="button"
                                    aria-label="Toggle password visibility"
                                    class="hidden absolute inset-y-0 left-4 flex items-center text-gray-600 hover:text-sky-500 transition duration-200 ease-in-out">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-sky-900 mb-2">رمز
                                جدید</label>
                            <div class="relative">
                                <input type="password" id="new_password" wire:model="new_password"
                                    class="bg-[rgba(246,191,12,0.2)] border border-[rgba(246,191,12,0.5)] text-sky-900 text-sm rounded-lg  focus:outline-none block w-full p-2.5 transition duration-200 ease-in-out hover:bg-[rgba(246,191,12,0.3)]"
                                    placeholder="رمز جدید تان را وارد کنید" required>
                                <button id="newPasswordToggleBtn" type="button" aria-label="Toggle password visibility"
                                    class="hidden absolute inset-y-0 left-4 flex items-center text-gray-600 hover:text-sky-500 transition duration-200 ease-in-out">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('new_password')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <label for="new_password_confirmation"
                                class="block text-sm font-medium text-sky-900 mb-2">تایید
                                رمز
                                جدید</label>
                            <div class="relative">
                                <input type="password" id="new_password_confirmation"
                                    wire:model="new_password_confirmation"
                                    class="bg-[rgba(246,191,12,0.2)] border border-[rgba(246,191,12,0.5)] text-sky-900 text-sm rounded-lg  focus:outline-none block w-full p-2.5 transition duration-200 ease-in-out hover:bg-[rgba(246,191,12,0.3)]"
                                    placeholder="رمز جدید تان را دوباره وارد کنید" required>

                                <button id="newPasswordConfirmationToggleBtn" type="button"
                                    aria-label="Toggle password visibility"
                                    class="hidden absolute inset-y-0 left-4 flex items-center text-gray-600 hover:text-sky-500 transition duration-200 ease-in-out">
                                    <i class="fas fa-eye"></i>
                                </button>

                            </div>
                            @error('new_password_confirmation')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit"
                                class="w-full text-white bg-sky-800 hover:bg-sky-700 focus:ring-4 focus:ring-sky-300 rounded-lg text-sm px-5 py-2.5 transition duration-200 ease-in-out">
                                ذخیره
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endif
    {{-- This section is shown for already logged in users to changed their password --}}
    @if (auth()->user()->first_logged_in)
        <h1 class="text-xl text-gray-800 font-bold mb-8">تغییر رمز عبور</h1>
        <form wire:submit.prevent="updatePassword" class="space-y-4 mb-12">
            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-sky-900 mb-2">رمز
                    فعلی</label>
                <div class="relative">
                    <input type="password" id="current_password" wire:model="current_password"
                        class="bg-[rgba(246,191,12,0.2)] border border-[rgba(246,191,12,0.5)] text-sky-900 text-sm rounded-lg  block w-full p-2.5 transition duration-200 ease-in-out hover:bg-[rgba(246,191,12,0.3)] focus:outline-none"
                        placeholder="رمز فعلی تان را وارد کنید" required>
                    <button id="currentPasswordToggleBtn" type="button" aria-label="Toggle password visibility"
                        class="hidden absolute inset-y-0 left-4 flex items-center text-gray-600 hover:text-sky-500 transition duration-200 ease-in-out">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('current_password')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label for="new_password" class="block text-sm font-medium text-sky-900 mb-2">رمز
                    جدید</label>
                <div class="relative">
                    <input type="password" id="new_password" wire:model="new_password"
                        class="bg-[rgba(246,191,12,0.2)] border border-[rgba(246,191,12,0.5)] text-sky-900 text-sm rounded-lg  focus:outline-none block w-full p-2.5 transition duration-200 ease-in-out hover:bg-[rgba(246,191,12,0.3)]"
                        placeholder="رمز جدید تان را وارد کنید" required>
                    <button id="newPasswordToggleBtn" type="button" aria-label="Toggle password visibility"
                        class="hidden absolute inset-y-0 left-4 flex items-center text-gray-600 hover:text-sky-500 transition duration-200 ease-in-out">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('new_password')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm New Password -->
            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-sky-900 mb-2">تایید
                    رمز
                    جدید</label>
                <div class="relative">
                    <input type="password" id="new_password_confirmation" wire:model="new_password_confirmation"
                        class="bg-[rgba(246,191,12,0.2)] border border-[rgba(246,191,12,0.5)] text-sky-900 text-sm rounded-lg  focus:outline-none block w-full p-2.5 transition duration-200 ease-in-out hover:bg-[rgba(246,191,12,0.3)]"
                        placeholder="رمز جدید تان را دوباره وارد کنید" required>

                    <button id="newPasswordConfirmationToggleBtn" type="button"
                        aria-label="Toggle password visibility"
                        class="hidden absolute inset-y-0 left-4 flex items-center text-gray-600 hover:text-sky-500 transition duration-200 ease-in-out">
                        <i class="fas fa-eye"></i>
                    </button>

                </div>
                @error('new_password_confirmation')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-start">
                <button type="submit"
                    class="text-white bg-sky-800 hover:bg-sky-700 focus:ring-4 focus:ring-sky-300 rounded-lg text-sm px-5 py-2.5 transition duration-200 ease-in-out">
                    ذخیره
                </button>
            </div>
        </form>
    @endif
</div>
@push('customJs')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Fields and Toggle Buttons
            const fields = [{
                    field: document.getElementById('current_password'),
                    toggleBtn: document.getElementById('currentPasswordToggleBtn'),
                },
                {
                    field: document.getElementById('new_password'),
                    toggleBtn: document.getElementById('newPasswordToggleBtn'),
                },
                {
                    field: document.getElementById('new_password_confirmation'),
                    toggleBtn: document.getElementById('newPasswordConfirmationToggleBtn'),
                },
            ];

            // Function to update visibility of toggle buttons
            function updateToggleButtonVisibility() {
                fields.forEach(({
                    field,
                    toggleBtn
                }) => {
                    if (field.value) {
                        toggleBtn.classList.remove('hidden');
                    } else {
                        toggleBtn.classList.add('hidden');
                    }
                });
            }

            // Add input listeners for dynamic visibility updates
            fields.forEach(({
                field
            }) => {
                field.addEventListener('input', updateToggleButtonVisibility);
            });

            // Add click event listener to toggle password visibility
            fields.forEach(({
                field,
                toggleBtn
            }) => {
                toggleBtn.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
                    field.setAttribute('type', type);

                    // Toggle FontAwesome icon
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            });

            // Re-run visibility check after validation errors (when DOM updates)
            const observer = new MutationObserver(updateToggleButtonVisibility);

            // Observe the form for changes (e.g., error messages being added)
            observer.observe(document.querySelector('form'), {
                childList: true,
                subtree: true,
            });

            // Initial visibility setup
            updateToggleButtonVisibility();
        });
    </script>
@endpush
