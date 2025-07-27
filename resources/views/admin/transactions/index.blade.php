@extends('layouts.main')

@section('title', 'Stock Transactions')
@section('page-title', '📦 Stock Reports')

@section('content')
<style>
  .table, .table th, .table td {
    color: black !important;
    background-color: white !important;
  }

  .table th {
    text-align: center;
  }

  .filter-select {
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.5;
  }

  .filter-form .form-label {
    font-weight: 600;
    font-size: 14px;
  }

  @media (min-width: 768px) {
    .filter-form {
      justify-content: end;
    }
  }

  .form-select {
    padding-right: 2.5rem !important;
    background-position: right 0.75rem center;
    font-size: 14px;
  }

  .form-select option {
    font-size: 14px;
  }

  .btn-pill {
    font-weight: bold;
    padding: 4px 12px;
    border-radius: 20px;
    transition: all 0.2s ease-in-out;
    font-size: 0.85rem;
  }

  .btn-pill:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  }
</style>


<div class="container">
  <h2 class="mb-4">
    @if ($type === 'in')
      Stock In List
    @elseif ($type === 'out')
      Stock Out List
    @else
      All Stock Transactions
    @endif
  </h2>

  <div class="row align-items-end mb-4">
    <div class="col-md-auto mb-2">
      @php
        $currentMonth = request('month');
        $currentYear = request('year');
      @endphp

      <a href="{{ route('admin.opening-stock.index') }}" 
         class="btn btn-outline-primary me-2 fw-bold{{ request()->routeIs('admin.opening-stock.index') ? 'active' : '' }}">
        <i class="fas fa-warehouse"></i> Opening Stock
      </a>

      <a href="{{ route('admin.items.index') }}" 
         class="btn btn-outline-dark me-2 fw-bold{{ request()->is('admin.items.index') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i> Current Stock
      </a>

      <div class="btn-group" role="group">
        <a href="{{ route('admin.transactions.index', ['type' => 'all', 'month' => $currentMonth, 'year' => $currentYear]) }}"
           class="btn btn-outline-secondary fw-bold {{ ($type ?? 'all') === 'all' ? 'active' : '' }}">
          <i class="fas fa-lis"></i> All
        </a>
        <a href="{{ route('admin.transactions.index', ['type' => 'in', 'month' => $currentMonth, 'year' => $currentYear]) }}"
           class="btn btn-outline-success fw-bold {{ $type === 'in' ? 'active' : '' }}">
          <i class="fas fa-arrow-down"></i> Stock In
        </a>
        <a href="{{ route('admin.transactions.index', ['type' => 'out', 'month' => $currentMonth, 'year' => $currentYear]) }}"
           class="btn btn-outline-danger fw-bold {{ $type === 'out' ? 'active' : '' }}">
          <i class="fas fa-arrow-up"></i> Stock Out
        </a>
      </div>

      @if ($type === 'in')
      <div class="ms-3 mt-2 mt-md-0 d-inline-block">
    <a href="{{ route('admin.stock-in.create') }}" class="btn btn-dark fw-bold">
        <i class="fas fa-plus"></i> Add Stock In
    </a>
</div>


      @endif
    </div>

    <div class="col filter-form">
      <form method="GET" id="filterForm" class="row row-cols-md-auto g-2 justify-content-end">
        <input type="hidden" name="type" value="{{ $type }}">

        <div class="col">
          <label for="month" class="form-label">Month</label>
          <select name="month" id="month" class="form-select filter-select"
                  onchange="document.getElementById('filterForm').submit()">
            <option value="">All</option>
            @foreach(range(1, 12) as $m)
              <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col">
          <label for="year" class="form-label">Year</label>
          <select name="year" id="year" class="form-select filter-select"
                  onchange="document.getElementById('filterForm').submit()">
            @php $currentYear = now()->year; @endphp
            @for ($y = $currentYear; $y >= $currentYear - 5; $y--)
              <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
          </select>
        </div>
      </form>
    </div>
  </div>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th class="text-center">No</th>
        <th>Item</th>
        <th class="text-center">Size</th>
        <th class="text-center">Qty</th>
        <th class="text-center">Transaction Type</th>
        <th>Date</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      @forelse($transactions as $transaction)
        <tr>
          <td class="text-center">{{ $loop->iteration }}</td>
          <td>{{ $transaction->itemVariant->item->name }}</td>
          <td class="text-center">{{ $transaction->itemVariant->size?->size_label ?? '-' }}</td>
          <td class="text-center">{{ $transaction->quantity }}</td>
          <td class="text-center">
            @if($transaction->transaction_type === 'in')
              <span class="btn btn-sm btn-pill btn-outline-success">
                <i class="fas fa-arrow-down"></i> IN
              </span>
            @else
              <span class="btn btn-sm btn-pill btn-outline-danger">
                <i class="fas fa-arrow-up"></i> OUT
              </span>
            @endif
          </td>
         <td class="text-center">{{ $transaction->transaction_date }}</td>
        <td class="text-center">
          @if ($transaction->transaction_type === 'in')
            {{ $transaction->description ?? '-' }}
          @elseif ($transaction->transaction_type === 'out')
            Approved by Inventory Staff
          @endif
        </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="text-center">No transaction data available.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
