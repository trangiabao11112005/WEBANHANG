<?php
class SessionHelper {

// Khởi động session nếu chưa bắt đầu
public static function start(){
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
}

// Kiểm tra đã đăng nhập chưa
public static function isLoggedIn(){
    self::start();
    return isset($_SESSION['username']);
}

// Kiểm tra admin
public static function isAdmin(){
    self::start();
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Kiểm tra user thường
public static function isUser(){
    self::start();
    return isset($_SESSION['role']) && $_SESSION['role'] === 'user';
}

// Lấy username
public static function getUsername(){
    self::start();
    return $_SESSION['username'] ?? null;
}

// Lấy role
public static function getRole(){
    self::start();
    return $_SESSION['role'] ?? 'guest';
}

// Logout
public static function logout(){
    self::start();
    session_unset();
    session_destroy();
}

}
?>