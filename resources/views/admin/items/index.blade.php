@extends('layouts.main')

@section('page-title', 'Current Stock')

@section('content')
<style>
  .btn-nav {
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
  }

  /* Buat semua teks tabel jadi warna hitam dan center */
  .table th,
  .table td {
    color: #000 !important;
    text-align: center !important;
    vertical-align: middle !important;
  }

  .table thead th {
    background-color: #f8f9fa !important;
  }
</style>

<div class="container">
  <h2 class="mb-4"><i class="fas fa-chart-bar"></i> Current Stock</h2>

  {{-- Navigasi Tombol --}}
 <div class="mb-4">
    <a href="{{ route('admin.opening-stock.index') }}" class="btn btn-outline-primary btn-nav fw-bold">
        <i class="fas fa-warehouse"></i> Opening Stock
    </a>
    <a href="{{ route('admin.transactions.index', ['type' => 'all']) }}" class="btn btn-outline-secondary btn-nav fw-bold">
        <i class="fas fa-list"></i> All Transactions
    </a>
    <a href="{{ route('admin.transactions.index', ['type' => 'in']) }}" class="btn btn-outline-success btn-nav fw-bold">
        <i class="fas fa-arrow-down"></i> Stock In
    </a>
    <a href="{{ route('admin.transactions.index', ['type' => 'out']) }}" class="btn btn-outline-danger btn-nav fw-bold">
        <i class="fas fa-arrow-up"></i> Stock Out
    </a>
</div>


  {{-- Table --}}
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Item Code</th>
          <th>Variant Code</th>
          <th>Item Name</th>
          <th>Current Stock</th>
        </tr>
      </thead>
      <tbody>
        @php $no = 1; @endphp
        @forelse ($items as $item)
          @foreach ($item->variants as $variant)
            <tr>
              <td>{{ $no++ }}</td>
              <td>{{ $item->code ?? '-' }}</td>
              <td>{{ $variant->variant_code ?? '-' }}</td>
              <td>{{ $item->name ?? '-' }}</td>
              <td>{{ $variant->current_stock ?? 0 }}</td>
            </tr>
          @endforeach
        @empty
          <tr>
            <td colspan="5">No items found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
