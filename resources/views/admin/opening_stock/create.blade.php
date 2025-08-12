@extends('layouts.main')

@section('title', 'Input Opening Stock')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">📦 Input Opening Stock</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.opening-stock.store') }}">
        @csrf

        <div class="mb-3">
            <label for="item_id" class="form-label">Select Item</label>
            <select name="item_id" id="item_id" class="form-select" required>
                <option value="">-- Select Item --</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="item_variant_id" class="form-label">Select Size</label>
            <select name="item_variant_id" id="item_variant_id" class="form-select" required>
                <option value="">-- Select Size --</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="log_date" class="form-label">Log Date (Beginning of Month)</label>
            <input type="date" name="log_date" id="log_date" class="form-control" value="{{ now()->startOfMonth()->toDateString() }}" required>
        </div>

        <div class="mb-3">
            <label for="opening_stock" class="form-label">Opening Stock Quantity</label>
            <input type="number" name="opening_stock" id="opening_stock" class="form-control" min="0" required>
        </div>

       <button type="submit" class="btn btn-success text-white fw-bold" style="background-color: #28a745; border-color: #28a745;">
  Save Opening Stock
</button>


    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const itemDropdown = document.getElementById('item_id');
    const sizeDropdown = document.getElementById('item_variant_id');

    if (itemDropdown) {
        itemDropdown.addEventListener('change', function () {
            const itemId = this.value;
            console.log("Item changed to: ", itemId);

            if (!itemId) return;

            fetch(`/admin/items/${itemId}/sizes`)
                .then(response => response.json())
                .then(data => {
                    console.log("Response data:", data);
                    sizeDropdown.innerHTML = '<option value="">-- Select Size --</option>';

                    if (data.length === 0) {
                        const option = document.createElement('option');
                        option.text = 'No sizes available';
                        sizeDropdown.add(option);
                        return;
                    }

                    data.forEach(variant => {
                        const option = document.createElement('option');
                        option.value = variant.id;
                        option.text = variant.size?.size_label || 'No Size';
                        sizeDropdown.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching sizes:', error);
                });
        });
    }
});
</script>
@endpush
