<div class="relative bg-white shadow-md sm:rounded-lg mx-4 my-4">
    <x-topHeader pageTitle='جواز ها' />
    <div class="bg-white px-2 py-2 md:px-16 lg:px-6 md:flex-row text-[#161931]" dir='rtl'>
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
                        <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer"
                            wire:click="sortBy('name_dr')">
                            <div class="flex justify-center">
                                <span>متقاضی</span>
                                @if ($sortField === 'name_dr')
                                    <span class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                @else
                                    <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200">
                            <div class="flex justify-center">
                                <span>نمبر جواز/تشخیصیه</span>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200">
                            <div class="flex justify-center">
                                <span>نمبر عریضه</span>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200 cursor-pointer"
                            wire:click="sortBy('province_id')">
                            <div class="flex justify-center">
                                <span>سنگ</span>
                                @if ($sortField === 'province_id')
                                    <span class="mr-2 text-gray-200">{{ $sortDirection === 'desc' ? '▲' : '▼' }}</span>
                                @else
                                    <span class="text-gray-400 mr-2"><i class="fa fa-sort"></i></span>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200">
                            <div class="flex justify-center">
                                <span>مقدار سنگ</span>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-2 border border-slate-200">
                            <div class="flex justify-center">
                                <span>رنگ سنگ</span>
                            </div>
                        </th>

                        <th scope="col" class="px-3 py-2 border border-slate-200">
                            <div class="flex justify-center">
                                <span>چاپ</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody wire:init="loadTableData">
                    @if ($licenses && count($licenses))
                        @foreach ($licenses as $index => $license)
                            <tr class="border-b hover:bg-warning-400">
                                {{ dd($license) }}
                                <td class="px-3 py-2 border border-slate-200">
                                    @if ($perPage)
                                        {{ $licenses->firstItem() + $index }}
                                    @else
                                        {{ ++$index }}
                                    @endif
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->company->name_dr ?? '' }}
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->name_en ?? '' }}
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->f_name ?? '' }}
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{-- @if ($provinces && count($provinces))
                                        @foreach ($provinces as $pr)
                                            @if ($individual->province_id == $pr->id)
                                                {{ $pr->name ?? '' }}
                                            @endif
                                        @endforeach
                                    @endif --}}
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->district ?? '' }}
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->date_of_birth ?? '' }}
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->nationality ?? '' }}
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->tin_num ?? '' }}
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->tazkira_num ?? '' }}
                                </td>
                                <td class="px-3 py-2 border border-slate-200">
                                    {{ $license->phone ?? '' }}
                                </td>


                                <td class="px-2 py-2 border border-slate-200 dark:text-white">
                                    @can('ویرایش شخص')
                                        <button
                                            @click=" @this.call('editIndividual', {{ $license->id }}); @this.call('openForm',0) "
                                            class=" text-gray-900 px-2 py-2 rounded">
                                            <span class="text-xl px-3 pt-5"><i class="fa  fa-edit text-sky-600"></i></span>
                                        </button>
                                    @endcan
                                    @can('حذف شخص')
                                        <button @click=" @this.call('toggleConfirm', {{ $license->id }})"
                                            class=" text-gray-900 px-2 py-2 rounded">
                                            <span class="text-xl px-3 pt-5"><i class="fa  fa-trash text-red-600"></i></span>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            @if ($noData)
                <h1 class="font-bold text-xl text-red-900">معلومات موجود نمیباشد! </h1>
            @endif
            <span wire:loading wire:target="loadTableData,search,perPage,sortBy">
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
                @if ($perPage && $licenses->isNotEmpty())
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <span>{{ $licenses->links('vendor.pagination.tailwind') }}</span>
                        <span wire:loading wire:target="previousPage, nextPage, gotoPage">
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
                @endif
            </nav>
        </div>
    </div>
</div>
