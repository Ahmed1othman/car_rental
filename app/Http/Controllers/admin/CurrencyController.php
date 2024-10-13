<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $items = Currency::paginate(10);
        return view('pages.admin.currencies.index', compact('items'));
    }

    public function create()
    {
        return view('pages.admin.currencies.create');
    }

    public function store(Request $request)
    {

        $request->merge([
            'is_default' => (bool)$request->is_default,
            'is_active' => (bool)$request->is_active,
        ]);

        $validated = $request->validate([
            'code' => 'required|string|max:3|unique:currencies',
            'symbol' => 'required|string|max:3|unique:currencies,symbol',
            'name' => 'required|string|max:255',
            'exchange_rate' => 'required|numeric|min:0',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ]);


        $currency = Currency::create($validated);
        if ($request->is_default) {
            $currency->setAsDefault();
        }

        return redirect()->route('admin.currencies.index')->with('success', 'Currency created successfully.');
    }

    public function edit(Currency $currency)
    {
        return view('pages.admin.currencies.edit', compact('currency'));
    }

    public function update(Request $request, Currency $currency)
    {
        $request->merge([
            'is_default' => (bool)$request->is_default,
            'is_active' => (bool)$request->is_active,
        ]);
        $validated = $request->validate([
            'code' => 'required|string|max:3|unique:currencies,code,' . $currency->id,
            'symbol' => 'required|string|max:3|unique:currencies,symbol,' . $currency->id,
            'name' => 'required|string|max:255',
            'exchange_rate' => 'required|numeric|min:0',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $currency->update($validated);

        if ($request->is_default) {
            $currency->setAsDefault();
        }

        return redirect()->route('admin.currencies.index')->with('success', 'Currency updated successfully.');
    }

    public function destroy(Currency $currency)
    {
        if ($currency->is_default) {
            return redirect()->route('admin.currencies.index')->with('error', 'Cannot delete the default currency.');
        }

        $currency->delete();
        return redirect()->route('admin.currencies.index')->with('success', 'Currency deleted successfully.');
    }
}
