<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <title>Add Multiple Asset</title>
</head>

<body class="inventory-body">
    <div class="container">
        <section class="card">
            <header class="form-header">
                <div>
                    <h1 class="form-title">Bulk Asset Creation</h1>
                    <p class="form-subtitle">Create multiple assets sharing the same specifications.</p>
                </div>
                <a href="{{route('inventory.display_index')}}" class="btn btn-outline btn-sm">
                    Back to List
                </a>
            </header>

            <form action="{{route('inventory.confirm_multipleCreate')}}" method="POST">
                @csrf

                <div class="bulk-grid">
                    <!-- LEFT: ASSET INFORMATION -->
                    <div class="bulk-column">
                        <h2 class="step-title">Asset Information</h2>

                        <div class="form-group">
                            <label for="device_type">Device Type</label>
                            <select name="device_type" id="device_type" class="select" required>
                                <option value="" selected>Select Type</option>
                                @foreach($device_type as $device_types)
                                <option value="{{$device_types->code}}"
                                    data-has-basic="{{ $device_types->has_basic }}"
                                    data-has-hardware="{{ $device_types->has_hardware }}"
                                    data-has-purchase="{{ $device_types->has_purchase }}"
                                    data-has-license-notes="{{ $device_types->has_license_notes }}">{{$device_types->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Brand</label>
                                <input type="text" name="brand" class="input" required>
                            </div>
                            <div class="form-group">
                                <label>Model</label>
                                <input type="text" name="model" id="model" class="input" required>
                            </div>
                        </div>
                        <div id="hardwareFields">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Processor</label>
                                    <input type="text" name="processor" class="input">
                                </div>
                                <div class="form-group">
                                    <label>RAM (GB)</label>
                                    <input type="number" name="ram_gb" class="input">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Storage</label>
                                    <input type="text" name="storage" class="input" placeholder="e.g. 512GB SSD">
                                </div>
                                <div class="form-group">
                                    <label>Monitor</label>
                                    <input type="text" name="monitor" class="input">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>GPU</label>
                                    <input type="text" name="gpu" class="input" placeholder="e.g. RTX 5090">
                                </div>
                                <div class="form-group">
                                    <label>Power Supply</label>
                                    <input type="text" name="power_supply" class="input">
                                </div>
                            </div>



                            <div class="form-row">
                                <div class="form-group">
                                    <label>Peripherals</label>
                                    <input type="text" name="peripherals" class="input" placeholder="e.g. Mouse, Keyboard">
                                </div>
                            </div>
                            <div id="softwareFields">

                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Operating System</label>
                                        <input type="text" name="operating_system" class="input">
                                    </div>
                                    <div class="form-group">
                                        <label>Product Key OS</label>
                                        <input type="text" name="product_key_os" class="input">
                                    </div>
                                </div>
                                <div class="form-row">

                                    <div class="form-group">
                                        <label>Product Key Others</label>
                                        <input type="text" name="product_key_other" class="input">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="purchaseFields">

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Purchase Date</label>
                                    <input type="date" name="purchase_date" class="input">
                                </div>
                                <div class="form-group">
                                    <label>Warranty Expiry</label>
                                    <input type="date" name="warranty_expiry" class="input">
                                </div>
                                <div class="form-group">
                                    <label>Vendor</label>
                                    <input type="text" name="vendor" class="input">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: SERIAL NUMBERS -->
                    <div class="bulk-column">
                        <h2 class="step-title">Serial Numbers</h2>

                        <p class="form-subtitle">
                            Enter one serial number per line. Each line will create one asset.
                        </p>

                        <div class="form-group">
                            <textarea name="serial_numbers"
                                rows="14"
                                class="textarea"
                                id="serial_numbers"
                                placeholder="One serial number per line"
                                required
                                style="resize: none;"></textarea>
                        </div>

                        <p class="serial-count">
                            Total: <span id="serialCount">0</span> asset(s)
                        </p>
                        <p id="duplicateMessage" class=" serial-count text-danger hidden"></p>
                        @if ($errors->has('serial_numbers'))
                        <p class="serial-count text-danger">
                            {{ $errors->first('serial_numbers') }}
                        </p>
                        @endif
                    </div>
                </div>

                <div class="step-actions">
                    <button type="submit" class="btn btn-primary" id="submitBtn">Create Assets</button>
                    <a href="{{route('inventory.display_index')}}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </section>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const deviceType = document.getElementById("device_type");

            const hardwareFields = document.getElementById("hardwareFields");
            const softwareFields = document.getElementById("softwareFields");
            const purchaseFields = document.getElementById("purchaseFields");

            function toggleFields() {
                if (!deviceType) return;

                const selectedOption =
                    deviceType.options[deviceType.selectedIndex];

                const deviceConfig = {
                    hasHardware: selectedOption.dataset.hasHardware === "1",
                    hasPurchase: selectedOption.dataset.hasPurchase === "1",
                    hasLicenseNotes: selectedOption.dataset.hasLicenseNotes === "1",
                };

                toggleSection(
                    hardwareFields,
                    deviceConfig.hasHardware
                );

                toggleSection(
                    softwareFields,
                    deviceConfig.hasLicenseNotes
                );

                toggleSection(
                    purchaseFields,
                    deviceConfig.hasPurchase
                );
            }

            function toggleSection(section, shouldShow) {
                if (!section) return;

                section.style.display = shouldShow ? "block" : "none";

                section.querySelectorAll(
                    "input, select, textarea"
                ).forEach((input) => {
                    input.disabled = !shouldShow;
                });
            }

            deviceType.addEventListener("change", toggleFields);

            toggleFields();
        });
    </script>
</body>

</html>