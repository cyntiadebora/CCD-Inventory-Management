@extends('layouts.main')

@section('title', 'Detail Cabin Crew')

@section('content')
    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }

        table th,
        table td {
            color: #000 !important;
        }
    </style>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header" style="background-color: transparent; border-left: 3px solid red; border-bottom: 3px solid red; color: black;">
                <h4 class="mb-0">Detail Cabin Crew</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3 text-center">
                        @if($crew->photo)
                            <img src="{{ asset('/images/' . $crew->photo) }}" alt="Foto" width="150" height="150" class="rounded-circle mb-3">
                        @else
                            <div class="text-muted">No photo available</div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <table class="table table-bordered">
                            <tr><th>Name</th><td>{{ $crew->name }}</td></tr>
                            <tr><th>Email</th><td>{{ $crew->email }}</td></tr>
                            <tr><th>ID Number</th><td>{{ $crew->id_number ?? '-' }}</td></tr>
                            <tr><th>Role</th><td>{{ ucwords(str_replace('_', ' ', $crew->role)) }}</td></tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($crew->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($crew->status === 'inactive')
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr><th>Gender</th><td>{{ ucfirst($crew->gender) }}</td></tr>
                            <tr><th>Base</th><td>{{ $crew->base }}</td></tr>
                            <tr><th>Join Date</th><td>{{ \Carbon\Carbon::parse($crew->join_date)->format('d M Y') }}</td></tr>
                            <tr><th>Rank</th><td>{{ $crew->rank ?? '-' }}</td></tr>
                            <tr><th>Batch</th><td>{{ $crew->batch ?? '-' }}</td></tr>
                        </table>

                        <div class="mt-4" style="max-width: 600px;">
                            @php
                                $itemsWithSize = $crew->userItemSizes->filter(function ($uis) {
                                    $hasSize = $uis->itemVariant && $uis->itemVariant->size;
                                    $notAllType = strtolower($uis->itemVariant->item->type ?? '') !== 'all';
                                    return $hasSize && $notAllType;
                                })->values();
                            @endphp

                            <h5 style="border-left: 3px solid #ff0000; border-bottom: 3px solid #ff0000; padding-left: 10px; padding-bottom: 5px;">
                                List of Items & Sizes
                            </h5>

                            @if($itemsWithSize->isEmpty())
                                <p class="text-center"><em>Tidak ada item yang terdaftar.</em></p>
                            @else
                                <table class="table table-bordered text-center" style="width: 100%; table-layout: fixed; font-size: 14px; background-color: #fff;">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Item Name</th>
                                            <th class="text-center">Size</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($itemsWithSize as $index => $uis)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="text-center">{{ $uis->itemVariant->item->name ?? '-' }}</td>
                                                <td class="text-center">{{ $uis->itemVariant->size->size_label ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        @if($nonSizeItems->isNotEmpty())
                            <div class="mt-4">
                                <h5 style="border-left: 3px solid #ff0000; border-bottom: 3px solid #ff0000; padding-left: 10px; padding-bottom: 5px;">
                                    Other Items (No Sizes)
                                </h5>

                                <div class="d-flex flex-wrap gap-4">
                                    @foreach($nonSizeItems as $item)
                                        <div class="d-flex align-items-center" style="color: #000;">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>{{ $item }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('users.index') }}" class="btn" style="border: 2px solid #28a745; color: #000; background-color: transparent;">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>

                            <a href="{{ route('users.edit', $crew->id) }}" class="btn" style="border: 2px solid #ffc107; color: #000; background-color: transparent;">
                                <i class="bi bi-pencil-square"></i> Edit Cabin Crew Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
