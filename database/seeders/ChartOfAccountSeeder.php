<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            // ASSET
            ['account_code' => '110101', 'account_name' => 'Petty Cash HO', 'account_type' => 'asset'],
            ['account_code' => '110102', 'account_name' => 'Petty Cash Outlet', 'account_type' => 'asset'],
            ['account_code' => '110201', 'account_name' => 'Bank 1', 'account_type' => 'asset'],
            ['account_code' => '110205', 'account_name' => 'BCA Cake Zero', 'account_type' => 'asset'],
            ['account_code' => '110501', 'account_name' => 'Raw Material Inventory', 'account_type' => 'asset'],
            ['account_code' => '110504', 'account_name' => 'Finished Goods', 'account_type' => 'asset'],

            // LIABILITY
            ['account_code' => '210101', 'account_name' => 'Account Payable Purchase', 'account_type' => 'liability'],
            ['account_code' => '210301', 'account_name' => 'Salary Payable', 'account_type' => 'liability'],
            ['account_code' => '210208', 'account_name' => 'PPN Payable', 'account_type' => 'liability'],

            // EQUITY
            ['account_code' => '310101', 'account_name' => 'Share Capital', 'account_type' => 'equity'],
            ['account_code' => '310202', 'account_name' => 'Retained Earnings Current Period', 'account_type' => 'equity'],

            // REVENUE
            ['account_code' => '410101', 'account_name' => 'Sales Food', 'account_type' => 'revenue'],
            ['account_code' => '410102', 'account_name' => 'Sales Beverage', 'account_type' => 'revenue'],

            // COST OF SALES
            ['account_code' => '510101', 'account_name' => 'COGS Food', 'account_type' => 'cos'],
            ['account_code' => '510102', 'account_name' => 'COGS Beverage', 'account_type' => 'cos'],

            // EXPENSES
            ['account_code' => '610201', 'account_name' => 'Salary Expense', 'account_type' => 'expense'],
            ['account_code' => '610302', 'account_name' => 'Utilities Expense', 'account_type' => 'expense'],
            ['account_code' => '610504', 'account_name' => 'Technology Expense', 'account_type' => 'expense'],
        ];

        foreach ($accounts as $account) {
            ChartOfAccount::firstOrCreate(
                ['account_code' => $account['account_code']],
                $account
            );
        }
    }
}
?>
</xai:function_call}





<xai:function_call name="edit_file">
<parameter name="path">c:/xampp/htdocs/project-erp/database/seeders/DatabaseSeeder.php
