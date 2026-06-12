<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

class ImportEmployeesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'file' => ['required', 'file', 'mimes:xlsx,xls'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (!$this->hasFile('file')) {
                return;
            }

            try {
                $spreadsheet = IOFactory::load($this->file('file')->getRealPath());
                $worksheet = $spreadsheet->getActiveSheet();

                // Row 1 is treated as heading row. Data starts from row 2.
                $totalRows = max($worksheet->getHighestDataRow() - 1, 0);
            } catch (Throwable $e) {
                $validator->errors()->add('file', 'The excel file does not contain readable data.');

                return;
            }

            if ($totalRows < 100) {
                $validator->errors()->add('file', 'The excel file must contain at least 100 records.');
            }
        });
    }
}
