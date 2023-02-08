<div>
    <div class="max-w-4xl mx-auto mt-5">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    建立商品類型
                </div>
                <div class="flex items-center px-4 py-3 text-right sm:px-6">
                    <x-jet-button wire:click="showModal({{ $modal_id }})">
                        新增
                    </x-jet-button>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6">
                                        排序
                                    </th>
                                    <th scope="col"
                                        class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6">
                                        名稱
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                        說明
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                        備註
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

                                @foreach($types as $type)
                                <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div style="display:{{ $type['display'] }}">
                                            <div
                                                class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                                <x-jet-secondary-button
                                                    wire:click="upSort( {{ $type['id']}},{{ $type['num']}}  )"
                                                    wire:loading.attr="disabled">
                                                    ⇧
                                                </x-jet-secondary-button>
                                            </div>
                                            <div
                                                class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                                <x-jet-secondary-button
                                                    wire:click="downSort( {{ $type['id']}},{{ $type['num']}}  )"
                                                    wire:loading.attr="disabled">
                                                    ⇩
                                                </x-jet-secondary-button>
                                            </div>
                                        </div>
                                    </td>
                                    <td
                                        class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                        {{ $type['name'] }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $type['content'] }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $type['remark'] }}
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div
                                            class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                            <input disabled="disabled" type="checkbox" name="toggle"
                                                id="toggle_{{ $type['id'] }}"
                                                class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                                                {{ $type['checked'] }} />
                                            <label for="toggle_{{ $type['id'] }}"
                                                class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                        </div>

                                    </td>
                                    <td
                                        class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">

                                        <x-jet-button wire:click="showModal({{ $type['id']}})"
                                            wire:loading.attr="disabled">
                                            編輯
                                        </x-jet-button>
                                        <x-jet-danger-button class="ml-2" wire:loading.attr="disabled"
                                            wire:click="showDeleteModal({{ $type['id']}})">
                                            刪除
                                        </x-jet-danger-button>
                                        <x-jet-secondary-button class="ml-2" wire:loading.attr="disabled"
                                            wire:click="goToItem({{ $type['id']}})">
                                            查看商品
                                        </x-jet-secondary-button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Show Modal -->
    <x-jet-dialog-modal wire:model="showingModal">
        <x-slot name="title">
            {{$send_btn_name}}類別
        </x-slot>
        <x-slot name="content">

            <div class="col-span-6 sm:col-span-6">
                <x-jet-label for="name" value="名稱" />
                <x-jet-input name="name" type="text" wire:model.defer="name" class="mt-1 block w-full" />
                <x-jet-input-error for="name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <x-jet-label for="content" value="簡介" />
                <textarea rows="3" name="content" class="form-input rounded-md shadow-sm mt-1 block w-full"
                    wire:model.defer="content">{{$content}}</textarea>
                <x-jet-input-error for="content" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-jet-label for="remark" value="備註" />
                <textarea rows="2" name="remark" class="form-input rounded-md shadow-sm mt-1 block w-full"
                    wire:model.defer="remark"></textarea>
                <x-jet-input-error for="remark" class="mt-2" />
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
                {{$send_btn_name}}
            </x-jet-danger-button>
        </x-slot>

    </x-jet-dialog-modal>

    <!-- Show DELETE Modal -->
    <x-jet-dialog-modal wire:model="showingDeleteModal">

        <x-slot name="title">
            {{$send_btn_name}}類別
        </x-slot>
        <x-slot name="content">
            確定要刪除{{$name}}嗎
        </x-slot>
        <x-slot name="footer">

            <x-jet-secondary-button wire:click.prevent="hideDeleteModal" wire:loading.attr="disabled">
                取消
            </x-jet-secondary-button>
            <x-jet-danger-button class="ml-2" wire:click.prevent="delete" wire:loading.attr="disabled">
                {{$send_btn_name}}
            </x-jet-danger-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
