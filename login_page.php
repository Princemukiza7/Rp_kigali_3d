<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - RP Kigali Geoportal</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --navy:#1a2a50;--navy2:#243870;--border:#dde3ef;--bg:#edf1f7;
}
body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#1a2a50 0%,#243870 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}

.login-container{background:#fff;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.3);width:100%;max-width:420px;overflow:hidden}

.login-header{background:var(--navy);padding:30px;text-align:center;color:#fff}
.login-header h1{font-size:22px;font-weight:800;margin-bottom:4px}
.login-header p{font-size:13px;opacity:0.8}

.login-body{padding:30px}

.tabs{display:flex;gap:10px;margin-bottom:24px}
.tab{flex:1;padding:12px;border:none;background:#f0f3f8;border-radius:8px;font:600 14px 'Inter',sans-serif;color:#8090a8;cursor:pointer;transition:all .15s}
.tab.active{background:var(--navy);color:#fff}

.form-group{margin-bottom:16px}
.form-group label{display:block;font:500 13px 'Inter',sans-serif;color:#333;margin-bottom:6px}
.form-group input{width:100%;padding:12px 14px;border:1.5px solid #d0d8e8;border-radius:8px;font:400 14px 'Inter',sans-serif;outline:none;transition:border-color .15s}
.form-group input:focus{border-color:var(--navy)}

.btn{width:100%;padding:14px;background:var(--navy);color:#fff;border:none;border-radius:8px;font:600 15px 'Inter',sans-serif;cursor:pointer;transition:background .15s}
.btn:hover{background:var(--navy2)}
.btn:disabled{background:#8090a8;cursor:not-allowed}

.error{background:#fee;border:1px solid #fcc;color:#c33;padding:12px;border-radius:8px;font-size:13px;margin-bottom:16px;display:none}
.error.show{display:block}

.success{background:#efe;border:1px solid #cfc;color:#282;padding:12px;border-radius:8px;font-size:13px;margin-bottom:16px;display:none}
.success.show{display:block}

.switch{text-align:center;margin-top:20px;font-size:13px;color:#555}
.switch a{color:var(--navy);text-decoration:none;font-weight:600}
.switch a:hover{text-decoration:underline}

.back-link{text-align:center;margin-top:16px}
.back-link a{color:#8090a8;text-decoration:none;font-size:13px}
.back-link a:hover{color:var(--navy)}
</style>
</head>
<body>

<div class="login-container">
  <div class="login-header">
    <h1>RP KIGALI GEOPORTAL</h1>
    <p>Indoor Navigation System</p>
  </div>
  
  <div class="login-body">
    <div class="tabs">
      <button class="tab active" id="tabLogin" onclick="showTab('login')">Sign In</button>
      <button class="tab" id="tabSignup" onclick="showTab('signup')">Sign Up</button>
    </div>
    
    <!-- Login Form -->
    <div id="loginForm">
      <div class="error" id="loginError"></div>
      <form onsubmit="handleLogin(event)">
        <div class="form-group">
          <label>Username or Email</label>
          <input type="text" id="loginUsername" placeholder="Enter username or email" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" id="loginPassword" placeholder="Enter password" required>
        </div>
        <button type="submit" class="btn" id="loginBtn">Sign In</button>
      </form>
      <div class="switch">
        Don't have an account? <a href="#" onclick="showTab('signup')">Sign Up</a>
      </div>
    </div>
    
    <!-- Signup Form -->
    <div id="signupForm" style="display:none">
      <div class="success" id="signupSuccess"></div>
      <div class="error" id="signupError"></div>
      <form onsubmit="handleSignup(event)">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" id="signupName" placeholder="Enter your name">
        </div>
        <div class="form-group">
          <label>Username *</label>
          <input type="text" id="signupUsername" placeholder="Choose a username" required>
        </div>
        <div class="form-group">
          <label>Email *</label>
          <input type="email" id="signupEmail" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
          <label>Password *</label>
          <input type="password" id="signupPassword" placeholder="Min 6 characters" required>
        </div>
        <div class="form-group">
          <label>Confirm Password *</label>
          <input type="password" id="signupConfirm" placeholder="Confirm password" required>
        </div>
        <button type="submit" class="btn" id="signupBtn">Create Account</button>
      </form>
      <div class="switch">
        Already have an account? <a href="#" onclick="showTab('login')">Sign In</a>
      </div>
    </div>
    
    <div class="back-link">
      <a href="rp-kigali-geoportal.php">← Back to Geoportal</a>
    </div>
  </div>
</div>

<script>
// Tab switching
function showTab(tab) {
  document.getElementById('tabLogin').classList.toggle('active', tab === 'login');
  document.getElementById('tabSignup').classList.toggle('active', tab === 'signup');
  document.getElementById('loginForm').style.display = tab === 'login' ? 'block' : 'none';
  document.getElementById('signupForm').style.display = tab === 'signup' ? 'block' : 'none';
  
  // Clear errors
  document.getElementById('loginError').classList.remove('show');
  document.getElementById('signupError').classList.remove('show');
  document.getElementById('signupSuccess').classList.remove('show');
}

// Handle Login
async function handleLogin(e) {
  e.preventDefault();
  const btn = document.getElementById('loginBtn');
  const err = document.getElementById('loginError');
  const username = document.getElementById('loginUsername').value.trim();
  const password = document.getElementById('loginPassword').value;
  
  if (!username || !password) {
    err.textContent = 'Please enter username and password';
    err.classList.add('show');
    return;
  }
  
  btn.disabled = true;
  btn.textContent = 'Signing in...';
  err.classList.remove('show');
  
  try {
    const formData = new FormData();
    formData.append('username', username);
    formData.append('password', password);
    
    const response = await fetch('login.php?action=login', {
      method: 'POST',
      body: formData
    });
    
    const data = await response.json();
    
    if (data.success) {
      // Store session info
      localStorage.setItem('user_type', data.user_type);
      localStorage.setItem('username', username);
      // Redirect to main page
      window.location.href = 'rp-kigali-geoportal.php';
    } else {
      err.textContent = data.message || 'Login failed';
      err.classList.add('show');
    }
  } catch (error) {
    err.textContent = 'Connection error. Please try again.';
    err.classList.add('show');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Sign In';
  }
}

// Handle Signup
async function handleSignup(e) {
  e.preventDefault();
  const btn = document.getElementById('signupBtn');
  const err = document.getElementById('signupError');
  const succ = document.getElementById('signupSuccess');
  const name = document.getElementById('signupName').value.trim();
  const username = document.getElementById('signupUsername').value.trim();
  const email = document.getElementById('signupEmail').value.trim();
  const password = document.getElementById('signupPassword').value;
  const confirm = document.getElementById('signupConfirm').value;
  
  if (!username || !email || !password) {
    err.textContent = 'Please fill in all required fields';
    err.classList.add('show');
    return;
  }
  
  if (password.length < 6) {
    err.textContent = 'Password must be at least 6 characters';
    err.classList.add('show');
    return;
  }
  
  if (password !== confirm) {
    err.textContent = 'Passwords do not match';
    err.classList.add('show');
    return;
  }
  
  btn.disabled = true;
  btn.textContent = 'Creating account...';
  err.classList.remove('show');
  succ.classList.remove('show');
  
  try {
    const formData = new FormData();
    formData.append('username', username);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('confirm_password', confirm);
    formData.append('full_name', name);
    
    const response = await fetch('login.php?action=signup', {
      method: 'POST',
      body: formData
    });
    
    const data = await response.json();
    
    if (data.success) {
      succ.textContent = 'Account created! Please sign in.';
      succ.classList.add('show');
      // Clear form
      document.getElementById('signupName').value = '';
      document.getElementById('signupUsername').value = '';
      document.getElementById('signupEmail').value = '';
      document.getElementById('signupPassword').value = '';
      document.getElementById('signupConfirm').value = '';
      // Switch to login
      setTimeout(() => showTab('login'), 1500);
    } else {
      err.textContent = data.message || 'Registration failed';
      err.classList.add('show');
    }
  } catch (error) {
    err.textContent = 'Connection error. Please try again.';
    err.classList.add('show');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Create Account';
  }
}
</script>
</body>
</html>