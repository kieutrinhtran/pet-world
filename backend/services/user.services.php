<?php
require_once __DIR__.'/../models/user.model.php';
class UserService {
    // Đăng ký
    public static function register($user_name, $password, $re_password) {
        if (!$user_name || !$password || !$re_password) {
            return ['success'=>false, 'message'=>'Vui lòng nhập đầy đủ thông tin!'];
        }
        if ($password !== $re_password) {
            return ['success'=>false, 'message'=>'Mật khẩu không khớp!'];
        }
        if (UserModel::getByUsername($user_name)) {
            return ['success'=>false, 'message'=>'Tên đăng nhập đã tồn tại!'];
        }
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $success = UserModel::register($user_name, $password_hash);
        return $success
            ? ['success'=>true, 'message'=>'Đăng ký thành công!']
            : ['success'=>false, 'message'=>'Đăng ký thất bại, vui lòng thử lại!'];
    }

    // Đăng nhập
    public static function login($user_name, $password) {
        if (!$user_name || !$password) {
            return ['success'=>false, 'message'=>'Vui lòng nhập đầy đủ thông tin!'];
        }
        $user = UserModel::getByUsername($user_name);
        if (!$user) return ['success'=>false, 'message'=>'Sai tên đăng nhập hoặc mật khẩu!'];
        if (!password_verify($password, $user['password'])) {
            return ['success'=>false, 'message'=>'Sai tên đăng nhập hoặc mật khẩu!'];
        }
        if ($user['status'] !== 'active') {
            return ['success'=>false, 'message'=>'Tài khoản đã bị khóa hoặc tạm ngưng!'];
        }
        // Có thể lưu session ở đây nếu muốn
        return ['success'=>true, 'message'=>'Đăng nhập thành công!', 'user'=>$user];
    }

    // Đổi mật khẩu
    public static function changePassword($user_name, $current_password, $new_password, $re_password) {
        if (!$user_name || !$current_password || !$new_password || !$re_password) {
            return ['success'=>false, 'message'=>'Vui lòng nhập đầy đủ thông tin!'];
        }
        $user = UserModel::getByUsername($user_name);
        if (!$user) return ['success'=>false, 'message'=>'Tài khoản không tồn tại!'];
        if (!password_verify($current_password, $user['password'])) {
            return ['success'=>false, 'message'=>'Mật khẩu hiện tại không đúng!'];
        }
        if ($new_password !== $re_password) {
            return ['success'=>false, 'message'=>'Mật khẩu mới không khớp!'];
        }
        if ($current_password === $new_password) {
            return ['success'=>false, 'message'=>'Mật khẩu mới không được trùng với mật khẩu hiện tại!'];
        }
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $success = UserModel::updatePassword($user_name, $new_password_hash);
        return $success
            ? ['success'=>true, 'message'=>'Đổi mật khẩu thành công!']
            : ['success'=>false, 'message'=>'Lỗi hệ thống, vui lòng thử lại!'];
    }
}
