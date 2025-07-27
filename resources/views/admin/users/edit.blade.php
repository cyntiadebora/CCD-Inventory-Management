@extends('layouts.main')

@section('title', 'Edit Cabin Crew')

@section('content')
<style>
    * {
        font-family: 'Arial', 'Helvetica', sans-serif !important;
    }
    label, input, select {
        color: #000 !important;
    }
    .item-row {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }
</style>

<div class="container mt-4">
    <div class="card">
        <div class="card-header" style="background-color: #ffffff; color: #000000; border-left: 4px solid #ff0000; border-bottom: 4px solid #ff0000;">
            <h4 class="mb-0">Edit Cabin Crew</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>There were some problems with your input:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.update', $crew->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-3 text-center">
                        <img id="preview" src="{{ $crew->photo ? asset('/images/' . $crew->photo) : 'https://via.placeholder.com/150' }}" alt="Preview" width="150" height="150" class="rounded-circle mb-3">
                        <input type="file" name="photo" accept="image/*" class="form-control form-control-sm" onchange="previewImage(event)">
                        <small class="text-muted">Change photo (optional)</small>
                    </div>

                    <div class="col-md-9">
                        <div class="mb-3">
                            <label>Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $crew->name) }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>ID Number</label>
                            <input type="text" name="id_number" value="{{ old('id_number', $crew->id_number) }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $crew->email) }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Role</label>
                            <input type="text" name="role" value="{{ old('role', $crew->role) }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select" required>
                                <option value="active" {{ old('status', $crew->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $crew->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Gender</label>
                            <select name="gender" id="gender" class="form-select" onchange="filterItemsByGender()" required>
                                <option value="male" {{ old('gender', $crew->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $crew->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Base</label>
                            <input type="text" name="base" value="{{ old('base', $crew->base) }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Join Date</label>
                            <input type="date" name="join_date" value="{{ old('join_date', $crew->join_date) }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Rank</label>
                            <input type="text" name="rank" value="{{ old('rank', $crew->rank) }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Batch</label>
                            <input type="text" name="batch" value="{{ old('batch', $crew->batch) }}" class="form-control">
                        </div>
                    </div>
                </div>

                <hr>
                <h4 class="mt-4">Cabin Crew Personal Items</h4>
                <div id="item-entries" class="mb-3">
                    @foreach($crew->userItemSizes as $index => $uis)
                        <div class="item-row">
                            <select name="items[{{ $index }}][item_id]" class="item-select form-select w-50" onchange="updateSizeOptions(this); updateItemOptions();">
                                <option value="">-- Select Item --</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}"
                                        data-has-size="{{ $item->has_size ? '1' : '0' }}"
                                        data-size-category="{{ $item->category_for_size }}"
                                        data-type="{{ $item->type }}"
                                        {{ $uis->itemVariant->item_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="items[{{ $index }}][size_id]" class="size-select form-select w-50" style="{{ $uis->itemVariant->size ? '' : 'display: none;' }}">
                                @foreach($sizes as $size)
                                    @if($size->category == $uis->itemVariant->item->category_for_size)
                                        <option value="{{ $size->id }}" {{ $uis->itemVariant->size_id == $size->id ? 'selected' : '' }}>{{ $size->size_label }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>

                <button type="button" onclick="addItemRow()" class="btn btn-outline-primary mb-4">Add Item</button>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn border border-warning text-dark fw-bold bg-transparent">
                        <i class="fas fa-save me-1"></i> Update
                    </button>

                    <a href="{{ route('users.show', $crew->id) }}" class="btn border border-secondary text-dark fw-bold bg-transparent ms-2">
                        <i class="fas fa-times-circle me-1"></i> Cancel
                    </a>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const sizes = @json($sizes);

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function getSelectedGender() {
        return document.getElementById('gender')?.value || '';
    }

    function filterItemsByGender() {
        const gender = getSelectedGender();
        const allItemSelects = document.querySelectorAll('.item-select');

        allItemSelects.forEach(select => {
            const currentValue = select.value;

            Array.from(select.options).forEach(option => {
                const type = option.getAttribute('data-type');
                if (option.value === "") {
                    option.hidden = false;
                } else if (type?.toLowerCase() === "all" || type?.toLowerCase() === gender?.toLowerCase()) {
                    option.hidden = false;
                } else {
                    option.hidden = true;
                }
            });

            if (select.querySelector(`option[value="${currentValue}"]`)?.hidden) {
                select.value = '';
            }
        });

        updateItemOptions();
    }

    function updateSizeOptions(selectEl) {
        const selectedOption = selectEl.options[selectEl.selectedIndex];
        const hasSize = selectedOption.getAttribute('data-has-size');
        const sizeCategory = selectedOption.getAttribute('data-size-category');

        const row = selectEl.closest('.item-row');
        const sizeSelect = row.querySelector('.size-select');

        if (hasSize == '1') {
            sizeSelect.style.display = 'inline-block';
            sizeSelect.innerHTML = '';

            const filteredSizes = sizes.filter(size => size.category === sizeCategory);
            filteredSizes.forEach(size => {
                const option = document.createElement('option');
                option.value = size.id;
                option.textContent = size.size_label;
                sizeSelect.appendChild(option);
            });
        } else {
            sizeSelect.style.display = 'none';
            sizeSelect.innerHTML = '';
        }
    }

    function updateItemOptions() {
        const allSelects = document.querySelectorAll('.item-select');
        const selectedValues = Array.from(allSelects)
            .map(select => select.value)
            .filter(val => val !== "");

        allSelects.forEach(select => {
            const currentValue = select.value;

            Array.from(select.options).forEach(option => {
                if (option.value === "") return;
                option.disabled = selectedValues.includes(option.value) && option.value !== currentValue;
            });
        });
    }

    function addItemRow() {
        const index = document.querySelectorAll('.item-row').length;
        const container = document.getElementById('item-entries');

        const newRow = document.createElement('div');
        newRow.classList.add('item-row');

        newRow.innerHTML = `
            <select name="items[${index}][item_id]" class="item-select form-select w-50" onchange="updateSizeOptions(this); updateItemOptions();">
                <option value="">-- Select Item --</option>
                @foreach ($items as $item)
                    <option 
                        value="{{ $item->id }}" 
                        data-has-size="{{ $item->has_size ? '1' : '0' }}" 
                        data-size-category="{{ $item->category_for_size }}"
                        data-type="{{ $item->type }}">
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>

            <select name="items[${index}][size_id]" class="size-select form-select w-50" style="display: none;"></select>
        `;

        container.appendChild(newRow);
        filterItemsByGender();
        updateItemOptions();
    }

    document.addEventListener('DOMContentLoaded', function () {
        filterItemsByGender();
        updateItemOptions();
    });
</script>
@endsection
