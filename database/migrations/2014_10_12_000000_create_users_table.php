<?php

use App\Containers\User\Enums\Status;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('login')->unique()->nullable()->comment('Логин');
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('first_name')->nullable()->comment('Имя');
            $table->string('last_name')->nullable()->comment('Фамилия');
            $table->string('middle_name')->nullable()->comment('Отчество');
            $table->decimal('phone_number', 13, 0, true)->nullable()
                ->comment('Телефон номер в полном формате без знаков');
            $table->dateTime('birthday')->nullable()->comment('Дата рождение');
            $table->enum('gender', ['male', 'female'])->nullable()->comment('Пол');

            $table->enum('status', [Status::Active->value, Status::Block->value])->default('active')
                ->comment('status пользователя');

            $table->json('properties')->nullable()->comment('Вдруг понадобится что-то добавить');

            $table->string('confirm_code', 8)->nullable()->comment('Код подтверждение для смс и email');

            $table->rememberToken();
            $table->timestamp('online_at')->nullable();
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
        Schema::dropIfExists('users');
    }
};
