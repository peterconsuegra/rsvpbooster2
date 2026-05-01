<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->unsignedInteger('party_size');
            $table->string('restaurant_name');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->decimal('purchase_value', 10, 2)->default(0);
            $table->char('currency', 3)->default('USD');

            // Minimal browser data used for Meta Conversions API matching.
            // Email and phone stay readable in the CRM and are hashed only immediately before sending to Meta.
            $table->string('client_ip_address', 45)->nullable();
            $table->text('client_user_agent')->nullable();
            $table->string('fbp')->nullable();
            $table->string('fbc')->nullable();

            $table->string('meta_event_id')->nullable()->unique();
            $table->timestamp('meta_event_sent_at')->nullable();
            $table->json('meta_response')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'reservation_date']);
            $table->index('meta_event_sent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
