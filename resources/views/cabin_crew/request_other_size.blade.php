@extends('layouts.main')

@section('title', 'Request a Different Item Size')

@section('content')
<div class="container mt-4" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <h2>Request a Different Item Size</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    <form method="POST" action="{{ route('requests.storeOtherSize') }}" id="requestOtherSizeForm" enctype="multipart/form-data">
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
            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Explain why you need a different size..."></textarea>
        </div>

        <div class="mb-3" id="proofImageContainer" style="display: none;">
            <label for="proof_image" class="form-label">Upload Proof Image (only for Approval)</label>
            <input type="file" name="proof_image" id="proof_image" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label class="form-label">Items</label>
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

                    {{-- Jika item memiliki size, tampilkan pilihan size --}}
                    @if($item->has_size)
                      <select name="items[{{ $item->id }}][size_id]"
    class="form-select ms-3 item-size-dropdown"
    style="width: 150px;" 
    data-checkbox-id="item{{ $item->id }}">


                            <option value="">Choose size</option>
                            @foreach($item->variants as $variant)
                                @if($variant->size)
                                    <option value="{{ $variant->size->id }}">
                                        {{ $variant->size->size_label }} - Stock: {{ $variant->current_stock }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    @endif

                    <input type="number"
                    name="items[{{ $item->id }}][quantity]"
                    placeholder="Qty"
                    min="1"
                    max="2"
                    class="form-control w-auto ms-3 quantity-input"
                    style="max-width: 80px;"
                    value="1"
                    data-checkbox-id="item{{ $item->id }}">
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-dark">Submit Request</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
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

    const form = document.getElementById('requestOtherSizeForm');
    form.addEventListener('submit', function (e) {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="items"][name$="[selected]"]');
        let atLeastOneChecked = Array.from(checkboxes).some(cb => cb.checked);
        if (!atLeastOneChecked) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'No item selected',
                text: 'Please select at least one item!',
                confirmButtonText: 'OK'
            });
        }
    });

    const typeSelect = document.getElementById('type');
    const proofContainer = document.getElementById('proofImageContainer');

    function toggleProofImage() {
        proofContainer.style.display = (typeSelect.value === 'approval') ? 'block' : 'none';
    }

    typeSelect.addEventListener('change', toggleProofImage);
    toggleProofImage();
});
</script>
@endsection
