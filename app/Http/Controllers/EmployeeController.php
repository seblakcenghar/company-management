<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportEmployeesPdfRequest;
use App\Http\Requests\ImportEmployeesRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Imports\EmployeesImport;
use App\Models\Employee;
use App\Models\EmployeeImportLog;
use App\Repositories\CompanyRepository;
use App\Repositories\EmployeeRepository;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\Auth;
use RuntimeException;
use Throwable;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function __construct(
        protected EmployeeRepository $employeeRepository,
        protected CompanyRepository $companyRepository
    ) {
    }

    public function index()
    {
        $employees = $this->employeeRepository->paginate(5);
        $companies = $this->companyRepository->getAll();

        return view('employees.index', compact('employees', 'companies'));
    }

    public function create()
    {
        $selectedCompany = null;
        $companyId = (int) session()->getOldInput('company_id');

        if ($companyId > 0) {
            $selectedCompany = $this->companyRepository->find($companyId);
        }

        return view('employees.create', compact('selectedCompany'));
    }

    public function import(ImportEmployeesRequest $request)
    {
        $import = new EmployeesImport((int) $request->validated('company_id'));

        try {
            Excel::import($import, $request->file('file'));
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'file' => 'Import failed. Please check file format and try again.',
                ]);
        }

        $failedRows = $import->failedRows();
        $failedRowsCount = count($failedRows);

        EmployeeImportLog::query()->delete();

        EmployeeImportLog::create([
            'user_id' => Auth::id(),
            'company_id' => (int) $request->validated('company_id'),
            'file_name' => $request->file('file')->getClientOriginalName(),
            'total_rows' => $import->totalRows(),
            'success_rows' => $import->successRows(),
            'failed_rows' => $failedRowsCount,
            'status' => $failedRowsCount > 0 ? 'partial' : 'success',
            'failed_data' => array_slice($failedRows, 0, 20),
        ]);

        if ($failedRowsCount > 0) {
            return redirect()
                ->route('employees.import.logs')
                ->with('warning', "Import finished with {$failedRowsCount} failed rows.");
        }

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employees imported successfully.');
    }

    public function importLogs()
    {
        $logs = EmployeeImportLog::with('company')
            ->latest()
            ->paginate(10);

        return view('employees.import_logs', compact('logs'));
    }

    public function exportPdf(ExportEmployeesPdfRequest $request)
    {
        $company = $this->companyRepository->find((int) $request->validated('company_id'));

        if (!$company) {
            return back()
                ->withInput()
                ->withErrors([
                    'company_id' => 'Selected company does not exist.',
                ]);
        }

        $company->load(['employees' => function ($query) {
            $query->orderBy('name');
        }]);

        try {
            return SnappyPdf::loadView('employees.exports.company', [
                'company' => $company,
            ])
                ->setPaper('a4')
                ->download('employees-' . $company->id . '.pdf');
        } catch (RuntimeException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'company_id' => 'PDF export failed. Ensure wkhtmltopdf is installed and executable on the server.',
                ]);
        }
    }

    public function store(StoreEmployeeRequest $request)
    {
        $this->employeeRepository->create($request->validated());

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load('company');

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $companies = $this->companyRepository->getAll();

        return view('employees.edit', compact('employee', 'companies'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $this->employeeRepository->update($employee, $request->validated());

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $this->employeeRepository->delete($employee);

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}