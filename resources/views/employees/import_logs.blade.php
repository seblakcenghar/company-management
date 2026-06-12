@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Employee Import Logs</h1>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to Employees</a>
    </div>

    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Company</th>
                        <th>File</th>
                        <th>Total</th>
                        <th>Success</th>
                        <th>Failed</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $log->company->name ?? '-' }}</td>
                            <td>{{ $log->file_name }}</td>
                            <td>{{ $log->total_rows }}</td>
                            <td>{{ $log->success_rows }}</td>
                            <td>{{ $log->failed_rows }}</td>
                            <td>
                                @if($log->status === 'success')
                                    <span class="badge bg-success">SUCCESS</span>
                                @elseif($log->status === 'partial')
                                    <span class="badge bg-warning text-dark">PARTIAL</span>
                                @else
                                    <span class="badge bg-danger">FAILED</span>
                                @endif
                            </td>
                        </tr>
                        @if(!empty($log->failed_data))
                            <tr>
                                <td colspan="7">
                                    <strong>Failed Rows (sample):</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach($log->failed_data as $item)
                                            <li>
                                                Row {{ $item['row'] ?? '-' }} -
                                                Name: {{ $item['name'] ?? '-' }},
                                                Email: {{ $item['email'] ?? '-' }} -
                                                {{ implode('; ', $item['errors'] ?? []) }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No import logs yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $logs->links() }}
    </div>
</div>
@endsection
