<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

<head>

    <meta charset="utf-8" />
    <title>Ubah Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Layout config Js -->
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />


</head>

<body>

    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="index.html" class="d-inline-block auth-logo">
                                    <img src="assets/images/logo-light.png" alt="" height="20">
                                </a>
                            </div>
                            <p class="mt-3 fs-15 fw-medium">PT.ANUGERAH MEGA LESTARI</p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Ubah Password</h5>

                                </div>
                                <div class="p-2 mt-4">
                                    <form class="needs-validation" action="{{ route('ubah.password', $email) }}"
                                        method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">Password <span
                                                    class="text-danger">*</span></label>
                                            <div class="position-relative auth-pass-inputgroup">
                                                <input type="password" class="form-control pe-5 password-input"
                                                    onpaste="return false" placeholder="Enter password"
                                                    id="passwordInput" aria-describedby="passwordInput" name="password">
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>
                                                <small class="text-danger" id="errorPassword"></small>
                                                @error('password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="password-input">Confrim Password <span
                                                    class="text-danger">*</span></label>
                                            <div class="position-relative auth-pass-inputgroup">
                                                <input type="password" class="form-control pe-5 password-input"
                                                    onpaste="return false" placeholder="Enter password"
                                                    id="confirmPasswordInput" aria-describedby="passwordInput"
                                                    name="password">
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>
                                                <small class="text-danger" id="errorConfirm"></small>
                                                @error('password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">Ubah Password</button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                            <!-- end card body -->
                        </div>


                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> Velzon. Crafted with <i class="mdi mdi-heart text-danger"></i>
                                by Themesbrand
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>


    <script>
        function checkPassword() {
            const passwordInput = document.getElementById('passwordInput').value;
            const confirmPasswordInput = document.getElementById('confirmPasswordInput').value;

            const errors = [];

            // Minimal 6 karakter dan harus ada huruf besar, huruf kecil, dan karakter unik
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{6,}$/;

            if (!passwordRegex.test(passwordInput)) {
                errors.push(
                    'Password harus memiliki minimal 6 karakter, mengandung huruf besar, huruf kecil, dan karakter unik.'
                );
            }

            // Cek apakah password dan confirm password sama
            if (passwordInput !== confirmPasswordInput) {
                errors.push('Confirm password tidak sesuai dengan password.');
            }

            showErrors(errors);
        }

        function showErrors(errors) {
            const errorPasswordElement = document.getElementById('errorPassword');
            const errorConfirmElement = document.getElementById('errorConfirm');

            errorPasswordElement.className = 'text-danger';
            errorConfirmElement.className = 'text-danger';

            errorPasswordElement.textContent = '';
            errorConfirmElement.textContent = '';

            errors.forEach(error => {
                if (error.includes('Password harus memiliki minimal 6 karakter')) {
                    errorPasswordElement.textContent += error + ' ';
                } else if (error.includes('Confirm password tidak sesuai dengan password')) {
                    errorConfirmElement.textContent = error;
                }
            });
        }

        function hideErrors() {
            const errorPasswordElement = document.getElementById('errorPassword');
            const errorConfirmElement = document.getElementById('errorConfirm');

            errorPasswordElement.textContent = '';
            errorConfirmElement.textContent = '';
        }

        function showSuccess(message) {
            // Lakukan sesuatu untuk menampilkan pesan sukses jika diperlukan
            console.log(message);
        }

        // Event listener untuk melakukan pengecekan saat input berubah
        const passwordInput = document.getElementById('passwordInput');
        const confirmPasswordInput = document.getElementById('confirmPasswordInput');

        passwordInput.addEventListener('input', checkPassword);
        confirmPasswordInput.addEventListener('input', checkPassword);
    </script>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>

    <!-- particles js -->
    <script src="{{ asset('assets/libs/particles.js/particles.js') }}"></script>
    <!-- particles app js -->
    <script src="{{ asset('assets/js/pages/particles.app.js') }}"></script>
    <!-- validation init -->
    <!-- password create init -->
    <script src="{{ asset('assets/js/pages/passowrd-create.init.js') }}"></script>


    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Check Email Anda',
                text: '{{ session('success') }}'
            });
        @endif
    </script>

    <script>
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Opss',
                text: '{{ session('error') }}'
            });
        @endif
    </script>

</body>

</html>
