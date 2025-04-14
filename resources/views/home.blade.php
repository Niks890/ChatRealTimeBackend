<!-- resources/views/home.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ</title>
</head>
<body>
    <h2>Chào mừng đến với Trang Chủ</h2>
    <p>Đăng nhập thành công!</p>
    <p class="welcome"></p>
    <button onclick="logout()">Đăng xuất</button>
    {{-- <button class="btn btn-info btn-block mt-2" onclick="getUser()">Lấy thông tin người
        dùng</button> --}}

    <button onclick="changePassword()">Đổi mật khẩu</button>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function changePassword() {
            window.location.href = 'http://127.0.0.1:8000/reset';
        }

        function logout() {
            $.ajax({
                url: 'http://127.0.0.1:8000/api/logout',
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function(response) {
                    alert('Đăng xuất thành công!');
                    localStorage.removeItem('access_token');
                    window.location.href = 'http://127.0.0.1:8000/login'; // Quay lại trang đăng nhập
                },
                error: function(error) {
                    alert('Đăng xuất thất bại!');
                    console.error(error.responseJSON);
                }
            });
        }


        function getUser() {
            $.ajax({
                url: 'http://127.0.0.1:8000/api/me',
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function(response) {
                    // alert('Lấy thông tin người dùng thành công!');
                    // console.log(response);
                    $('.welcome').text('Xin chào, ' + response.name + '!');
                },
                error: function(error) {
                    if (error.status === 401) {
                        // Token hết hạn, thử làm mới token
                        refreshToken().then(() => {
                            // Gọi lại hàm sau khi làm mới token
                            getUser();
                        }).catch(() => {
                            // Nếu làm mới thất bại, chuyển về trang đăng nhập
                            alert('Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.');
                            localStorage.removeItem('access_token');
                            window.location.href = 'http://127.0.0.1:8000/login';
                        });
                    } else {
                        alert('Lỗi khi lấy thông tin người dùng!');
                        console.error(error.responseJSON);
                    }
                }
            });
        }

        function refreshToken() {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: 'http://127.0.0.1:8000/api/refresh',
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('access_token')
                    },
                    success: function(response) {
                        localStorage.setItem('access_token', response.access_token);
                        resolve();
                    },
                    error: function(error) {
                        reject(error);
                    }
                });
            });
        }
         // Gọi hàm lấy thông tin người dùng khi tải trang
         $(document).ready(function() {
            getUser();
        });
    </script>
</body>
</html>
