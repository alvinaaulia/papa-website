<?php

namespace App\Http\Controllers\Rules;

use App\Http\Controllers\Controller;
use App\Models\Rules\CompanyTax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index()
    {
        $taxes = CompanyTax::orderByDesc('effective_date')
            ->orderBy('tax_name')
            ->paginate(10);

        return view('rules.tax.index', [
            'taxes' => $taxes,
            'type_menu' => 'tax',
        ]);
    }

    public function create()
    {
        return view('rules.tax.add-tax', [
            'type_menu' => 'tax',
        ]);
    }

    public function showView(CompanyTax $tax)
    {
        return view('rules.tax.detail-tax', [
            'tax' => $tax,
            'type_menu' => 'tax',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatePayload($request);
        $data['tax_code'] = strtoupper(trim($data['tax_code']));

        $tax = CompanyTax::create($data);

        return response()->json([
            'message' => 'Data pajak berhasil dibuat',
            'data' => $tax,
        ], 201);
    }

    public function show(CompanyTax $tax)
    {
        return response()->json($tax);
    }

    public function update(Request $request, CompanyTax $tax)
    {
        $data = $this->validatePayload($request, $tax->tax_id);

        if (isset($data['tax_code'])) {
            $data['tax_code'] = strtoupper(trim($data['tax_code']));
        }

        $tax->update($data);

        return response()->json([
            'message' => 'Data pajak berhasil diperbarui',
            'data' => $tax->fresh(),
        ]);
    }

    public function activate(CompanyTax $tax)
    {
        $tax->update(['is_active' => true]);

        return response()->json([
            'message' => 'Data pajak diaktifkan',
            'data' => $tax->fresh(),
        ]);
    }

    public function disable(CompanyTax $tax)
    {
        $tax->update(['is_active' => false]);

        return response()->json([
            'message' => 'Data pajak dinonaktifkan',
            'data' => $tax->fresh(),
        ]);
    }

    private function validatePayload(Request $request, ?int $taxId = null): array
    {
        $uniqueRule = 'unique:company_taxes,tax_code';
        if ($taxId) {
            $uniqueRule .= ',' . $taxId . ',tax_id';
        }

        return $request->validate([
            'tax_code' => ['required', 'string', 'max:50', $uniqueRule],
            'tax_name' => ['required', 'string', 'max:150'],
            'tax_type' => ['required', 'in:PPH21,BPJS,OTHER'],
            'calculation_method' => ['required', 'in:FIXED,PERCENTAGE,PROGRESSIVE,GROSS_UP'],
            'tax_rate' => ['nullable', 'numeric', 'min:0'],
            'income_min' => ['nullable', 'numeric', 'min:0'],
            'income_max' => ['nullable', 'numeric', 'min:0'],
            'effective_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:effective_date'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
