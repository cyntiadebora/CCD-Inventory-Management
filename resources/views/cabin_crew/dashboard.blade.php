@extends('layouts.main')

@section('content')
<style>
  .border-merah {
    border-left: 3px solid red !important;
    border-bottom: 3px solid red !important;
    border-top: none !important;
    border-right: none !important;
    background-color: transparent !important;
    color: black !important;
  }

  .container, .container * {
    font-family: Arial, sans-serif !important;
  }

  .table, .table th, .table td {
    color: black !important;
    background-color: white !important;
  }

  .img-square {
    width: 100px;
    height: 100px;
    object-fit: cover;
    cursor: pointer;
  }
  html {
  scroll-behavior: smooth;
}
.modal-backdrop {
  z-index: 1050 !important;
}

.modal.show {
  z-index: 1060 !important;
}
  .modal-content {
    padding: 1.5rem 1rem 1rem 1rem;
    position: relative;
  }

  .modal-content .btn-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 10;
  }

  .modal-body img {
    max-height: 70vh;
    max-width: 100%;
    object-fit: contain;
  }

</style>

<div class="container mt-4">

  {{-- Wrapper merah untuk tombol dan card --}}
<div class="card mt-3" style="border-left: 3px solid red; background-color: white; border-radius: 8px;">
  <div class="card-body p-3">
    {{-- Tombol ke History --}}
    <div class="mb-3">
      <a href="#request-history" class="btn btn-outline-danger">
        📜 View History Request
      </a>
    </div>

    {{-- Card Informasi Pribadi --}}
    <div class="card border-0 m-0">
      <div class="card-header bg-white text-dark ps-2 pe-2 py-2 border-0">
      <h5 class="card-title mb-0">My Personal Information & Items</h5>
    </div>

    <div class="card-body p-0">
      <table class="table mb-0">
        <tbody>
          <tr>
            <th style="width: 25%;">Photo</th>
            <td style="width: 5%;">:</td>
            <td>
              @if ($user->photo)
                <img src="{{ asset('images/' . $user->photo) }}"
                  class="img-square img-thumbnail"
                  alt="Profile Photo"
                  onclick="showImageModal('{{ asset('images/' . $user->photo) }}')">
              @else
                <img src="{{ asset('images/default-profile.png') }}"
                  class="img-square img-thumbnail"
                  alt="Default Photo"
                  onclick="showImageModal('{{ asset('images/default-profile.png') }}')">
              @endif
            </td>
          </tr>
          <tr>
            <th>Name</th>
            <td>:</td>
            <td>{{ $user->name }}</td>
          </tr>

          @php
            $info = [
              'Email' => $user->email,
              'ID Number' => $user->id_number,
              'Gender' => ucfirst($user->gender),
              'Base' => $user->base,
              'Join Date' => \Carbon\Carbon::parse($user->join_date)->translatedFormat('d F Y'),
              'Rank' => $user->rank ?? '-',
              'Batch' => $user->batch ?? '-',
              'Status' => ucfirst($user->status),
              'Role' => ucfirst($user->role),
            ];
            $itemsWithSize = $user->userItemSizes->filter(fn($uis) => $uis->itemVariant && $uis->itemVariant->item->has_size);
            $itemsWithoutSize = $user->userItemSizes->filter(fn($uis) => $uis->itemVariant && !$uis->itemVariant->item->has_size);
          @endphp

          @foreach ($info as $label => $value)
            <tr>
              <th>{{ $label }}</th>
              <td>:</td>
              <td>{{ $value }}</td>
            </tr>
          @endforeach
          <tr>
  <td colspan="3" class="text-end">
    <a href="{{ route('password.reset.form') }}" class="btn btn-sm btn-danger">
      🔒 Changed Password
    </a>
  </td>
