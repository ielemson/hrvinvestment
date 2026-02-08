<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('amount_requested', 15, 2);
            $table->decimal('amount_approved', 15, 2)->nullable();

            $table->unsignedInteger('tenure_months'); // e.g. 6, 12
            $table->decimal('interest_rate', 6, 2)->default(0); // e.g. 12.50

            $table->text('purpose')->nullable();

            $table->enum('status', [
                'draft',
                'submitted',
                'under_review',
                'approved',
                'rejected',
                'disbursed',
                'active',
                'completed',
                'defaulted',
                'cancelled',
            ])->default('submitted')->index();

            // Admin audit trail
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // Disbursement & repayment summary (optional but useful)
            $table->date('disbursed_at')->nullable();
            $table->date('first_due_date')->nullable();

            $table->decimal('total_payable', 15, 2)->nullable();
            $table->decimal('outstanding_balance', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
