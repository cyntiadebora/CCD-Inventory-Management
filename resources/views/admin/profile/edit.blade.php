@extends('layouts.main')

@section('title', 'Edit Personal Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="container mt-4" style="max-width: 600px; font-family: sans-serif;">
    <h2>Edit Your Profile</h2>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.personal-profile.update') }}" enctype="multipart/form-data" class="mt-4">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $admin->name) }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select" required>
                <option value="male" {{ old('gender', $admin->gender) === 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', $admin->gender) === 'female' ? 'selected' : '' }}>Female</option>
            </select>
            @error('gender') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Photo</label><br>
            @if ($admin->photo)
                <img src="{{ asset('images/' . $admin->photo) }}" alt="Admin Photo" width="100" class="mb-2 rounded">
            @endif
            <input type="file" name="photo" class="form-control">
            @error('photo') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <hr>

        <div class="mb-3">
            <label class="form-label">New Password <small class="text-muted">(Leave blank if not changing)</small></label>
            <input type="password" name="password" class="form-control">
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-dark">Update Profile</button>
    </form>
</div>
@endsection
