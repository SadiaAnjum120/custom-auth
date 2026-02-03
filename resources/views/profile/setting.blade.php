@extends('layouts.app')

@section('content')
  <div class="row">
    <div class="col-12">
      @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if ($errors->any())
        <div class="alert alert-danger alert-dismissible" role="alert">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
    </div>
  </div>

  <!-- Profile update -->
  <div class="card mb-4">
    <h5 class="card-header">Account</h5>
    <div class="card-body pt-4">
      <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row gy-4 gx-6 mb-4">
          <div class="col-md-6">
            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
            <input
              class="form-control @error('first_name') is-invalid @enderror"
              type="text"
              id="first_name"
              name="first_name"
              value="{{ old('first_name', $user->first_name) }}"
              required />
            @error('first_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
            <input
              class="form-control @error('last_name') is-invalid @enderror"
              type="text"
              name="last_name"
              id="last_name"
              value="{{ old('last_name', $user->last_name) }}"
              required />
            @error('last_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
            <input
              class="form-control @error('email') is-invalid @enderror"
              type="email"
              id="email"
              name="email"
              value="{{ old('email', $user->email) }}"
              required />
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input
              type="text"
              id="phone_number"
              name="phone_number"
              class="form-control @error('phone_number') is-invalid @enderror"
              value="{{ old('phone_number', $user->phone_number) }}"
              placeholder="e.g. 202 555 0111" />
            @error('phone_number')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-12">
            <label for="address" class="form-label">Address</label>
            <input
              type="text"
              class="form-control @error('address') is-invalid @enderror"
              id="address"
              name="address"
              value="{{ old('address', $user->address) }}"
              placeholder="Address" />
            @error('address')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="mt-2">
          <button type="submit" class="btn btn-primary me-3">Save changes</button>
          <a href="{{ route('profile') }}" class="btn btn-label-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Password update -->
  <div class="card">
    <h5 class="card-header">Change Password</h5>
    <div class="card-body pt-4">
      <form action="{{ route('profile.password.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row gy-4 gx-6 mb-4">
          <div class="col-12">
            <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
            <input
              type="password"
              id="current_password"
              name="current_password"
              class="form-control @error('current_password') is-invalid @enderror"
              required
              autocomplete="current-password" />
            @error('current_password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
            <input
              type="password"
              id="password"
              name="password"
              class="form-control @error('password') is-invalid @enderror"
              required
              minlength="8"
              autocomplete="new-password" />
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-body-secondary">Min. 8 characters</small>
          </div>
          <div class="col-md-6">
            <label for="password_confirmation" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
            <input
              type="password"
              id="password_confirmation"
              name="password_confirmation"
              class="form-control"
              required
              minlength="8"
              autocomplete="new-password" />
          </div>
        </div>
        <div class="mt-2">
          <button type="submit" class="btn btn-primary">Update password</button>
        </div>
      </form>
    </div>
  </div>
@endsection
