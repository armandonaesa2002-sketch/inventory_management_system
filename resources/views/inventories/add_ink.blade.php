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
                @php
                $isEdit = isset($edit_ink);
                $isDelete = isset($delete_ink);
                @endphp
                <div>
                    <h1 class="form-title">@if($isEdit)
                        Update Ink
                        @elseif($isDelete)
                        Delete Ink
                        @else
                        Add Ink
                        @endif</h1>
                    <p class="form-subtitle">
                        {{$isDelete ? 'This action is permanent and will remove related records.' : ''}}
                    </p>
                </div>
                <a href="{{route('inventory.ink_stock')}}" class="btn btn-outline btn-sm">
                    Back to List
                </a>
            </header>
            @if($isDelete)
            <div class="alert alert-danger">
                This process has cascade effect and <strong>cannot be undone</strong>. Proceed with caution.
            </div>
            @endif
            <form action="{{$isEdit ? route('inventory.update_ink', $edit_ink->id) : ($isDelete ? route('inventory.remove_ink', $delete_ink->id) : route('inventory.create_ink'))}}" method="POST">
                @csrf
                @method('post')
                @if(session('duplicate_ink'))
                <div class="alert alert-danger">
                    {{session('duplicate_ink')}}
                </div>
                @endif
                @if(session('info'))
                <div class="alert alert-success">
                    {{ session('info') }}
                </div>
                @endif
                <div class="form-group">
                    <label for="ink_brand">Brand</label>
                    <select name="ink_brand" id="ink_brand" class="select" data-other-target="ink_other_wrapper" {{$isDelete ? 'disabled' : 'required'}}>
                        @if($isEdit)
                        <option selected>{{$edit_ink->brand}}</option>
                        <option disabled>Select other brand</option>
                        @elseif($isDelete)
                        <option selected>{{$delete_ink->brand}}</option>
                        @else
                        <option value="" selected>Select brand</option>
                        @endif
                        <option>Epson</option>
                        <option>Brother</option>
                        <option>Pixma</option>
                        <option>HP</option>
                        <option value="other">Other</option>
                    </select>
                    <div id="ink_other_wrapper" style="display: none; margin-top:5px;">
                        <input type="text" id="ink_other" name="other_brand" class="input" placeholder="Input Other Brand">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="ink_type">Type</label>
                        <select name="ink_type" id="ink_type" class="select" data-other-target="ink_type_other" {{$isDelete ? 'disabled' : 'required'}}>
                            @if($isEdit)
                            <option selected>{{$edit_ink->type}}</option>
                            <option disabled>Select other type</option>
                            @elseif($isDelete)
                            <option selected>{{$delete_ink->type}}</option>
                            @else
                            <option value="" selected>Select type</option>
                            @endif
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
                            <select name="color" id="color" class="select" data-other-target="color_other" {{$isDelete ? 'disabled':'required'}}>
                                @if($isEdit)
                                <option selected>{{$edit_ink->color}}</option>
                                <option disabled>Select other color</option>
                                @elseif($isDelete)
                                <option selected>{{$delete_ink->color}}</option>
                                @else
                                <option value="" selected>Select color</option>
                                @endif
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
                            @if($isEdit)
                            value="{{$edit_ink->stock}}"
                            disabled
                            @elseif($isDelete)
                            value="{{$delete_ink->stock}}"
                            disabled
                            @else
                            placeholder="Ink Stock" min="0"
                            name="ink_stock"
                            required
                            @endif>
                    </div>
                    <div class="form-group">
                        <label>Reorder level</label>
                        <input type="number" name="reorder_level" class="input" placeholder="Reoder Level" min="1"
                            value="{{$isEdit ? $edit_ink->reorder_level : ($isDelete ? $delete_ink->reorder_level : '')}}"
                            {{$isDelete? 'disabled' : 'required'}}>
                    </div>

                </div>

                <div class="step-actions">
                    <button type="submit" class="btn {{$isDelete ? 'btn-danger': 'btn-primary'}}">
                        @if($isEdit)
                        Update Ink
                        @elseif($isDelete)
                        Delete Ink
                        @else
                        Add Ink
                        @endif
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