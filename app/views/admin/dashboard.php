<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2>Dashboard Admin</h2>
            <p>Chào mừng quản trị viên, đây là bảng điều khiển quản lý.</p>
            <a href="/Admin/security" class="btn btn-warning mb-3">
                <i class="fa-solid fa-shield-alt"></i> Security Dashboard
            </a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Sản phẩm</h5>
                    <p class="card-text display-4"><?= $stats['products']; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Danh mục</h5>
                    <p class="card-text display-4"><?= $stats['categories']; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Đơn hàng</h5>
                    <p class="card-text display-4"><?= $stats['orders']; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h5 class="card-title">Người dùng</h5>
                    <p class="card-text display-4"><?= $stats['users']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card border-secondary">
                <div class="card-body">
                    <h5 class="card-title">Quản trị viên</h5>
                    <p class="card-text"><?= $stats['admins']; ?> tài khoản admin</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Activity Logs</h5>
                </div>
                <div class="card-body">
                    <div id="logs-container" style="max-height: 400px; overflow-y: auto;">
                        <?php if (!empty($logs)): ?>
                            <table class="table table-striped table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Timestamp</th>
                                        <th>IP</th>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody id="logs-table-body">
                                    <?php foreach ($logs as $log): ?>
                                        <?php
                                        // Parse log line: "timestamp | IP: ip | User: user | Action: action | Details: details"
                                        $parts = explode(' | ', $log);
                                        $timestamp = $parts[0] ?? '';
                                        $ip = str_replace('IP: ', '', $parts[1] ?? '');
                                        $user = str_replace('User: ', '', $parts[2] ?? '');
                                        $action = str_replace('Action: ', '', $parts[3] ?? '');
                                        $details = str_replace('Details: ', '', $parts[4] ?? '');
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($timestamp); ?></td>
                                            <td><?php echo htmlspecialchars($ip); ?></td>
                                            <td><?php echo htmlspecialchars($user); ?></td>
                                            <td><?php echo htmlspecialchars($action); ?></td>
                                            <td><?php echo htmlspecialchars($details); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No logs available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateLogs() {
    fetch('/Admin/getLogsAjax')
        .then(response => response.json())
        .then(logs => {
            const tbody = document.getElementById('logs-table-body');
            tbody.innerHTML = '';
            logs.forEach(log => {
                const parts = log.split(' | ');
                const timestamp = parts[0] || '';
                const ip = (parts[1] || '').replace('IP: ', '');
                const user = (parts[2] || '').replace('User: ', '');
                const action = (parts[3] || '').replace('Action: ', '');
                const details = (parts[4] || '').replace('Details: ', '');
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${timestamp}</td>
                    <td>${ip}</td>
                    <td>${user}</td>
                    <td>${action}</td>
                    <td>${details}</td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching logs:', error));
}

// Update logs every 5 seconds
setInterval(updateLogs, 5000);

// Initial load is already there
</script>

<?php include 'app/views/shares/footer.php'; ?>
