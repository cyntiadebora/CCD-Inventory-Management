@extends('layouts.main')

@php
  $sizeLabelsByType = $sizeByCategory->mapWithKeys(function($group, $key) {
      return [$key => $group->pluck('size_label')];
  });

  $oldCategory = old('category');
  $oldVariants = old('variants', []);
@endphp

<script>
  const sizeLabelsByType = {!! json_encode($sizeLabelsByType) !!};
</script>

@section('content')
<div class="container py-4">
  <h3>Create New Item</h3>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
      <label for="name" class="form-label">Item Name</label>
      <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>
    

    <div class="mb-3">
      <label for="type" class="form-label">Item Type</label>
      <select name="type" class="form-control" required>
        <option value="">-- Select Type --</option>
        <option value="Male" {{ old('type') == 'Male' ? 'selected' : '' }}>Male</option>
        <option value="Female" {{ old('type') == 'Female' ? 'selected' : '' }}>Female</option>
        <option value="All" {{ old('type') == 'All' ? 'selected' : '' }}>All</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="code" class="form-label">Item Code</label>
      <input type="text" id="code_input" name="code" class="form-control" value="{{ old('code') }}" placeholder="Enter Item Code">
    </div>

    <div class="form-check mb-3">
      <input type="hidden" name="has_size" value="0">
      <input class="form-check-input" type="checkbox" id="has_size" name="has_size" value="1" {{ old('has_size') ? 'checked' : '' }}>
      <label class="form-check-label" for="has_size">Has Size</label>
    </div>

    <div id="size_fields" style="display: none;">
      <div class="mb-3">
        <label for="category" class="form-label">Size Category</label>
        <select class="form-select" name="category" id="category">
          <option value="">-- Select Category --</option>
          @foreach ($categories as $category)
            <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
              {{ ucfirst($category) }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Variants (Size Only)</label>
        <div id="variant-container">
          @if (count($oldVariants) > 0)
            @foreach ($oldVariants as $index => $variant)
              <div class="row mb-2 variant-row">
                <div class="col-md-4">
                  <select name="variants[{{ $index }}][size]" class="form-select variant-size">
                    <option value="">-- Select Size --</option>
                    @foreach (($sizeLabelsByType[$oldCategory] ?? []) as $label)
                      <option value="{{ $label }}" {{ (isset($variant['size']) && $variant['size'] == $label) ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4">
                  <input type="text" name="variants[{{ $index }}][variant_code]" class="form-control" placeholder="Variant Code" value="{{ $variant['variant_code'] ?? '' }}">
                </div>
                <div class="col-auto">
                  <button type="button" class="btn btn-danger btn-sm remove-variant">✕</button>
                </div>
              </div>
            @endforeach
          @else
            <div class="row mb-2 variant-row">
  <div class="col-md-2">
    <select name="variants[0][size]" class="form-select variant-size">
      <option value="">-- Select Size --</option>
    </select>
  </div>
  <div class="col-md-2">
    <input type="text" name="variants[0][variant_code]" class="form-control" placeholder="Variant Code">
  </div>
  <div class="col-md-2">
    <input type="number" name="variants[0][min_stock]" class="form-control" placeholder="Min Stock" min="0">
  </div>
  <div class="col-md-2">
    <input type="number" name="variants[0][max_stock]" class="form-control" placeholder="Max Stock" min="0">
  </div>
  <div class="col-auto">
    <button type="button" class="btn btn-danger btn-sm remove-variant">✕</button>
  </div>
</div>

          @endif
        </div>
        <button type="button" id="add-variant" class="btn btn-secondary btn-sm">+ Add Variant</button>
      </div>
    </div>

    <div class="mb-3">
      <label for="photo" class="form-label">Image</label>
      <input type="file" name="photo" class="form-control">
    </div>

    <div id="stock_thresholds" style="display: none;">
  <hr>
  <h5>Stock Settings (Thresholds)</h5>
  <div class="mb-3">
    <label for="min_stock">Minimum Stock</label>
    <input type="number" name="min_stock" class="form-control" value="{{ old('min_stock', 0) }}" min="0">
  </div>
  <div class="mb-3">
    <label for="max_stock">Maximum Stock</label>
    <input type="number" name="max_stock" class="form-control" value="{{ old('max_stock', 0) }}" min="0">
  </div>
</div>


    <button type="submit" class="btn btn-outline-success">Save Item</button>
  </form>
</div>

<script>
  const hasSizeCheckbox = document.getElementById('has_size');
  const sizeFields = document.getElementById('size_fields');
  const variantContainer = document.getElementById('variant-container');
  const addVariantBtn = document.getElementById('add-variant');
  const categorySelect = document.getElementById('category');

 function toggleSizeFields() {
  const showSizeFields = hasSizeCheckbox.checked;
  sizeFields.style.display = showSizeFields ? 'block' : 'none';
  document.getElementById('stock_thresholds').style.display = showSizeFields ? 'none' : 'block';
}



  function populateSizeOptions(selectElement, category, selectedValue = '') {
    selectElement.innerHTML = '<option value="">-- Select Size --</option>';
    if (!category || !sizeLabelsByType[category]) return;

    sizeLabelsByType[category].forEach(size => {
      const option = document.createElement('option');
      option.value = size;
      option.textContent = size;
      if (size === selectedValue) {
        option.selected = true;
      }
      selectElement.appendChild(option);
    });
  }

 function addVariantRow(index = null) {
  if (index === null) {
    index = variantContainer.querySelectorAll('.variant-row').length;
  }

  const row = document.createElement('div');
  row.classList.add('row', 'mb-2', 'variant-row');
  row.innerHTML = `
    <div class="col-md-2">
      <select name="variants[${index}][size]" class="form-select variant-size">
        <option value="">-- Select Size --</option>
      </select>
    </div>
    <div class="col-md-2">
      <input type="text" name="variants[${index}][variant_code]" class="form-control" placeholder="Variant Code">
    </div>
    <div class="col-md-2">
      <input type="number" name="variants[${index}][min_stock]" class="form-control" placeholder="Min Stock" min="0">
    </div>
    <div class="col-md-2">
      <input type="number" name="variants[${index}][max_stock]" class="form-control" placeholder="Max Stock" min="0">
    </div>
    <div class="col-auto">
      <button type="button" class="btn btn-danger btn-sm remove-variant">✕</button>
    </div>
  `;
  variantContainer.appendChild(row);

  const selectedCategory = categorySelect.value;
  const selectSize = row.querySelector('select.variant-size');
  populateSizeOptions(selectSize, selectedCategory);
}


  function updateAllVariantSizes() {
    const selectedCategory = categorySelect.value;
    variantContainer.querySelectorAll('select.variant-size').forEach(select => {
      const currentVal = select.value;
      populateSizeOptions(select, selectedCategory, currentVal);
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    toggleSizeFields();

    hasSizeCheckbox.addEventListener('change', toggleSizeFields);
    categorySelect.addEventListener('change', updateAllVariantSizes);
    addVariantBtn.addEventListener('click', () => addVariantRow());

    variantContainer.addEventListener('click', function (e) {
      if (e.target.classList.contains('remove-variant')) {
        e.target.closest('.variant-row').remove();
      }
    });

    updateAllVariantSizes();
  });
</script>

@endsection
