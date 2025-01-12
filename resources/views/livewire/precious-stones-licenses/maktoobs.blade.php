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
        <div>
            <button onclick="document.execCommand('bold', false, null)">Bold</button>
            <button onclick="document.execCommand('italic', false, null)">Italic</button>
            <button onclick="document.execCommand('underline', false, null)">Underline</button>
        </div>
        <x-maktoobLayout>


            <div id="editor" contenteditable="true" class="border border-gray-200 rounded-lg p-2 w-full h-40">
                Edit this text and style it!
            </div>

        </x-maktoobLayout>

    </div>
</div>
@push('customJs')
@endpush
