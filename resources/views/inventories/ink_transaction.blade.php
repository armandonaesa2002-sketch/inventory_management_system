<!DOCTYPE html>
<html>

<head>
    <title>Ink Stock</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body class="inventory-body">
    <div class="container">
        <section class="card">
            <header class="form-header">

                <div>
                    <h1 class="form-title">
                        Ink Transaction
                    </h1>
                    <p class="form-subtitle">

                    </p>
                </div>
                <a href="{{route('inventory.ink_stock')}}" class="btn btn-outline btn-sm">
                    Back to List
                </a>
            </header>

            <form action="" method="POST">
                @csrf
                @method('post')
                <div class="form-group">
                    <label for="transaction_type">Transaction Type</label>
                    <select name="transaction_type" id="transaction_type" class="select">
                        <option value="" selected>Select type</option>
                        <option value="release">Release</option>
                        <option value="receive">Receive</option>
                        <option value="adjustment">Adjustment</option>
                    </select>

                </div>
                <div class="form-group">
                    <label for="ink_brand">Ink Type</label>
                    <select name="ink_brand" id="ink_brand" class="select" data-other-target="ink_other_wrapper">
                        <option value="" selected>Select Ink</option>
                        @foreach($ink_stock as $inks)
                        <option>{{$inks->brand}} {{$inks->type}}: {{$inks->color}}</option>
                        @endforeach
                    </select>

                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="ink_type">Type</label>
                        <select name="ink_type" id="ink_type" class="select" data-other-target="ink_type_other">


                            <option value="" selected>Select type</option>

                            <option>003</option>
                            <option>664</option>
                            <option>057</option>
                            <option>790</option>
                            <option>71</option>
                            <option>5000</option>
                            <option>BT D60</option>
                            <option>269XL</option>
                            <option>GT52</option>
                            <option>TF11F</option>
                            <option value="other">Other</option>
                        </select>
                        <div id="ink_type_other" style="display: none; margin-top:5px;">
                            <input type="text" id="ink_type_other" name="ink_type_other" class="input" placeholder="Input Other type">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-group">
                            <label for="color">Color</label>
                            <select name="color" id="color" class="select" data-other-target="color_other">

                                <option value="" selected>Select color</option>

                                <option>Black</option>
                                <option>Cyan</option>
                                <option>Light Cyan</option>
                                <option>Yellow</option>
                                <option>Magenta</option>
                                <option>Light Magenta</option>
                                <option value="other">Other</option>
                            </select>
                            <div id="color_other" style="display: none; margin-top:5px;">
                                <input type="text" id="color_other" name="color_other" class="input" placeholder="Input Other color">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Stock</label>
                        <input type="number" class="input"
                            placeholder="Ink Stock" min="0"
                            name="ink_stock"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Reorder level</label>
                        <input type="number" name="reorder_level" class="input" placeholder="Reoder Level" min="1">
                    </div>

                </div>

                <div class="step-actions">
                    <button type="submit" class="btn btn-primary">
                        Add Transaction
                    </button>
                    <a href="{{route('inventory.ink_stock')}}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </section>
    </div>
    <script>
        document.querySelectorAll('[data-other-target]').forEach(select => {
            const targetId = select.dataset.otherTarget;
            const target = document.getElementById(targetId);

            select.addEventListener('change', function() {
                const input = target.querySelector('input');

                if (this.value === 'other') {
                    target.style.display = 'block';
                    input.required = true;
                } else {
                    target.style.display = 'none';
                    input.required = false;
                    input.value = '';
                }
            });
        });
    </script>
</body>

</html>