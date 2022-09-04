<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <title>Cá cảnh Duy Nguyễn | Login</title>
    <style>
      .divider:after,
      .divider:before {
        content: "";
        flex: 1;
        height: 1px;
        background: #eee;
      }
      .h-custom {
      height: calc(100% - 73px);
      }
      @media (max-width: 450px) {
        .h-custom {
        height: 100%;
        }
      }
    </style>
</head>
<body>
    <section class="vh-100">
        <div class="container-fluid h-custom">
          <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-9 col-lg-6 col-xl-5">
              <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
                class="img-fluid" alt="Sample image">
            </div>
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
              <form action="{{ route('admin.login') }}" method="POST">
                @csrf
                <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                  <h1>Đăng nhập</h1>
                </div>
      
                <div class="divider d-flex align-items-center my-4">
                  <p class="text-center fw-bold mx-3 mb-0"></p>
                </div>
      
                <div class="form-outline mb-4">
                  <input type="text" name="username" class="form-control form-control-lg" placeholder="Tên đăng nhập" />
                  {{-- <label class="form-label" for="form3Example3">Email address</label> --}}
                </div>
      
                <!-- Password input -->
                <div class="form-outline mb-3">
                  <input type="password" name="password" class="form-control form-control-lg" placeholder="Mật khẩu" />
                  {{-- <label class="form-label" for="form3Example4">Password</label> --}}
                </div>
                @if (session('msg'))
                    <div class="alert alert-danger">
                        {{ session('msg') }}
                    </div>
                @endif
      
                <div class="text-center text-lg-start mt-4 pt-2">
                  <button type="submit" class="btn btn-primary btn-lg"
                    style="padding-left: 2.5rem; padding-right: 2.5rem;">Đăng nhập vào hệ thống</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>
  </body>
</html>