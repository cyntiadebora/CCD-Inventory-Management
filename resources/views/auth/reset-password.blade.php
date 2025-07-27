@extends('layouts.main') {{-- ganti sesuai layout utama kamu --}}

@section('title', 'Reset Password')

@section('content')
<div class="container mt-5">
    <h2>Reset Password</h2>

    {{-- Notifikasi sukses --}}
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    {{-- Notifikasi error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.reset.save') }}">
        @csrf

        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" name="password" class="form-control" required minlength="8">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control" required minlength="8">
        </div>

        <button type="submit" class="btn btn-danger">Update Password</button>
    </form>
</div>
@endsection
