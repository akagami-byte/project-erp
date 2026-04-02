<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;

class ChartOfAccountController extends Controller
{
    public function index(Request $request): View|Factory
    {
        $query = ChartOfAccount::active();

        $accounts = $query->with('parent')->paginate(20);

        return view('chart-of-accounts.index', compact('accounts'));
    }

    public function create(): View|Factory
    {
        $parents = ChartOfAccount::active()->get()->where('id', '!=', request('id'));; // Avoid self-parent

        return view('chart-of-accounts.create', compact('parents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'account_code' => 'required|unique:chart_of_accounts,account_code',
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:asset,liability,equity,revenue,cos,expense',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'is_active' => 'boolean',
        ]);

        ChartOfAccount::create($request->all());

        return redirect()->route('chart-of-accounts.index')
            ->with('success', 'Akun akuntansi berhasil dibuat.');
    }

    public function show(ChartOfAccount $chartOfAccount): View|Factory
    {
        $chartOfAccount->load('children', 'journalDetails');

        return view('chart-of-accounts.show', compact('chartOfAccount'));
    }

    public function edit(ChartOfAccount $chartOfAccount): View|Factory
    {
        $parents = ChartOfAccount::active()->where('id', '!=', $chartOfAccount->id)->get();

        return view('chart-of-accounts.edit', compact('chartOfAccount', 'parents'));
    }

    public function update(Request $request, ChartOfAccount $chartOfAccount): RedirectResponse
    {
        $request->validate([
            'account_code' => 'required|unique:chart_of_accounts,account_code,' . $chartOfAccount->id,
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:asset,liability,equity,revenue,cos,expense',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'is_active' => 'boolean',
        ]);

        $chartOfAccount->update($request->all());

        return redirect()->route('chart-of-accounts.index')
            ->with('success', 'Akun akuntansi berhasil diupdate.');
    }

    public function destroy(ChartOfAccount $chartOfAccount): RedirectResponse
    {
        if ($chartOfAccount->journalDetails()->count() > 0) {
            return redirect()->route('chart-of-accounts.index')
                ->with('error', 'Akun tidak bisa dihapus karena sudah digunakan di journal.');
        }

        $chartOfAccount->delete();

        return redirect()->route('chart-of-accounts.index')
            ->with('success', 'Akun akuntansi berhasil dihapus.');
    }

    public function deactivate(ChartOfAccount $chartOfAccount): RedirectResponse
    {
        $chartOfAccount->update(['is_active' => false]);

        return redirect()->route('chart-of-accounts.index')
            ->with('success', 'Akun berhasil dinonaktifkan.');
    }
}
?>

