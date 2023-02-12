<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->char('ig', 255)->nullable(false)->comment('ig')->index();
            $table->char('name', 255)->nullable(false)->comment('名稱')->index();
            $table->unsignedInteger('basic_price')->default(0)->comment('價錢');
            $table->unsignedInteger('item_price')->default(0)->comment('價錢');
            $table->char('phone', 255)->nullable(false)->comment('電話')->index();
            $table->char('photo', 255)->nullable(false)->comment('照片')->index();
            $table->char('adress', 255)->nullable(false)->comment('地址')->index();
            $table->char('status', 12)->nullable(false)->comment('狀態')->index();
            $table->text('item_data')->nullable()->comment('全商品單價')->index();
            $table->text('buy_data')->nullable()->comment('訂購數量')->index();
            $table->text('remark')->nullable()->comment('備註');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
