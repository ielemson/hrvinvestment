<?php

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
        Schema::table('loans', function (Blueprint $table) {
            $table->string('project_name')->nullable()->after('user_id');
            $table->decimal('project_amount_requested', 18, 2)->nullable()->after('project_name');

            $table->string('company_name')->nullable()->after('project_amount_requested');
            $table->string('company_address')->nullable()->after('company_name');

            $table->string('ceo_name')->nullable()->after('company_address');
            $table->string('ceo_email')->nullable()->after('ceo_name');

            $table->string('project_location')->nullable()->after('ceo_email');

            $table->enum('project_urgency', ['urgent', 'not_urgent'])->nullable()->after('project_location');

            // use enum or string; string is safest for future expansion
            $table->string('project_type')->nullable()->after('project_urgency');

            $table->text('project_summary')->nullable()->after('project_type');

            $table->enum('loan_type', ['debt_financing', 'equity', 'joint_venture', 'investment'])->nullable()
                ->after('project_summary');

            $table->unsignedTinyInteger('expected_duration_years')->nullable()->after('loan_type');

            $table->enum('previous_investor_funding', ['yes', 'no'])->nullable()->after('expected_duration_years');

            // store file path (recommended) rather than storing the file itself
            $table->string('bank_account_statement_path')->nullable()->after('previous_investor_funding');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn([
                'project_name',
                'project_amount_requested',
                'company_name',
                'company_address',
                'ceo_name',
                'ceo_email',
                'project_location',
                'project_urgency',
                'project_type',
                'project_summary',
                'loan_type',
                'expected_duration_years',
                'previous_investor_funding',
                'bank_account_statement_path',
            ]);
        });
    }
};
