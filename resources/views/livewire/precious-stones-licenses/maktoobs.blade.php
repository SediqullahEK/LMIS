<div class="relative bg-white shadow-md sm:rounded-lg mx-4 my-4">
    <x-topHeader pageTitle='مکتوب ها' />
    <div class="bg-white w-full flex flex-col gap-5 px-2 py-2 md:px-16 lg:px-6 md:flex-row text-[#161931]" dir='rtl'>
        <aside class="py-4 w-full md:w-1/3 lg:w-1/4">
            <div class="p-4 text-sm border-l border-gray-200">
                <a href="#" wire:click="toggle('user')"
                    class="flex items-center mb-2 px-3 py-2.5 font-bold border rounded-full transition
                            {{-- @if ($user) bg-[#D4AF37] text-gray-100 @else bg-white text-gray-900 @endif --}}
                            hover:bg-[#D4AF37] hover:text-gray-100">
                    <i class="fa fa-dollar ml-2"></i>
                    به وزارت محترم مالیه
                </a>

                <a href="#" wire:click="toggle('role')"
                    class="flex items-center mb-2 px-3 py-2.5 font-bold border rounded-full transition
                            {{-- @if ($role) bg-[#D4AF37] text-gray-100 @else bg-white text-gray-900 @endif --}}
                            hover:bg-[#D4AF37] hover:text-gray-100">
                    <i class="fa fa-dollar ml-2"></i>
                    به ریاست محترم عواید
                </a>

                <a href="#" wire:click="toggle('permission')"
                    class="flex items-center mb-2 px-3 py-2.5 font-bold border rounded-full transition
                            {{-- @if ($permission) bg-[#D4AF37] text-gray-100 @else bg-white text-gray-900 @endif --}}
                            hover:bg-[#D4AF37] hover:text-gray-100">
                    <i class="fa fa-home ml-2"></i>
                    به ریاست محترم معادن ولایت
                </a>

            </div>
        </aside>
        <div class="w-2/3 mx-auto">
            <x-maktoobLayout>
                <div class="border border-gray-200 rounded-lg">
                    <div class="justify-start py-1">
                        <button onclick="document.execCommand('justifyFull', false, null)"
                            class="text-2xl mr-2  border border-gray-200 rounded-lg px-2 py-1 hover:bg-gray-50 cursor pointer"
                            title="Justify"><strong>J</strong></button>
                        <button onclick="document.execCommand('justifyRight', false, null)"
                            class="text-2xl border border-gray-200 rounded-lg px-2 py-1 hover:bg-gray-50 cursor pointer"
                            title="Align Right"><strong>R</strong></button>
                        <button onclick="document.execCommand('justifyCenter', false, null)"
                            class="text-2xl border border-gray-200 rounded-lg px-2 py-1 hover:bg-gray-50 cursor pointer"
                            title="Align Center"><strong>C</strong></button>
                        <button onclick="document.execCommand('justifyLeft', false, null)"
                            class="text-2xl border border-gray-200 rounded-lg px-2 py-1 hover:bg-gray-50 cursor pointer"
                            title="Align Left"><strong>L</strong></button>

                        <button onclick="document.execCommand('strikeThrough', false, null)"
                            class="text-2xl border border-gray-200 rounded-lg px-2 py-1 hover:bg-gray-50 cursor pointer"
                            title="Strikethrough"><strong>S</strong></button>
                        <button onclick="document.execCommand('underline', false, null)"
                            class="text-2xl border border-gray-200 rounded-lg px-2 py-1 hover:bg-gray-50 cursor pointer"
                            title="Underline"><strong><u>U</u></strong></button>
                        <button onclick="document.execCommand('italic', false, null)"
                            class="text-2xl border border-gray-200 rounded-lg px-2 py-1 hover:bg-gray-50 cursor pointer"
                            title="Italic"><strong><em>I</em></strong></button>
                        <button onclick="document.execCommand('bold', false, null)"
                            class="text-2xl border border-gray-200 rounded-lg px-2 py-1 hover:bg-gray-50 cursor pointer"
                            title="Bold"><strong>B</strong></button>
                    </div>
                    <hr>
                    <div id="editor" contenteditable="true" class="p-2 w-full h-40 focus:outline-none  overflow-auto"
                        style="font-family: Arial, sans-serif; font-size: 14px;">
                        <strong>به وزارت محترم مالیه!</strong><br>
                        <strong>قابل توجه معینیت عواید و گمرکات!</strong><br>
                        <strong>موضوع:</strong> اجازه انتقال مقدار (2500) کیلو گرام سنگ ابرک به خارج از کشور؛
                        با ابراز تمنیات و آرزومند یهای نیک؛
                    </div>
                </div>

            </x-maktoobLayout>
        </div>


    </div>
    <button class="mx-6 my-8 p-3 text-white bg-sky-800 rounded-lg">
        چاپ
    </button>
</div>
@push('customJs')
@endpush
