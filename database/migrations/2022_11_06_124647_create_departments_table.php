<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->comment('Філіали');
            $table->integer('id', true, true)->comment('Ідентифікатор');
            $table->integer('uid', false, true)->unique('uid')->comment('Ідентифікатор із загальної бази');
            $table->string('name', 50)->comment('Назва');
            $table->json('settings')->nullable()->comment('Обмеження');
            $table->boolean('active')->default(1)->index('active')->comment('Активний');
            $table->timestamp('added')->useCurrent()->comment('Доданий');
            $table->timestamp('updated')->nullable()->comment('Змінений');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
