<!DOCTYPE html>
<html>
<head>
    <title>Đặt Lại Mật Khẩu</title>
</head>
<body>
    <h2>Đặt Lại Mật Khẩu</h2>
    <p>Nhấp vào liên kết dưới đây để đặt lại mật khẩu của bạn:</p>
    <a href="{{ route('password.reset', ['token' => $token]) }}">Đặt Lại Mật Khẩu</a>
</body>
</html>