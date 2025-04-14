<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Đăng ký tài khoản</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Họ tên</label>
                            <input type="text" class="form-control" id="name" placeholder="Nhập họ tên">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Nhập email">
                        </div>
                        <div class="form-group">
                            <label for="password">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" placeholder="Nhập mật khẩu">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control" id="confirm_password"
                                placeholder="Xác nhận mật khẩu">
                        </div>
                        <button onclick="register()" class="btn btn-success btn-block">Đăng ký</button>
                        <button onclick="redirectToLogin()" class="btn btn-link btn-block">Đã có tài khoản? Đăng
                            nhập</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function register() {
            const password = $('#password').val();
            const confirmPassword = $('#confirm_password').val();

            if (password !== confirmPassword) {
                alert('Mật khẩu và xác nhận mật khẩu không khớp!');
                return;
            }

            $.ajax({
                url: 'http://127.0.0.1:8000/api/auth/register',
                method: 'POST',
                data: {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    password: password,
                    password_confirmation: confirmPassword
                },
                success: function(response) {
                    alert('Đăng ký thành công! Vui lòng đăng nhập.');
                    redirectToLogin();
                },
                error: function(error) {
                    if (error.responseJSON && error.responseJSON.errors) {
                        let errorMessage = 'Đăng ký thất bại:\n';
                        $.each(error.responseJSON.errors, function(key, messages) {
                            errorMessage += '- ' + messages[0] + '\n';
                        });
                        alert(errorMessage);
                    } else {
                        alert('Đăng ký thất bại! Vui lòng thử lại.');
                    }
                    console.error(error.responseJSON);
                }
            });
        }

        function redirectToLogin() {
            window.location.href = 'http://127.0.0.1:8000/login'; // Chuyển đến trang đăng nhập
        }
    </script>
</body>

</html>
