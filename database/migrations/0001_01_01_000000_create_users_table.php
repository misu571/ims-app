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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at', 6)->nullable();
            $table->string('phone')->unique();
            $table->timestamp('phone_verified_at', 6)->nullable();
            $table->string('password');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps(6);
        });
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('entry_by')->constrained('users');
            $table->timestamps(6);
        });

        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('entry_by')->constrained('users');
            $table->timestamps(6);
        });

        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('religion')->nullable();
            $table->string('blood_group')->nullable();
            $table->longText('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->string('nid_reg_id')->unique()->nullable();
            $table->timestamp('nid_reg_verified_at')->nullable();
            $table->string('dob_reg_id')->unique()->nullable();
            $table->timestamp('dob_reg_verified_at')->nullable();
            $table->date('doj');
            $table->string('employee_type');
            $table->string('employment_type');
            $table->foreignId('designation_id')->constrained('designations');
            $table->foreignId('department_id')->constrained('departments');
            $table->timestamps(6);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at', 6)->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('designations');
        Schema::dropIfExists('user_details');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
