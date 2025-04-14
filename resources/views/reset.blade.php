<!DOCTYPE html>
<html>

<head>
    <title>Đặt lại mật khẩu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2>Đặt lại mật khẩu</h2>
        <input type="password" id="password" placeholder="Mật khẩu mới" class="form-control mb-2">
        <input type="password" id="confirm_password" placeholder="Xác nhận mật khẩu" class="form-control mb-2">
        <button onclick="resetPassword()" class="btn btn-success">Đặt lại mật khẩu</button>
    </div>

    <script>
        function resetPassword() {
            const password = $('#password').val();
            const confirmPassword = $('#confirm_password').val();

            if (password !== confirmPassword) {
                alert('Mật khẩu không khớp!');
                return;
            }

            // Lấy email và token từ localStorage (trong trường hợp quên mật khẩu)
            const email = localStorage.getItem('reset_email');
            const token = localStorage.getItem('reset_token');

            // Tạo đối tượng dữ liệu
            let data = {
                password: password,
                password_confirmation: confirmPassword,
            };

            // Nếu có email và token trong localStorage (quên mật khẩu)
            if (email && token) {
                data.email = email;
                data.token = token;
            }

            $.ajax({
                url: 'http://127.0.0.1:8000/api/auth/reset-password',
                method: 'POST',
                data: data,
                success: function(response) {
                    alert(response.message);
                    // Xóa email và token sau khi đặt lại thành công
                    localStorage.removeItem('reset_email');
                    localStorage.removeItem('reset_token');
                    window.location.href = 'http://127.0.0.1:8000/login';
                },
                error: function(error) {
                    alert('Đặt lại mật khẩu thất bại! ' + error.responseJSON.message);
                    console.error(error.responseJSON);
                }
            });
        }
    </script>
</body>

</html>
