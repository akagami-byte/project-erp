<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use InvalidArgumentException;

class AccountingService
{
    public function createJournalEntry(array $data)
    {
        $journalDate = $data['date'];
        $referenceCode = $data['reference_code'];
        $transactionType = $data['transaction_type'];
        $description = $data['description'] ?? '';
        $branchId = $data['branch_id'] ?? null;
        $details = $data['details'];

        // Validate details structure
        if (empty($details)) {
            throw new InvalidArgumentException('Journal details cannot be empty.');
        }

        // Calculate totals
        $totalDebit = collect($details)->sum('debit');
        $totalCredit = collect($details)->sum('credit');

        if (abs($totalDebit - $totalCredit) > 0.01) {
            throw new InvalidArgumentException('Journal entries must be balanced. Debit: ' . $totalDebit . ', Credit: ' . $totalCredit);
        }

        return DB::transaction(function () use ($journalDate, $referenceCode, $transactionType, $description, $branchId, $details) {
            // Create journal header
            $journal = JournalEntry::create([
                'journal_date' => $journalDate,
                'reference_code' => $referenceCode,
                'transaction_type' => $transactionType,
                'description' => $description,
                'created_by' => Auth::id() ?? null,
                'branch_id' => $branchId,
            ]);

            // Create details
            foreach ($details as $detail) {
                $account = ChartOfAccount::where('account_code', $detail['account_code'])->firstOrFail();

                JournalEntryDetail::create([
                    'journal_entry_id' => $journal->id,
                    'account_id' => $account->id,
                    'description' => $detail['description'] ?? '',
                    'debit' => $detail['debit'] ?? 0,
                    'credit' => $detail['credit'] ?? 0,
                ]);
            }

            return $journal->load('details.account');
        });
    }

    // Example for Goods Receipt
    public function createGoodsReceiptJournal($goodsReceiptTotal, $referenceCode, $date, $branchId = null)
    {
        return $this->createJournalEntry([
            'date' => $date,
            'reference_code' => $referenceCode,
            'transaction_type' => 'Goods Receipt',
            'description' => 'Inventory receipt from purchase',
            'branch_id' => $branchId,
            'details' => [
                [
                    'account_code' => '110501', // Raw Material Inventory
                    'description' => 'Inventory Increase',
                    'debit' => $goodsReceiptTotal,
                    'credit' => 0,
                ],
                [
                    'account_code' => '210101', // Accounts Payable Purchase
                    'description' => 'Purchase Liability',
                    'debit' => 0,
                    'credit' => $goodsReceiptTotal,
                ],
            ],
        ]);
    }
}
?>

