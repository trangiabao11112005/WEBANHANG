<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2>🔍 SQL Injection (SQLi) Attack Test</h2>
            <p class="lead">Learn how SQL injection works and how the security system protects against it.</p>
        </div>
    </div>

    <!-- Status Alert -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-<?php echo $securityEnabled ? 'success' : 'danger'; ?>" role="alert">
                <strong>Security Status: <?php echo $securityEnabled ? '✅ ENABLED (Protected)' : '❌ DISABLED (Vulnerable)'; ?></strong><br>
                <?php echo $securityEnabled 
                    ? 'The system is PROTECTED against SQL injection attacks.'
                    : 'The system is VULNERABLE to SQL injection attacks. Dangerous payloads will be executed!'
                ?>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-6">
            <!-- Test Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Test SQL Injection</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username or SQL payload">
                            <small class="form-text text-muted mt-2">
                                Try these payloads:
                                <br>
                                <code>' OR '1'='1</code> - Bypass login
                                <br>
                                <code>admin' --</code> - Comment out password check
                                <br>
                                <code>' UNION SELECT *</code> - Extract data
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary">Search User</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <!-- Explanation -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">What is SQL Injection?</h5>
                </div>
                <div class="card-body">
                    <p><strong>SQL Injection</strong> is an attack where malicious SQL code is inserted into input fields to manipulate database queries.</p>
                    
                    <h6>Common Payloads:</h6>
                    <ul>
                        <li><code>' OR '1'='1</code> - Always True condition</li>
                        <li><code>admin' --</code> - Comment out remaining query</li>
                        <li><code>'; DROP TABLE users; --</code> - Delete entire table</li>
                    </ul>

                    <h6 class="mt-4">Security Protection:</h6>
                    <ul>
                        <li>✅ Prepared Statements - Separate code from data</li>
                        <li>✅ Input Validation - Check for SQL keywords</li>
                        <li>✅ Query Monitoring - Detect suspicious patterns</li>
                        <li>✅ Error Handling - Don't expose database errors</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Results -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-<?php echo $securityEnabled ? 'warning' : 'danger'; ?>">
                <div class="card-header bg-<?php echo $securityEnabled ? 'warning' : 'danger'; ?> text-white">
                    <h5 class="mb-0">Test Result</h5>
                </div>
                <div class="card-body">
                    <p><strong>Input Received:</strong> <code><?php echo htmlspecialchars($_POST['username']); ?></code></p>
                    
                    <?php if ($securityEnabled): ?>
                        <div class="alert alert-warning" role="alert">
                            <strong>🛡️ Attack Blocked!</strong><br>
                            The security system detected this as a potential SQL injection attack and blocked it.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger" role="alert">
                            <strong>⚠️ Vulnerable!</strong><br>
                            Without security protections, dangerous SQL could be executed. This is why security is critical!
                        </div>
                    <?php endif; ?>

                    <?php if ($result && !$securityEnabled): ?>
                        <h6 class="mt-3">Query Results:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($result as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($row['role'] ?? ''); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Demo Instructions -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">How to Run This Demo</h5>
                </div>
                <div class="card-body">
                    <h6>Step 1: With Security DISABLED (Vulnerable)</h6>
                    <ol>
                        <li>Make sure security is OFF (check the status above)</li>
                        <li>Paste one of the payloads into the username field</li>
                        <li>Click "Search User" - the injection will succeed</li>
                        <li>Notice how you can access data without proper credentials</li>
                    </ol>

                    <h6 class="mt-4">Step 2: Turn Security ON</h6>
                    <ol>
                        <li>Go to <a href="/Admin/security" target="_blank">Admin → Security Panel</a></li>
                        <li>Click "Enable Security System"</li>
                        <li>Return to this page</li>
                    </ol>

                    <h6 class="mt-4">Step 3: With Security ENABLED (Protected)</h6>
                    <ol>
                        <li>Try the same payload again</li>
                        <li>The attack will be blocked</li>
                        <li>Check the admin panel to see it logged as an attack</li>
                    </ol>
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
