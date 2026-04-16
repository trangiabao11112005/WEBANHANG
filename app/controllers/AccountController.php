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
        $securityEnabled = SecurityMiddleware::isSecurityEnabled();
        $message = '';
        $attemptUser = '';
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
        $securityEnabled = SecurityMiddleware::isSecurityEnabled();
        $message = '';
        $attemptUser = $username;

        if ($username === '' || $password === '') {
            $message = "❌ Vui lòng nhập đầy đủ thông tin";
            include 'app/views/account/login.php';
            return;
        }

        if ($securityEnabled) {
            $user = $this->accountModel->getAccountByUsername($username);
        } else {
            // VULNERABLE login path for demonstration when security is disabled
            $query = "SELECT * FROM account WHERE username = '" . $username . "' LIMIT 0,1";
            try {
                $stmt = $this->db->query($query);
                $user = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
            } catch (PDOException $e) {
                echo "❌ SQL Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "<br>";
                echo "<strong>Query:</strong> " . htmlspecialchars($query, ENT_QUOTES, 'UTF-8');
                return;
            }
        }

        if ($user && ($securityEnabled ? password_verify($password, $user['password']) : true)) {
            SecurityMiddleware::resetAttempt();
            session_regenerate_id(true);

            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            LogHelper::log('LOGIN', 'Đăng nhập thành công');

            header("Location: /");
            exit;
        }

        SecurityMiddleware::increaseAttempt();
        LogHelper::log('LOGIN_FAILED', 'Cố găng đăng nhập thất bại cho tên tài khoản: ' . $username);
        $message = "❌ Sai tài khoản hoặc mật khẩu";
        include 'app/views/account/login.php';
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
            $errors[] = 'Mật khẩu phải có >= 6 ký tự';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'Mật khẩu và Xác nhẫn mật khẩu không khỊ';
        }

        if ($this->accountModel->getAccountByUsername($username)) {
            $errors[] = 'Tên tài khoản đã tồn tại';
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
