<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CompanyRepository
{
    public function paginate(int $perPage = 5): LengthAwarePaginator
    {
        return Company::latest()->paginate($perPage);
    }

    public function getAll(): Collection
    {
        return Company::orderBy('name')->get();
    }

    public function find(int $id): ?Company
    {
        return Company::find($id);
    }

    public function paginateForSelect(?string $search, int $perPage = 10): LengthAwarePaginator
    {
        return Company::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function create(array $data): Company
    {
        return Company::create($data);
    }

    public function update(Company $company, array $data): bool
    {
        return $company->update($data);
    }

    public function delete(Company $company): bool
    {
        return $company->delete();
    }
}