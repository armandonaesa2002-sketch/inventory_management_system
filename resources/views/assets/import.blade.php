<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Import Assets</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body class="inventory-body">
    <div class="container-import">
        <section class="card">

            <header class="form-header">
                <div>
                    <h1 class="form-title">Import Assets</h1>
                    <p class="form-subtitle">
                        Upload your Excel file to import asset records.
                    </p>
                </div>
            </header>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('assets.import.preview') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="form-group">
                    <label for="file">Select Excel File</label>

                    <input
                        type="file"
                        name="file"
                        id="file"
                        class="input"
                        accept=".xlsx,.xls,.csv"
                        required>

                    <small>Accepted formats: XLSX, XLS, CSV</small>
                </div>

                <div class="step-actions">
                    <button type="submit" class="btn btn-primary">
                        Preview Excel
                    </button>

                    <a href="{{ url('/inventory') }}" class="btn btn-outline">
                        Cancel
                    </a>
                </div>
            </form>

        </section>
    </div>
</body>

</html>
