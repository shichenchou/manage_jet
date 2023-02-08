
<div>
    <div class="max-w-4xl mx-auto mt-5">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    商品列表 -
                    <select wire:model.defer="type_id" class="form-control" wire:change="searchPage">
                        <option value="0">全部</option>
                        @foreach($types as $type)
                        <option value="{{$type['id']}}">{{$type['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center px-4 py-3 text-right sm:px-6">
                    @if($showSort)
                    <x-jet-button wire:click="showModal({{ $modal_id }})">
                        新增
                    </x-jet-button>
                    @endif
                </div>
            </div>
        </div>
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">

            </div>

        </div>
        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    @if($showSort)
                                    <th scope="col"
                                        class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6">
                                        排序
                                    </th>
                                    @endif

                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                        名稱
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                        價錢
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                        備註
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">

                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                        狀態
                                    </th>
                                    <th scope="col" class="relative py-3 pl-3 pr-4 sm:pr-6">

                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($items as $item)
                                <tr>
                                    @if($showSort)
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div style="display:{{ $item['display'] }}">
                                            <div
                                                class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                                <x-jet-secondary-button
                                                    wire:click="upSort( {{ $item['id']}},{{ $item['num']}}  )"
                                                    wire:loading.attr="disabled">
                                                    ⇧
                                                </x-jet-secondary-button>
                                            </div>
                                            <div
                                                class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                                <x-jet-secondary-button
                                                    wire:click="downSort( {{ $item['id']}},{{ $item['num']}}  )"
                                                    wire:loading.attr="disabled">
                                                    ⇩
                                                </x-jet-secondary-button>
                                            </div>
                                        </div>
                                    </td>
                                    @endif
                                    <td
                                        class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                        {{ $item['name'] }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $item['price'] }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $item['remark'] }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <img style="width:40%" src="storage/{{ $item['photo'] }}">

                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div
                                            class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                            <input disabled="disabled" type="checkbox" name="toggle"
                                                id="toggle_{{ $item['id'] }}"
                                                class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                                                {{ $item['checked'] }} />
                                            <label for="toggle_{{ $item['id'] }}"
                                                class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                        </div>

                                    </td>
                                    <td
                                        class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">

                                        <x-jet-button wire:click="showModal({{ $item['id']}})"
                                            wire:loading.attr="disabled">
                                            編輯
                                        </x-jet-button>
                                        <x-jet-danger-button class="ml-2" wire:loading.attr="disabled"
                                            wire:click="showDeleteModal({{ $item['id']}})">
                                            刪除
                                        </x-jet-danger-button>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">

                </div>
                <div class="flex items-center px-4 py-3 text-right sm:px-6">
                    <x-jet-secondary-button class="ml-2" wire:loading.attr="disabled" wire:click="backPage">
                        返回
                    </x-jet-secondary-button>
                </div>
            </div>
        </div>
    </div>
    <!-- Show Modal -->
    <x-jet-dialog-modal wire:model="showingModal">
        <x-slot name="title">
            {{$assign['send_btn_name']}}商品
        </x-slot>
        <x-slot name="content">

            <div class="col-span-6 sm:col-span-6">
                <x-jet-label for="name" value="名稱" />
                <x-jet-input name="name" type="text" wire:model.defer="name" class="mt-1 block w-full" />
                <x-jet-input-error for="name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <x-jet-label for="price" value="價錢" />
                <x-jet-input name="price" type="Number" min="0" wire:model.defer="price" class="mt-1 block w-full" />
                <x-jet-input-error for="price" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-jet-label for="remark" value="備註" />
                <textarea rows="2" name="remark" class="form-input rounded-md shadow-sm mt-1 block w-full"
                    wire:model.defer="remark"></textarea>
                <x-jet-input-error for="remark" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                @if ($photo_old)
                <img style="height:40% ;width:40%" src="storage/{{ $photo_old }}">
                @endif
                @if ($photo)
                <img style="height:40% ;width:40%" src="{{ $photo->temporaryUrl() }}">
                @endif
                <x-jet-label for="photo" value="上傳商品圖片" />
                <x-jet-input name="photo" type="file" wire:model.defer="photo" class="mt-1 block w-full"
                    wire:change.prevent="photoCallBack" />
                <x-jet-input-error for="photo" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <x-jet-label for="display" value="狀態" />
                <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                    <input wire:model="hasDisplay" type="checkbox" name="toggle" id="toggle"
                        class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                    <label for="toggle"
                        class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                </div>
            </div>

        </x-slot>
        <x-slot name="footer">

            <x-jet-secondary-button wire:click.prevent="hideModal" wire:loading.attr="disabled">
                取消
            </x-jet-secondary-button>
            <x-jet-danger-button class="ml-2" wire:click.prevent="setData" wire:loading.attr="disabled">
                {{$assign['send_btn_name']}}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
    <!-- Show DELETE Modal -->
    <x-jet-dialog-modal wire:model="showingDeleteModal">

        <x-slot name="title">
            {{$assign['send_btn_name']}}商品
        </x-slot>
        <x-slot name="content">
            確定要刪除{{$name}}嗎
        </x-slot>
        <x-slot name="footer">

            <x-jet-secondary-button wire:click.prevent="hideDeleteModal" wire:loading.attr="disabled">
                取消
            </x-jet-secondary-button>
            <x-jet-danger-button class="ml-2" wire:click.prevent="delete" wire:loading.attr="disabled">
                {{$assign['send_btn_name']}}
            </x-jet-danger-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
