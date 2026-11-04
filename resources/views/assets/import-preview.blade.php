<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Excel Preview</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .container-fluid {
            padding: 30px;
        }

        .preview-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        th {
            white-space: nowrap;
            background-color: #198754 !important;
            color: white !important;
        }

        td {
            white-space: nowrap;
        }
    </style>
</head>

<body>

    <div class="container-fluid">

        <div class="preview-card">

            <h3>Excel Import Preview</h3>

            <p class="text-muted">
                Review your Excel data before importing.
            </p>

            <div class="alert alert-info">
                Total Rows: <strong>{{ $rows->count() }}</strong>
            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

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

            <a href="{{ route('assets.import.form') }}"
                class="btn btn-secondary">
                Back
            </a>

        </div>

    </div>

</body>

</html>