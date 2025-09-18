<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void

       {
           Schema::table('ratings', function (Blueprint $table) {
               $table->unsignedBigInteger('instructor_id')->nullable()->after('id')->index();
               // add FK (ensure 'instructors' table exists)
               $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade');
           });
       }

    public function down()
    {
        Schema::table('ratings', function (Blueprint $table) {
            // drop foreign then column
            $table->dropForeign(['instructor_id']);
            $table->dropColumn('instructor_id');
        });
    }
};
