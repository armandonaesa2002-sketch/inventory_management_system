<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Form</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="inventory-body">

<div class="container">
    <section class="card">
        <header class="form-header">
            <div>
                <h1 class="form-title">Add Asset</h1>
                <p class="form-subtitle">Fill in the details to register a new asset.</p>
            </div>
            <a href="{{route('inventory.display_index')}}" class="btn btn-outline btn-sm">
                Back to List
            </a>
        </header>
        
        <!-- STEP INDICATOR -->
        <div class="step-indicator">
            <div class="step-indicator-item active" data-step="1">
                <span class="circle">1</span>
                <span class="label">Basic Info</span>
            </div>
            <div class="step-indicator-item" data-step="2">
                <span class="circle">2</span>
                <span class="label">Hardware</span>
            </div>
            <div class="step-indicator-item" data-step="3">
                <span class="circle">3</span>
                <span class="label">Purchase</span>
            </div>
            <div class="step-indicator-item" data-step="4">
                <span class="circle">4</span>
                <span class="label">License & Notes</span>
            </div>
        </div>
        
        <div class="multi-step">
            <form action="{{route('inventory.add')}}" method="post">
                @csrf
                @method('post')
                
                <input type="hidden" id="is_edit" value="0">
                <input type="hidden" id="original_device_type" value="">
                <input type="hidden" id="original_asset_tag" value="">
                
                <!-- STEP 1 -->
                <div class="step active">
                    <h2 class="step-title">Basic Info</h2>
                    @if(session('serial_number'))
                    <div class="alert alert-danger">
                        {{session('serial_number')}}
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="asset_tag">Asset Tag</label>
                        <input type="text" name="asset_tag" id="asset_tag" class="input" readonly>
                        <div class="error">Required field</div>
                    </div>

                    <div class="form-group">
                        <label for="device_type">Device Type</label>
                        <select name="device_type" id="device_type" class="select" required>
                            <option value="" selected>Select Type</option>
                            <option value="laptop">Laptop</option>
                            <option value="aio">All in One</option>
                            <option value="mac">Mac</option>
                            <option value="desktop">Desktop</option>
                            <option value="minipc">Mini PC</option>
                            <option value="printer">Printer</option>
                        </select>
                        <div class="error">Required field</div>
                    </div>

                    <div class="form-group">
                        <label>Brand</label>
                        <input type="text" name="brand" class="input" required>
                        <div class="error">Required field</div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Model</label>
                            <input type="text" name="model" id="model" class="input" required>
                            <div class="error">Required field</div>
                        </div>
                        <div class="form-group">
                            <label>Serial Number</label>
                            <input type="text" name="serial_number" id="serial_number" class="input" required>
                            <div class="error">Required field</div>
                        </div>
                    </div>

                    <div class="step-actions">
                        <button type="button" class="btn btn-primary" onclick="nextStep()">Next</button>
                        <a href="{{route('inventory.display_index')}}" class="btn btn-outline">Cancel</a>
                    </div>
                </div>

                <!-- STEP 2 -->
                <div class="step">
                    <h2 class="step-title">Hardware</h2>

                    <div class="form-group">
                        <label>Processor</label>
                        <input type="text" name="processor" class="input">
                        <div class="error">Required field</div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>RAM (GB)</label>
                            <input type="number" name="ram_gb" class="input">
                            <div class="error">Required field</div>
                        </div>
                        <div class="form-group">
                            <label>Storage</label>
                            <input type="text" name="storage" class="input" placeholder="e.g. 512GB SSD">
                            <div class="error">Required field</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Monitor</label>
                            <input type="text" name="monitor" class="input">
                            <div class="error">Required field</div>
                        </div>
                        <div class="form-group">
                            <label>GPU</label>
                            <input type="text" name="gpu" class="input" placeholder="e.g. RTX 5090">
                            <div class="error">Required field</div>
                        </div>
                        
                        <div class="form-group">
                            <label>Power Supply</label>
                            <input type="text" name="power_supply" class="input">
                            <div class="error">Required field</div>
                        </div>
                        <div class="form-group">
                            <label>Peripherals</label>
                            <input type="text" name="peripherals" class="input" placeholder="e.g. Mouse, Keyboard">
                            <div class="error">Required field</div>
                        </div>
                    </div>

                    <div class="step-actions">
                        <button type="button" class="btn btn-primary" onclick="nextStep()">Next</button>
                        <button type="button" class="btn btn-outline" onclick="prevStep()">Back</button>
                        <a href="{{route('inventory.display_index')}}" class="btn btn-outline">Cancel</a>
                    </div>
                </div>

                <!-- STEP 3 -->
                <div class="step">
                    <h2 class="step-title">Purchase Info</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Purchase Date</label>
                            <input type="date" name="purchase_date" class="input">
                            <div class="error">Required field</div>
                        </div>
                        <div class="form-group">
                            <label>Warranty Expiry</label>
                            <input type="date" name="warranty_expiry" class="input">
                            <div class="error">Required field</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Vendor</label>
                        <input type="text" name="vendor" class="input">
                        <div class="error">Required field</div>
                    </div>

                    <div class="step-actions">
                        <button type="button" id="next_step_3" class="btn btn-primary" onclick="nextStep()">Next</button>
                        <button type="submit" id="submit_step_3" style="display:none;" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-outline" onclick="prevStep()">Back</button>
                        <a href="{{route('inventory.display_index')}}" class="btn btn-outline">Cancel</a>
                    </div>
                </div>

                <!-- STEP 4 -->
                <div class="step">
                    <h2 class="step-title">License & Notes</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Product Key OS</label>
                            <input type="text" name="product_key_os" class="input">
                            <div class="error">Required field</div>
                        </div>
                        <div class="form-group">
                            <label>Product Key Others</label>
                            <input type="text" name="product_key_other" class="input">
                            <div class="error">Required field</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-group">
                            <label>Operating System</label>
                            <input type="text" name="operating_system" class="input">
                            <div class="error">Required field</div>
                        </div>
                        <label>Remarks</label>
                        <textarea name="remarks" rows="4" class="textarea" style="resize:none;"></textarea>
                        <div class="error">Required field</div>
                    </div>

                    <div class="step-actions">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-outline" onclick="prevStep()">Back</button>
                        <a href="{{route('inventory.display_index')}}" class="btn btn-outline">Cancel</a>
                    </div>
                </div>

                <div class="progress-container">
                    <div class="progress-bar" id="progressBar"></div>
                </div>
            </form>
        </div>
    </section>
</div>

<script src="{{ asset('js/multi-step.js') }}"></script>
</body>

</html>