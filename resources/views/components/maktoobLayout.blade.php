<div class="card bg-white shadow-lg rounded-lg p-4 w-full md:w-96 mx-auto" style="font-size: 0.4rem;">
    <div class="flex items-center justify-between relative">
        <div class="absolute left-0">
            <img src="{{ asset('storage/system_images/logo.webp') }}" alt="Logo" class="w-8 h-8 md:w-8 md:h-8">
        </div>
        <div class="text-center flex flex-col justify-center items-center mx-auto">
            <div class="flex justify-between items-center">
                <div class="flex flex-col">
                    <p>د افغانستان اسلامی امارت</p>
                    <p>د کانونو او پترولیم وزارت</p>
                </div>
                <img src="{{ asset('storage/system_images/Picture2.jpg') }}" alt="Logo"
                    class="w-8 h-8 md:w-8 md:h-8">
                <div class="flex flex-col">
                    <p>امارت اسلامی افغانستان</p>
                    <p>وزارت معادن و پترولیم</p>
                </div>

            </div>


            <p>Islamic Emirate of Afghanistan</p>
            <p>Ministry of Mines & Petroleum</p>
            <p>ریاست تسهیل سرمایه گذاری و صدور جواز نامه ها</p>
            <p>مدیریت اجرائیه</p>
        </div>
        <div class="absolute right-0">
            <img src="{{ asset('storage/system_images/flagLogo.jpg') }}" alt="Logo" class="w-8 h-8 md:w-8 md:h-8">
        </div>
    </div>
    <div class="flex justify-between items-center">
        <div class="flex flex-col justify-start my-2 space-x-4">
            <p class="font-bold">شماره: ---------</p>

            <p>تاریخ ه.ق:</p>
            <p>تاریخ ه.ش:</p>

        </div>
        <div class="flex justify-end items-center my-1 space-x-1">
            <label>
                <input class="w-2 h-2" type="checkbox" /> عاجل
            </label>
            <label>
                <input class="w-2 h-2" type="checkbox" /> عادی
            </label>
            <label>
                <input class="w-2 h-2" type="checkbox" /> اطمینانیه
            </label>
            <label>
                <input class="w-2 h-2" type="checkbox" /> ابلاغیه
            </label>
            <label>
                <input class="w-2 h-2" type="checkbox" /> سایر
            </label>
        </div>
    </div>
    <hr class="border-1 border-gray-700 ">

    <div class="mt-4">
        {{ $slot }}
    </div>
    <div>
        <hr class="mb-2 mt-12">
        <div class="mb-2 flex items-center justify-between">
            <p>Makrorayan Squar, Kabul</p>
            <p>د مکرویان خلورلاری، کابل</p>
            <p>چهارراهی مکرویان، کابل</p>
        </div>
        <hr>
        <div class="mb-6 flex items-center justify-between">
            <p>www.momp.gov.af</p>
            <p dir="ltr">+92 20 230 44 55</p>
            <p>PR@momp.gov.af</p>
        </div>
    </div>
</div>
