@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Employee Detail</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $employee->name }}</p>
            <p><strong>Company:</strong> {{ $employee->company->name ?? '-' }}</p>
            <p><strong>Email:</strong> {{ $employee->email }}</p>
        </div>
    </div>

    <a href="{{ route('employees.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection