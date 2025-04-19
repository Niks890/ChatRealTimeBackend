<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthController extends Controller
{

    // public function register(UserRegisterRequest $request)
    // {
    //     $validateData = $request->validated();

    //     DB::beginTransaction();

    //     try {
    //         $user = User::create([
    //             'name' => $validateData['name'],
    //             'email' => $validateData['email'],
    //             'password' => bcrypt($validateData['password']),
    //         ]);
    //         // Gán quyền mặc định, ví dụ role "user"
    //         $defaultRoleId = Role::where('name', 'user')->value('id');

    //         if ($defaultRoleId) {
    //             DB::table('user_roles')->insert([
    //                 'user_id' => $user->id,
    //                 'role_id' => $defaultRoleId,
    //             ]);
    //         }
    //         DB::commit();
    //         // Nếu bạn đang dùng JWT Auth
    //         $token = auth('api')->login($user);
    //         return $this->respondWithToken($token);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => 'Đăng ký thất bại',
    //             'error' => $e->getMessage(), // Lỗi chính
    //             'trace' => $e->getTraceAsString(), // Stack trace để kiểm tra chi tiết lỗi
    //         ], 500);
    //     }
    // }



    public function register(UserRegisterRequest $request)
    {
        $validateData = $request->validated();

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $validateData['name'],
                'email' => $validateData['email'],
                'password' => bcrypt($validateData['password']),
            ]);

            // Gán role mặc định
            $defaultRoleId = Role::where('name', 'admin')->value('id');
            if ($defaultRoleId) {
                DB::table('user_roles')->insert([
                    'user_id' => $user->id,
                    'role_id' => $defaultRoleId,
                ]);
            }

            DB::commit();

            // Gửi email xác minh
            event(new Registered($user));


            return response()->json([
                'message' => 'Đăng ký thành công! Vui lòng kiểm tra email để xác minh tài khoản.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Đăng ký thất bại',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
        ], [
            'email.exists' => 'Email không tồn tại.',
            'email.required' => 'Email không được bỏ trống.',
            'password.required' => 'Mật khẩu không được bỏ trống.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Sai email hoặc mật khẩu!'], 401);
        }

        // Đặt thời gian sống của cookie (10p)
        $cookieLifetime = 10;

        $accessTokenCookie = cookie(
            'access_token',
            $token,
            $cookieLifetime,
            '/',
            'localhost',  // Đặt domain là 127.0.0.1
            // null,
            false,        // Không bắt buộc HTTPS
            true,         // HttpOnly
            false,        // Không ép buộc sử dụng cùng miền
            'Lax'         // SameSite
        );
        // if (! $user->hasVerifiedEmail()) {
        //     return response()->json(['message' => 'Email chưa xác minh'], 403);
        // }
        return response()->json([
            'message' => 'Login success',
        ])->withCookie($accessTokenCookie);
    }



    public function me()
    {
        $user = User::with('roles')->find(auth('api')->id());
        return response()->json($user);
    }

    public function logout()
    {
        auth()->logout();
        // auth()->user()->update(['status' => 'offline']);
        // Xóa cookie access_token
        $forgetCookie = cookie()->forget('access_token');
        return response()->json(['message' => 'Successfully logged out'])->withCookie($forgetCookie);
    }




    public function refresh()
    {
        return $this->respondWithToken(auth('api')->$this->refresh());
    }


    // protected function respondWithToken($token)
    // {
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => auth('api')->Config::get('jwt.ttl') * 60
    //     ])->header('Authorization', 'Bearer ' . $token);
    // }


    // protected function respondWithToken($token)
    // {
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => auth('api')->factory()->getTTL() * 1
    //         // 'expires_in' => auth()->factory()->getTTL() * 60
    //     ]);
    // }



    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Kiểm tra email có tồn tại không
        $user = DB::table('users')->where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email không tồn tại!'], 404);
        }

        // Tạo token reset
        $token = Str::random(60);
        $email = $request->email;

        // Lưu vào database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        return response()->json(['message' => 'Yêu cầu đặt lại mật khẩu thành công!', 'token' => $token, 'email' => $email]);
    }


    public function resetPassword(Request $request)
    {
        // Nếu đã đăng nhập, chỉ cần xác thực mật khẩu mới
        if (auth()->check()) {
            $request->validate([
                'password' => 'required|confirmed|min:6',
            ]);

            // Đổi mật khẩu cho người dùng đã đăng nhập
            $user = auth()->user();
            $user->this->update(['password' => Hash::make($request->password)]);

            // Đăng xuất sau khi đổi mật khẩu
            auth()->logout();

            return response()->json(['message' => 'Đổi mật khẩu thành công! Vui lòng đăng nhập lại.']);
        }

        // Nếu chưa đăng nhập (quên mật khẩu), xử lý như cũ
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        // Tìm token trong cơ sở dữ liệu
        $reset = DB::table('password_reset_tokens')->where('email', $request->email)->where('token', $request->token)->first();

        if (!$reset) {
            return response()->json(['message' => 'Token không hợp lệ hoặc đã hết hạn!'], 400);
        }

        // Đặt lại mật khẩu
        DB::table('users')->where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        // Xóa token sau khi đổi mật khẩu thành công
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Đặt lại mật khẩu thành công!']);
    }
}
