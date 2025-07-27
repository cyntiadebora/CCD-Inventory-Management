@extends('layouts.main')

@section('title', 'Request Form')
@section('page-title', 'Request Item')

@section('content')
<style>
  .btn-pill {
      font-weight: bold;
      padding: 4px 10px;
      border-radius: 20px;
      transition: all 0.2s ease-in-out;
      font-size: 0.85rem;
  }
  .btn-pill:hover {
      transform: scale(1.05);
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  }
</style>
<div class="container mt-4" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <h2>Create Request</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('requests.store') }}" id="requestForm" enctype="multipart/form-data">

        @csrf

        <div class="mb-3">
            <label for="type" class="form-label">Request Type</label>
            <select name="type" id="type" class="form-control" required>
                @foreach ($types as $type)
                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description (Optional)</label>
            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
            <div id="proofImageContainer" class="mb-3" style="display: none;">
    <label for="proof_image" class="form-label">Upload Proof Image (only for Approval)</label>
    <input type="file" name="proof_image" id="proof_image" class="form-control" accept="image/*">
</div>

        </div>

        {{-- Item selector --}}
        <div class="mb-3">
            <label class="form-label">Items</label>
            @error('items')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            @foreach ($items as $item)
                <div class="form-check d-flex align-items-center mb-2">
                    <input class="form-check-input"
                        type="checkbox"
                        id="item{{ $item->id }}"
                        name="items[{{ $item->id }}][selected]"
                        value="1">

                    <label class="form-check-label ms-2 flex-grow-1"
                        for="item{{ $item->id }}"
                        style="max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $item->name }}
                    </label>

                    <input type="number"
                        name="items[{{ $item->id }}][quantity]"
                        placeholder="Qty"
                        min="1"
                        max="2"
                        class="form-control w-auto ms-3 quantity-input"
                        style="max-width: 80px;"
                        value="1">

                    {{-- Stock info --}}
                    @php
                        $userSize = $userSizes[$item->id] ?? null;
                        $variant = null;

                        if ($item->has_size && $userSize) {
                            $variant = $item->variants->filter(function($v) use ($userSize) {
                                return optional($v->size)->size_label === optional($userSize)->size_label;
                            })->first();
                        } elseif (!$item->has_size) {
                            $variant = $item->variants->firstWhere('size_id', null);
                        }
                    @endphp

                    @if ($variant)
  <span class="btn btn-sm btn-pill btn-outline-secondary ms-2">
      📦 Stock: {{ $variant->current_stock }}
      @if($variant->size)
          (Size {{ $variant->size->size_label }})
      @endif
  </span>
@else
  <span class="btn btn-sm btn-pill btn-outline-danger ms-2">
      ❗ Stock not found
  </span>
@endif

                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-dark">Submit Request</button>
    </form>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Batas maksimum 2 qty
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        input.addEventListener('input', function () {
            if (this.value > 2) {
                this.value = 2;
                Swal.fire({
                    icon: 'info',
                    title: 'Maximum quantity is 2',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
            if (this.value < 1) {
                this.value = 1;
            }
        });
    });

    // Validasi checkbox sebelum submit
    const form = document.getElementById('requestForm');
    form.addEventListener('submit', function (e) {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="items"][name$="[selected]"]');
        let atLeastOneChecked = Array.from(checkboxes).some(cb => cb.checked);

        if (!atLeastOneChecked) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'No item selected',
                text: 'Please select at least one item before submitting!',
                confirmButtonText: 'OK',
                confirmButtonColor: '#00c851' 
            });
        }
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('type');
    const proofContainer = document.getElementById('proofImageContainer');

    function toggleProofImage() {
        proofContainer.style.display = (typeSelect.value === 'approval') ? 'block' : 'none';
    }

    typeSelect.addEventListener('change', toggleProofImage);
    toggleProofImage(); // Trigger on load
});
</script>
@endsection
