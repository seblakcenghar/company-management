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
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Companies</h1>
        <div class="header-actions">
            <a href="{{ route('companies.create') }}" class="btn btn-primary">Add Company</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Logo</th>
                <th>Website</th>
                <th width="220">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($companies as $company)
                <tr>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->email }}</td>
                    <td>
                        <img src="{{ route('companies.logo', $company) }}" width="60" alt="{{ $company->name }}">
                    </td>
                    <td>
                        <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                    </td>
                    <td>
                        <a href="{{ route('companies.show', $company) }}" class="btn btn-info btn-sm">Show</a>
                        <a href="{{ route('companies.edit', $company) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('companies.destroy', $company) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Delete this company?')" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $companies->links() }}
</div>
@endsection