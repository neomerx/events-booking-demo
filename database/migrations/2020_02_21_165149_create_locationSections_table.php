<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationSectionsTable extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_sections', function (Blueprint $table) {
            $table->bigIncrements('location_section_id');
            $table->unsignedBigInteger('location_id');
            $table->string('name', 50);
            $table->enum('map_shape', [
                'rect',
                'circle',
                'poly',
            ]);
            $table->string('map_coordinates', 100);
            $table->string('map_show_logo_at', 10);
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
        Schema::dropIfExists('location_sections');
    }
}