</tr>


          {{-- Item dengan size --}}
          @if ($itemsWithSize->count())
            @foreach ($itemsWithSize as $userItem)
              <tr>
                <td><strong>{{ ucfirst($userItem->itemVariant->item->name ?? '-') }}</strong></td>
                <td>:</td>
                <td>
                  <div class="d-flex align-items-center">
                    @if (!empty($userItem->itemVariant->item->photo))
                      <img src="{{ asset('images/' . $userItem->itemVariant->item->photo) }}"
                        width="60"
                        class="img-thumbnail me-3"
                        alt="{{ $userItem->itemVariant->item->name }}">
                    @else
                      <span class="text-muted me-3">No photo</span>
                    @endif
                    <span>Size: {{ $userItem->itemVariant->size->size_label ?? '-' }}</span>
                  </div>
                </td>
              </tr>
            @endforeach
          @endif

          {{-- Item tanpa size --}}
          @if ($itemsWithoutSize->count())
            <tr>
              <td><strong>Non-size Items</strong></td>
              <td>:</td>
              <td>
                @foreach ($itemsWithoutSize as $userItem)
                  <span class="me-3">
                    <span style="color: green;">&#10004;</span> {{ $userItem->itemVariant->item->name ?? '-' }}
                  </span>
                @endforeach
              </td>
            </tr>
          @endif

        </tbody>
      </table>
    </div>
  </div>

  {{-- Request Terbaru --}}
  @if ($latestRequest)
    <div class="card mt-4">
      <div class="card-header border-merah text-dark">
        <h5 class="card-title mb-0">Latest Request Status</h5>
      </div>
      <div class="card-body">
        <table class="table table-bordered mb-0">
          <tr>
            <th>Request Date</th>
            <td>{{ $latestRequest->created_at->format('d F Y, H:i') }}</td>
          </tr>
          <tr>
            <th>Status</th>
            <td>
  <span class="btn btn-sm btn-pill 
    @if ($latestRequest->status == 'approved') btn-outline-success
    @elseif ($latestRequest->status == 'pending') btn-outline-warning
    @elseif ($latestRequest->status == 'rejected') btn-outline-danger
    @elseif ($latestRequest->status == 'waiting_return') btn-outline-info
    @else btn-outline-secondary
    @endif">
    @if ($latestRequest->status === 'approved') ✅ Approved
    @elseif ($latestRequest->status === 'pending') ⏳ Pending
    @elseif ($latestRequest->status === 'rejected') ❌ Rejected
    @elseif ($latestRequest->status === 'waiting_return') 🔄 Waiting Return
    @else {{ ucfirst($latestRequest->status) }}
    @endif
  </span>
</td>

          </tr>
         <tr>
          <th>Admin Message</th>
          <td>{{ $latestRequest->admin_message ?? '-' }}</td>
        </tr>
        </table>

        @if($latestRequest->requestItems->count())
          <hr>
          <h6>Requested Items:</h6>
          <ul class="list-group">
            @foreach($latestRequest->requestItems as $requestItem)
              <li class="list-group-item d-flex align-items-center">
                @if(!empty($requestItem->item->photo))
                  <img src="{{ asset('images/' . $requestItem->item->photo) }}"
                    alt="{{ $requestItem->item->name }}"
                    class="img-square me-3">
                @endif
                <div>
                  <strong>{{ $requestItem->item->name ?? 'Unnamed Item' }}</strong>
                  @if($requestItem->size)
                    - Size: {{ $requestItem->size->size_label }}
                  @endif
                  <br>
                  Quantity: {{ $requestItem->quantity ?? 1 }}
                </div>
              </li>
            @endforeach
          </ul>
        @else
          <p>No items requested.</p>
        @endif
      </div>
    </div>
  @endif

 {{-- Riwayat Semua Request --}}
@if($allRequests->count())
  <div class="card mt-4" id="request-history">
    <div class="card-header border-merah text-dark">
      <h5 class="card-title mb-0">🗂 All Request History</h5>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
          <tr>
            <th>Date</th>
            <th>Type Request</th> {{-- ✅ Sudah diganti sebelumnya --}}
            <th>Status</th>        {{-- ✅ Tambahan baru --}}
            @foreach($allItems as $item)
              <th>{{ $item->name }}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach($allRequests as $request)
            <tr>
              <td>{{ $request->created_at->format('d M Y, H:i') }}</td>
              <td>{{ ucfirst($request->type) }}</td>
              <td>
  <span class="btn btn-sm btn-pill 
    @if ($request->status == 'approved') btn-outline-success
    @elseif ($request->status == 'pending') btn-outline-warning
    @elseif ($request->status == 'rejected') btn-outline-danger
    @elseif ($request->status == 'waiting_return') btn-outline-info
    @else btn-outline-secondary
    @endif">
    @if ($request->status === 'approved') ✅ Approved
    @elseif ($request->status === 'pending') ⏳ Pending
    @elseif ($request->status === 'rejected') ❌ Rejected
    @elseif ($request->status === 'waiting_return') 🔄 Waiting Return
    @else {{ ucfirst($request->status) }}
    @endif
  </span>
</td>

              @foreach($allItems as $item)
                @php
                  $matched = $request->requestItems->firstWhere('item_id', $item->id);
                @endphp
                <td>
                  @if($matched)
                    @if($matched->size)
                      {{ $matched->size->size_label }}
                    @else
                      <span class="text-success">✔</span>
                    @endif
                  @else
                    <span class="text-muted">–</span>
                  @endif
                </td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif

@push('modals')
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-white border-0 position-relative rounded-4 shadow">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

      <div class="modal-body text-center">
        <img src="" id="modalImage"
             class="img-fluid rounded shadow"
             alt="Preview"
             style="max-height: 80vh;">
      </div>
    </div>
  </div>
</div>
@endpush

@push('scripts')
<script>
  function showImageModal(imageUrl) {
    const modalImage = document.getElementById('modalImage');
    modalImage.src = imageUrl;
    const modal = new bootstrap.Modal(document.getElementById('photoModal'));
    modal.show();
  }
</script>
@endpush

@endsection


