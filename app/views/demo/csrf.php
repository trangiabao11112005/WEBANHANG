<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2>🔐 Cross-Site Request Forgery (CSRF) Attack Test</h2>
            <p class="lead">Learn how CSRF attacks work and how CSRF tokens protect your application.</p>
        </div>
    </div>

    <!-- Status Alert -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-<?php echo $securityEnabled ? 'success' : 'danger'; ?>" role="alert">
                <strong>Security Status: <?php echo $securityEnabled ? '✅ ENABLED (Protected)' : '❌ DISABLED (Vulnerable)'; ?></strong><br>
                <?php echo $securityEnabled 
                    ? 'The system REQUIRES CSRF tokens for all forms.'
                    : 'The system does NOT require CSRF tokens. Forms are vulnerable to CSRF attacks!'
                ?>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-6">
            <!-- Safe Form with CSRF Token -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Form WITH CSRF Token (Safe)</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="action1" class="form-label">Action</label>
                            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf_token); ?>">
                            <input type="text" class="form-control" id="action1" name="action" placeholder="Change email, Transfer funds, etc." value="">
                        </div>
                        <button type="submit" class="btn btn-success">Submit Safe Form</button>
                    </form>
                    <div class="alert alert-success mt-3 mb-0" role="alert">
                        <small>✅ This form includes a CSRF token. It will only work if you submitted it from this page.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <!-- Explanation -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">What is CSRF?</h5>
                </div>
                <div class="card-body">
                    <p><strong>Cross-Site Request Forgery (CSRF)</strong> tricks users into performing unwanted actions on another website where they're logged in.</p>
                    
                    <h6>How CSRF Works:</h6>
                    <ol>
                        <li>User logs into their bank website</li>
                        <li>User visits a malicious website while still logged in</li>
                        <li>The malicious site sends a request to the bank (e.g., transfer money)</li>
                        <li>Because user is logged in, the bank executes the request!</li>
                    </ol>

                    <h6 class="mt-4">Protection:</h6>
                    <ul>
                        <li>✅ CSRF Tokens - Unique tokens per request</li>
                        <li>✅ SameSite Cookies - Restrict cookie scope</li>
                        <li>✅ HTTP-Only Cookies - Prevent JavaScript access</li>
                        <li>✅ Origin Validation - Check request source</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Attack Scenario -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">⚠️ CSRF Attack Scenario</h5>
                </div>
                <div class="card-body">
                    <p><strong>How an attacker could exploit missing CSRF protection:</strong></p>
                    <ol>
                        <li>
                            <strong>Attacker creates malicious webpage:</strong>
                            <pre><code>&lt;!-- Malicious attacker's website --&gt;
&lt;html&gt;
&lt;body onload="document.csrf.submit()"&gt;
&lt;form name="csrf" action="<?php echo htmlspecialchars($_SERVER['HTTP_HOST']); ?>/Admin/settings" method="POST"&gt;
    &lt;input type="hidden" name="change" value="admin_email"&gt;
    &lt;input type="hidden" name="value" value="attacker@evil.com"&gt;
&lt;/form&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
                        </li>
                        <li>
                            <strong>Attacker tricks you into visiting:</strong> Sends you a link disguised as something interesting
                        </li>
                        <li>
                            <strong>Form auto-submits:</strong> The hidden form submits to this application
                        </li>
                        <li>
                            <strong>Without CSRF token:</strong> The action succeeds because you're logged in!
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- CSRF Token Explanation -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">How CSRF Tokens Work</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Without CSRF Token:</h6>
                            <div class="card bg-danger text-white mb-3">
                                <div class="card-body small">
                                    <code>POST /transfer</code><br>
                                    <code>amount=1000</code><br>
                                    <code>to=attacker</code>
                                    <p class="mt-2 mb-0">❌ Any malicious site can forge this request!</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>With CSRF Token:</h6>
                            <div class="card bg-success text-white mb-3">
                                <div class="card-body small">
                                    <code>POST /transfer</code><br>
                                    <code>amount=1000</code><br>
                                    <code>csrf=<?php echo substr($csrf_token, 0, 8); ?>...</code>
                                    <p class="mt-2 mb-0">✅ Attacker can't guess the token!</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="mt-4">Your Current CSRF Token:</h6>
                    <code class="d-block bg-light p-2 rounded"><?php echo $csrf_token; ?></code>
                    <p class="text-muted small mt-2">This token is unique to your session. It's different every time you reload this page.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Demo Instructions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">How This Demo Works</h5>
                </div>
                <div class="card-body">
                    <h6>Understanding CSRF Protection:</h6>
                    <ul>
                        <li>When security is ON: Forms must include a valid CSRF token</li>
                        <li>When security is OFF: Forms don't require tokens (vulnerable)</li>
                        <li>Tokens are checked on the server before processing the form</li>
                        <li>Invalid or missing tokens result in a blocked request</li>
                    </ul>

                    <div class="alert alert-info mt-3 mb-0">
                        <strong>Try it:</strong> Copy this page's URL, open it in a different browser/incognito window, and try the form - it won't work because the token is tied to your specific session!
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-world Examples -->
    <div class="row mt-4">
        <div class="col-12">
            <h4 class="mb-3">Real-World CSRF Examples</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Bank Account</h6>
                    <p class="card-text small">Unauthorized fund transfers to attacker's account.</p>
                    <span class="badge badge-danger">Critical Risk</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Email Settings</h6>
                    <p class="card-text small">Change recovery email to attacker's address.</p>
                    <span class="badge badge-danger">Critical Risk</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Password Change</h6>
                    <p class="card-text small">Reset user password without their knowledge.</p>
                    <span class="badge badge-danger">Critical Risk</span>
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
