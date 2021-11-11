<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('message_id');
            $table->longText('message');
            $table->unsignedInteger('sender_id');
            $table->unsignedInteger('receiver_id');
            $table->tinyInteger('seen_status')->default(0)
                ->comment('1:seen');
            $table->tinyInteger('deliver_status')->default(0)
                ->comment('1:delivered');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('messages');
    }
}
