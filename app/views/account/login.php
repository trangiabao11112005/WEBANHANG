<?php include 'app/views/shares/header.php'; ?>

<section class="vh-100 d-flex align-items-center" style="background-color: #f2f2f5;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">Đăng nhập</h3>
                            <p class="text-muted mb-0">Nhập tài khoản và mật khẩu để tiếp tục.</p>
                        </div>

                        <?php if ($message !== ''): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!SecurityMiddleware::isSecurityEnabled()): ?>
                            <div class="alert alert-warning" role="alert">
                                <strong>Cảnh báo:</strong> Bảo mật đang tắt. Mẫu đăng nhập này dễ bị SQLi và XSS.
                                <br>
                                Thử: <code>admin' OR '1'='1</code> hoặc <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code>
                            </div>
                        <?php endif; ?>

                        <form action="/account/checklogin" method="post" autocomplete="off">
                            <input type="hidden" name="csrf" value="<?= SecurityMiddleware::generateCSRF(); ?>">

                            <div class="mb-3">
                                <label for="usernameInput" class="form-label">Tên tài khoản</label>
                                <input id="usernameInput" type="text" name="username" class="form-control form-control-lg" placeholder="Tên tài khoản" value="<?php echo htmlspecialchars($attemptUser ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="passwordInput" class="form-label">Mật khẩu</label>
                                <input id="passwordInput" type="password" name="password" class="form-control form-control-lg" placeholder="Mật khẩu">
                            </div>

                            <?php if (!SecurityMiddleware::isSecurityEnabled() && !empty($attemptUser)): ?>
                                <div class="alert alert-secondary" role="alert">
                                    <strong>Unsafe reflection:</strong>
                                    <div id="unsafe-login-reflection" class="mt-2"></div>
                                </div>
                                <script>
                                    var div = document.getElementById('unsafe-login-reflection');
                                    div.innerHTML = <?php echo json_encode($attemptUser); ?>;
                                    var scripts = div.getElementsByTagName('script');
                                    for (var i = 0; i < scripts.length; i++) {
                                        eval(scripts[i].textContent);
                                    }
                                </script>
                            <?php endif; ?>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <a href="#!" class="text-decoration-none">Quên mật khẩu?</a>
                                <button type="submit" class="btn btn-primary btn-lg">Đăng nhập</button>
                            </div>
                        </form>

                        <div class="text-center pt-3 border-top">
                            <p class="mb-2 text-muted">Chưa có tài khoản? <a href="/account/register" class="text-decoration-none">Đăng ký</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'app/views/shares/footer.php'; ?>