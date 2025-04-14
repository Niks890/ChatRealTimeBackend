<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Đăng nhập</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" class="form-control" placeholder="Nhập email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Mật khẩu</label>
                            <input type="password" id="password" class="form-control" placeholder="Nhập mật khẩu"
                                required>
                        </div>
                        <button class="btn btn-primary btn-block" onclick="login()">Đăng nhập</button>
                        {{-- <button class="btn btn-info btn-block mt-2" onclick="getUser()">Lấy thông tin người
                            dùng</button> --}}
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="http://127.0.0.1:8000/register" style="text-align: center">Đăng ký</a>
                                <a href="http://127.0.0.1:8000/forgot">Quên mật khẩu?</a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function login() {
            $.ajax({
                url: 'http://127.0.0.1:8000/api/auth/login',
                method: 'POST',
                data: {
                    email: $('#email').val(),
                    password: $('#password').val()
                },
                success: function(response) {
                    alert('Đăng nhập thành công!');
                    localStorage.setItem('access_token', response.access_token);
                    console.log(response);
                    // Chuyển hướng sang trang chủ sau khi đăng nhập thành công
                    window.location.href = 'http://127.0.0.1:8000/home'; // Đổi URL sang trang bạn muốn
                },
                error: function(error) {
                    alert('Đăng nhập thất bại!');
                    console.error(error.responseJSON);
                }
            });
        }





        // function getUser() {
        //     $.ajax({
        //         url: 'http://127.0.0.1:8000/api/me',
        //         method: 'POST',
        //         headers: {
        //             'Authorization': 'Bearer ' + localStorage.getItem('access_token')
        //         },
        //         success: function(response) {
        //             alert('Lấy thông tin người dùng thành công!');
        //             console.log(response);
        //         },
        //         error: function(error) {
        //             if (error.status === 401) {
        //                 // Token hết hạn, thử làm mới token
        //                 refreshToken().then(() => {
        //                     // Gọi lại hàm sau khi làm mới token
        //                     getUser();
        //                 }).catch(() => {
        //                     // Nếu làm mới thất bại, chuyển về trang đăng nhập
        //                     alert('Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.');
        //                     localStorage.removeItem('access_token');
        //                     window.location.href = 'http://127.0.0.1:8000/login';
        //                 });
        //             } else {
        //                 alert('Lỗi khi lấy thông tin người dùng!');
        //                 console.error(error.responseJSON);
        //             }
        //         }
        //     });
        // }

        // function refreshToken() {
        //     return new Promise((resolve, reject) => {
        //         $.ajax({
        //             url: 'http://127.0.0.1:8000/api/refresh',
        //             method: 'POST',
        //             headers: {
        //                 'Authorization': 'Bearer ' + localStorage.getItem('access_token')
        //             },
        //             success: function(response) {
        //                 localStorage.setItem('access_token', response.access_token);
        //                 resolve();
        //             },
        //             error: function(error) {
        //                 reject(error);
        //             }
        //         });
        //     });
        // }
    </script>
</body>

</html>
