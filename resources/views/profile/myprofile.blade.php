@extends('layouts.app')

@section('content')




    <div class="row">
      <div class="col-12">
        <div class="card mb-6">
          <div class="user-profile-header-banner">
            <img src="{{ asset('assets/img/pages/profile-banner.png') }}"
                 alt="Banner image"
                 class="rounded-top" />
          </div>

          <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-5">
            <div class="shrink-0 mt-n2 mx-sm-0 mx-auto">
              <img src="{{ asset('assets/img/avatars/1.png') }}"
                   alt="user image"
                   class="d-block h-auto ms-0 ms-sm-6 rounded user-profile-img" />
            </div>

            <div class="grow mt-3 mt-lg-5">
              <div class="d-flex align-items-md-end align-items-sm-start align-items-center
                          justify-content-md-between justify-content-start mx-5
                          flex-md-row flex-column gap-4">

                <div class="user-profile-info">
                  <h4 class="mb-2 mt-lg-6">John Doe</h4>
                  <ul class="list-inline mb-0 d-flex align-items-center flex-wrap
                             justify-content-sm-start justify-content-center gap-4 my-2">

                    <li class="list-inline-item d-flex gap-2 align-items-center">
                      <i class="icon-base ti tabler-palette icon-lg"></i>
                      <span class="fw-medium">UX Designer</span>
                    </li>

                    <li class="list-inline-item d-flex gap-2 align-items-center">
                      <i class="icon-base ti tabler-map-pin icon-lg"></i>
                      <span class="fw-medium">Vatican City</span>
                    </li>

                    <li class="list-inline-item d-flex gap-2 align-items-center">
                      <i class="icon-base ti tabler-calendar icon-lg"></i>
                      <span class="fw-medium">Joined April 2021</span>
                    </li>

                  </ul>
                </div>

                <a href="javascript:void(0)" class="btn btn-primary mb-1">
                  <i class="icon-base ti tabler-user-check icon-xs me-2"></i>
                  Connected
                </a>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ Header -->

    <!-- Navbar pills -->
    <div class="row">
      <div class="col-md-12">
        <div class="nav-align-top">
          <ul class="nav nav-pills flex-column flex-sm-row mb-6 gap-sm-0 gap-2">

            <li class="nav-item">
              <a class="nav-link active" href="javascript:void(0);">
                <i class="icon-base ti tabler-user-check icon-sm me-1_5"></i>
                Profile
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="{{ url('pages-profile-teams') }}">
                <i class="icon-base ti tabler-users icon-sm me-1_5"></i>
                Teams
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="{{ url('pages-profile-projects') }}">
                <i class="icon-base ti tabler-layout-grid icon-sm me-1_5"></i>
                Projects
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="{{ url('pages-profile-connections') }}">
                <i class="icon-base ti tabler-link icon-sm me-1_5"></i>
                Connections
              </a>
            </li>

          </ul>
        </div>
      </div>
    </div>
    <!--/ Navbar pills -->

    <!-- User Profile Content -->
    <div class="row">
      <div class="col-xl-4 col-lg-5 col-md-5">

        <!-- About User -->
        <div class="card mb-6">
          <div class="card-body">
            <p class="card-text text-uppercase text-body-secondary small mb-0">About</p>

            <ul class="list-unstyled my-3 py-1">
              <li class="d-flex align-items-center mb-4">
                <i class="icon-base ti tabler-user icon-lg"></i>
                <span class="fw-medium mx-2">Full Name:</span>
                <span>John Doe</span>
              </li>

              <li class="d-flex align-items-center mb-4">
                <i class="icon-base ti tabler-check icon-lg"></i>
                <span class="fw-medium mx-2">Status:</span>
                <span>Active</span>
              </li>

              <li class="d-flex align-items-center mb-4">
                <i class="icon-base ti tabler-crown icon-lg"></i>
                <span class="fw-medium mx-2">Role:</span>
                <span>Developer</span>
              </li>

              <li class="d-flex align-items-center mb-4">
                <i class="icon-base ti tabler-flag icon-lg"></i>
                <span class="fw-medium mx-2">Country:</span>
                <span>USA</span>
              </li>

              <li class="d-flex align-items-center mb-2">
                <i class="icon-base ti tabler-language icon-lg"></i>
                <span class="fw-medium mx-2">Languages:</span>
                <span>English</span>
              </li>
            </ul>

            <p class="card-text text-uppercase text-body-secondary small mb-0">Contacts</p>

            <ul class="list-unstyled my-3 py-1">
              <li class="d-flex align-items-center mb-4">
                <i class="icon-base ti tabler-phone-call icon-lg"></i>
                <span class="fw-medium mx-2">Contact:</span>
                <span>(123) 456-7890</span>
              </li>

              <li class="d-flex align-items-center mb-4">
                <i class="icon-base ti tabler-messages icon-lg"></i>
                <span class="fw-medium mx-2">Skype:</span>
                <span>john.doe</span>
              </li>

              <li class="d-flex align-items-center mb-4">
                <i class="icon-base ti tabler-mail icon-lg"></i>
                <span class="fw-medium mx-2">Email:</span>
                <span>john.doe@example.com</span>
              </li>
            </ul>

          </div>
        </div>
        <!--/ About User -->

      </div>
    </div>
    <!--/ User Profile Content -->


@endsection
