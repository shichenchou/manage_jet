<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommodityItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commodity_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('type_id')->default(0)->comment('商品類型');
            $table->char('name', 255)->nullable(false)->comment('名稱')->index();
            $table->unsignedInteger('price')->default(0)->comment('價錢');
            $table->text('remark')->nullable()->comment('備註')->index();
            //$table->unsignedInteger('discount_type')->default(0)->comment('折扣類型');
            $table->unsignedTinyInteger('sort')->default(255)->comment('排序');
            $table->tinyInteger('display')->default(1)->comment('顯示')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commodity_items');
    }
}
