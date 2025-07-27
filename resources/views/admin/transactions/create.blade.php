@extends('layouts.main')

@section('title', 'Stock In')
@section('page-title', 'Stock In')

@section('content')
<style>
    label {
        font-weight: bold;
    }
</style>

<div class="container">
    <h2 class="mb-4">Add New Item</h2>

    <form action="{{ route('admin.stock-in.store') }}" method="POST">
        @csrf

        {{-- Select Item --}}
        <div class="mb-3">
            <label for="item_id">Item</label>
            <select id="item_id" name="item_id" class="form-control" required>
                <option value="">-- Select Item --</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" data-has-size="{{ $item->has_size ? '1' : '0' }}">
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Select Size (shown only if has_size) --}}
        <div class="mb-3" id="size_section" style="display: none;">
            <label for="size_id">Size</label>
            <select id="size_id" name="size_id" class="form-control">
                <option value="">-- Select Size --</option>
                   @foreach($sizes as $size)
                    <option value="{{ $size->id }}">
                        {{ $size->category }} - {{ $size->size_label }}
                    </option>
                @endforeach

            </select>
        </div>

        {{-- Quantity --}}
        <div class="mb-3">
            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" class="form-control" required min="1">
        </div>

        {{-- Transaction Date --}}
        <div class="mb-3">
            <label for="transaction_date">Transaction Date</label>
            <input type="datetime-local" id="transaction_date" name="transaction_date" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label for="description">Description (optional)</label>
            <input type="text" id="description" name="description" class="form-control">
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn btn-success">Submit Stock In</button>
    </form>
</div>

{{-- Script to show/hide size selector --}}
<script>
    const itemSelect = document.getElementById('item_id');
    const sizeSection = document.getElementById('size_section');

    itemSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const hasSize = selectedOption.getAttribute('data-has-size');
        sizeSection.style.display = hasSize === '1' ? 'block' : 'none';
    });
     @if ($errors->any())
        // Ambil error pertama yang relevan (misal dari controller Laravel)
        Swal.fire({
            icon: 'warning',
            title: 'Stock Failed to be Added',
            text: "{{ $errors->first('quantity') }}",
        });
    @endif
</script>
@endsection
