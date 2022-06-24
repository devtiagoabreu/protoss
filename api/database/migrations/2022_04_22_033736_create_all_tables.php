<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

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
            $table->string('email', 255);
            $table->string('password', 255);
            $table->string('name', 255);
            $table->date('birthdate', 100);
            $table->string('city', 255)->nullable();
            $table->string('work', 255)->nullable();
            $table->string('avatar', 255)->default('default.jpg');
            $table->string('cover', 255)->default('cover.jpg');
            $table->string('token', 255)->nullable();
        });

        Schema::create('userrelations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_from');
            $table->integer('user_to');
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->string('type', 30);
            $table->datetime('created_at');
            $table->text('body');
        });

        Schema::create('postlikes', function (Blueprint $table) {
            $table->id();
            $table->integer('id_post');
            $table->integer('id_user');
            $table->datetime('created_at');
        });

        Schema::create('postcomments', function (Blueprint $table) {
            $table->id();
            $table->integer('id_post');
            $table->integer('id_user');
            $table->datetime('created_at');
            $table->text('body');
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->integer('id_product');
            $table->string('email', 255);
            $table->string('password', 255)->nullable();
            $table->string('name', 255);
            $table->string('contact_a', 255)->nullable();
            $table->string('contact_b', 255)->nullable();
            $table->date('birthdate', 100)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('work', 255)->nullable();
            $table->string('avatar', 255)->default('default.jpg');
            $table->string('cover', 255)->default('cover.jpg');
            $table->string('token', 255)->nullable();
            $table->string('lead_status', 255)->default('A');
            $table->string('active', 255)->default('1');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->string('name', 255);
            $table->text('description')->nullable();;
            $table->text('config')->nullable();
            $table->string('active', 255)->default('1');
            $table->datetime('created_at');
        });

        Schema::create('tokens', function (Blueprint $table) {
            $table->integer('id_user');
            $table->string('type', 30);
            $table->datetime('created_at');
            $table->text('token');
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
        Schema::dropIfExists('userrelations');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('postlikes');
        Schema::dropIfExists('postcomments');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('products');
        Schema::dropIfExists('tokens');
    }
};
