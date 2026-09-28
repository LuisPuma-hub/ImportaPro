<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Iniciar Sesión - ImportaPro</title>
  <link rel="stylesheet" href="/style.css">
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    
    .site-header {
      background: #fff;
      box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }
    
    .site-header .navbar {
      background: #fff;
    }
    
    .site-header .logo a {
      color: #1769ff;
    }
    
    .site-header .navbar-items a {
      color: #666;
    }
    
    .site-header .navbar-items a:hover {
      color: #1769ff;
    }
    
    .login-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }
    
    .login-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
      max-width: 480px;
      width: 100%;
      padding: 48px 40px;
    }
    
    .login-card h2 {
      margin: 0 0 8px;
      font-size: 1.8em;
      color: #111;
      text-align: center;
    }
    
    .login-card .subtitle {
      text-align: center;
      color: #666;
      margin-bottom: 32px;
      font-size: 0.95em;
    }
    
    .login-form {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    
    .login-form .form-group {
      display: flex;
      flex-direction: column;
    }
    
    .login-form label {
      display: block;
      margin-bottom: 8px;
      color: #333;
      font-weight: 500;
      font-size: 0.95em;
    }
    
    .login-form input {
      padding: 12px 14px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 1em;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    
    .login-form input:focus {
      outline: none;
      border-color: #1769ff;
      box-shadow: 0 0 0 3px rgba(23, 105, 255, 0.1);
    }
    
    .login-form input[type="email"]::placeholder {
      color: #999;
    }
    
    .login-form input[type="password"]::placeholder {
      color: #999;
    }
    
    .remember-forgot {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.9em;
    }
    
    .remember-forgot label {
      display: flex;
      align-items: center;
      gap: 6px;
      margin: 0;
      font-weight: 400;
      cursor: pointer;
    }
    
    .remember-forgot a {
      color: #1769ff;
      text-decoration: none;
    }
    
    .remember-forgot a:hover {
      text-decoration: underline;
    }
    
    .login-btn {
      background: #1769ff;
      color: #fff;
      padding: 14px;
      border: none;
      border-radius: 6px;
      font-size: 1em;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
      margin-top: 8px;
    }
    
    .login-btn:hover {
      background: #0055cc;
    }
    
    .login-btn:active {
      transform: scale(0.98);
    }
    
    .login-footer {
      text-align: center;
      margin-top: 20px;
      font-size: 0.9em;
      color: #666;
    }
    
    .login-footer a {
      color: #1769ff;
      text-decoration: none;
      font-weight: 600;
    }
    
    .login-footer a:hover {
      text-decoration: underline;
    }
    
    .error-alert {
      background: #ffebee;
      color: #c62828;
      padding: 12px 14px;
      border-radius: 6px;
      margin-bottom: 16px;
      border: 1px solid #ef9a9a;
      font-size: 0.9em;
    }
    
    @media (max-width: 600px) {
      .login-header nav {
        width: 100%;
      }
      
      .login-header .nav-links {
        gap: 16px;
      }
      
      .login-card {
        padding: 32px 24px;
      }
      
      .login-card h2 {
        font-size: 1.5em;
      }
    }
  </style>
</head>
<body>
  <header class="site-header">
    <nav class="navbar">
      <div class="navbar-logo">
        <h1 class="logo"><a href="?action=home">ImportaPro</a></h1>
      </div>
      <div class="navbar-items">
        <a href="?action=home">Inicio</a>
        <a href="#catalog">Catálogo</a>
        <a href="?action=about">Nosotros</a>
        <a href="?action=contact">Contacto</a>
      </div>
      <div class="navbar-user">
        <a href="?action=login" style="color:#1769ff;text-decoration:none;padding:8px 16px;border:1px solid #1769ff;border-radius:6px;font-size:0.95em;transition:all 0.2s;margin-right:8px;" onmouseover="this.style.background='#f0f4ff'" onmouseout="this.style.background='transparent'">Iniciar Sesión</a>
        <a href="?action=register" style="color:#fff;text-decoration:none;padding:8px 16px;background:#1769ff;border-radius:6px;font-size:0.95em;transition:background 0.2s;" onmouseover="this.style.background='#0055cc'" onmouseout="this.style.background='#1769ff'">Registrarse</a>
      </div>
    </nav>
  </header>
  
  <div class="login-container">
    <div class="login-card">
      <h2>Iniciar Sesión</h2>
      <p class="subtitle">Accede a tu cuenta de ImportaPro</p>
      
      <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="error-alert">
          <?php echo htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
        </div>
      <?php endif; ?>
      
      <form method="post" action="?action=login_post" class="login-form">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="tu@email.com" required>
        </div>
        
        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password" placeholder="••••••••" required>
        </div>
        
        <div class="remember-forgot">
          <label>
            <input type="checkbox" name="remember" value="1">
            Recuérdame
          </label>
          <a href="#forgot">¿Olvidaste tu contraseña?</a>
        </div>
        
        <button type="submit" class="login-btn">Iniciar Sesión</button>
      </form>
      
      <div class="login-footer">
        ¿No tienes cuenta? <a href="?action=register">Regístrate aquí</a>
      </div>
    </div>
  </div>
</body>
</html>