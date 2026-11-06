<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Excel Preview</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body class="inventory-body">
    <div class="container-preview">
        <section class="card">

            <header class="form-header">
                <div>
                    <h1 class="form-title">Excel Import Preview</h1>
                    <p class="form-subtitle">
                        Review your Excel data before importing.
                    </p>
                </div>
            </header>

            <p class="form-subtitle">
                Total Rows: <strong>{{ $rows->count() }}</strong>
            </p>

            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            @foreach ($rows->first()?->keys() ?? [] as $heading)
                                <th>{{ $heading }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($rows as $row)
                            <tr>
                                @foreach ($row as $value)
                                    <td>{{ $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <form action="{{ route('assets.import.confirm') }}" method="POST">
                @csrf

                @php
                    $hasUser = $rows->contains(function ($row) {
                        return !empty(trim($row['user'] ?? ''));
                    });
                @endphp

                @if ($hasUser)
                    <div class="form-group">
                        <label for="preparedby_id">Prepared By</label>

                        <select
                            name="preparedby_id"
                            id="preparedby_id"
                            class="select"
                            required>
                            <option value="">Select Prepared By</option>

                            @foreach ($preparedByOptions as $option)
                                <option value="{{ $option->id }}">
                                    {{ $option->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="step-actions">
                    <button type="submit" class="btn btn-primary">
                        Confirm Import
                    </button>

                    <a href="{{ route('assets.import.form') }}" class="btn btn-outline">
                        Cancel
                    </a>
                </div>
            </form>

        </section>
    </div>
</body>

</html>
