<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;

class EmployeesImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    use RemembersChunkOffset;

    protected int $totalRows = 0;

    protected int $successRows = 0;

    protected array $failedRows = [];

    public function __construct(
        protected int $companyId
    ) {
    }

    public function collection(Collection $rows): void
    {
        $payload = [];
        $chunkOffset = $this->getChunkOffset();
        $rowNumberInChunk = 0;

        foreach ($rows as $row) {
            $rowNumberInChunk++;

            $normalized = [
                'name' => trim((string) ($row['name'] ?? '')),
                'email' => trim((string) ($row['email'] ?? '')),
            ];

            if ($normalized['name'] === '' && $normalized['email'] === '') {
                continue;
            }

            $this->totalRows++;

            $validator = Validator::make($normalized, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
            ]);

            if ($validator->fails()) {
                $this->failedRows[] = [
                    'row' => $chunkOffset + $rowNumberInChunk + 1,
                    'name' => $normalized['name'],
                    'email' => $normalized['email'],
                    'errors' => $validator->errors()->all(),
                ];

                continue;
            }

            $payload[] = $normalized;
        }

        if (empty($payload)) {
            return;
        }

        $now = now();

        $payload = collect($payload)->map(function ($row) use ($now) {
            return [
                'company_id' => $this->companyId,
                'name' => $row['name'],
                'email' => $row['email'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all();

        Employee::insert($payload);
        $this->successRows += count($payload);
    }

    public function chunkSize(): int
    {
        return 10;
    }

    public function totalRows(): int
    {
        return $this->totalRows;
    }

    public function successRows(): int
    {
        return $this->successRows;
    }

    public function failedRows(): array
    {
        return $this->failedRows;
    }
}
