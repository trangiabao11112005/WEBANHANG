# 🛡️ Security Demo System - Complete Guide

## Overview

This security demo system allows you to demonstrate how web application security works. You can **toggle the security system ON and OFF** to show the difference between vulnerable and protected states.

## Quick Start

### Access the Demo System

1. **Visit the Demo Home Page**: Navigate to `http://localhost/Demo`
2. **Go to Admin Panel**: Visit `http://localhost/Admin/security` to toggle security

## Security Toggle

### How to Enable/Disable Security

1. **Open Admin Panel**
   - URL: `http://localhost/Admin/security`
   - Login with admin credentials

2. **Toggle Security Status**
   - Green button: "Enable Security System"
   - Red button: "Disable Security System"
   - Click the button to toggle

3. **Confirm the Change**
   - The button text will change immediately
   - The status card will update
   - All tests will now use the new security state

## Available Attack Tests

### 1. 🔍 SQL Injection (SQLi) Test
**URL**: `http://localhost/Demo/sqli`

**What it demonstrates**:
- How SQL injection attacks work
- Database query manipulation
- Difference between vulnerable and safe queries

**Test payloads**:
```
' OR '1'='1
admin' --
' UNION SELECT * FROM account --
' OR 1=1 --
```

**What you'll see**:
- **Security OFF**: Injection succeeds, database is queried unsafely
- **Security ON**: Attack is detected and blocked with error message

### 2. 📝 XSS (Cross-Site Scripting) Test
**URL**: `http://localhost/Demo/xss`

**What it demonstrates**:
- JavaScript injection attacks
- Browser-side code execution
- Output encoding protection

**Test payloads**:
```
<script>alert('XSS')</script>
<img src=x onerror=alert('XSS')>
<svg onload=alert('XSS')>
javascript:alert('XSS')
```

**What you'll see**:
- **Security OFF**: JavaScript alert appears (vulnerable!)
- **Security ON**: Attack is blocked, no JavaScript executes

### 3. 🔐 CSRF (Cross-Site Request Forgery) Test
**URL**: `http://localhost/Demo/csrf`

**What it demonstrates**:
- How CSRF tokens protect forms
- Token validation
- Session-based security

**Key features**:
- Shows current CSRF token
- Demonstrates attack scenarios
- Explains token validation

### 4. 🔓 Brute Force Protection Test
**URL**: `http://localhost/Demo/bruteforce`

**What it demonstrates**:
- Login attempt limiting
- Account lockout mechanisms
- Protection against password guessing

**Test procedure**:
1. Try logging in with wrong password
2. After 5 failures, account locks (with security ON)
3. With security OFF, unlimited attempts allowed

## Step-by-Step Demo Walkthrough

### Demo Scenario: SQL Injection

**Part 1: Show the Vulnerability (Security OFF)**

1. Go to `http://localhost/Admin/security`
2. Click "Disable Security System"
3. Go to `http://localhost/Demo/sqli`
4. Enter payload: `' OR '1'='1`
5. Click "Search User"
6. **Result**: Users are found - the injection worked!
7. Point out: Without protection, attackers can bypass authentication

**Part 2: Demonstrate Protection (Security ON)**

1. Go back to `http://localhost/Admin/security`
2. Click "Enable Security System"
3. Return to `http://localhost/Demo/sqli`
4. Enter the same payload: `' OR '1'='1`
5. Click "Search User"
6. **Result**: Attack is blocked with message "SQL Injection detected!"
7. Point out: Security system prevents the malicious query

**Part 3: Review Logs**

1. Go to `http://localhost/Admin/security`
2. Scroll down to see "Attacks by Type"
3. You'll see the SQLi attempts logged
4. Show the attack log in the dashboard

### Demo Scenario: XSS Attack

**Part 1: Show the Vulnerability (Security OFF)**

1. Go to `http://localhost/Admin/security`
2. Ensure security is OFF
3. Go to `http://localhost/Demo/xss`
4. Enter payload: `<script>alert('XSS')</script>`
5. Click "Send Message"
6. **Result**: JavaScript alert pops up (vulnerable!)
7. Point out: The script executed in the browser!

**Part 2: Demonstrate Protection (Security ON)**

1. Go to `http://localhost/Admin/security`
2. Click "Enable Security System"
3. Return to `http://localhost/Demo/xss`
4. Enter the same payload: `<script>alert('XSS')</script>`
5. Click "Send Message"
6. **Result**: No alert appears, attack is blocked
7. Point out: Security detected and prevented the script injection

## Security Features Being Demonstrated

### 1. SQL Injection Protection
- **Detection**: Scans for SQL keywords and dangerous patterns
- **Blocking**: Stops requests containing detected SQL
- **Logging**: Records all SQL injection attempts
- **Auto-block**: IPs are blocked after 10 attacks in 1 hour

Patterns detected:
- `OR`, `AND` with `=`
- `UNION SELECT`
- `INSERT INTO`, `DELETE FROM`, `DROP TABLE`
- Comments: `--`, `;`

### 2. XSS Protection
- **Detection**: Scans for script tags and event handlers
- **Blocking**: Prevents JavaScript injection attempts
- **Logging**: Records all XSS attempts
- **Encoding**: Output is properly escaped

Patterns detected:
- `<script>` tags
- Event handlers: `on[event]=`
- `javascript:` protocol

### 3. CSRF Protection
- **Token Generation**: Unique token per session
- **Token Validation**: Required for all state-changing forms
- **Session Binding**: Token tied to specific user session
- **Protection**: Invalid tokens rejected

