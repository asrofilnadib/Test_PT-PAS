@section('style')
  <style>
    .form-control.is-invalid {
      border-color: #dc3545;
    }

    .invalid-feedback {
      display: block;
      color: #dc3545;
      font-size: 0.875rem;
    }
  </style>
@endsection
<x-guest-layout>
  <div class="card">
    <div class="card-body">
      <!-- Logo -->
      <div class="d-flex flex-column align-items-center my-4">
        <a href="#" class="d-flex align-items-center text-decoration-none gap-2">
          <div class="app-brand-logo d-flex justify-content-center align-items-center">
            <img src="{{ asset('assets/img/logo/logo pas.jpg') }}"
                 alt="Logo PT PAS"
                 class="img-fluid rounded shadow-sm"
                 style="width: 16rem; object-fit: cover;">
          </div>
        </a>
      </div>
      <!-- /Logo -->
      <h3 class="mb-3 pt-2 justify-content-center align-items-center text-center">Login</h3>

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <form id="formAuthentication" class="mb-3" action="{{ route('login.action') }}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="email" class="form-label">Username</label>
          <input
            type="text"
            class="form-control"
            id="username"
            name="username"
            placeholder="Enter your username"
            autofocus />
        </div>
        <div class="mb-5 form-password-toggle">
          <div class="d-flex justify-content-between">
            <label class="form-label" for="password">Password</label>
          </div>
          <div class="input-group input-group-merge">
            <input
              type="password"
              id="password"
              class="form-control"
              name="password"
              placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
              aria-describedby="password" />
            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
          </div>
        </div>
        <div class="mb-3">
          <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
        </div>
      </form>
    </div>
  </div>
</x-guest-layout>
