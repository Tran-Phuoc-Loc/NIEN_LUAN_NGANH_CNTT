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
    <style>
        body {
            background: linear-gradient(135deg, #f06, #f9a);
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            border-bottom: none;
        }

        .card-body {
            text-align: center;
        }

        .btn-primary {
            background-color: #ff4d4d;
            border-color: #ff4d4d;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #ff1a1a;
            border-color: #ff1a1a;
        }

        .login-link {
            color: #fff;
            font-weight: bold;
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div id="app" class="d-flex align-items-center justify-content-center vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Chào mừng!</div>

                        <div class="card-body">
                            <p>Chào mừng bạn đến với hệ thống quản lý hoạt động đoàn/hội của chúng tôi.</p>
                            <p>Bạn cần <a href="#" class="login-link">đăng nhập</a> để sử dụng các chức năng.</p>
                            <a href="#" class="btn btn-primary mt-4">Đăng nhập ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
