@extends('auth.layout')
@section('title')
    Register - {{ config('app.name') }}
@endsection

@section('content')
<div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6" style="max-width: 900px;">


        <!-- Register Card --> <div class="card"> <div class="card-body">
             <!-- Logo --> <div class="app-brand justify-content-center mb-6">
                 <a href="index.html" class="app-brand-link"> <span class="app-brand-logo demo">
                    <span class="text-primary"> <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="currentColor" /> <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" /> <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" /> <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="currentColor" />
                    </svg> </span> </span>
                    <span class="app-brand-text demo text-heading fw-bold">Vuexy</span> </a> </div>
                    <!-- /Logo -->
                 <div class=" text-center">
                <h4 class="mb-1 ">Adventure starts here 🚀</h4>
                <p class="mb-6 ">Make your app management easy and fun!</p>
                 </div>

                <form id="formAuthentication" class="mb-6" action="{{ route('register.store') }}" method="POST">
                    @csrf

                    <h5 class="mb-4">Personal Details</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3 form-control-validation">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Enter your first name" required />
                            @error('first_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 form-control-validation">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Enter your last name" required />
                            @error('last_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3 form-control-validation">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" placeholder="Enter your address" />
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 form-control-validation">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required />
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3 form-password-toggle form-control-validation">
                            <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password" placeholder="••••••••••••" required />
                                <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
                            </div>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 form-password-toggle form-control-validation">
                            <label class="form-label" for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="confirm_password" class="form-control" name="password_confirmation" placeholder="••••••••••••" required />
                                <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
                            </div>
                            @error('password_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <h5 class="mb-4">Shop Details</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3 form-control-validation">
                            <label for="shop_name" class="form-label">Shop Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="shop_name" name="shop_name" value="{{ old('shop_name') }}" placeholder="Enter your shop name" required />
                            @error('shop_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 form-control-validation">
                            <label for="shop_url" class="form-label">Shop URL <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="shop_url" name="shop_url" value="{{ old('shop_url') }}"
                             placeholder="https://example-shop.com" required />
                         <small class="text-muted">Enter full URL, e.g., https://example-shop.com</small>
    @error('shop_url')
        <small class="text-danger">{{ $message }}</small>
    @enderror
                        </div>
                    </div>

                    <div class="mb-3 form-control-validation">
                        <label for="shop_number" class="form-label">Shop Contact Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="shop_number" name="shop_number" value="{{ old('shop_number') }}" placeholder="Enter shop contact number" required />
                        @error('shop_number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="mb-4 form-check">
                        <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" />
                        <label class="form-check-label" for="terms-conditions">
                            I agree to <a href="javascript:void(0);">privacy policy & terms</a>
                        </label>
                    </div>

                    <!-- Sign Up Button -->
                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3">Sign up</button>
                </form>

                <!-- Already have an account text -->
                <p class="text-center mt-3">
                    <span>Already have an account?</span>
                    <a href="{{ route('login') }}">
                        <span>Sign in instead</span>
                    </a>
                </p>

            </div>
        </div>
        <!-- Register Card -->
    </div>
</div>
@endsection
