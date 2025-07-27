@extends('layouts.main')

@section('title', 'Daftar Cabin Crew')
@section('page-title', 'User Management')
@section('content')
<style>
    * {
        font-family: 'Inter', sans-serif !important;
    }

    /* Pastikan semua teks di tabel hitam dan center */
    table.table th,
    table.table td {
        color: #000 !important;
        text-align: center !important; /* Tambahkan ini */
        vertical-align: middle !important; /* Agar isi rata tengah secara vertikal juga */
    }

    .table-secondary th {
        background-color: #e9ecef !important;
        color: #000 !important;
    }

    .card-header.grey-header {
        background-color: rgb(136, 131, 131) !important;
        color: white !important;
    }

    .card-header.grey-header h4 {
        color: white !important;
    }

    .table-header-white th {
        background-color: #ffffff !important;
        color: #000 !important;
        box-shadow: none !important;
        border-bottom: 3px solid #ffffff !important;
    }
    .hover-elevate {
        transition: all 0.2s ease-in-out;
    }

    .hover-elevate:hover {
        box-shadow: 0 4px 8px rgba(0, 128, 0, 0.3); /* bayangan hijau */
        transform: translateY(-2px); /* naik sedikit */
        background-color: rgba(0, 128, 0, 0.05); /* efek hijau muda */
    }
</style>



<div class="container mt-4">
        <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('users.create') }}"
        class="btn border border-success text-dark fw-bold mb-3 hover-elevate">
            <i class="bi bi-plus-circle"></i> Add Cabin Crew
        </a>
    </div>
    <div class="card">
       <div class="card-header" style="background-color: transparent; border-left: 2px solid #FF0000; border-bottom: 2px solid #FF0000; border-top: none; border-right: none;">
            <h4 class="mb-0" style="color: black;">{{ $judul }}</h4>
        </div>
     <div class="card-body">

    <!-- Form Pencarian -->
    <form action="{{ route('users.index') }}" method="GET" class="mb-3 d-flex justify-content-end">
        <input type="text" name="search" class="form-control w-25 me-2" placeholder="🔍 Search name..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-outline-dark fw-bold">Search</button>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-header-white">
                <tr>
                    <th>No</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Gender</th>
                    <th>Base</th>
                    <th>Join Date</th>
                    <th>Rank</th>
                    <th>Batch</th>
                    <th>Action</th> 
                </tr>
            </thead>
            <tbody>
                @forelse($cabinCrews as $index => $crew)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($crew->photo)
                                <img src="{{ asset('/images/' . $crew->photo) }}" alt="Foto" width="50" height="50" class="rounded-circle">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $crew->name }}</td>
                        <td>{{ $crew->email }}</td>
                        <td>
                            @if($crew->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($crew->status === 'inactive')
                                <span class="badge bg-warning text-dark">Inactive</span>
                            @endif
                        </td>
                        <td>{{ ucfirst($crew->gender) }}</td>
                        <td>{{ $crew->base }}</td>
                        <td>{{ \Carbon\Carbon::parse($crew->join_date)->format('d M Y') }}</td>
                        <td>{{ $crew->rank ?? '-' }}</td>
                        <td>{{ $crew->batch ?? '-' }}</td>
                       <td>
                        <a href="{{ route('users.show', $crew->id) }}" 
                        class="btn btn-sm btn-outline-success fw-bold text-dark border-2 mb-1">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        
                        <form action="{{ route('users.destroy', $crew->id) }}" method="POST" 
                            style="display: inline-block;" 
                            onsubmit="return confirm('Are you sure you want to delete this cabin crew?');">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                class="btn btn-sm btn-outline-danger fw-bold text-dark border-2"
                                onclick="confirmDelete('{{ route('users.destroy', $crew->id) }}')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-white bg-warning">
                            No cabin crew found{{ request('search') ? ' for "' . request('search') . '"' : '' }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    </div>
</div>
<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="confirmDeleteLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        Are you sure you want to delete this Cabin Crew?
      </div>
      <div class="modal-footer">
            <form id="deleteForm" method="POST" class="d-flex gap-2">
                @csrf
                @method('DELETE')
                <button type="button"
                    class="btn border border-secondary text-dark fw-bold"
                    data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="submit"
                    class="btn border border-danger text-dark fw-bold">
                    <i class="bi bi-trash"></i> Yes, Delete
                </button>
            </form>
        </div>

    </div>
  </div>
</div>

<script>
    function confirmDelete(action) {
        const form = document.getElementById('deleteForm');
        form.action = action;
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        modal.show();
    }
</script>

@endsection
