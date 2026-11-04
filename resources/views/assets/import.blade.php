<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Import Assets</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .import-container {
            max-width: 700px;
            margin: 60px auto;
        }

        .import-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .upload-icon {
            font-size: 45px;
            color: #198754;
        }
    </style>
</head>

<body>

    <div class="container import-container">

        <div class="import-card">

            <h3 class="mb-2">Import Assets</h3>

            <p class="text-muted">
                Upload your Excel file to import asset records.
            </p>

            <!-- Validation Errors -->
            @if ($errors->any())

            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
            </div>

            @endif

            <!-- Upload Form -->
            <form action="{{ route('assets.import.preview') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Select Excel File
                    </label>

                    <input
                        type="file"
                        name="file"
                        class="form-control"
                        accept=".xlsx,.xls,.csv"
                        required>

                    <small class="text-muted">
                        Accepted formats: XLSX, XLS, CSV
                    </small>

                </div>

                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-success">
                        Preview Excel
                    </button>

                    <a href="{{ url('/inventory') }}"
                        class="btn btn-secondary">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>

</html>