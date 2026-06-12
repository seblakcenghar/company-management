<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Employees - {{ $company->name }}</title>
    <style>
        @page {
            margin: 14mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        h1 {
            margin: 0 0 6px 0;
            font-size: 20px;
        }

        .meta {
            margin-bottom: 16px;
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        thead {
            display: table-header-group !important;
        }

        tbody {
            display: table-row-group;
        }

        th, td {
            border: 1px solid #cfd4dc;
            padding: 8px;
            text-align: left;
        }

        tr {
            page-break-inside: avoid !important;
        }

        th {
            background: #f5f7fa;
        }

        .generated-at {
            margin: 6px 0 16px 0;
            color: #666;
            font-size: 11px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <h1>Employee List</h1>
    <div class="meta">
        <div><strong>Company:</strong> {{ $company->name }}</div>
        <div><strong>Email:</strong> {{ $company->email }}</div>
        <div><strong>Total Employees:</strong> {{ $company->employees->count() }}</div>
    </div>
    <div class="generated-at">Generated at: {{ now()->format('Y-m-d H:i:s') }}</div>

    @php
        $rowsPerPage = 38;
        $chunks = $company->employees->chunk($rowsPerPage);
    @endphp

    @forelse($chunks as $chunkIndex => $chunk)
        @if($chunkIndex > 0)
            <div class="page-break"></div>
        @endif

        <table>
            <thead>
                <tr>
                    <th width="60">#</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chunk as $rowIndex => $employee)
                    <tr>
                        <td>{{ ($chunkIndex * $rowsPerPage) + $rowIndex + 1 }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <table>
            <thead>
                <tr>
                    <th width="60">#</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3">No employee data.</td>
                </tr>
            </tbody>
        </table>
    @endforelse
</body>
</html>
