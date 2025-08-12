@extends('layouts.main')

@section('content')

<!-- Tambahan gaya font sans-serif -->
<style>
  body {
    font-family: sans-serif !important;
  }
 
    .table-hover tbody tr:hover {
    background-color: #ffffff !important;
  }
</style>

@php
   use Carbon\Carbon;

  // Set timezone ke Asia/Jakarta (WIB) dan locale English (default)
  $now = Carbon::now('Asia/Jakarta');
  $day = $now->format('l'); // Hari dalam bahasa Inggris, misal Monday, Tuesday, dll
@endphp

<div class="container-fluid py-4">
 {{-- Format Hari, Tanggal, dan Jam --}}
<div class="row mb-3">
  <div class="col-12">
    <div class="alert bg-white shadow-lg rounded p-3" role="alert" style="color: black;">
      <strong>{{ $day }}</strong>, <span id="realtime-clock"></span> WIB
    </div>
  </div>
</div>


  <div class="row">

    {{-- Card 1: Crew Active --}}
    <div class="col-lg-3 col-md-6 col-12 mb-4">
      <a href="{{ route('admin.users.active') }}" style="text-decoration:none;">
        <div class="card border-merah-cerah" style="cursor:pointer;">
          <div class="card-body p-3 position-relative">
            <div class="row">
              <div class="col-8 text-start">
                <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                  <i class="fas fa-users text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
                </div>
                <h5 class="text-black font-weight-bolder mb-0 mt-3">{{ $activeCrewCount }}</h5>
                <span class="text-black text-sm">Crew Active</span>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    {{-- Card 2: All Request --}}
    <div class="col-lg-3 col-md-6 col-12 mb-4">
      <a href="{{ route('admin.requests.index') }}" style="text-decoration: none;">
        <div class="card border-merah-cerah">
          <div class="card-body p-3 position-relative">
            <div class="row">
              <div class="col-8 text-start">
                <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                  <i class="fas fa-inbox text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
                </div>
                <h5 class="text-black font-weight-bolder mb-0 mt-3">{{ $totalRequests }}</h5>
                <span class="text-black text-sm">All Request</span>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    {{-- Card 3: Re-Order --}}
    <div class="col-lg-3 col-md-6 col-12 mb-4">
    <a href="{{ route('admin.items.reorder') }}" style="text-decoration: none;">
      <div class="card border-merah-cerah" style="cursor: pointer;">
        <div class="card-body p-3 position-relative">
          <div class="row">
            <div class="col-8 text-start">
              <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                <i class="fas fa-redo text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
              </div>
              <h5 class="text-black font-weight-bolder mb-0 mt-3">{{ $reorderCount }}</h5>
              <span class="text-black text-sm">Re-Order</span>
            </div>
          </div>
        </div>
      </div>
      </a>
    </div>
{{-- Button Update Item --}}
<div class="col-12 mb-2 d-flex justify-content-start">
  <a href="#inventory-stock-table" class="btn fw-bold text-white" style="background-color: #ff4d4f;">
    <i class="fas fa-arrow-down"></i> Update Item
  </a>
</div>

{{-- Chart Full Width --}}
<div class="col-12 mb-4">
  <div class="card border-merah-cerah">
    <div class="card-body">
      <h5 class="text-black font-weight-bolder text-center mb-4">Stock per Item</h5>
      <div style="position: relative; height:400px;">
        <canvas id="itemPieChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Modal untuk tampilkan stock per size -->
<div class="modal fade" id="sizeModal" tabindex="-1" aria-labelledby="sizeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sizeModalLabel">Stock per Size</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="sizeDetails">
        <!-- Konten muncul dari JS -->
      </div>
    </div>
  </div>
</div>


  </div>

{{-- Tombol Add Item --}}
<div class="mb-3 d-flex justify-content-end">
  <a href="{{ route('admin.items.create') }}" class="btn btn-primary fw-bold" style="background-color: #ff4d4f; border-color: #ff4d4f;">
    <i class="fas fa-plus"></i> Add Item
  </a>
</div>

{{-- Tombol Input Opening Stock --}}
<div class="mb-4 d-flex justify-content-end">
  <a href="{{ route('admin.opening-stock.create') }}" class="btn btn-outline-success fw-bold">
    <i class="fas fa-box-open"></i> Input Opening Stock
  </a>
</div>


{{-- Table Inventory Stock Status --}}
<div class="card" id="inventory-stock-table">
  <div class="card-header"
       style="background-color: #ffffff; 
              border-left: 4px solid #ff4d4f; 
              border-bottom: 4px solid #ff4d4f; 
              border-top: none; 
              border-right: none;">
    <h5 class="mb-0" style="color: #000000; font-weight: bold;">Inventory Stock Status</h5>
  </div>

  <div class="card-body table-responsive p-0">
    <table class="table table-hover align-items-center mb-0">
      <thead style="background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-bottom: 2px solid #ff4d4d;" class="text-dark">
        <tr>
          <th class="text-center text-lg font-weight-bolder">No</th>
          <th class="text-lg font-weight-bolder">Name</th>
          <th class="text-center text-lg font-weight-bolder">Image</th>
          <th class="text-center text-lg font-weight-bolder">Min Stock/Size</th>
          <th class="text-center text-lg font-weight-bolder">Max Stock/Size</th>
          <th class="text-center text-lg font-weight-bolder">Current Stock</th>

          <th class="text-center text-lg font-weight-bolder">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($items as $item)
          <tr>
            <td class="text-sm text-dark text-center">{{ $loop->iteration }}</td>
            <td class="text-sm text-dark">{{ $item->name }}</td>
            <td class="text-sm text-dark text-center">
              @if($item->photo && file_exists(public_path('images/' . $item->photo)))
                <img src="{{ asset('images/' . $item->photo) }}" alt="{{ $item->name }}" width="60" height="60" style="object-fit: cover;">
              @else
                <span class="text-dark">No image</span>
              @endif
            </td>
{{-- Min Stock --}}
<td class="text-sm text-dark text-center">
  @if($item->variants->isEmpty())
    0
  @elseif(!$item->has_size)
    {{ $item->variants->first()->min_stock ?? '0' }}
  @else
    @foreach($item->variants as $variant)
      <div style="border: 1px solid #ddd; border-radius: 4px; padding: 4px 6px; margin-bottom: 4px; background-color: transparent;">
  <strong>{{ $variant->size->size_label ?? 'No Size' }}</strong>: {{ $variant->min_stock }}
