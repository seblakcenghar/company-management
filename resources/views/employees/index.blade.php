@extends('layouts.app')

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .header-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .header-actions .btn {
        border-radius: 999px;
        padding: 0.45rem 1rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .action-buttons .btn {
        border-radius: 999px;
        padding: 0.45rem 0.9rem;
        white-space: nowrap;
    }

    .table-action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
    }

    @media (max-width: 767.98px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            width: 100%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }

        .action-buttons {
            display: grid !important;
            grid-template-columns: 1fr;
            width: 100%;
        }

        .action-buttons .btn,
        .action-buttons form {
            width: 100%;
        }

        .action-buttons .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Employees</h1>
        <div class="header-actions">
            <a href="{{ route('employees.import.logs') }}" class="btn btn-outline-dark">Import Logs</a>
            <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Import / Export Employees</div>
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Company</label>
                    <select id="shared_company_id" class="form-select">
                        <option value="">-- Select Company --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-5">
                    <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        <input type="hidden" name="company_id" id="import_company_id" value="{{ old('company_id') }}">
                        <label class="form-label">Excel File (.xlsx/.xls, min 100 records)</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls">
                        @error('file') <small class="text-danger">{{ $message }}</small> @enderror
                    </form>
                </div>
                <div class="col-md-3"></div>
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2 action-buttons">
                        <button class="btn btn-success" form="importForm" type="submit">Import</button>
                        <form action="{{ route('employees.export.pdf') }}" method="POST" id="exportForm" class="d-inline">
                            @csrf
                            <input type="hidden" name="company_id" id="export_company_id" value="{{ old('company_id') }}">
                            <button class="btn btn-dark" type="submit">Export PDF</button>
                        </form>
                        <a href="{{ asset('samples/employees_import_100.xlsx') }}" class="btn btn-outline-secondary">Sample File</a>
                        <a href="{{ asset('samples/employees_import_failed_100.xlsx') }}" class="btn btn-outline-danger">Sample Failed</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Company</th>
                <th>Email</th>
                <th width="220">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->company->name ?? '-' }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>
                        <div class="table-action-buttons">
                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('employees.destroy', $employee) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete this employee?')" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $employees->links() }}
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const sharedCompany = document.getElementById('shared_company_id');
        const importCompany = document.getElementById('import_company_id');
        const exportCompany = document.getElementById('export_company_id');

        if (!sharedCompany || !importCompany || !exportCompany) {
            return;
        }

        const sync = () => {
            importCompany.value = sharedCompany.value;
            exportCompany.value = sharedCompany.value;
        };

        sync();
        sharedCompany.addEventListener('change', sync);
    })();
</script>
@endpush