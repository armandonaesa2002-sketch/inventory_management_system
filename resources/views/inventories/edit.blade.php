<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <title>Edit Asset</title>
</head>
<body class="inventory-body">
<div class="container">
    <section class="card">
        <header class="form-header">
            <div>
                <h1 class="form-title">Edit Asset</h1>
                <p class="form-subtitle">Update details for this asset.</p>
            </div>
            <a href="{{route('inventory.display_index')}}" class="btn btn-outline btn-sm">
                Back to List
            </a>
        </header>

        @if(session('info'))
            <div class="alert alert-success">
                {{ session('info') }}
            </div>
        @endif

        <div class="asset-tag-label">
            <span class="label">Asset Tag:</span>
            <span class="value">{{$asset->asset_tag ?? ''}}</span>
        </div>

        <!-- TABS BUTTONS -->
        <div class="tabs">
            <button class="tab-btn active" type="button" onclick="openTab(event, 'basic')">Basic Info</button>
            <button class="tab-btn" type="button" onclick="openTab(event, 'hardware')">Hardware</button>
            <button class="tab-btn" type="button" onclick="openTab(event, 'purchase')">Purchase Info</button>
            <button class="tab-btn" type="button" onclick="openTab(event, 'software')">License & Notes</button>
        </div>

        <form method="POST" action="{{route('inventory.update_asset', ['asset' => $asset])}}">
            @csrf
            @method('PUT')

            <input type="hidden" id="is_edit" value="1">
            <input type="hidden" id="original_device_type" value="{{ $asset->device_type }}">
            <input type="hidden" id="original_asset_tag" value="{{ $asset->asset_tag }}">

            <!-- BASIC -->
            <div id="basic" class="tab-content active">
                <div class="form-group">
                    <label for="asset_tag">Asset Tag</label>
                    <input type="text" name="asset[asset_tag]" id="asset_tag"
                           class="input" value="{{$asset->asset_tag ?? ''}}" readonly>
                </div>

                <div class="form-group">
                    <label for="device_type">Device Type</label>
                    <select name="asset[device_type]" id="device_type" class="select">
                        <option value="laptop"  {{old('asset.device_type', $asset->device_type) == 'laptop' ? 'selected' : ''}}>Laptop</option>
                        <option value="aio"     {{old('asset.device_type', $asset->device_type) == 'aio' ? 'selected' : ''}}>All in One</option>
                        <option value="mac"     {{old('asset.device_type', $asset->device_type) == 'mac' ? 'selected' : ''}}>Mac</option>
                        <option value="desktop" {{old('asset.device_type', $asset->device_type) == 'desktop' ? 'selected' : ''}}>Desktop</option>
                        <option value="printer" {{old('asset.device_type', $asset->device_type) == 'printer' ? 'selected' : ''}}>Printer</option>
                    </select>
                    <div class="error">Required field</div>
                </div>

                <div class="form-group">
                    <label for="brand">Brand</label>
                    <input type="text" name="asset[brand]" placeholder="Brand" class="input"
                           value="{{$asset->brand ?? ''}}">
                    <div class="error">Required field</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" name="asset[model]" id="model" placeholder="Model"
                               class="input" value="{{$asset->model ?? ''}}">
                        <div class="error">Required field</div>
                    </div>

                    <div class="form-group">
                        <label for="serial_number">Serial Number</label>
                        <input type="text" name="asset[serial_number]" id="serial_number"
                               placeholder="Serial Number" class="input"
                               value="{{$asset->serial_number ?? ''}}">
                        <div class="error">Required field</div>
                    </div>
                </div>
            </div>

            <!-- HARDWARE -->
            <div id="hardware" class="tab-content">
                <div class="form-group">
                    <label for="processor">Processor</label>
                    <input type="text" name="hardware[processor]" value="{{$asset->hardware->processor ?? ''}}"
                           placeholder="Processor" class="input">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="ram">RAM (GB)</label>
                        <input type="number" name="hardware[ram_gb]" value="{{$asset->hardware->ram_gb ?? ''}}"
                               placeholder="RAM" class="input">
                    </div>
                    <div class="form-group">
                        <label for="storage">Storage</label>
                        <input type="text" name="hardware[storage]" value="{{$asset->hardware->storage ?? ''}}"
                               placeholder="Storage" class="input">
                    </div>
                    
                    <div class="form-group">
                        <label for="monitor">Monitor</label>
                        <input type="text" name="hardware[monitor]" value="{{$asset->hardware->monitor ?? ''}}"
                        placeholder="Monitor" class="input">
                    </div>
                    <div class="form-group">
                        <label for="gpu">GPU</label>
                        <input type="text" name="hardware[gpu]" value="{{$asset->hardware->gpu ?? ''}}"
                        placeholder="e.g. RTX 5090" class="input">
                    </div>
                    <div class="form-group">
                        <label for="power_supply">Power Supply</label>
                        <input type="text" name="hardware[power_supply]" value="{{$asset->hardware->power_supply ?? ''}}" class="input">
                    </div>
                    <div class="form-group">
                        <label for="peripherals">Peripherals</label>
                        <input type="text" name="hardware[peripherals]" value="{{$asset->hardware->peripherals ?? ''}}"
                        placeholder="e.g. Mouse, Keyboard" class="input">
                    </div>
                </div>
            </div>

            <!-- PURCHASE -->
            <div id="purchase" class="tab-content">
                <div class="form-row">
                    <div class="form-group">
                        <label for="purchase_date">Purchase Date</label>
                        <input type="date" name="asset[purchase_date]"
                               value="{{$asset->purchase_date ?? ''}}" class="input">
                    </div>
                    <div class="form-group">
                        <label for="warranty_expiry">Warranty Expiry</label>
                        <input type="date" name="asset[warranty_expiry]"
                               value="{{$asset->warranty_expiry ?? ''}}" class="input">
                    </div>
                </div>

                <div class="form-group">
                    <label for="vendor">Vendor</label>
                    <input type="text" name="asset[vendor]" value="{{$asset->vendor ?? ''}}"
                           class="input" placeholder="Vendor">
                </div>
            </div>

            <!-- SOFTWARE -->
            <div id="software" class="tab-content">
                <div class="form-group">
                    <label for="operating_system">Operating System</label>
                    <input type="text" name="software[operating_system]"
                           placeholder="Operating System" class="input"
                           value="{{$asset->software->operating_system ?? ''}}">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="product_key_os">Product Key OS</label>
                        <input type="text" name="software[product_key_os]"
                               placeholder="Product Key OS" class="input"
                               value="{{$asset->software->product_key_os ?? ''}}">
                    </div>
                    <div class="form-group">
                        <label for="product_key_other">Product Key Others</label>
                        <input type="text" name="software[product_key_other]"
                               placeholder="Product Key Others" class="input"
                               value="{{$asset->software->product_key_other ?? ''}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="asset[remarks]" placeholder="Remarks"
                              rows="4" style="resize:none;" class="textarea">{{$asset->remarks}}</textarea>
                </div>
            </div>

            <div class="step-actions">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{route('inventory.display_index')}}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </section>
</div>

<script src="{{ asset('js/multi-step.js') }}"></script>
</body>

</html>