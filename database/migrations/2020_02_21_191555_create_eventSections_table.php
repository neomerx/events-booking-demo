<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSectionsTable extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_sections', function (Blueprint $table) {
            $table->bigIncrements('event_section_id');
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('location_section_id');
            $table->unsignedInteger('price');
            $table->string('company_name', 100)->nullable();
            $table->string('company_description', 500)->nullable();
            $table->text('company_logo_base64')->nullable();
            $table->string('contact_name', 50)->nullable();
            $table->string('contact_email', 255)->nullable();
            $table->string('contact_phone', 15)->nullable();
            $table->timestamps();

            $table
                ->foreign('event_id')
                ->references('event_id')
                ->on('events')
                ->onDelete(self::ACTION_CASCADE)->onUpdate(self::ACTION_CASCADE);

            $table
                ->foreign('location_section_id')
                ->references('location_section_id')
                ->on('location_sections')
                ->onDelete(self::ACTION_CASCADE)->onUpdate(self::ACTION_CASCADE);

            $table->unique(['event_id', 'location_section_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_sections');
    }
}
