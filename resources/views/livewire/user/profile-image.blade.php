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
                <li class="flex items-center justify-between px-4 py-2 hover:bg-gray-100">
                    <a href="{{ route('user_management') }}" class="flex-1 text-right">مدیریت کاربران</a>
                    <i class="fa fa-id-card text-gray-600"></i>
                </li>
            @endcan

            <li class="flex items-center justify-between px-4 py-2 hover:bg-gray-100">
                <a href="{{ route('user_profile') }}" class="flex-1 text-right">پروفایل</a>
                <i class="fa fa-user text-gray-600"></i>
            </li>

            <li>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-between px-4 py-2 hover:bg-gray-100">
                        <span class="flex-1 text-right">خارج شدن از سیستم</span>
                        <i class="fa fa-door-open text-gray-600"></i>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
