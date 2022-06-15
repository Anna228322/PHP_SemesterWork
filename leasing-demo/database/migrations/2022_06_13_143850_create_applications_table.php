<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('contact_id');
            $table->unsignedBigInteger('csv_id');

            $table->string('object_type');
            $table->string('lease_term');
            $table->double('sum');

            $table->foreign('contact_id')
                ->references('id')
                ->on('contacts')
                ->onDelete('cascade');

            $table->foreign('csv_id')
                ->references('id')
                ->on('csvs')
                ->onDelete('cascade');

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
        Schema::dropIfExists('applications');
    }
};
