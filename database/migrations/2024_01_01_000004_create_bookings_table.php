<?php
// database/migrations/2024_01_01_000004_create_bookings_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('booking_reference')->unique(); // TB-20250426-A1B2

            // Booker details (snapshot — in case user changes account)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->string('customer_nationality')->nullable();

            // Trip details
            $table->date('travel_date');
            $table->unsignedSmallInteger('group_size')->default(1);
            $table->text('special_requests')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();

            // Pricing (snapshot at booking time)
            $table->decimal('price_per_person', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('currency', 3)->default('USD');

            // Status
            $table->enum('status', [
                'pending',      // just submitted
                'confirmed',    // admin confirmed
                'in_progress',  // currently on the trip
                'completed',    // trip done
                'cancelled',    // cancelled
            ])->default('pending');

            $table->text('admin_notes')->nullable(); // internal notes
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('travel_date');
            $table->index('customer_email');
        });
    }

    public function down(): void { Schema::dropIfExists('bookings'); }
};
