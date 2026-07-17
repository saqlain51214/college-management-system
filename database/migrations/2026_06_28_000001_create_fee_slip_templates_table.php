<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_slip_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('variant', 20)->default('kiu'); // kiu | classic | modern | minimal
            $table->boolean('is_active')->default(false);
            $table->string('orientation', 10)->default('landscape'); // landscape | portrait

            // College header
            $table->string('college_name')->nullable();
            $table->string('college_subtitle')->nullable();
            $table->string('college_short_name', 20)->nullable();
            $table->string('logo_path')->nullable();

            // Colors
            $table->string('primary_color', 20)->default('#009999');
            $table->string('accent_color', 20)->default('#1a56db');
            $table->string('text_color', 20)->default('#111111');

            // Bank
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_account_title')->nullable();
            $table->string('bank_branch')->nullable();

            // Challan refs
            $table->string('ref_prefix', 30)->nullable();
            $table->string('bill_prefix', 30)->nullable();

            // Copies (JSON array of strings like ["Bank Copy","Accounts Copy","Student Copy"])
            $table->json('copies')->nullable();

            // Fee table
            $table->string('fee_display_mode', 10)->default('dynamic'); // dynamic | static
            $table->json('fee_items')->nullable();

            // Options (booleans)
            $table->boolean('show_barcode')->default(true);
            $table->boolean('show_watermark')->default(false);
            $table->string('watermark_text')->nullable();
            $table->boolean('show_in_words')->default(true);
            $table->boolean('show_depositor_fields')->default(true);
            $table->boolean('show_ref_no')->default(true);
            $table->boolean('show_consumer_no')->default(true);
            $table->boolean('show_accountant_sig')->default(false);

            // Content
            $table->text('instructions')->nullable();
            $table->string('footer_text')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_slip_templates');
    }
};
