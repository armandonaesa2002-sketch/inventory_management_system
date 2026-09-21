<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <title>Document</title>
</head>

<body class="inventory-body">
    <div class="container">


        <section class="card">

            <header class="form-header">
                <div>
                    <h1 class="form-title">Ink Inventory Movement</h1>
                    <p class="form-subtitle">
                        Release, Receive and Adjustment in one submission.
                    </p>
                </div>

                <a href="{{ route('inventory.ink_stock') }}"
                    class="btn btn-outline btn-sm">
                    Back to List
                </a>
            </header>


            <form action="#" method="POST">

                @csrf
                @method('post')


                <!-- MOVEMENT TYPE -->

                <div class="form-group">

                    <label for="movement_type">
                        Movement Type
                    </label>

                    <select
                        name="movement_type"
                        id="movement_type"
                        class="select"
                        required>

                        <option value="" selected>
                            Select movement type
                        </option>

                        <option value="IN">
                            Receive
                        </option>

                        <option value="OUT">
                            Release
                        </option>

                        <option value="ADJUSTMENT">
                            Adjustment
                        </option>

                    </select>

                </div>


                <!-- TRANSACTIONS -->

                <div id="transactions">


                    <!-- =========================
                        TRANSACTION
                    ========================== -->

                    <div class="transaction-group ink-group">

                        <div class="inkheader">

                            <h4 class="group-title">
                                Transaction 1
                            </h4>

                            <button
                                type="button"
                                class="btn btn-danger remove-transaction"
                                style="display:none;">

                                Remove

                            </button>

                        </div>


                        <div class="form-row3">

                            <!-- INK -->

                            <div class="form-group">

                                <label>
                                    Ink
                                </label>

                                <select
                                    name="transactions[0][ink_id]"
                                    class="select"
                                    required>

                                    <option value="" selected>
                                        Select ink
                                    </option>

                                    @foreach($ink_stock as $inks)
                                    <option>{{$inks->brand}} {{$inks->type}}: {{$inks->color}}</option>
                                    @endforeach

                                </select>

                            </div>


                            <!-- QUANTITY -->

                            <div class="form-group">

                                <label>
                                    Quantity
                                </label>

                                <input
                                    type="number"
                                    name="transactions[0][quantity]"
                                    class="input"
                                    placeholder="Quantity"
                                    min="1"
                                    required>

                            </div>
                            <div class="form-group"
                                style="display:none;">

                                <label>
                                    Receive by
                                </label>

                                <input
                                    type="text"
                                    name="transactions[0][receive_by]"
                                    class="input"
                                    placeholder="receive by"
                                    required>

                            </div>
                            <div class="form-group"
                                style="display:none;">

                                <label>
                                    Release to
                                </label>

                                <input
                                    type="text"
                                    name="transactions[0][release_to]"
                                    class="input"
                                    placeholder="Release to"
                                    required>

                            </div>

                        </div>

                        <!-- ADJUSTMENT OPTIONS -->

                        <div
                            id="adjustment-fields"
                            style="display:none; margin-top:20px;">

                            <div class="form-row3">

                                <div class="form-group">

                                    <label>
                                        Adjustment Type
                                    </label>

                                    <select
                                        name="adjustment_type"
                                        id="adjustment_type"
                                        class="select">

                                        <option value="increase">
                                            Increase
                                        </option>

                                        <option value="decrease">
                                            Decrease
                                        </option>

                                    </select>

                                </div>


                                <div class="form-group">

                                    <label>
                                        Reason
                                    </label>

                                    <input
                                        type="text"
                                        name="reason"
                                        id="reason"
                                        class="input"
                                        placeholder="Reason for adjustment">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>







                <!-- ADD TRANSACTION -->

                <button
                    type="button"
                    id="add-group"
                    class="btn btn-outline">

                    + Add Another Transaction

                </button>


                <!-- ACTIONS -->

                <div class="step-actions">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Save Transactions

                    </button>

                    <a
                        href="{{ route('inventory.ink_stock') }}"
                        class="btn btn-outline">

                        Cancel

                    </a>

                </div>

            </form>

        </section>
    </div>
    <script>
        let transactionIndex = 1;


        /*
        |--------------------------------------------------------------------------
        | MOVEMENT TYPE
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('movement_type')
            .addEventListener('change', function() {

                const adjustmentFields =
                    document.getElementById('adjustment-fields');

                // Get all receive_by and release_to form groups
                const receiveByFields =
                    document.querySelectorAll('[name*="receive_by"]');

                const releaseToFields =
                    document.querySelectorAll('[name*="release_to"]');


                // Handle adjustment fields
                if (this.value === 'ADJUSTMENT') {

                    adjustmentFields.style.display = 'block';

                    // Hide both receive_by and release_to for adjustments
                    receiveByFields.forEach(field => {
                        field.closest('.form-group').style.display = 'none';
                        field.removeAttribute('required');
                        field.value = "";
                    });

                    releaseToFields.forEach(field => {
                        field.closest('.form-group').style.display = 'none';
                        field.removeAttribute('required');
                        field.value = "";
                    });

                } else if (this.value === 'IN') {

                    adjustmentFields.style.display = 'none';

                    // Show receive_by, hide release_to
                    receiveByFields.forEach(field => {
                        field.closest('.form-group').style.display = 'block';
                        field.setAttribute('required', 'required');
                    });

                    releaseToFields.forEach(field => {
                        field.closest('.form-group').style.display = 'none';
                        field.removeAttribute('required');
                        field.value = "";
                    });

                } else if (this.value === 'OUT') {

                    adjustmentFields.style.display = 'none';

                    // Show release_to, hide receive_by
                    receiveByFields.forEach(field => {
                        field.closest('.form-group').style.display = 'none';
                        field.removeAttribute('required');
                        field.value = "";
                    });

                    releaseToFields.forEach(field => {
                        field.closest('.form-group').style.display = 'block';
                        field.setAttribute('required', 'required');
                    });

                } else {

                    // Default: hide all conditional fields
                    adjustmentFields.style.display = 'none';

                    receiveByFields.forEach(field => {
                        field.closest('.form-group').style.display = 'none';
                        field.removeAttribute('required');
                        field.value = "";
                    });

                    releaseToFields.forEach(field => {
                        field.closest('.form-group').style.display = 'none';
                        field.removeAttribute('required');
                        field.value = "";
                    });

                }

            });



        /*
        |--------------------------------------------------------------------------
        | REMOVE TRANSACTION
        |--------------------------------------------------------------------------
        */

        document.addEventListener('click', function(e) {

            if (!e.target.classList.contains('remove-transaction')) {
                return;
            }

            const transactions =
                document.querySelectorAll('.transaction-group');

            if (transactions.length === 1) {
                return;
            }

            e.target
                .closest('.transaction-group')
                .remove();

            updateTransactionNumbers();

        });


        /*
        |--------------------------------------------------------------------------
        | ADD TRANSACTION
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('add-group')
            .addEventListener('click', function() {

                const container =
                    document.getElementById('transactions');

                const index =
                    document.querySelectorAll('.transaction-group').length;

                const firstTransaction =
                    document.querySelector('.transaction-group');

                const newTransaction =
                    firstTransaction.cloneNode(true);


                /*
                |--------------------------------------------------------------------------
                | SHOW REMOVE BUTTON
                |--------------------------------------------------------------------------
                */

                newTransaction
                    .querySelector('.remove-transaction')
                    .style.display = 'block';


                /*
                |--------------------------------------------------------------------------
                | RESET INPUTS
                |--------------------------------------------------------------------------
                */

                newTransaction
                    .querySelectorAll('input')
                    .forEach(input => {

                        input.value = '';

                    });


                /*
                |--------------------------------------------------------------------------
                | RESET SELECTS
                |--------------------------------------------------------------------------
                */

                newTransaction
                    .querySelectorAll('select')
                    .forEach(select => {

                        select.selectedIndex = 0;

                    });


                /*
                |--------------------------------------------------------------------------
                | UPDATE TITLE
                |--------------------------------------------------------------------------
                */

                newTransaction
                    .querySelector('.group-title')
                    .textContent =
                    `Transaction ${index + 1}`;


                /*
                |--------------------------------------------------------------------------
                | UPDATE NAMES
                |--------------------------------------------------------------------------
                */

                newTransaction
                    .querySelectorAll('[name]')
                    .forEach(input => {

                        input.name =
                            input.name.replace(
                                /transactions\[\d+\]/,
                                `transactions[${index}]`
                            );

                    });


                container.appendChild(newTransaction);

                transactionIndex++;

            });


        /*
        |--------------------------------------------------------------------------
        | UPDATE TRANSACTION NUMBERS
        |--------------------------------------------------------------------------
        */

        function updateTransactionNumbers() {

            const transactions =
                document.querySelectorAll('.transaction-group');


            transactions.forEach((transaction, index) => {

                transaction
                    .querySelector('.group-title')
                    .textContent =
                    `Transaction ${index + 1}`;


                transaction
                    .querySelectorAll('[name]')
                    .forEach(input => {

                        input.name =
                            input.name.replace(
                                /transactions\[\d+\]/,
                                `transactions[${index}]`
                            );

                    });

            });


            transactionIndex = transactions.length;

        }
    </script>
</body>

</html>