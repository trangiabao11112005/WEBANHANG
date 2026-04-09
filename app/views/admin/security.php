<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2>Bảng điều khển Bảo mật</h2>
            <p>Giám sát các mối đe dọa bảo mật và quản lý các địa chỉ IP bị chặn.</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div>
                        <h5 class="card-title">Trạng thái hệ thống bảo mật</h5>
                        <p class="card-text mb-0">Hệ thống bảo mật hiện đang: <strong><?php echo $securityEnabled ? 'BẬT' : 'TẮT'; ?></strong></p>
                        <p class="text-muted mb-0">Chỉ quản trị viên có thể bật hoặc tắt tính năng này.</p>
                    </div>
                    <button id="toggle-security" class="btn <?php echo $securityEnabled ? 'btn-danger' : 'btn-success'; ?> mt-3 mt-md-0" data-enabled="<?php echo $securityEnabled ? '0' : '1'; ?>">
                        <?php echo $securityEnabled ? 'Tắt hệ thống bảo mật' : 'Bật hệ thống bảo mật'; ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Tổng số tấn công</h5>
                    <p class="card-text display-4"><?php echo $securityStats['total_attacks']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Tấn công theo loại</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Loại</th>
                                <th>Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($securityStats['attacks_by_type'] as $type => $count): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($type); ?></td>
                                    <td><?php echo $count; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Tấn công theo IP</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Địa chỉ IP</th>
                                <th>Số lượng</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($securityStats['attacks_by_ip'] as $ip => $count): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ip); ?></td>
                                    <td><?php echo $count; ?></td>
                                    <td>
                                        <?php if (in_array($ip, $securityStats['blocked_ips'])): ?>
                                            <button class="btn btn-success btn-sm unblock-ip" data-ip="<?php echo htmlspecialchars($ip); ?>">Bỏ chặn</button>
                                        <?php else: ?>
                                            <button class="btn btn-danger btn-sm block-ip" data-ip="<?php echo htmlspecialchars($ip); ?>">Chặn</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Các IP bị chặn</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($securityStats['blocked_ips'] as $ip): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($ip); ?>
                                <button class="btn btn-success btn-sm unblock-ip" data-ip="<?php echo htmlspecialchars($ip); ?>">Bỏ chặn</button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Tài khoản người dùng</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tên tài khoản</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($accounts as $account): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($account['username']); ?></td>
                                    <td><?php echo htmlspecialchars($account['role']); ?></td>
                                    <td>
                                        <?php if (in_array($account['username'], $securityStats['blocked_accounts'])): ?>
                                            <span class="badge badge-danger">Bị chặn</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Hoạt động</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (in_array($account['username'], $securityStats['blocked_accounts'])): ?>
                                            <button class="btn btn-success btn-sm unblock-account" data-username="<?php echo htmlspecialchars($account['username']); ?>">Bỏ chặn</button>
                                        <?php else: ?>
                                            <button class="btn btn-danger btn-sm block-account" data-username="<?php echo htmlspecialchars($account['username']); ?>">Chặn</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Block IP
    document.querySelectorAll('.block-ip').forEach(button => {
        button.addEventListener('click', function() {
            const ip = this.getAttribute('data-ip');
            fetch('/Admin/blockIp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'ip=' + encodeURIComponent(ip)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        });
    });

    // Unblock IP
    document.querySelectorAll('.unblock-ip').forEach(button => {
        button.addEventListener('click', function() {
            const ip = this.getAttribute('data-ip');
            fetch('/Admin/unblockIp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'ip=' + encodeURIComponent(ip)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        });
    });

    // Block Account
    document.querySelectorAll('.block-account').forEach(button => {
        button.addEventListener('click', function() {
            const username = this.getAttribute('data-username');
            fetch('/Admin/blockAccount', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'username=' + encodeURIComponent(username)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        });
    });

    // Unblock Account
    document.querySelectorAll('.unblock-account').forEach(button => {
        button.addEventListener('click', function() {
            const username = this.getAttribute('data-username');
            fetch('/Admin/unblockAccount', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'username=' + encodeURIComponent(username)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        });
    });

    const toggleButton = document.getElementById('toggle-security');
    if (toggleButton) {
        toggleButton.addEventListener('click', function() {
            const enabled = this.getAttribute('data-enabled');
            fetch('/Admin/toggleSecurity', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'enabled=' + encodeURIComponent(enabled)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        });
    }
});
</script>

<?php include 'app/views/shares/footer.php'; ?>