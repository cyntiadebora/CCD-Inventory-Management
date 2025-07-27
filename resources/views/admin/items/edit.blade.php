@extends('layouts.main')

@section('content')
<div class="container py-4">
  <h3>Edit Item</h3>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label">Item Name</label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Item Type</label>
      <select name="type" class="form-control" required>
        <option value="">-- Select Type --</option>
        <option value="Male" {{ old('type', $item->type) == 'Male' ? 'selected' : '' }}>Male</option>
        <option value="Female" {{ old('type', $item->type) == 'Female' ? 'selected' : '' }}>Female</option>
        <option value="All" {{ old('type', $item->type) == 'All' ? 'selected' : '' }}>All</option>
      </select>
    </div>

    <div id="code_field" class="mb-3" style="display: {{ old('has_size', $item->has_size) ? 'none' : 'block' }};">
      <label class="form-label">Item Code</label>
      <input type="text" name="code" class="form-control" value="{{ old('code', $item->code) }}">
    </div>

    <div class="form-check mb-1">
    <input type="hidden" name="has_size" value="{{ $item->has_size }}">
    <input type="checkbox" id="has_size" class="form-check-input"
          {{ old('has_size', $item->has_size) ? 'checked' : '' }} disabled>
    <label class="form-check-label" for="has_size">Has Size</label>
  </div>
    @if (!$item->has_size)
      <div class="text-muted small mb-3">* Item ini tidak memiliki size dan tidak bisa diubah menjadi item dengan size.</div>
    @endif

    <div id="size_fields" style="display: {{ old('has_size', $item->has_size) ? 'block' : 'none' }};">
      <div class="mb-3">
        <label class="form-label">Category</label>
        <select id="category" name="category" class="form-select" disabled>
          <option value="">-- Select Category --</option>
          @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ old('category', $item->category) == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
          @endforeach
        </select>
        <input type="hidden" name="category" value="{{ old('category', $item->category) }}">
      </div>
      <div class="mb-3">
        <label class="form-label">Variants</label>
        <div id="variant-container">
          @php
            $variants = old('variants', collect($item->variants)->map(function($v){
              return [
                'size' => $v->size->size_label ?? '',
                'variant_code' => $v->variant_code,
                'min_stock' => $v->min_stock,
                'max_stock' => $v->max_stock
              ];
            })->toArray());
            $selectedCategory = old('category', $item->category);
          @endphp

          @foreach ($variants as $idx => $var)
            <div class="row mb-2 variant-row">
              @if (isset($item->variants[$idx]))
                <input type="hidden" name="variants[{{ $idx }}][id]" value="{{ $item->variants[$idx]->id }}">
              @endif

              <input type="hidden" name="variants[{{ $idx }}][size]" value="{{ $var['size'] }}">
              <div class="col-md-2">
                <select class="form-select variant-size" disabled>
                  <option value="">-- Select Size --</option>
                  @foreach ($sizeByCategory[$selectedCategory] ?? [] as $size)
                    <option value="{{ $size->size_label }}" {{ $var['size'] == $size->size_label ? 'selected' : '' }}>
                      {{ $size->size_label }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-2">
                <input type="text" name="variants[{{ $idx }}][variant_code]" class="form-control"
                      value="{{ $var['variant_code'] }}" placeholder="Variant Code">
              </div>

              <div class="col-md-2">
                <input type="number" name="variants[{{ $idx }}][min_stock]" class="form-control"
                      value="{{ old("variants.$idx.min_stock", $var['min_stock']) }}" placeholder="Min Stock">
              </div>

              <div class="col-md-2">
                <input type="number" name="variants[{{ $idx }}][max_stock]" class="form-control"
                      value="{{ old("variants.$idx.max_stock", $var['max_stock']) }}" placeholder="Max Stock">
              </div>

              <div class="col-auto">
                <button type="button" class="btn btn-danger btn-sm remove-variant">✕</button>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Preview gambar lama --}}
    @if ($item->photo)
      <div class="mb-3">
        <label class="form-label">Current Image</label><br>
        <img src="{{ asset('images/' . $item->photo) }}" alt="Current Image" style="max-width: 150px;">
      </div>
    @endif

    <div class="mb-3">
      <label class="form-label">Image</label>
      <input type="file" name="photo" class="form-control">
    </div>

   @php
  $variant = $item->variants->first();
@endphp

@if (!$item->has_size)
  <hr>
  <h5>Stock Settings</h5>
  <div id="stock_fields">
    <div class="mb-3">
      <label class="form-label">Minimum Stock</label>
      <input type="number" name="min_stock" id="min_stock" class="form-control"
             value="{{ old('min_stock', $variant->min_stock ?? '') }}" placeholder="Minimum Stock">
    </div>
    <div class="mb-3">
      <label class="form-label">Maximum Stock</label>
      <input type="number" name="max_stock" id="max_stock" class="form-control"
             value="{{ old('max_stock', $variant->max_stock ?? '') }}" placeholder="Maximum Stock">
    </div>
  </div>
    @else
      
    @endif

    <div class="mb-3">
      <button type="submit" class="btn btn-outline-success text-dark">Update Item</button>
    </div>
  </form>
</div>

<script>
  const hasSize = document.getElementById('has_size');
  const codeField = document.getElementById('code_field');
  const sizeFields = document.getElementById('size_fields');
  const category = document.getElementById('category');
  const container = document.getElementById('variant-container');
  const minStockInput = document.getElementById('min_stock');
  const maxStockInput = document.getElementById('max_stock');
  const sizeLabels = @json($sizeByCategory->mapWithKeys(fn($group, $cat) => [$cat => $group->pluck('size_label')]));

  function toggleFields() {
    const isChecked = hasSize.checked;
    sizeFields.style.display = isChecked ? 'block' : 'none';
    codeField.style.display = isChecked ? 'none' : 'block';
    if (minStockInput && maxStockInput) {
      minStockInput.readOnly = isChecked;
      maxStockInput.readOnly = isChecked;
    }
  }

  function populate(select, categoryVal, selected = '') {
    select.innerHTML = '<option value="">-- Select Size --</option>';
    (sizeLabels[categoryVal] || []).forEach(s => {
      const option = new Option(s, s, s === selected, s === selected);
      select.append(option);
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    toggleFields();
    if (!hasSize.disabled) {
      hasSize.addEventListener('change', toggleFields);
    }

    category.addEventListener('change', () => {
      container.querySelectorAll('.variant-size').forEach(select => {
        populate(select, category.value, select.value);
      });
    });

    container.addEventListener('click', e => {
      if (e.target.matches('.remove-variant')) {
        e.target.closest('.variant-row').remove();
      }
    });

    container.querySelectorAll('.variant-size').forEach(select => {
      populate(select, category.value, select.value);
    });
  });
</script>
@endsection
