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
                    <h1 class="form-title">Add Multiple Ink</h1>
                    <p class="form-subtitle">Add multiple ink groups and colors in one submission.</p>
                </div>
                <a href="{{route('inventory.ink_stock')}}" class="btn btn-outline btn-sm">
                    Back to List
                </a>
            </header>


            <form action="{{route('inventory.create_multipleink')}}" method="POST">
                @csrf
                @method('post')

                @if(session('duplicate_ink'))
                <div class="alert alert-danger">
                    {{ session('duplicate_ink') }}
                </div>
                @endif
                @if ($errors->has('color_error'))
                <div class="alert alert-danger">
                    {{ $errors->first('color_error') }}
                </div>
                @endif



                <div id="ink-groups">

                    <!-- =========================
                        INK GROUP
                    ========================== -->

                    <div class="ink-group">

                        <div class="inkheader">

                            <h4 class="group-title">
                                Ink Group 1
                            </h4>
                            <button type="button" class="btn btn-danger remove-ink-group" style="display:none;">
                                Remove Group
                            </button>
                        </div>

                        <div class="form-row2">
                            <!-- BRAND -->

                            <div class="form-group">

                                <label>Brand</label>

                                <select
                                    name="groups[0][brand]"
                                    class="select brand-select"
                                    required>

                                    <option value="" selected>
                                        Select brand
                                    </option>

                                    <option>Epson</option>
                                    <option>Brother</option>
                                    <option>Pixma</option>
                                    <option>HP</option>

                                    <option value="other">
                                        Other
                                    </option>

                                </select>


                                <div
                                    class="other-brand"
                                    style="display:none; margin-top:5px;">

                                    <input
                                        type="text"
                                        name="groups[0][other_brand]"
                                        class="input"
                                        placeholder="Input Other Brand">

                                </div>

                            </div>




                            <!-- TYPE -->

                            <div class="form-group">

                                <label>Type</label>

                                <select
                                    name="groups[0][type]"
                                    class="select type-select"
                                    required>

                                    <option value="" selected>
                                        Select type
                                    </option>

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

                                    <option value="other">
                                        Other
                                    </option>

                                </select>


                                <div
                                    class="other-type"
                                    style="display:none; margin-top:5px;">

                                    <input
                                        type="text"
                                        name="groups[0][other_type]"
                                        class="input"
                                        placeholder="Input Other Type">

                                </div>

                            </div>


                            <!-- REORDER LEVEL -->

                            <div class="form-group">

                                <label>
                                    Reorder Level
                                </label>

                                <input
                                    type="number"
                                    name="groups[0][reorder_level]"
                                    class="input"
                                    placeholder="Reorder Level"
                                    min="1"
                                    required>

                            </div>

                        </div>


                        <!-- =========================
                            COLORS
                        ========================== -->

                        <div class="form-group">

                            <label>Colors</label>


                            <div class="colors">

                                <!-- BLACK -->

                                <div class="color-row">

                                    <div class="color-name">
                                        <label>
                                            <input
                                                type="checkbox"
                                                name="groups[0][colors][0][selected]"
                                                value="Black"
                                                class="color-checkbox">

                                            Black
                                        </label>
                                    </div>

                                    <input
                                        type="number"
                                        name="groups[0][colors][0][stock]"
                                        class="input color-stock"
                                        placeholder="Stock"
                                        min="0" disabled>

                                </div>


                                <!-- CYAN -->

                                <div class="color-row">

                                    <div class="color-name">
                                        <label>
                                            <input
                                                type="checkbox"
                                                name="groups[0][colors][1][selected]"
                                                value="Cyan"
                                                class="color-checkbox">

                                            Cyan
                                        </label>
                                    </div>

                                    <input
                                        type="number"
                                        name="groups[0][colors][1][stock]"
                                        class="input color-stock"
                                        placeholder="Stock"
                                        min="0" disabled>

                                </div>

                                <!-- YELLOW -->

                                <div class="color-row">

                                    <div class="color-name">
                                        <label>
                                            <input
                                                type="checkbox"
                                                name="groups[0][colors][2][selected]"
                                                value="Yellow"
                                                class="color-checkbox">

                                            Yellow
                                        </label>
                                    </div>

                                    <input
                                        type="number"
                                        name="groups[0][colors][2][stock]"
                                        class="input color-stock"
                                        placeholder="Stock"
                                        min="0" disabled>

                                </div>


                                <!-- MAGENTA -->

                                <div class="color-row">

                                    <div class="color-name">
                                        <label>
                                            <input
                                                type="checkbox"
                                                name="groups[0][colors][3][selected]"
                                                value="Magenta"
                                                class="color-checkbox">

                                            Magenta
                                        </label>
                                    </div>

                                    <input
                                        type="number"
                                        name="groups[0][colors][3][stock]"
                                        class="input color-stock"
                                        placeholder="Stock"
                                        min="0" disabled>

                                </div>

                            </div>


                            <!-- CUSTOM COLORS -->



                            <div class="addcolor">
                                <label>Additional Colors</label>
                                <button
                                    type="button"
                                    class="btn btn-outline add-color">

                                    + Add Color

                                </button>
                            </div>
                            <div
                                class="custom-colors"
                                style="margin-top:10px;">

                            </div>

                        </div>

                    </div>

                </div>


                <!-- ADD GROUP -->

                <button
                    type="button"
                    id="add-group"
                    class="btn btn-outline">

                    + Add Another Ink Group

                </button>


                <!-- ACTIONS -->

                <div class="step-actions">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Add Inks

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
        let groupIndex = 1;


        /*
        |--------------------------------------------------------------------------
        | OTHER BRAND / TYPE
        |--------------------------------------------------------------------------
        */

        document.addEventListener('change', function(e) {

            if (
                e.target.classList.contains('brand-select') ||
                e.target.classList.contains('type-select')
            ) {

                const group = e.target.closest('.ink-group');

                const otherInput =
                    e.target.classList.contains('brand-select') ?
                    group.querySelector('.other-brand') :
                    group.querySelector('.other-type');


                if (e.target.value === 'other') {

                    otherInput.style.display = 'block';
                    otherInput.querySelector('input').required = true;

                } else {

                    otherInput.style.display = 'none';
                    otherInput.querySelector('input').required = false;

                    otherInput.querySelector('input').value = '';

                }
            }

        });


        /*
        |--------------------------------------------------------------------------
        | COLOR CHECKBOX / STOCK
        |--------------------------------------------------------------------------
        */

        document.addEventListener('change', function(e) {

            if (!e.target.classList.contains('color-checkbox')) {
                return;
            }

            const row = e.target.closest('.color-row');
            const stockInput = row.querySelector('.color-stock');

            if (e.target.checked) {

                stockInput.disabled = false;
                stockInput.required = true;

            } else {

                stockInput.disabled = true;
                stockInput.required = false;
                stockInput.value = '';

            }

        });


        /*
        |--------------------------------------------------------------------------
        | ADD CUSTOM COLOR
        |--------------------------------------------------------------------------
        */

        document.addEventListener('click', function(e) {

            if (!e.target.classList.contains('add-color')) {
                return;
            }


            const group =
                e.target.closest('.ink-group');


            const groupIndex = [...document.querySelectorAll('.ink-group')]
                .indexOf(group);


            const container =
                group.querySelector('.custom-colors');


            const colorIndex =
                container.children.length;


            const row =
                document.createElement('div');

            row.className =
                'custom-color-row';


            row.innerHTML = `

        <input
            type="text"
            name="groups[${groupIndex}][custom_colors][${colorIndex}][name]"
            class="input"
            placeholder="Color name"
            required
        >

        <input
            type="number"
            name="groups[${groupIndex}][custom_colors][${colorIndex}][stock]"
            class="input"
            placeholder="Stock"
            min="0"
            required
        >

        <button
            type="button"
            class="remove-color">
            x
        </button>

        `;


            container.appendChild(row);

        });


        /*
        |--------------------------------------------------------------------------
        | REMOVE CUSTOM COLOR
        |--------------------------------------------------------------------------
        */

        document.addEventListener('click', function(e) {

            if (
                e.target.classList.contains('remove-color')
            ) {

                e.target
                    .closest('.custom-color-row')
                    .remove();

            }

        });


        /*
        |--------------------------------------------------------------------------
        | REMOVE INK GROUP
        |--------------------------------------------------------------------------
        */

        document.addEventListener('click', function(e) {

            if (
                e.target.classList.contains('remove-ink-group')
            ) {

                e.target
                    .closest('.ink-group')
                    .remove();

            }

        });


        /*
        |--------------------------------------------------------------------------
        | ADD INK GROUP
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('add-group')
            .addEventListener('click', function() {

                const container =
                    document.getElementById('ink-groups');


                const index =
                    document.querySelectorAll('.ink-group').length;


                const firstGroup =
                    document.querySelector('.ink-group');


                const newGroup =
                    firstGroup.cloneNode(true);


                /*
                |--------------------------------------------------------------------------
                | Show Remove Group
                |--------------------------------------------------------------------------
                */

                newGroup
                    .querySelector('.remove-ink-group')
                    .style.display = 'block';


                /*
                | Reset values
                */

                newGroup
                    .querySelectorAll('input')
                    .forEach(input => {

                        if (input.type === 'checkbox') {

                            input.checked = false;

                        } else {

                            input.value = '';

                        }

                    });


                newGroup
                    .querySelectorAll('select')
                    .forEach(select => {

                        select.selectedIndex = 0;

                    });


                /*
                |--------------------------------------------------------------------------
                | Reset color stock inputs
                |--------------------------------------------------------------------------
                */

                newGroup
                    .querySelectorAll('.color-stock')
                    .forEach(input => {

                        input.disabled = true;
                        input.required = false;
                        input.value = '';

                    });


                /*
                | Hide Other fields
                */

                newGroup
                    .querySelector('.other-brand')
                    .style.display = 'none';

                newGroup
                    .querySelector('.other-type')
                    .style.display = 'none';


                /*
                | Remove custom colors
                */

                newGroup
                    .querySelector('.custom-colors')
                    .innerHTML = '';


                /*
                | Update group title
                */

                newGroup
                    .querySelector('.group-title')
                    .textContent =
                    `Ink Group ${index + 1}`;


                /*
                | Update input names
                */

                newGroup
                    .querySelectorAll('[name]')
                    .forEach(input => {

                        input.name =
                            input.name.replace(
                                /groups\[\d+\]/,
                                `groups[${index}]`
                            );

                    });


                container.appendChild(newGroup);

            });
    </script>
</body>

</html>