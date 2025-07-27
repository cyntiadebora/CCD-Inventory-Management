@extends('layouts.main')

@section('content')
<div class="container py-4" style="font-family: 'Times New Roman', Times, serif;">
    <h4 class="mb-4">Stock Details: {{ $item->name }}</h4>

    <div class="table-responsive">
        <table class="table table-hover table-striped shadow-sm rounded overflow-hidden">
            <thead class="bg-danger text-white">
                <tr>
                    <th>Size</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @if($item->has_size && $item->variants->count())
                    @foreach($item->variants as $variant)
                    <tr>
                        {{-- Asumsi $variant->size adalah relasi model Size, sehingga akses label --}}
                        <td>{{ $variant->size->size_label ?? $variant->size }}</td>
                        <td>{{ $variant->current_stock ?? 0 }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td> - </td>
                        <td>{{ $item->current_stock ?? 0 }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <a href="{{ route('dashboard') }}" class="btn btn-danger mt-3">Back</a>
</div>
@endsection
