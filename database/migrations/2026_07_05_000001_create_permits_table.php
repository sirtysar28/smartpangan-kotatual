<?php

namespace App\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('permits', function (Blueprint $table) {
            $table->id();
            $table->string('permit_number')->unique()->nullable();
            $table->foreignId('umkm_id')->nullable()->constrained('umkms')->nullOnDelete();
            $table->string('business_name');
            $table->string('applicant_name');
            $table->string('applicant_phone')->nullable();
            $table->text('business_address')->nullable();
            $table->string('business_category')->nullable();
            $table->text('food_products')->nullable();
            $table->enum('permit_type', ['pduk', 'bpom', 'halal', 'sp_irt', 'lainnya'])->default('pduk');
            $table->enum('permit_status', ['pending', 'approved', 'rejected', 'expired'])->default('pending');
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('document_file')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permits');
    }
};
