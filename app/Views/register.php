<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Crear Cuenta - ImportaPro</title>
  <link rel="stylesheet" href="/style.css">
  <style>
    body {
      background: #f8f9fa;
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
    
    .register-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }
    
    .register-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.08);
      max-width: 580px;
      width: 100%;
      padding: 48px 40px;
    }
    
    .register-card h2 {
      margin: 0 0 8px;
      font-size: 1.8em;
      color: #111;
      text-align: center;
    }
    
    .register-card .subtitle {
      text-align: center;
      color: #666;
      margin-bottom: 32px;
      font-size: 0.95em;
    }
    
    .account-type {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin-bottom: 24px;
    }
    
    .account-type-label {
      margin-bottom: 8px;
      font-weight: 500;
      color: #333;
      font-size: 0.95em;
    }
    
    .account-type-option {
      border: 2px solid #ddd;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s;
      background: #fff;
    }
    
    .account-type-option:hover {
      border-color: #1769ff;
      background: #f0f4ff;
    }
    
    .account-type-option.active {
      border-color: #1769ff;
      background: #e3f2fd;
    }
    
    .account-type-option input[type="radio"] {
      display: none;
    }
    
    .account-type-option .icon {
      font-size: 2em;
      margin-bottom: 8px;
    }
    
    .account-type-option .label {
      font-weight: 500;
      color: #333;
    }
    
    .register-form {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }
    
    .form-group {
      display: flex;
      flex-direction: column;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #333;
      font-weight: 500;
      font-size: 0.95em;
    }
    
    .form-group label .required {
      color: #e53935;
    }
    
    .form-group input {
      padding: 12px 14px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 1em;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    
    .form-group input:focus {
      outline: none;
      border-color: #1769ff;
      box-shadow: 0 0 0 3px rgba(23, 105, 255, 0.1);
    }
    
    .form-group input::placeholder {
      color: #999;
    }
    
    .terms-checkbox {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      font-size: 0.9em;
      margin-top: 8px;
    }
    
    .terms-checkbox input[type="checkbox"] {
      margin-top: 3px;
    }
    
    .terms-checkbox label {
      margin: 0;
      font-weight: 400;
      color: #666;
    }
    
    .terms-checkbox a {
      color: #1769ff;
      text-decoration: none;
    }
    
    .terms-checkbox a:hover {
      text-decoration: underline;
    }
    
    .register-btn {
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
    
    .register-btn:hover {
      background: #0055cc;
    }
    
    .register-btn:active {
      transform: scale(0.98);
    }
    
    .register-footer {
      text-align: center;
      margin-top: 20px;
      font-size: 0.9em;
      color: #666;
    }
    
    .register-footer a {
      color: #1769ff;
      text-decoration: none;
      font-weight: 600;
    }
    
    .register-footer a:hover {
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
    
    @media (max-width: 768px) {
      .form-row {
        grid-template-columns: 1fr;
      }
      
      .register-card {
        padding: 32px 24px;
      }
      
      .register-card h2 {
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
  
  <div class="register-container">
    <div class="register-card">
      <h2>Crear Cuenta</h2>
      <p class="subtitle">Regístrate para empezar a importar con ImportaPro</p>
      
      <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="error-alert">
          <?php echo htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
        </div>
      <?php endif; ?>
      
      <form method="post" action="?action=register_post" class="register-form">
        <div>
          <label class="account-type-label">Tipo de Cuenta</label>
          <div class="account-type">
            <label class="account-type-option active" onclick="this.classList.add('active'); this.nextElementSibling.classList.remove('active');">
              <input type="radio" name="account_type" value="personal" checked>
              <div class="icon">👤</div>
              <div class="label">Personal</div>
            </label>
            <label class="account-type-option" onclick="this.classList.add('active'); this.previousElementSibling.classList.remove('active');">
              <input type="radio" name="account_type" value="empresa">
              <div class="icon">🏢</div>
              <div class="label">Empresa</div>
            </label>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="name">Nombre Completo <span class="required">*</span></label>
            <input type="text" id="name" name="name" placeholder="Tu nombre" required>
          </div>
          
          <div class="form-group">
            <label for="email">Email <span class="required">*</span></label>
            <input type="email" id="email" name="email" placeholder="tu@email.com" required>
          </div>
        </div>
        
        <div class="form-group">
          <label for="phone">Teléfono <span class="required">*</span></label>
          <input type="tel" id="phone" name="phone" placeholder="+1 234 567 8900" required>
        </div>
        
        <div class="form-group">
          <label for="address">Dirección <span class="required">*</span></label>
          <input type="text" id="address" name="address" placeholder="Dirección completa" required>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="password">Contraseña <span class="required">*</span></label>
            <input type="password" id="password" name="password" placeholder="••••••••" required>
          </div>
          
          <div class="form-group">
            <label for="password_confirm">Confirmar Contraseña <span class="required">*</span></label>
            <input type="password" id="password_confirm" name="password_confirm" placeholder="••••••••" required>
          </div>
        </div>
        
        <div class="terms-checkbox">
          <input type="checkbox" id="terms" name="terms" required>
          <label for="terms">Acepto los <a href="#terms">términos y condiciones</a> y la <a href="#privacy">política de privacidad</a></label>
        </div>
        
        <button type="submit" class="register-btn">Crear Cuenta</button>
      </form>
      
      <div class="register-footer">
        ¿Ya tienes cuenta? <a href="?action=login">Inicia sesión aquí</a>
      </div>
    </div>
  </div>
</body>
</html>
