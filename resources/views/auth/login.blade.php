@extends('auth.app')
@section('title')
    Login
@endsection
@section('content')
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                        <h6 class="text-center">Login Sistem</h6>
                        <form class="pt-3" method="post">
                            @csrf
                            <div class="form-group">
                                <input type="email"
                                    class="form-control @error('email') is-invalid @enderror form-control-lg"
                                    id="exampleInputEmail1" placeholder="Email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password"
                                        class="form-control @error('password') is-invalid @enderror form-control-lg"
                                        id="password" placeholder="Password" name="password">
                                    <div class="input-group-append" style="border-radius: 0">
                                        <button class="btn" type="button" id="togglePassword"
                                            style=" background-color: #f8f9fa;border-radius:0 5px 5px 0">
                                            <i class="mdi mdi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <label class="form-check-label text-muted">
                                        <input type="checkbox" class="form-check-input" name="rememberme">
                                        Ingat Saya
                                    </label>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-block btn-info btn-lg font-weight-medium auth-form-btn">SIGN
                                    IN</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#togglePassword i');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.classList.toggle('mdi-eye');
            eyeIcon.classList.toggle('mdi-eye-off');
        });
    </script>
@endpush
