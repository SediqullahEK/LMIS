<div>
    <div id="userMenuToggle" class="flex items-center cursor-pointer">
        @if (auth()->check())
            <p class="text-white mr-4">{{ $user->full_name }}</p>
            <img id="profile-photo"
                src="{{ $user->profile_photo_path ? url('storage/' . $user->profile_photo_path) : url('storage/user_profiles/profileIcon.png') }}"
                class="rounded-full" style="height: 40px; width: 40px" alt="" loading="lazy" wire:loading.attr="src">
        @endif


    </div>

    <!-- User Menu Dropdown -->
    <div id="userMenuDropdown" class="absolute top-14 right-4 hidden w-48 bg-white rounded-lg shadow-lg" dir="rtl">
        <ul class="py-2 text-sm text-gray-700">
            @can('مدیریت کاربران')
                <li>
                    <a href="{{ route('user_management') }}" class="block px-4 py-2 hover:bg-gray-100">مدیریت
                        کاربران</a>
                </li>
            @endcan

            <li>
                <a href="{{ route('user_profile') }}" class="block px-4 py-2 hover:bg-gray-100">پروفایل</a>
            </li>
            <li>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="w-full text-right px-4 py-2 hover:bg-gray-100">
                        خارج شدن از سیستم
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
