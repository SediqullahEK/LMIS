 <main class="w-full">
     <div class="flex items-center justify-between ">
         <img src="{{ asset('storage/system_images/flagLogo.jpg') }}" alt="Logo"
             class="w-16 h-16 mx-auto mb-4 md:w-20 md:h-20">

         <p>د افغانستان اسلامی امارت</p>
         <p>د کانونو او پترولیم وزارت</p>
         <p>امارت اسلامی افغانستان</p>
         <p>وزارت معادن و پترولیم</p>
         <p>Islamic Emirate of Afghanistan</p>
         <p>Ministry of Mines & Petroleum</p>
         <p>ریاست تسهیل سرمایه گذاری</p>
         <p>شماره: ---------</p>

         <p>تاریخ ه.ق:</p>
         <p>تاریخ ه.ش:</p>
         <label>
             <input type="checkbox" /> عاجل
         </label>
         <label>
             <input type="checkbox" /> عادی
         </label>
         <label>
             <input type="checkbox" /> اطمینانیه
         </label>
         <label>
             <input type="checkbox" /> ابلاغیه
         </label>
         <label>
             <input type="checkbox" /> سایر
         </label>
         <img src="{{ asset('storage/system_images/logo.webp') }}" alt="Logo"
             class="w-16 h-16 mx-auto mb-4 md:w-20 md:h-20">

     </div>
     <div>
         {{ $slot }}
     </div>
     <div>
         <hr class="mb-6 mt-12">
         <hr>
         <div class="flex items-center justify-between">
             <p>www.momp.gov.af</p>
             <p dir="ltr">+92 20 230 44 55</p>
             <p>PR@momp.gov.af</p>
         </div>
     </div>

 </main>
