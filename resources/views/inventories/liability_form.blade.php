<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Liability Form Test</h1>

    <p>Assignment ID: {{ $assignment->id }}</p>

    <p>Asset ID: {{ $asset->id }}</p>

    <p>Asset Tag: {{ $asset->asset_tag }}</p>

    <p>Device Type: {{ $asset->device_type_label }}</p>

    <p>Brand: {{ $asset->brand }}</p>

    <p>Model: {{ $asset->model }}</p>

    <p>Serial Number: {{ $asset->serial_number }}</p>
</body>
</html>