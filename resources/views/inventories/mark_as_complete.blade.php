<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm page</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="inventory-body">
    <div class="container">
        <section class="card">
            <header class="form-header">
                <div>
                    <h1 class="form-title">Complete Repair</h1>
                    <p class="form-subtitle">Select where the asset should be assigned after the repair is completed.</p>
                </div>
                <a href="{{route('inventory.display_index')}}" class="btn btn-outline btn-sm">
                    Back to List
                </a>
            </header>
            <form action="#">
                <label>
  <input type="radio" name="assignmentOption" value="keep" checked>
  Keep Assigned to Current User
</label>

<label>
  <input type="radio" name="assignmentOption" value="spare">
  Return to Spare Inventory
</label>
            </form>
        </section>
    </div>    
</body>
</html>