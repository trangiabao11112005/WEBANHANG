<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2>📝 Cross-Site Scripting (XSS) Attack Test</h2>
            <p class="lead">Learn how JavaScript injection attacks work and how the security system prevents them.</p>
        </div>
    </div>

    <!-- Status Alert -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-<?php echo $securityEnabled ? 'success' : 'danger'; ?>" role="alert">
                <strong>Security Status: <?php echo $securityEnabled ? '✅ ENABLED (Protected)' : '❌ DISABLED (Vulnerable)'; ?></strong><br>
                <?php echo $securityEnabled 
                    ? 'The system is PROTECTED against XSS attacks.'
                    : 'The system is VULNERABLE to XSS attacks. Malicious scripts will be executed!'
                ?>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-6">
            <!-- Test Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Test XSS Injection</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="3" placeholder="Enter a message or JavaScript payload"></textarea>
                            <small class="form-text text-muted mt-2">
                                Try these payloads:
                                <br>
                                <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code>
                                <br>
                                <code>&lt;img src=x onerror=alert('XSS')&gt;</code>
                                <br>
                                <code>&lt;svg onload=alert('XSS')&gt;</code>
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <!-- Explanation -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">What is XSS?</h5>
                </div>
                <div class="card-body">
                    <p><strong>Cross-Site Scripting (XSS)</strong> is an attack where malicious JavaScript code is injected into web pages and executed in users' browsers.</p>
                    
                    <h6>Common Payload Types:</h6>
                    <ul>
                        <li><strong>Script Tags:</strong> <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code></li>
                        <li><strong>Event Handlers:</strong> <code>&lt;img onerror=alert('XSS')&gt;</code></li>
                        <li><strong>Data URIs:</strong> <code>javascript:alert('XSS')</code></li>
                    </ul>

                    <h6 class="mt-4">Security Protection:</h6>
                    <ul>
                        <li>✅ HTML Encoding - Convert special chars to entities</li>
                        <li>✅ Content Security Policy - Restrict script sources</li>
                        <li>✅ Input Validation - Detect script patterns</li>
                        <li>✅ Output Escaping - Escape data before displaying</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Results -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-<?php echo $securityEnabled ? 'warning' : 'danger'; ?>">
                <div class="card-header bg-<?php echo $securityEnabled ? 'warning' : 'danger'; ?> text-white">
                    <h5 class="mb-0">Test Result</h5>
                </div>
                <div class="card-body">
                    <p><strong>Input Received:</strong></p>
                    <pre><code><?php echo htmlspecialchars($_POST['message']); ?></code></pre>
                    
                    <?php if ($securityEnabled): ?>
                        <div class="alert alert-warning" role="alert">
                            <strong>🛡️ Attack Blocked!</strong><br>
                            The security system detected this as a potential XSS attack and blocked it.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger" role="alert">
                            <strong>⚠️ Vulnerable to XSS!</strong><br>
                            Without protection, JavaScript payloads execute in the browser. In a real attack, this could steal user data, redirect to phishing sites, or create malware!
                        </div>

                        <h6 class="mt-3">Message Display (VULNERABLE - unescaped):</h6>
                        <div class="card bg-light border-danger">
                            <div class="card-body">
                                <!-- INTENTIONALLY VULNERABLE FOR DEMO - Shows what happens without protection -->
                                <p class="mb-0" id="unsafe-display"></p>
                            </div>
                        </div>
                        <script>
                            // This demonstrates the vulnerability - DO NOT USE IN PRODUCTION
                            document.getElementById('unsafe-display').innerHTML = <?php echo json_encode($_POST['message']); ?>;
                        </script>
                    <?php endif; ?>

                    <?php if (!$securityEnabled && $displayText): ?>
                        <h6 class="mt-3">What a Safe Version Would Show:</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0 pre-wrap" style="word-break: break-word;"><?php echo $displayText; ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- XSS Types Explanation -->
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="mb-3">Types of XSS Attacks</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Stored XSS</h6>
                </div>
                <div class="card-body small">
                    <p>Malicious script is saved in database and executed for all users who view it.</p>
                    <p class="text-danger mb-0"><strong>Most Dangerous!</strong></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Reflected XSS</h6>
                </div>
                <div class="card-body small">
                    <p>Script is included in URL and executed when user clicks a malicious link.</p>
                    <p class="text-warning mb-0"><strong>Common Phishing Vector</strong></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">DOM-based XSS</h6>
                </div>
                <div class="card-body small">
                    <p>Vulnerable JavaScript manipulates DOM based on untrusted input.</p>
                    <p class="text-info mb-0"><strong>Client-Side Vulnerability</strong></p>
                </div>
            </div>
        </div>
    </div>

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
                        <li>Paste <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code> into the message field</li>
                        <li>Click "Send Message"</li>
                        <li>An alert will appear - proving the JavaScript executed!</li>
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
                        <li>No alert will appear - the attack is blocked</li>
                        <li>Check the admin panel to see it logged as an attack</li>
                    </ol>

                    <div class="alert alert-info mt-3 mb-0">
                        <strong>⚠️ Important:</strong> This demo shows why output encoding is crucial. Always escape user input before displaying it!
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
