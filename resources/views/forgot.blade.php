<!DOCTYPE html>
<html>
<head>
    <title>Quên mật khẩu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Quên mật khẩu</h2>
        <input type="email" id="email" placeholder="Nhập email" class="form-control mb-2">
        <button onclick="forgotPassword()" class="btn btn-primary">Lấy lại mật khẩu</button>
        <div id="tokenResult" class="mt-3"></div>
    </div>

    <script>
function forgotPassword() {

    const email = $('#email').val();
    if (!email) {
        alert('Vui lòng nhập email!');
        return;
    }

    $.ajax({
        url: 'http://127.0.0.1:8000/api/auth/forgot-password',
        method: 'POST',
        data: { email: email },
        success: function(response) {
            alert(response.message);
            console.log('Email để lưu:', email);
            console.log('Token nhận được:', response.token);

            // Lưu email và token vào localStorage
            localStorage.setItem('reset_email', email); 
            localStorage.setItem('reset_token', response.token);

            // Kiểm tra sau khi lưu
            console.log('Email đã lưu:', localStorage.getItem('reset_email'));
            console.log('Token đã lưu:', localStorage.getItem('reset_token'));

            // Chuyển đến trang đặt lại mật khẩu
            window.location.href = 'http://127.0.0.1:8000/reset';
        },
        error: function(error) {
            alert(error.responseJSON.message);
        }
    });
}

    </script>
</body>
</html>