### 4. Brute Force Protection
- **Attempt Limiting**: Max 5 failed attempts per session
- **Account Lockout**: Temporary lock after failures
- **Logging**: All attempts logged with timestamp and IP
- **Auto-block**: IPs blocked after multiple failed attempts

### 5. Rate Limiting
- **Request Tracking**: Monitors requests per IP/time window
- **Blocking**: Restricts repeated requests
- **Logging**: Records rate limit violations

## Log Files

All security events are logged in `logs/` directory:

### `attack.log`
Records all detected attacks:
```
2026-04-15 10:30:45 | IP: 127.0.0.1 | TYPE: SQLi | DATA: ' OR '1'='1
2026-04-15 10:31:12 | IP: 127.0.0.1 | TYPE: XSS | DATA: <script>alert('XSS')</script>
```

### `blocked_ips.txt`
Records IP addresses after auto-blocking:
```
192.168.1.100|1650098945
10.0.0.50|1650099012
```

### `blocked_accounts.txt`
Records locked accounts:
```
attacker_user|1650098945
```

### `rate_limit.json`
Tracks rate limit violations per IP

### `security_status.json`
Current security system state:
```json
{
    "enabled": true
}
```

## Admin Panel Features

**URL**: `http://localhost/Admin/security`

### Security Status Card
- Shows current state (ENABLED/DISABLED)
- Big toggle button
- One-click enable/disable

### Attack Statistics
- Total attacks detected
- Attacks by type (SQLi, XSS, etc.)
- Attacks by IP address

### IP Management
- List of blocked IPs
- Manual block/unblock buttons
- Persistent blocking

### Account Management
- List of all user accounts
- Status indicators (Active/Blocked)
- Manual block/unblock buttons

## Architecture

### Files Modified/Created

**Controllers**:
- `app/controllers/AdminController.php` - Enhanced with security toggle
- `app/controllers/DemoController.php` - New demo test controller

**Views**:
- `app/views/demo/index.php` - Demo home page
- `app/views/demo/sqli.php` - SQL injection test
- `app/views/demo/xss.php` - XSS test
- `app/views/demo/csrf.php` - CSRF token test
- `app/views/demo/bruteforce.php` - Brute force test

**Middleware**:
- `app/helpers/SecurityMiddleware.php` - Enhanced with `isSecurityEnabled()`

**Config Files**:
- `logs/security_status.json` - Stores security toggle state

### How Security Toggle Works

1. **When toggled OFF** (`security_status.json` → `{"enabled": false}`):
   - SecurityMiddleware checks the status
   - All security checks are skipped
   - Vulnerable code paths execute
   - Attacks pass through

2. **When toggled ON** (`security_status.json` → `{"enabled": true}`):
   - SecurityMiddleware enables all checks
   - SQL injection detection runs
   - XSS detection runs
   - Rate limiting runs
   - Attacks are blocked and logged

## Security Considerations

⚠️ **WARNING**: This demo system is for **EDUCATIONAL AND TESTING PURPOSES ONLY**

- **Never** deploy with security disabled in production
- **Never** allow public access to demo pages
- **Always** use this locally or on internal networks only
- **Delete** before using application in production

## Testing Recommendations

1. **First Demo**: SQL Injection
   - Easiest to understand
   - Clear visual feedback
   - Good starting point

2. **Second Demo**: XSS
   - Shows browser-side vulnerabilities
   - Demonstrates how JavaScript executes
   - Impactful visual evidence

3. **Third Demo**: CSRF
   - Explains token-based protection
   - More abstract but important
   - Shows cross-site attack scenarios

4. **Fourth Demo**: Brute Force
   - Shows login protection
   - Demonstrates rate limiting
   - Real-world security concern

## Troubleshooting

### Demo page shows blank
- Check if demo.php files exist in `app/views/demo/`
- Verify directory was created: `app/views/demo/`
- Check error logs

### Security toggle not working
- Verify `logs/` directory is writable
- Check `logs/security_status.json` permissions
- Ensure SecurityMiddleware.php is updated

### Attacks not being detected
- Verify security is ENABLED in admin panel
- Check that SecurityMiddleware::isSecurityEnabled() returns true
- Review `logs/attack.log` for entries

### Admin panel not showing toggle
- Check that AdminController.php imports SecurityMiddleware
- Verify admin user role is set correctly
- Clear browser cache

## API Endpoints

### Get Security Status
```
GET /Demo/status
```
Returns:
```json
{
    "security_enabled": true,
    "timestamp": "2026-04-15 10:30:45",
    "description": "Security system is ACTIVE..."
}
```

### Toggle Security
```
POST /Admin/toggleSecurity
Parameters: enabled=1 or enabled=0
```
Response:
```json
{
    "success": true,
    "enabled": true,
    "message": "Security system has been enabled"
}
```

## Educational Value

This system teaches:
1. **How attacks work** - See real vulnerable code in action
2. **How protection works** - See blocked attacks documented
3. **Security monitoring** - Review attack logs and statistics
4. **Risk assessment** - Understand impact of missing protections
5. **Best practices** - Learn proper secure coding techniques

## Next Steps

After understanding the demo:
1. Review the SecurityMiddleware code
2. Understand pattern detection methods
3. Learn about prepared statements (for SQL safety)
4. Research htmlspecialchars() (for XSS prevention)
5. Study CSRF token implementation
6. Implement in your own projects

## Support

For issues or questions:
1. Check the logs in `logs/` directory
2. Review SecurityMiddleware.php code
3. Test with different payloads
4. Verify security status in admin panel
