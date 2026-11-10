<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Import Results</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body class="inventory-body">
    <div class="container">
        <section class="card">

            <header class="form-header">
                <div>
                    <h1 class="form-title">Excel Import Results</h1>
                    <p class="form-subtitle">
                        Summary of the records processed from your Excel file.
                    </p>
                </div>
                <div class="step-actions">
                    @if(isset($batch) && !$batch->reverted_at)
                        <form action="{{ route('assets.import.revert', $batch->id) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to revert this import? All assets and related records from this batch will be deleted.')">

                            @csrf

                            <button type="submit" class="btn btn-danger">
                                Revert Import
                            </button>
                        </form>
                    @elseif(isset($batch) && $batch->reverted_at)
                        <span class="badge bg-secondary">
                            This import has already been reverted.
                        </span>
                    @endif
                    <a href="{{ route('assets.import.form') }}" class="btn btn-primary">
                        Back to Import
                    </a>
                </div>
            </header>
            <div class="stats-grid">
                <div class="stat-card stat-available">
                    <span class="stat-label">Successful Import</span>
                    <span class="stat-value">{{ $success }}</span>
                </div>

                <div class="stat-card stat-lost">
                    <span class="stat-label">Failed Imports</span>
                    <span class="stat-value">{{ count($failed) }}</span>
                </div>
            </div>
            

            @if (count($failed) > 0)
                <div class="form-group">
                    <label>Failed Records</label>

                    <p>
                        <a href="{{ route('assets.import.download-failed') }}"
                           class="btn btn-outline">
                            Download Failed Excel
                        </a>
                    </p>
                </div>

                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Excel Row</th>
                                <th>Reason</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($failed as $item)
                                <tr>
                                    <td>{{ $item['row'] }}</td>
                                    <td>{{ $item['reason'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
            <div class="alert alert-success">
                All records imported successfully!
            </div>
            @endif

            

        </section>
    </div>
</body>

</html>
