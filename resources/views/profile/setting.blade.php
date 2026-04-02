@extends('layouts.app')

@section('content')

  <!-- Alerts -->
  <div class="row">
    <div class="col-12">
      @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger alert-dismissible" role="alert">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
    </div>
  </div>

  <!-- Profile Tabs Card -->
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">

        <li class="nav-item">
          <button class="nav-link active"
                  id="account-tab"
                  data-bs-toggle="tab"
                  data-bs-target="#account"
                  type="button">
            Account Information
          </button>
        </li>

        <li class="nav-item">
          <button class="nav-link"
                  id="password-tab"
                  data-bs-toggle="tab"
                  data-bs-target="#password"
                  type="button">
            Change Password
          </button>
        </li>

      </ul>
    </div>

    <div class="card-body">
      <div class="tab-content">

        <!-- ================= ACCOUNT TAB ================= -->
        <div class="tab-pane fade show active" id="account">

          <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row gy-4 gx-6 mb-4">

              <div class="col-md-6">
                <label class="form-label">First Name <span class="text-danger">*</span></label>
                <input type="text"
                       name="first_name"
                       class="form-control @error('first_name') is-invalid @enderror"
                       value="{{ old('first_name', $user->first_name) }}"
                       required>
                @error('first_name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                <input type="text"
                       name="last_name"
                       class="form-control @error('last_name') is-invalid @enderror"
                       value="{{ old('last_name', $user->last_name) }}"
                       required>
                @error('last_name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">E-mail <span class="text-danger">*</span></label>
                <input type="email"
                       name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email) }}"
                       required>
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="text"
                       name="phone_number"
                       class="form-control @error('phone_number') is-invalid @enderror"
                       value="{{ old('phone_number', $user->phone_number) }}">
                @error('phone_number')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-12">
                <label class="form-label">Address</label>
                <input type="text"
                       name="address"
                       class="form-control @error('address') is-invalid @enderror"
                       value="{{ old('address', $user->address) }}">
                @error('address')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>

          </form>
        </div>


        <!-- ================= PASSWORD TAB ================= -->
        <div class="tab-pane fade" id="password">

          <form action="{{ route('profile.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row gy-4 gx-6 mb-4">

              <div class="col-12">
                <label class="form-label">Current Password <span class="text-danger">*</span></label>
                <input type="password"
                       name="current_password"
                       class="form-control @error('current_password') is-invalid @enderror"
                       required>
                @error('current_password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">New Password <span class="text-danger">*</span></label>
                <input type="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required minlength="8">
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                <input type="password"
                       name="password_confirmation"
                       class="form-control"
                       required minlength="8">
              </div>

            </div>

            <button type="submit" class="btn btn-primary">Update Password</button>

          </form>
        </div>

      </div>
    </div>
  </div>

@endsection
