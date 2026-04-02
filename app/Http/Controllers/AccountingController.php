<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;

class AccountingController extends Controller
{
    public function index(Request $request): View|Factory
    {
        $query = JournalEntry::withDetails()->latest();

        // Filters
        if ($request->filled('date_from')) {
            $query->byDateRange($request->date_from, $request->date_to ?? now());
        }
        if ($request->filled('date_to')) {
            $endDate = $request->date_to;
            if (!$request->filled('date_from')) {
                $query->byDateRange(now()->subMonth(), $endDate);
            }
        }
        if ($request->filled('search')) {
            $query->byReference($request->search);
        }
        if ($request->filled('type')) {
            $query->byType($request->type);
        }
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $journals = $query->paginate(20);

        $accounts = ChartOfAccount::active()->get();

        return view('accounting.index', compact('journals', 'accounts'));
    }

    public function show(JournalEntry $journalEntry): View|Factory
    {
        $journalEntry->load('details.account.parent');

        return view('accounting.show', compact('journalEntry'));
    }
}
?>

