<div>
    <div class="max-w-4xl mx-auto mt-5">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    我的訂單
                    <select wire:model.defer="status" class="form-control ml-2" wire:change="searchPage">
                        <option value="0">狀態：全部</option>
                        @foreach($status_txt as $status_key => $status_val)
                        <option value="{{$status_key}}">狀態：{{$status_val}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center px-4 py-3 text-right sm:px-6">
                    <x-jet-button class="ml-2" wire:click="goShop">
                        新增
                    </x-jet-button>
                </div>

            </div>
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">

                </div>
                <div class="flex items-center px-4 py-3 text-right sm:px-6">
                        <x-jet-secondary-button class="ml-2" wire:click="prePage">
                            上一頁
                        </x-jet-button>
                        <input class="border-solid border border-gray-300 ml-2 " type="Number"
                                min="1" max="{{$last_page}}" placeholder="" value="{{$current_page}}" wire:model="current_page" wire:click="changePage">
                        <span class=" ml-2 ">頁/共{{$last_page}}頁</span>
                        <x-jet-secondary-button class="ml-2" wire:click="nextPage">
                                下一頁
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
                                        IG
                                    </th>
                                    <th scope="col"
                                        class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6">
                                        姓名/地址/電話
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                        圖片
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                        金額
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                        訂購內容/備註
                                    </th>

                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                        建立時間 </br> 更新時間
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                                        訂單狀態
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($Orders as $order)
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $order['ig'] }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                        {{ $order['name'] }}</br>{{ $order['adress'] }}</br>{{ $order['phone'] }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <img style="width:80%" src="storage/{{ $order['photo'] }}">
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        設計費：{{ $order['basic_price'] }} </br>
                                        加購商品：{{ $order['item_price'] }}</br>
                                        總價：{{ $order['total_price'] }}
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        @foreach($order['item'] as $type_id => $item_data)
                                        <h3>{{ $types[$type_id]['name'] }}：</h3>
                                            @foreach($item_data as $item_id => $item_price)
                                            {{ $items[$item_id]['name']}} ${{ $item_price }}</br>
                                            @endforeach
                                        @endforeach
                                        </br>
                                        @if($order['remark'] == '')
                                         <p>備註：無<p>
                                        @else
                                            <p> 備註：{{ $order['remark'] }}<p>
                                        @endif



                                    </td>

                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $order['created_at'] }} </br>
                                        {{ $order['updated_at'] }}
                                    </td>
                                    <td
                                        class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <x-jet-secondary-button wire:click="showStatus({{ $order['id'] }})">
                                            {{$status_txt[$order['status']]}}
                                        </x-jet-secondary-button>
                                        <!--@if($order['status'] == 'draft')
                                        <x-jet-secondary-button class="ml-2" wire:click="">
                                            編輯
                                        </x-jet-secondary-button>
                                        @endif-->
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
    <!-- Show DELETE Modal -->
    <x-jet-dialog-modal wire:model="showingStatusModal">
        <x-slot name="title">
           付款訂單
        </x-slot>
        <x-slot name="content">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6">
                            {{ $modal_ig }}
                        </th>
                        <th scope="col"
                            class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 sm:pl-6">
                            </br>{{ $modal_name }}</br>{{ $modal_adress }}</br>{{ $modal_phone }}
                        </th>
                        <th scope="col"
                            class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                            訂單狀態
                        </th>
                        <th scope="col"
                            class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">
                            時間
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($logs as $log)
                    <tr>
                        <td></td>
                        <td></td>
                        <td>{{$status_txt[$log['status']]}}</td>
                        <td>{{$log['created_at']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </x-slot>
        <x-slot name="footer">
            @if($modal_status == 'submitted' )
            <form method="POST" action="/cart_ecpay/check">
                <input type="hidden" name="id" value="{{$modal_id}}">
            @csrf
            <x-jet-danger-button class="ml-2" type="submit">
                付款
            </x-jet-danger-button>
            </form>
            @endif
            <x-jet-secondary-button class="ml-2" wire:click.prevent="hideStatus" wire:loading.attr="disabled">
                關閉
            </x-jet-secondary-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
