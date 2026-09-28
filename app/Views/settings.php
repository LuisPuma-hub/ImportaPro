<?php if (!empty($_SESSION['settings_message'])): ?>
    <div class="alert" style="background:#e0ffe0;color:#155724;padding:12px 16px;margin-bottom:16px;border-radius:6px;border:1px solid #c3e6c3;">
      ✓ <?php echo $_SESSION['settings_message']; unset($_SESSION['settings_message']); ?>
    </div>
  <?php endif; ?>

<?php include __DIR__ . '/layout/header.php'; ?>
<main class="container">
  <h2 style="margin-top:24px;">Configuración del Sitio</h2>
  <p class="muted">Gestiona la información y apariencia del sitio web</p>
  
  <form id="settingsForm" method="POST" action="?action=update_settings">
    <!-- Información de la Empresa -->
    <div class="card" style="padding:32px;margin:24px 0;">
      <h3 style="margin:0 0 24px;padding-bottom:16px;border-bottom:2px solid #f0f0f0;">Información de la Empresa</h3>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;">
        <div>
          <label style="display:block;margin-bottom:8px;font-weight:500;color:#333;">Nombre de la Empresa</label>
          <input type="text" name="company_name" value="<?php echo htmlspecialchars($settings['company_name']); ?>" required style="width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:1em;">
        </div>
        
        <div>
          <label style="display:block;margin-bottom:8px;font-weight:500;color:#333;">Email de Contacto</label>
          <input type="email" name="company_email" value="<?php echo htmlspecialchars($settings['company_email']); ?>" required style="width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:1em;">
        </div>
      </div>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
        <div>
          <label style="display:block;margin-bottom:8px;font-weight:500;color:#333;">Teléfono</label>
          <input type="text" name="company_phone" value="<?php echo htmlspecialchars($settings['company_phone']); ?>" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:1em;">
        </div>
        
        <div>
          <label style="display:block;margin-bottom:8px;font-weight:500;color:#333;">Dirección</label>
          <input type="text" name="company_address" value="<?php echo htmlspecialchars($settings['company_address']); ?>" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:1em;">
        </div>
      </div>
    </div>
    
    <!-- Contenido del Sitio Web -->
    <div class="card" style="padding:32px;margin:24px 0;">
      <h3 style="margin:0 0 24px;padding-bottom:16px;border-bottom:2px solid #f0f0f0;">Contenido del Sitio Web</h3>
      
      <div style="margin-bottom:24px;">
        <label style="display:block;margin-bottom:8px;font-weight:500;color:#333;">Texto del Banner Principal</label>
        <input type="text" name="site_banner_text" value="<?php echo htmlspecialchars($settings['site_banner_text']); ?>" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:1em;" placeholder="Importa productos del mundo con confianza">
      </div>
      
      <div>
        <label style="display:block;margin-bottom:8px;font-weight:500;color:#333;">Descripción</label>
        <textarea name="site_description" rows="4" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:1em;font-family:inherit;resize:vertical;" placeholder="Simplificamos el proceso de importación..."><?php echo htmlspecialchars($settings['site_description']); ?></textarea>
      </div>
    </div>
    
    <!-- Información Bancaria -->
    <div class="card" style="padding:32px;margin:24px 0;">
      <h3 style="margin:0 0 24px;padding-bottom:16px;border-bottom:2px solid #f0f0f0;">Información Bancaria</h3>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;">
        <div>
          <label style="display:block;margin-bottom:8px;font-weight:500;color:#333;">Banco</label>
          <input type="text" name="bank_name" value="<?php echo htmlspecialchars($settings['bank_name']); ?>" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:1em;" placeholder="Banco Nacional de Comercio">
        </div>
        
        <div>
          <label style="display:block;margin-bottom:8px;font-weight:500;color:#333;">Titular</label>
          <input type="text" name="bank_account_holder" value="<?php echo htmlspecialchars($settings['bank_account_holder']); ?>" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:1em;" placeholder="ImportaPro S.A. de C.V.">
        </div>
      </div>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
        <div>
          <label style="display:block;margin-bottom:8px;font-weight:500;color:#333;">Número de Cuenta</label>
          <input type="text" name="bank_account_number" value="<?php echo htmlspecialchars($settings['bank_account_number']); ?>" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:1em;" placeholder="1234567890">
        </div>
        
        <div>
          <label style="display:block;margin-bottom:8px;font-weight:500;color:#333;">CLABE</label>
          <input type="text" name="bank_clabe" value="<?php echo htmlspecialchars($settings['bank_clabe']); ?>" maxlength="18" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:1em;" placeholder="012345678901234567">
        </div>
      </div>
    </div>
    
    <!-- Botones de acción -->
    <div style="display:flex;gap:16px;justify-content:flex-end;margin:24px 0 48px;">
      <button type="button" onclick="window.location.href='?action=dashboard'" class="btn" style="padding:12px 24px;background:#f5f5f5;color:#666;border:1px solid #ddd;">
        Cancelar
      </button>
      <button type="submit" class="btn primary" style="padding:12px 32px;display:flex;align-items:center;gap:8px;">
        <span>💾</span> Guardar Cambios
      </button>
    </div>
  </form>
  
  <!-- Toast de confirmación -->
  <div id="settingsToast" style="position:fixed;bottom:24px;right:24px;background:#2e7d32;color:#fff;padding:14px 20px;border-radius:8px;box-shadow:0 10px 24px rgba(46,125,50,0.3);opacity:0;pointer-events:none;transition:opacity 0.3s ease-out;z-index:1300;display:flex;align-items:center;gap:10px;">
    <span style="font-size:1.2em;">✓</span>
    <span>Configuración guardada correctamente</span>
  </div>
</main>

<script>
  const settingsForm = document.getElementById('settingsForm');
  const settingsToast = document.getElementById('settingsToast');
  
  settingsForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(settingsForm);
    
    try {
      const res = await fetch('?action=update_settings', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      
      const data = await res.json();
      
      if (data.success) {
        settingsToast.style.opacity = '1';
        settingsToast.style.pointerEvents = 'auto';
        
        setTimeout(() => {
          settingsToast.style.opacity = '0';
          settingsToast.style.pointerEvents = 'none';
        }, 3000);
      } else {
        alert('Error al guardar: ' + (data.error || 'Error desconocido'));
      }
    } catch (err) {
      alert('Error de red al guardar la configuración');
      console.error(err);
    }
  });
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
