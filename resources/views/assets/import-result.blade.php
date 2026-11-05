
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f4f6f9;">

<div class="container py-5">

    <div class="card shadow-sm p-4">

        <h3>Excel Import Results</h3>

        <div class="row my-4">
            <div class="col-md-6">
                <div class="alert alert-success">
                    Successful Imports:
                    <strong>{{ $success }}</strong>
                </div>
            </div>

            <div class="col-md-6">
                <div class="alert alert-danger">
                    Failed Imports:
                    <strong>{{ count($failed) }}</strong>
                </div>
            </div>
        </div>

        @if(count($failed) > 0)

            <h5>Failed Records</h5>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Excel Row</th>
                            <th>Reason</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($failed as $item)
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

        <a href="{{ route('assets.import.form') }}" class="btn btn-primary">
            Back to Import
        </a>

    </div>

</div>

</body>
</html>
