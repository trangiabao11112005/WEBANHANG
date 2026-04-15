<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                <strong>⚠️ Security Demo Mode</strong><br>
                This page is for <strong>educational and testing purposes only</strong>. 
                You can demonstrate security vulnerabilities by toggling the security system on and off in the <a href="/Admin/security">Admin Panel</a>.
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h2>Security System Demo</h2>
            <p>Learn how the security system protects against common web vulnerabilities.</p>
        </div>
    </div>

    <!-- Security Status Card -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-<?php echo $securityEnabled ? 'success' : 'danger'; ?>">
                <div class="card-body">
                    <h5 class="card-title">Security System Status</h5>
                    <p class="card-text mb-0">
                        Status: <strong class="<?php echo $securityEnabled ? 'text-success' : 'text-danger'; ?>">
                            <?php echo $securityEnabled ? '✅ ENABLED' : '❌ DISABLED'; ?>
                        </strong>
                    </p>
                    <p class="text-muted mb-0 mt-2 small">
                        <?php echo $securityEnabled 
                            ? 'Attacks will be detected and blocked.'
                            : 'Security protections are OFF. Vulnerable to attacks.'; 
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">How to Toggle Security</h5>
                    <p class="card-text small">
                        1. Go to <a href="/Admin/security" target="_blank">Admin Security Panel</a><br>
                        2. Click the button to toggle security ON/OFF<br>
                        3. Return here to test attacks
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Demo Tests -->
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="mb-4">Available Security Tests</h4>
        </div>
    </div>

    <div class="row g-4">
        <!-- SQL Injection Test -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">🔍 SQL Injection (SQLi)</h5>
                    <p class="card-text">Test how the system handles SQL injection attacks in login fields.</p>
                    <p class="small text-muted">
                        <strong>Examples:</strong><br>
                        • <code>' OR '1'='1</code><br>
                        • <code>admin' --</code><br>
                        • <code>' UNION SELECT *</code>
                    </p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="/Demo/sqli" class="btn btn-primary btn-sm w-100">Test SQLi</a>
                </div>
            </div>
        </div>

        <!-- XSS Test -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">📝 Cross-Site Scripting (XSS)</h5>
                    <p class="card-text">Test protection against JavaScript injection and XSS attacks.</p>
                    <p class="small text-muted">
                        <strong>Examples:</strong><br>
                        • <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code><br>
                        • <code>&lt;img src=x onerror=alert('XSS')&gt;</code><br>
                        • <code>javascript:alert('XSS')</code>
                    </p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="/Demo/xss" class="btn btn-primary btn-sm w-100">Test XSS</a>
                </div>
            </div>
        </div>

        <!-- CSRF Test -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">🔐 Cross-Site Request Forgery (CSRF)</h5>
                    <p class="card-text">Test CSRF token validation for form submissions.</p>
                    <p class="small text-muted">
                        <strong>How it works:</strong><br>
                        Forms require valid CSRF tokens. Invalid tokens are rejected.
                    </p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="/Demo/csrf" class="btn btn-primary btn-sm w-100">Test CSRF</a>
                </div>
            </div>
        </div>

        <!-- Brute Force Test -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">🔓 Brute Force Protection</h5>
                    <p class="card-text">Test login attempt rate limiting and account lockout.</p>
                    <p class="small text-muted">
                        <strong>Protection:</strong><br>
                        After 5 failed attempts, further login is blocked.
                    </p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="/Demo/bruteforce" class="btn btn-primary btn-sm w-100">Test Brute Force</a>
                </div>
            </div>
        </div>

        <!-- IP Blocking Test -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">📍 IP Blocking</h5>
                    <p class="card-text">Test automatic IP blocking after multiple attacks.</p>
                    <p class="small text-muted">
                        <strong>How it works:</strong><br>
                        IPs are auto-blocked after 10 attacks in 1 hour.
                    </p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="/Admin/security" class="btn btn-primary btn-sm w-100">View Blocked IPs</a>
                </div>
            </div>
        </div>

        <!-- Logs Test -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">📊 Attack Logs</h5>
                    <p class="card-text">View detailed logs of all detected and blocked attacks.</p>
                    <p class="small text-muted">
                        <strong>Includes:</strong><br>
                        • Attack type and timestamp<br>
                        • Source IP address<br>
                        • Attack payload
                    </p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="/Admin/security" class="btn btn-primary btn-sm w-100">View Logs</a>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="mb-3">How to Use This Demo</h4>
            <div class="card">
                <div class="card-body">
                    <ol>
                        <li>
                            <strong>Turn OFF Security:</strong> Go to Admin → Security Panel and click "Disable Security System"
                        </li>
                        <li>
                            <strong>Test an Attack:</strong> Click on a test above (e.g., SQL Injection) and try the payload examples
                        </li>
                        <li>
                            <strong>Observe the Vulnerability:</strong> You'll see the injection succeeds without protection
                        </li>
                        <li>
                            <strong>Turn ON Security:</strong> Go back to Admin → Security Panel and click "Enable Security System"
                        </li>
                        <li>
                            <strong>Test Again:</strong> Try the same attack payload - it will be blocked!
                        </li>
                        <li>
                            <strong>Review Logs:</strong> Check the Admin Panel to see blocked attacks and suspicious IPs
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Features Overview -->
    <div class="row mt-4 mb-4">
        <div class="col-12">
            <h4 class="mb-3">Security Features Protected</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action">
                    <strong>✅ SQL Injection Detection</strong><br>
                    <small>Detects SQL keywords and dangerous patterns</small>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <strong>✅ XSS Attack Prevention</strong><br>
                    <small>Blocks script tags and event handlers</small>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <strong>✅ CSRF Token Validation</strong><br>
                    <small>Ensures all forms have valid tokens</small>
                </a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action">
                    <strong>✅ Brute Force Protection</strong><br>
                    <small>Limits failed login attempts per session</small>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <strong>✅ Rate Limiting</strong><br>
                    <small>Tracks request rates by IP address</small>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <strong>✅ Attack Logging</strong><br>
                    <small>Records all detected attacks for audit</small>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
