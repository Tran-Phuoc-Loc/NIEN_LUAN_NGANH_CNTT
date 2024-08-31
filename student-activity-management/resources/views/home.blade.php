<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chào mừng đến với hệ thống quản lý hoạt động đoàn/hội</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Link CSS -->
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>
<style>
    body {
    background: url('/storage/images/background.png') no-repeat center center fixed;
    background-size: cover; /* Đảm bảo hình nền bao phủ toàn bộ màn hình */
    color: #fff;
    font-family: Arial, sans-serif;
}
</style>
<body>
    <div id="app" class="d-flex align-items-center justify-content-center vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Chào mừng!</div>

                        <div class="card-body">
                            <p>Chào mừng bạn đến với hệ thống quản lý hoạt động đoàn/hội của chúng tôi.</p>
                            <p>Bạn cần <a href="{{ route('login') }}" class="login-link">đăng nhập</a> để sử dụng các chức năng.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary mt-4">Đăng nhập ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>