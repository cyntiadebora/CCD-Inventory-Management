@extends('layouts.main')

@section('title', 'Opening Stock List')
@section('page-title', 'Opening Stock')

@section('content')
<style>
  .table th, .table td {
    color: black !important;
    vertical-align: middle;
  }

  .table thead {
    background-color: #f8f9fa;
  }

  .table tbody tr:hover {
    background-color: #f1f1f1;
  }

  .table th {
    font-weight: 600;
    font-size: 15px;
  }

  .table td {
    font-size: 14px;
  }

  .table {
    border: 1px solid #dee2e6;
  }
</style>

<div class="container">
 <div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0">📦 Opening Stock Records</h2>

  <div class="d-flex gap-2">
    <div class="btn-group me-2">
        <a href="{{ route('admin.transactions.index', ['type' => 'all']) }}" class="btn btn-outline-secondary btn-sm fw-bold">
            <i class="fas fa-list"></i> All Transactions
        </a>
        <a href="{{ route('admin.transactions.index', ['type' => 'in']) }}" class="btn btn-outline-success btn-sm fw-bold">
            <i class="fas fa-arrow-down"></i> Stock In
        </a>
        <a href="{{ route('admin.transactions.index', ['type' => 'out']) }}" class="btn btn-outline-danger btn-sm fw-bold">
            <i class="fas fa-arrow-up"></i> Stock Out
        </a>
    </div>

    <a href="{{ route('admin.items.index') }}" class="btn btn-outline-primary btn-sm fw-bold">
        <i class="fas fa-chart-bar"></i> Current Stock
    </a>

    <form id="bulkDeleteForm" action="{{ route('admin.opening-stock.bulk-delete') }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="button" class="btn btn-danger btn-sm fw-bold" id="deleteSelectedBtn" disabled>
            <i class="fas fa-trash"></i> Delete Selected
        </button>
    </form>
</div>

</div>


  <form id="bulkDeleteCheckboxForm">
    <div class="table-responsive">
      <table class="table table-bordered table-striped shadow-sm">
        <thead>
          <tr class="text-center">
            <th><input type="checkbox" id="selectAll"></th>
            <th>Date</th>
            <th>Item Code</th>
            <th>Variant Code</th>
            <th>Item Name</th>
            <th>Opening Stock</th>
          </tr>
        </thead>
        <tbody>
          @forelse($stockLogs as $log)
            <tr class="text-center">
              <td>
                <input type="checkbox" name="ids[]" form="bulkDeleteForm" value="{{ $log->id }}" class="selectBox">
              </td>
              <td>{{ \Carbon\Carbon::parse($log->log_date)->format('d M Y') }}</td>
              <td>{{ $log->itemVariant->item->code ?? '-' }}</td>
              <td>{{ $log->itemVariant->variant_code ?? '-' }}</td>
              <td class="text-start">{{ $log->itemVariant->item->name ?? '-' }}</td>
              <td>{{ $log->opening_stock }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted">No opening stock data available.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </form>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Checkbox Pilih Semua
  document.getElementById('selectAll').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('.selectBox');
    checkboxes.forEach(cb => cb.checked = this.checked);
    toggleDeleteButton();
  });

  // Aktifkan tombol delete jika ada yang dipilih
  const checkboxes = document.querySelectorAll('.selectBox');
  checkboxes.forEach(cb => cb.addEventListener('change', toggleDeleteButton));

  function toggleDeleteButton() {
    const anyChecked = document.querySelectorAll('.selectBox:checked').length > 0;
    document.getElementById('deleteSelectedBtn').disabled = !anyChecked;
  }

  // SweetAlert2 konfirmasi
  document.getElementById('deleteSelectedBtn').addEventListener('click', function () {
    Swal.fire({
      title: 'Are you sure?',
      text: "Selected opening stock records will be deleted!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Yes, delete!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('bulkDeleteForm').submit();
      }
    });
  });
</script>
@endsection
