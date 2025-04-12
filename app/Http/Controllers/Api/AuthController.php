<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Http\Controllers\Controller;
class AuthController extends Controller
{
    // دالة لتسجيل الدخول
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // إنشاء التوكن
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,  // تأكد من إرجاع التوكن
        ]);
    }

    // دالة لاستعادة كلمة المرور
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent to your email.'])
            : response()->json(['message' => 'Failed to send reset link.'], 400);
    }

    // دالة لإعادة تعيين كلمة المرور
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => bcrypt($password)])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password has been reset successfully.'])
            : response()->json(['message' => 'Failed to reset password.'], 400);
    }
    // دالة لتسجيل الخروج
public function logout(Request $request)
{
    // الحصول على التوكن الحالي للمستخدم
    $request->user()->currentAccessToken()->delete();

    // إرجاع استجابة تؤكد تسجيل الخروج بنجاح
    return response()->json(['message' => 'Successfully logged out']);
}

}



