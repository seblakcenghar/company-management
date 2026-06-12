<?php

namespace App\Http\Controllers;

use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Repositories\CompanyRepository;
use App\Services\CompanyLogoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function employeePdf(Company $company)
    {
        $company->load(['employees' => function ($query) {
            $query->orderBy('name');
        }]);

        return SnappyPdf::loadView('employees.exports.company', [
            'company' => $company,
        ])
            ->setPaper('a4')
            ->download('employees-' . $company->id . '.pdf');
    }

    public function options(Request $request): JsonResponse
    {
        $companies = $this->companyRepository->paginateForSelect(
            $request->string('q')->toString(),
            10
        );

        return response()->json([
            'results' => $companies->getCollection()->map(function ($company) {
                return [
                    'id' => $company->id,
                    'text' => $company->name,
                ];
            })->values(),
            'pagination' => [
                'more' => $companies->hasMorePages(),
            ],
        ]);
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