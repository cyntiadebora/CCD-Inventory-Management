@extends('layouts.main')

@section('title', 'Create Cabin Crew')

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
            <h4 class="mb-0">Create Cabin Crew</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

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

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-3 text-center">
                        <img id="preview" src="https://via.placeholder.com/150" alt="Preview" width="150" height="150" class="rounded-circle mb-3">
                        <input type="file" name="photo" accept="image/*" class="form-control form-control-sm" onchange="previewImage(event)">
                        <small class="text-muted">Upload photo (optional)</small>
                    </div>

                    <div class="col-md-9">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="id_number" class="form-label">ID Number</label>
                            <input type="text" class="form-control" id="id_number" name="id_number" value="{{ old('id_number') }}">
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <input type="text" class="form-control" id="role" name="role" value="{{ old('role', 'Cabin Crew') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender" onchange="filterItemsByGender()" required>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="base" class="form-label">Base</label>
                            <input type="text" class="form-control" id="base" name="base" value="{{ old('base') }}">
                        </div>

                        <div class="mb-3">
                            <label for="join_date" class="form-label">Join Date</label>
                            <input type="date" class="form-control" id="join_date" name="join_date" value="{{ old('join_date') }}">
                        </div>

                        <div class="mb-3">
                            <label for="rank" class="form-label">Rank</label>
                            <input type="text" class="form-control" id="rank" name="rank" value="{{ old('rank') }}">
                        </div>

                        <div class="mb-3">
                            <label for="batch" class="form-label">Batch</label>
                            <input type="text" class="form-control" id="batch" name="batch" value="{{ old('batch') }}">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{5,}" 
                                title="Password must contain uppercase, lowercase letters, numbers, and be at least 5 characters long." 
                                required>
                            <small class="form-text text-muted">
                                Must include uppercase, lowercase, numbers, and be minimum 5 characters.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                </div>

                <hr>

                <h4 class="mt-4">Cabin Crew Personal Items</h4>
                <div id="item-entries" class="mb-3">
                    <div class="item-row">
                        <select name="items[0][item_id]" class="item-select form-select w-50" onchange="updateSizeOptions(this); updateItemOptions();"
>
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

                        <select name="items[0][size_id]" class="size-select form-select w-50" style="display: none;"></select>
                    </div>
                </div>

                <button type="button" onclick="addItemRow()" class="btn btn-outline-primary mb-4">Add Item</button>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn border border-success text-success bg-transparent">Save</button>
                    <a href="{{ route('users.index') }}" class="btn border border-secondary text-secondary bg-transparent ms-2">Cancel</a>
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

            // Reset value jika value lama tidak cocok lagi
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

                // Nonaktifkan item yang sudah dipilih di tempat lain
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
        filterItemsByGender(); // Pastikan filter berdasarkan gender
        updateItemOptions();   // Hindari item ganda
    }

    // Trigger filter saat gender berubah
    document.getElementById('gender')?.addEventListener('change', function () {
        filterItemsByGender();
    });

    // Update item options saat ada perubahan
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('item-select')) {
            updateItemOptions();
        }
    });

    // Trigger awal saat halaman dimuat (opsional)
    document.addEventListener('DOMContentLoaded', function () {
        filterItemsByGender();
        updateItemOptions();
    });
</script>

@endsection