</div>

    @endforeach
  @endif
</td>

{{-- Max Stock --}}
<td class="text-sm text-dark text-center">
  @if($item->variants->isEmpty())
    0
  @elseif(!$item->has_size)
    {{ $item->variants->first()->max_stock ?? '0' }}
  @else
    @foreach($item->variants as $variant)
      <div style="border: 1px solid #ddd; border-radius: 4px; padding: 4px 6px; margin-bottom: 4px; background-color: transparent;">
  <strong>{{ $variant->size->size_label ?? 'No Size' }}</strong>: {{ $variant->max_stock }}
</div>

    @endforeach
  @endif
</td>

{{-- Current Stock --}}
  <td class="text-sm text-dark text-center">
    @if($item->variants->isEmpty())
      0
     @elseif(!$item->has_size)
      @php
      $variant = $item->variants->first();
      $isLow = optional($variant)->current_stock <= optional($variant)->min_stock;
      $bgColor = $isLow ? '#ffcccc' : '#d4edda';
      $textColor = $isLow ? '#721c24' : '#155724';
    @endphp
  <div style="border: 1px solid #ddd; border-radius: 4px; padding: 4px 6px;
              background-color: {{ $bgColor }};
              color: {{ $textColor }};
              font-weight: bold;">
    {{ $variant->current_stock ?? 0 }}
  </div>


  @else
    @foreach($item->variants as $variant)
      @php
        $isLow = $variant->current_stock <= $variant->min_stock;
        $bgColor = $isLow ? '#ffcccc' : '#d4edda'; // merah atau hijau lembut
        $textColor = $isLow ? '#721c24' : '#155724'; // teks merah tua atau hijau tua
      @endphp
      <div style="border: 1px solid #ddd; border-radius: 4px; padding: 4px 6px; margin-bottom: 4px;
                  background-color: {{ $bgColor }};
                  color: {{ $textColor }};
                  font-weight: bold;">
        <strong>{{ $variant->size->size_label ?? 'No Size' }}</strong>: {{ $variant->current_stock }}
      </div>

    @endforeach
  @endif
</td>


            {{-- Action --}}
           <td class="text-sm text-dark text-center text-nowrap">
            <div class="d-flex justify-content-center gap-1">
              {{-- Tombol Update --}}
              <a href="{{ route('admin.items.edit', $item->id) }}"
                class="btn btn-sm btn-pill"
                style="border: 2px solid #ffc107; color: black; background-color: transparent;">
                 Update
              </a>

              {{-- Tombol Delete --}}
              <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" class="delete-form" style="display:inline">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="btn btn-sm btn-pill delete-btn"
                    style="border: 2px solid red; color: black; background-color: transparent;">
                    🗑️ Delete
            </button>
          </form>
            </div>
          </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-dark">No data available</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>


<!-- JavaScript untuk update waktu realtime -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const stockData = @json($stockPerItem);

  window.onload = function () {
    const labels = stockData.map(item => item.item_name);
    const data = stockData.map(item => item.total_stock);

    const canvas = document.getElementById('itemPieChart');
    if (!canvas) {
      console.error("Canvas #itemPieChart not found");
      return;
    }

    const ctx = canvas.getContext('2d');

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
        label: 'Total Stock',
        data: data,
        backgroundColor: 'rgba(255, 77, 77, 0.6)',
        borderRadius: 10, // ✅ Ujung bar jadi bulat
        barThickness: 30, // (opsional) agar batangnya lebih ramping/tebal sesuai keinginan
        borderSkipped: false // (opsional) biar tidak ada sisi tajam
      }]
      },
      options: {
        responsive: true,
         maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'right',
          }
        },
        onClick: (event, elements) => {
          if (elements.length > 0) {
            const index = elements[0].index;
            const item = stockData[index];
            const detailHtml = item.sizes.map(s => `<li><strong>${s.size}</strong>: ${s.stock}</li>`).join('');

            document.getElementById('sizeModalLabel').innerText = `Stock per Size for ${item.item_name}`;
            document.getElementById('sizeDetails').innerHTML = `<ul>${detailHtml}</ul>`;
            new bootstrap.Modal(document.getElementById('sizeModal')).show();
          }
        }
      }
    });
  };
</script>
<script>
  function updateClock() {
    const now = new Date();

    const tanggal = now.toLocaleDateString('id-ID', {
      day: 'numeric', month: 'long', year: 'numeric'
    });

    const jam = now.toLocaleTimeString('id-ID', {
      hour: '2-digit', minute: '2-digit', second: '2-digit'
    });

    document.getElementById('realtime-clock').textContent = `${tanggal}, ${jam}`;
  }

  setInterval(updateClock, 1000);
  updateClock(); // panggil langsung agar tidak nunggu 1 detik
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault(); // Cegah submit langsung

        Swal.fire({
          title: 'Are you sure?',
          text: "This item will be permanently deleted!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit(); // Submit manual kalau user setuju
          }
        });
      });
    });
  });
</script>



@endsection
