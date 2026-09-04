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
                                <option value="laptop">Laptop</option>
                                <option value="aio">All in One</option>
                                <option value="mac">Mac</option>
                                <option value="desktop">Desktop</option>
                                <option value="printer">Printer</option>
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
                        <div id="computerFields">
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
                                <div class="form-group">
                                    <label>Operating System</label>
                                    <input type="text" name="operating_system" class="input">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Product Key OS</label>
                                    <input type="text" name="product_key_os" class="input">
                                </div>
                                <div class="form-group">
                                    <label>Product Key Others</label>
                                    <input type="text" name="product_key_other" class="input">
                                </div>
                            </div>
                        </div>
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
        // document.addEventListener('DOMContentLoaded', function () {
        //     const textarea = document.getElementById('serial_numbers');
        //     const counter  = document.getElementById('serialCount');

        //     if (textarea && counter) {
        //         const updateCount = () => {
        //             const lines = textarea.value
        //                 .split('\n')
        //                 .map(l => l.trim())
        //                 .filter(l => l !== '');
        //             counter.textContent = lines.length;
        //         };
        //         textarea.addEventListener('input', updateCount);
        //         updateCount();
        //     }
        // });
        const textarea = document.getElementById('serial_numbers');
        const counter = document.getElementById('serialCount');
        const duplicateMessage = document.getElementById('duplicateMessage');
        const submitBtn = document.getElementById('submitBtn');

        function updateSerialInfo() {

            const serials = textarea.value
                .split(/\r?\n/)
                .map(s => s.trim())
                .filter(s => s !== '');

            // Update count
            counter.textContent = serials.length;

            // Check duplicates
            const seen = new Set();
            const duplicates = [];

            serials.forEach(serial => {
                if (seen.has(serial) && !duplicates.includes(serial)) {
                    duplicates.push(serial);
                }
                seen.add(serial);
            });

            if (duplicates.length > 0) {
                duplicateMessage.classList.remove('hidden');
                duplicateMessage.textContent =
                    'Duplicate serial number(s): ' + duplicates.join(', ');
                submitBtn.disabled = true;
            } else {
                duplicateMessage.classList.add('hidden');
                duplicateMessage.textContent = '';
                submitBtn.disabled = false;
            }
        }

        textarea.addEventListener('input', updateSerialInfo);
        updateSerialInfo();



        document.addEventListener('DOMContentLoaded', function() {

            const deviceType = document.getElementById('device_type');
            const computerFields = document.getElementById('computerFields');

            function toggleFields() {

                const isPrinter = deviceType.value === 'printer';

                computerFields.style.display = isPrinter ? 'none' : 'block';

                computerFields.querySelectorAll('input, select, textarea').forEach(input => {
                    input.disabled = isPrinter;
                });
            }

            deviceType.addEventListener('change', toggleFields);

            toggleFields(); // para gumana agad pag refresh
        });
    </script>
</body>

</html>