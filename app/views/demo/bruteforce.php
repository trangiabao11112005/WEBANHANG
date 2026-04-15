<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2>🔓 Brute Force Attack Protection Test</h2>
            <p class="lead">Learn how applications protect against repeated login attempts and brute force attacks.</p>
        </div>
    </div>

    <!-- Status Alert -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-<?php echo $securityEnabled ? 'success' : 'danger'; ?>" role="alert">
                <strong>Security Status: <?php echo $securityEnabled ? '✅ ENABLED (Protected)' : '❌ DISABLED (Vulnerable)'; ?></strong><br>
                <?php echo $securityEnabled 
                    ? 'The system limits failed login attempts and blocks after 5 failures.'
                    : 'The system allows unlimited login attempts. Attackers can brute force passwords!'
                ?>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-6">
            <!-- Explanation -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">What is Brute Force?</h5>
                </div>
                <div class="card-body">
                    <p><strong>Brute Force Attack</strong> - Attacker tries many password combinations until finding the correct one.</p>
                    
                    <h6>How Brute Force Works:</h6>
                    <ol>
                        <li>Attacker gets a user's username</li>
                        <li>Tries thousands of common passwords</li>
                        <li>If passwords are weak, accounts get compromised</li>
                        <li>Without rate limiting, attacker can try 1000s per minute!</li>
                    </ol>

                    <h6 class="mt-4">Current Attempt Count: <strong class="text-danger"><?php echo $attempts; ?>/5</strong></h6>
                    <?php if ($attempts >= 5): ?>
                        <div class="alert alert-danger mt-2 mb-0">
                            Account temporarily locked due to too many failed attempts.
                        </div>
                    <?php endif; ?>

                    <h6 class="mt-4">Protection Methods:</h6>
                    <ul>
                        <li>✅ Attempt Limiting - Allow max 5 failed attempts</li>
                        <li>✅ Lockout Period - Lock account for 15-30 minutes</li>
                        <li>✅ Progressive Delays - Increase delay between attempts</li>
                        <li>✅ IP-based Blocking - Block IPs after multiple failures</li>
                        <li>✅ CAPTCHA - Require human verification</li>
                        <li>✅ Strong Password Policy - Require complex passwords</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <!-- Test Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Simulate Login Attempts</h5>
                </div>
                <div class="card-body">
                    <?php if ($attempts < 5): ?>
                        <form method="POST" action="/Account/testLogin">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="admin" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Try any password">
                                <small class="text-muted">Security is <?php echo $securityEnabled ? 'ON' : 'OFF'; ?></small>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-danger" role="alert">
                            <h5>🔒 Account Locked</h5>
                            <p>Maximum login attempts exceeded. Account is temporarily locked.</p>
                            <button class="btn btn-warning btn-sm" onclick="location.reload()">Reset Demo</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Attack Simulation -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Attack Timeline (Without Protection)</h5>
                </div>
                <div class="card-body small">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Password Tried</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>00:00</td>
                                <td>password123</td>
                                <td>❌ Failed</td>
                            </tr>
                            <tr>
                                <td>00:01</td>
                                <td>123456</td>
                                <td>❌ Failed</td>
                            </tr>
                            <tr>
                                <td>00:02</td>
                                <td>admin</td>
                                <td>❌ Failed</td>
                            </tr>
                            <tr>
                                <td>...(many more tries)...</td>
                                <td>...</td>
                                <td>...</td>
                            </tr>
                            <tr>
                                <td>45:30</td>
                                <td>correct_password</td>
                                <td>✅ Success</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Brute Force Statistics</h5>
                </div>
                <div class="card-body">
                    <p><strong>Time to guess 8-char password:</strong></p>
                    <ul class="small mb-0">
                        <li>With unlimited attempts: ~45 minutes</li>
                        <li>With 5 attempts/hour limit: ~1 month</li>
                        <li>With IP blocking: Practically impossible</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Best Practices</h5>
                </div>
                <div class="card-body small mb-0">
                    <ul class="mb-0">
                        <li>Use strong passwords (12+ characters)</li>
                        <li>Enable 2-factor authentication</li>
                        <li>Use a password manager</li>
                        <li>Never reuse passwords</li>
                        <li>Monitor for unauthorized access</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Protection Methods -->
    <div class="row mt-4">
        <div class="col-12">
            <h4 class="mb-3">Brute Force Protection Features</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">1. Attempt Limiting</h6>
                </div>
                <div class="card-body small">
                    <p>Maximum 5 failed login attempts per session before temporary lockout.</p>
                    <code>if (attempts > 5) block_login();</code>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">2. Account Lockout</h6>
                </div>
                <div class="card-body small">
                    <p>After multiple failures, temporarily lock the account to prevent further attempts.</p>
                    <code>lock_account(duration=15min);</code>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">3. Exponential Backoff</h6>
                </div>
                <div class="card-body small">
                    <p>Increase delay between attempts: 1s, 2s, 4s, 8s, 16s...</p>
                    <code>delay = 2^attempt_count;</code>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">4. CAPTCHA on Failure</h6>
                </div>
                <div class="card-body small">
                    <p>Require CAPTCHA verification after multiple failed attempts.</p>
                    <code>if (attempts > 3) require_captcha();</code>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">5. 2-Factor Authentication</h6>
                </div>
                <div class="card-body small">
                    <p>Even with correct password, require second verification factor (SMS, Email, etc).</p>
                    <code>verify_otp_code();</code>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">6. Logging & Alerts</h6>
                </div>
                <div class="card-body small">
                    <p>Log all failed attempts and alert on suspicious patterns.</p>
                    <code>log_attempt(); alert_admin();</code>
                </div>
            </div>
        </div>
    </div>

    <!-- How Security Works -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">How This Demo Works</h5>
                </div>
                <div class="card-body">
                    <h6>With Security DISABLED:</h6>
                    <ul>
                        <li>No attempt limiting</li>
                        <li>Unlimited login tries</li>
                        <li>No lockout or delays</li>
                        <li>Vulnerable to brute force</li>
                    </ul>

                    <h6 class="mt-3">With Security ENABLED:</h6>
                    <ul>
                        <li>Maximum 5 failed attempts per session</li>
                        <li>Session gets locked temporarily</li>
                        <li>IP gets logged and tracked</li>
                        <li>Protected against brute force</li>
                    </ul>

                    <div class="alert alert-info mt-3 mb-0">
                        <strong>Try it:</strong> Try logging in 5 times with wrong passwords when security is ON - you'll see the protection in action!
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 mb-4">
        <div class="col-12">
            <a href="/Demo" class="btn btn-secondary">← Back to Demo Home</a>
            <a href="/Admin/security" class="btn btn-primary">Go to Admin Panel</a>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
