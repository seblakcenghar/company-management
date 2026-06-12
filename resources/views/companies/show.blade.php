@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Company Detail</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $company->name }}</p>
            <p><strong>Email:</strong> {{ $company->email }}</p>
            <p><strong>Website:</strong> <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a></p>
            <p><strong>Logo:</strong><br>
                <img src="{{ route('companies.logo', $company) }}" width="120" alt="{{ $company->name }}">
            </p>
        </div>
    </div>

    <a href="{{ route('companies.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection