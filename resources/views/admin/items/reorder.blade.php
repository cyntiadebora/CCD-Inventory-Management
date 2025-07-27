@extends('layouts.main')
@section('page-title', 'All Items that Require Reordering')

@section('content')
<div class="container-fluid py-4">
  <div class="card shadow-sm border-0">
    <div class="card-header bg-white" style="border-left: 4px solid #ff4d4f; border-bottom: 4px solid #ff4d4f;">
    <h5 class="mb-0 text-black fw-bold">Inventory Reorder List</h5>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover mb-0">
        <thead style="background-color: #f8f9fa;">
          <tr>
            <th style="color: #000; text-align: center;">No</th>
            <th style="color: #000; text-align: center;">Name</th>
            <th style="color: #000; text-align: center;">Size</th>
            <th style="color: #000; text-align: center;">Min Stock</th>
            <th style="color: #000; text-align: center;">Current Stock</th>
          </tr>
        </thead>
        <tbody>
          @php $no = 1; @endphp
          @forelse ($reorderItems as $item)
            @foreach($item->variants as $variant)
              @if ($variant->current_stock <= $variant->min_stock)
                <tr>
                  <td style="color: #000; text-align: center;">{{ $no++ }}</td>
                  <td style="color: #000; text-align: center;">{{ $item->name }}</td>
                  <td style="color: #000; text-align: center;">
                    @if ($item->has_size)
                      {{ $variant->size->size_label ?? '-' }}
                    @else
                      No Size
                    @endif
                  </td>
                  <td style="color: #000; text-align: center;">{{ $variant->min_stock }}</td>
                  <td style="color: #000; text-align: center;">{{ $variant->current_stock }}</td>
                </tr>
              @endif
            @endforeach
          @empty
            <tr>
              <td colspan="5" class="text-center" style="color: #000;">No items need re-order.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
