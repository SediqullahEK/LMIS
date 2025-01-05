 <div class="p-4">
     <div class="grid grid-cols-3 gap-4">

         <div class="h-24 bg-gray-200 rounded-lg"></div>
         <div class="h-24 bg-gray-200 rounded-lg"></div>
         <div class="h-24 bg-gray-200 rounded-lg"></div>
     </div>
     {{-- <div class="p-4">
         <div>
             <label for="text" class="block text-sm font-medium text-gray-700">Enter Text for QR Code</label>
             <input type="text" id="text"
                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                 wire:model="text" placeholder="Enter text here" />
         </div>

         <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600"
             wire:click="generateQrCode">
             Generate QR Code
         </button>

         @if ($qrCodeDataUrl)
             <div class="mt-4">
                 <img src="{{ $qrCodeDataUrl }}" alt="Generated QR Code" class="rounded-lg shadow-lg">
             </div>
         @endif
     </div> --}}

 </div>
