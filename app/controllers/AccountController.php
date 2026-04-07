<?php
require_once('app/config/database.php');
require_once 'app/models/AccountModel.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/helpers/SecurityMiddleware.php';
require_once 'app/helpers/LogHelper.php';

class AccountController
{

    private $db;
    private $accountModel;

    public function __construct()
    {
        SessionHelper::start();

        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    // =========================    
    // FORM LOGIN
    // =========================
    public function login()
    {
        include 'app/views/account/login.php';
    }

    // =========================
    // XỬ LÝ LOGIN
    // =========================
    public function checklogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /account/login');
            exit;
        }

        SecurityMiddleware::verifyCSRF($_POST['csrf'] ?? '');

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            echo "❌ Vui lòng nhập đầy đủ thông tin";
            return;
        }

        $user = $this->accountModel->getAccountByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            SecurityMiddleware::resetAttempt();
            session_regenerate_id(true);

            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            LogHelper::log('LOGIN', 'Successful login');

            header("Location: /");
            exit;
        }

        SecurityMiddleware::increaseAttempt();
        LogHelper::log('LOGIN_FAILED', 'Failed login attempt for username: ' . $username);
        echo "❌ Sai tài khoản hoặc mật khẩu";
    }

    // =========================
    // LOGOUT
    // =========================
    public function logout()
    {
        LogHelper::log('LOGOUT', 'User logged out');
        SessionHelper::logout();
        header("Location: /");
        exit;
    }

    // =========================
    // REGISTER
    // =========================
    public function register()
    {
        $errors = [];
        include 'app/views/account/register.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /account/register');
            exit;
        }

        SecurityMiddleware::verifyCSRF($_POST['csrf'] ?? '');

        $username = trim($_POST['username'] ?? '');
        $fullname = trim($_POST['fullname'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmpassword'] ?? '';

        $errors = [];

        if ($username === '' || $fullname === '' || $password === '' || $confirmPassword === '') {
            $errors[] = 'Vui lòng nhập đầy đủ thông tin';
        }

        if (strlen($password) < 6) {
            $errors[] = 'Password phải >= 6 ký tự';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'Password và Confirm Password không khớp';
        }

        if ($this->accountModel->getAccountByUsername($username)) {
            $errors[] = 'Username đã tồn tại';
        }

        if (!empty($errors)) {
            include 'app/views/account/register.php';
            return;
        }

        if ($this->accountModel->save($username, $fullname, $password)) {
            LogHelper::log('REGISTER', 'New user registered: ' . $username);
            header('Location: /account/login');
            exit;
        }

        $errors[] = 'Đăng ký thất bại, vui lòng thử lại sau.';
        include 'app/views/account/register.php';
    }
}
