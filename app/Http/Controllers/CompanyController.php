<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Repositories\CompanyRepository;
use App\Services\CompanyLogoService;

class CompanyController extends Controller
{
    public function __construct(
    protected CompanyRepository $companyRepository,
    protected CompanyLogoService $companyLogoService) {

    }

    public function index()
    {
        $companies = $this->companyRepository->paginate(5);

        return view('companies.index', compact('companies'));
    }

    public function logo(Company $company)
    {
        if (!$this->companyLogoService->exists($company->logo)) {
            abort(404);
        }

        return response()->file(
            $this->companyLogoService->path($company->logo)
        );
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(StoreCompanyRequest $request)
    {
        $data = $request->validated();

        $data['logo'] = $this->companyLogoService->store($request->file('logo'));

        $this->companyRepository->create($data);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company created successfully.');
    }

    public function show(Company $company)
    {
        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $this->companyLogoService->delete($company->logo);
            $data['logo'] = $this->companyLogoService->store($request->file('logo'));
        }

        $this->companyRepository->update($company, $data);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        $this->companyLogoService->delete($company->logo);
        $this->companyRepository->delete($company);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company deleted successfully.');
    }
}