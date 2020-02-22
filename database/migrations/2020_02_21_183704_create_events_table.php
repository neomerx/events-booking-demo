<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('event_id');
            $table->unsignedBigInteger('location_id');
            $table->boolean('is_active')->default(false);
            $table->string('name', 50);
            $table->date('date_from');
            $table->date('date_to');
            $table->timestamps();

            $table
                ->foreign('location_id')
                ->references('location_id')
                ->on('locations')
                ->onDelete(self::ACTION_CASCADE)->onUpdate(self::ACTION_CASCADE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
