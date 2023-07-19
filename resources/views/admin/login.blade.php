<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/x-icon">
    <title>SSJaya - Admin Login</title>
    <!-- Custom CSS -->
    <link href="{{ asset('assets/themes/admin/dist/css/style.min.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
            style="background:url({{ asset('assets/themes/admin/assets/images/big/auth-bg.jpg') }}) no-repeat center center;">

            {{-- Main Content --}}
            <div class="auth-box row">
                <div class="col-lg-12 col-md-12 bg-white">
                    <div class="p-3">
                        <div class="text-center">
                            <img src="{{ asset('assets/images/logo/favicon.png') }}" alt="wrapkit"
                                style="height: 40px;">
                        </div>
                        <h2 class="mt-3 text-center">Sign In</h2>
                        <p class="text-center">Masukkan Username dan password untuk masuk.</p>
                        <div class="text-message"></div>
                        <form class="mt-4" id="form-login">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="username">Username</label>
                                        <input class="form-control" name="username" id="username" type="text"
                                            placeholder="masukkan username">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="password">Password</label>
                                        <input class="form-control" name="password" id="password" type="password"
                                            placeholder="masukkan password">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <button type="submit" class="btn btn-block btn-dark btn_login">Login</button>
                                </div>
                                <div class="col-lg-12 text-center mt-5">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="{{ asset('assets/themes/velzon/js/jquery-3.6.0.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('assets/themes/admin/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/themes/admin/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script>
        $(".preloader ").fadeOut();
        $(".btn_login").click((e) => {
            e.preventDefault()
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').empty();

            var formData = new FormData($('#form-login')[0]);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: `{{ route('admin.login') }}`,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: (data) => {
                    location.href = data.route;
                },
                error: (error) => {
                    $.each(error.responseJSON.errors, function(field, messages) {
                        $(`[name=${field}]`).addClass('is-invalid');
                        $(`[name=${field}]`).next().text(messages[0]);
                    })
                }
            })
        })
    </script>
</body>

</html>
