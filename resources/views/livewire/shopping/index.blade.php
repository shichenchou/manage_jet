<form wire:submit.prevent="submit">
    <div>
        <div class="max-w-4xl mx-auto mt-5">
            <div class="card shadow mb-4">
                <div class="card-body p-4">
                    <div class="px-4 sm:px-12 lg:px-8">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                基本資料
                            </div>
                        </div>
                        <div class="sm:flex sm:items-center">
                            <div class="col-span-6 sm:col-span-6 p-2 ">
                                <x-jet-label for="ig" value="IG" />
                                <x-jet-input name="ig" type="text" wire:model="data.ig" class="mt-1 block w-full" />
                                <x-jet-input-error for="ig" class="mt-2" />
                            </div>
                            <div class="col-span-6 sm:col-span-6 p-2 ">
                                <x-jet-label for="name" value="姓名" />
                                <x-jet-input name="name" type="text" wire:model="data.name" class="mt-1 block w-full" />
                                <x-jet-input-error for="name" class="mt-2" />
                            </div>
                            <div class="col-span-6 sm:col-span-6 p-2 ">
                                <x-jet-label for="phone" value="電話" />
                                <x-jet-input name="phone" type="text" wire:model="data.phone"
                                    class="mt-1 block w-full" />
                                <x-jet-input-error for="phone" class="mt-2" />
                            </div>


                        </div>
                        <div class="items-center">
                            <div class="col-span-6 sm:col-span-6 p-2 ">
                                <x-jet-label for="adress" value="地址" />
                                <textarea rows="2" name="adress"
                                    class="form-input rounded-md shadow-sm mt-1 block w-full"
                                    wire:model="data.adress"></textarea>
                                <x-jet-input-error for="adress" class="mt-2" />
                            </div>
                            <div class="col-span-6 sm:col-span-6 p-2 ">
                                <x-jet-label for="remark" value="備註" />
                                <textarea rows="2" name="adress"
                                    class="form-input rounded-md shadow-sm mt-1 block w-full"
                                    wire:model="data.remark"></textarea>
                                <x-jet-input-error for="remark" class="mt-2" />
                            </div>
                        </div>
                        <div class="items-center">
                            @if ($photo)
                            <img style="height:40% ;width:40%" src="{{ $photo->temporaryUrl() }}">
                            @endif
                            <x-jet-label for="photo" value="上傳商品圖片" />
                            <x-jet-input name="photo" type="file" wire:model="photo" class="mt-1 block w-full"
                                wire:change="photoCallBack" />
                            <x-jet-input-error for="photo" class="mt-2" />

                            <div class="col-span-6 sm:col-span-6 p-2 ">
                                <x-jet-label for="basic_price" value="設計費" />
                                <x-jet-input name="basic_price" type="number" wire:model="data.basic_price" class="mt-1 block w-full"  style="width:20%"/>
                                <x-jet-input-error for="basic_price" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @foreach($types as $type)
        <div class="max-w-4xl mx-auto mt-5">
            <div class="card shadow mb-4">
                <div class="card-body p-4">
                    <div class="px-4 sm:px-12 lg:px-8">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">

                                <p class="text-sm font-bold mt-2"> {{ $type['name'] }}</p>
                                <span class="text-xs">{{ $type['content'] }}</span>
                                <span class="text-xs">{{ $type['remark'] }}</span>
                            </div>
                        </div>
                        <div class="sm:flex sm:items-center">
                            <div class="mx-2 mt-2 grid lg:grid-cols-6 sm:grid-cols-4 gap-3">
                                @if(isset($items[$type['id']] ))
                                @foreach($items[$type['id']] as $item)
                                <div class="border-2 border-gray-100 rounded-lg shadow-lg px-5 pt-5 pb-2 ml-2">
                                    <img class="object-cover h-36 w-full" src="storage/{{ $item['photo'] }}">
                                    <p class="text-sm font-bold mt-2">{{$item['name']}}</p>
                                    <p class="text-center">
                                        <span class="text-xs font-extrabold text-pink-500">{{$item['remark']}}</span>
                                    </p>
                                    <p class="text-left">
                                        <span class="text-xs">價錢</span>
                                        <span class="text-red-600 font-extrabold">${{$item['price']}}</span>
                                    </p>
                                    <p class="text-right">
                                        <x-jet-input style="width:60%" type="Number" min="0" wire:model="data.buy.{{$type['id']}}.{{$item['id']}}"
                                            class="border-solid border border-gray-300 ml-2" />
                                        <span class="text-xs">個</span>
                                    </p>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>

        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">

                </div>
                <div class="flex items-center px-4 py-3 text-right sm:px-6">
                <x-jet-button wire:click="" wire:loading.attr="disabled" >
                    送出
                </x-jet-button>
                <x-jet-secondary-button class="ml-2" wire:loading.attr="disabled" wire:click="backPage">
                        取消
                    </x-jet-secondary-button>
                </div>
            </div>
        </div>
</form>
