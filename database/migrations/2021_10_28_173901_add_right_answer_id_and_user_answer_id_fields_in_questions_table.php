<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRightAnswerIdAndUserAnswerIdFieldsInQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->foreignId('user_answer_id')->nullable()->references('id')->on('answers');
            $table->foreignId('right_answer_id')->nullable()->references('id')->on('answers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['user_answer_id']);
            $table->dropForeign(['right_answer_id']);
            $table->dropColumn('user_answer_id');
            $table->dropColumn('right_answer_id');
        });
    }
}
