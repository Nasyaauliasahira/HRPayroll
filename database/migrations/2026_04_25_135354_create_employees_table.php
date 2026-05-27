<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('nik', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->text('address')->nullable();
            $table->string('photo')->nullable();
            $table->date('join_date')->nullable();
            $table->enum('employment_type', ['permanent', 'contract', 'internship', 'intern', 'fulltime', 'parttime'])->nullable();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('position_id');
            $table->decimal('base_salary', 15, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};