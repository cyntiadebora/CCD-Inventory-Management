@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h2>Detail Item</h2>
    <div class="card p-3">
        <p><strong>Name:</strong> {{ $item->name }}</p>
        <p><strong>Has Size:</strong> {{ $item->has_size ? 'Yes' : 'No' }}</p>
        <p><strong>Min Stock:</strong> {{ $item->min_stock }}</p>
        <p><strong>Max Stock:</strong> {{ $item->max_stock }}</p>
        <p><strong>Current Stock:</strong> {{ $item->current_stock }}</p>
        @if ($item->photo)
            <p><strong>Photo:</strong><br>
            <img src="{{ asset('/images/' . $item->photo) }}" width="200" style="object-fit: cover;">
            </p>
        @endif
    </div>
</div>
@endsection
