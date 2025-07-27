@extends('layouts.main')

@section('title', 'Request List')
@section('page-title', 'History of Requests')
@php use Illuminate\Support\Str; @endphp

@section('content')
<style>
    table, table td, table th, table span, table ul, table li {
        color: black !important;
    }

    .btn-pill {
        font-weight: bold;
        padding: 6px 12px;
        border-radius: 25px;
        transition: all 0.2s ease-in-out;
    }

    .btn-pill:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
     /* Hindari latar belakang halaman menjadi gelap saat modal terbuka */
    .modal-backdrop.show {
        opacity: 0.2 !important; /* bisa juga 0.1 atau 0.3 */
    }

    /* Modal tetap terang dan jelas */
    .modal-content {
        background-color: #fff !important;
        color: #000 !important;
    }

    /* Optional: agar teks lebih mudah dibaca */
    .modal-body p {
        font-size: 16px;
        line-height: 1.6;
    }
    .modal-backdrop {
    display: none !important;
</style>

<div class="container mt-4" style="font-family: 'Times New Roman', Times, serif;">
    <h2 class="mb-4">📋 Request List</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between mb-2">
        <form action="{{ route('admin.requests.bulkDelete') }}" method="POST" id="bulkDeleteForm">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                🗑 Delete Selected
            </button>
        </form>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle" style="color: black;">
                    <thead style="border-top: 2px solid red; border-bottom: 2px solid red;">
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Name</th>
                            <th>Base</th>
                            <th>Request Type</th>
                            <th>Status</th>
                            <th>Items</th>
                            <th>Description</th>
                            <th>Request Date</th>
                            <th>Proof</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
             <tbody>
    @forelse ($requests as $request)
        <tr>
            <td><input type="checkbox" name="selected_requests[]" form="bulkDeleteForm" value="{{ $request->id }}"></td>
            <td>{{ $request->user->name ?? 'Unknown' }}</td>
            <td>{{ $request->user->base ?? 'Unknown' }}</td>
            <td>
                <span class="btn btn-sm btn-pill 
                    @if ($request->type === 'buyer') btn-outline-primary 
                    @elseif ($request->type === 'approval') btn-outline-warning 
                    @else btn-outline-secondary 
                    @endif">
                    @if ($request->type === 'buyer') 🛒 Buyer
                    @elseif ($request->type === 'approval') 🧾 Approval
                    @else 📦 {{ ucfirst($request->type) }}
                    @endif
                </span>
            </td>
            <td>
                <span class="btn btn-sm btn-pill 
                    @if ($request->status === 'approved') btn-outline-success
                    @elseif ($request->status === 'pending') btn-outline-warning
                    @elseif ($request->status === 'rejected') btn-outline-danger
                    @elseif ($request->status === 'waiting_return') btn-outline-info
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
            <td class="text-start">
                <ul class="list-unstyled mb-0 ps-3">
                    @foreach ($request->requestItems as $ri)
                        <li>
                            {{ $ri->itemVariant?->item?->name ?? 'No item name' }} (Qty: {{ $ri->quantity }})

                            @if($ri->itemVariant?->size?->size_label)
                                - Size: {{ $ri->itemVariant->size->size_label }}
                            @elseif($ri->size_label)
                                - Size: {{ $ri->size_label }}
                            @endif
                                @if($ri->custom_size)
                                    <span class="badge" style="background-color: #f28b82 !important; color: #fff !important; font-weight: bold !important;">
                                        New size
                                    </span>
                                @else
                                    <span class="badge" style="background-color: #81c784 !important; color: #fff !important; font-weight: bold !important;">
                                        Personal size
                                    </span>
                                @endif
                        </li>
                    @endforeach
                </ul>
            </td>

            {{-- Description button --}}
            <td>
                <button type="button" class="btn btn-link p-0 text-decoration-underline" data-bs-toggle="modal" data-bs-target="#descModal{{ $request->id }}">
                    {{ Str::limit($request->description, 50, '...') }}
                </button>
            </td>

            <td>{{ $request->created_at->format('d M Y') }}</td>

            {{-- Proof Image with Modal --}}
            <td>
                @if($request->proof_image_path)
                    <img src="{{ asset('/images/' . $request->proof_image_path) }}"
                         alt="proof"
                         style="max-width: 80px; max-height: 80px; object-fit: cover; cursor: pointer;"
                         title="Click to enlarge"
                         data-bs-toggle="modal"
                         data-bs-target="#proofModal{{ $request->id }}">
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>

            {{-- Action --}}
        <td>
    @if($request->status === 'pending')
        @if($request->type === 'approval')
            <form id="return-form-{{ $request->id }}" action="{{ route('admin.requests.waitingReturn', $request->id) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <textarea name="admin_message" rows="2" placeholder="✍️ Message to crew..." 
                        class="form-control mb-2 text-dark" required></textarea>
                <button type="button" 
                        class="btn btn-outline-warning btn-sm btn-pill text-dark fw-bold swal-confirm-btn"
                        data-form-id="return-form-{{ $request->id }}"
                        data-message="Are you sure you want to mark this request as waiting for item return?">
                    🔄 Confirm Return
                </button>
            </form>
        @else
            <form id="approve-form-{{ $request->id }}" action="{{ route('admin.requests.approve', $request->id) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <textarea name="admin_message" rows="2" placeholder="✍️ Enter message..." class="form-control mb-2" required></textarea>
                <button type="button" 
                        class="btn btn-outline-success btn-sm btn-pill swal-confirm-btn"
                        data-form-id="approve-form-{{ $request->id }}"
                        data-message="Are you sure you want to approve this request?">
                    ✅ Approve
                </button>
            </form>
        @endif

        <form id="reject-form-{{ $request->id }}" action="{{ route('admin.requests.reject', $request->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <button type="button"
                    class="btn btn-outline-danger btn-sm btn-pill mt-1 text-dark fw-bold swal-confirm-btn"
                    data-form-id="reject-form-{{ $request->id }}"
                    data-message="Are you sure you want to reject this request?">
                ❌ Reject
            </button>
        </form>

    @elseif($request->status === 'waiting_return')
        <form id="approve-return-form-{{ $request->id }}" action="{{ route('admin.requests.approve', $request->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <input type="hidden" name="admin_message" value="The item has been returned. The request is approved.">
            <button type="button"
                    class="btn btn-outline-success btn-sm btn-pill swal-confirm-btn"
                    data-form-id="approve-return-form-{{ $request->id }}"
                    data-message="Approve this request after item has been returned?">
                ✅ Approve After Return
            </button>
        </form>
    @else
        <span class="text-muted">No actions</span>
    @endif
</td>


            </td>
        </tr>

        {{-- MODAL: Description --}}
        <div class="modal fade" id="descModal{{ $request->id }}" tabindex="-1" aria-labelledby="descModalLabel{{ $request->id }}" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="descModalLabel{{ $request->id }}">📄 Full Description</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-start">
                <p style="white-space: pre-line;">{{ $request->description }}</p>
              </div>
            </div>
          </div>
        </div>

        {{-- MODAL: Proof Image --}}
        @if($request->proof_image_path)
        <div class="modal fade" id="proofModal{{ $request->id }}" tabindex="-1" aria-labelledby="proofModalLabel{{ $request->id }}" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="proofModalLabel{{ $request->id }}">Proof Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-center">
                <img src="{{ asset('/images/' . $request->proof_image_path) }}"
                     alt="Proof"
                     class="img-fluid rounded"
                     style="max-height: 80vh;">
              </div>
            </div>
          </div>
        </div>
        @endif
    @empty
        <tr>
            <td colspan="10" class="text-center text-muted">
                No request data available.
            </td>
        </tr>
    @endforelse
</tbody>


                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Handle select all checkbox
    document.getElementById('selectAll').addEventListener('change', function (e) {
        const checkboxes = document.querySelectorAll('input[name="selected_requests[]"]');
        checkboxes.forEach(cb => cb.checked = e.target.checked);
    });

    // Handle bulk delete form
    document.getElementById('bulkDeleteForm').addEventListener('submit', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Delete Selected Requests?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Yes, delete!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit();
            }
        });
    });

    // General handler for all confirmation buttons (approve, reject, return)
    document.querySelectorAll('.swal-confirm-btn').forEach(button => {
        button.addEventListener('click', function () {
            const formId = this.getAttribute('data-form-id');
            const message = this.getAttribute('data-message') || "Are you sure?";
            Swal.fire({
                title: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Yes, continue',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        });
    });
</script>
@endpush
