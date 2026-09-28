<?php include __DIR__ . '/layout/header.php'; ?>
<main style="font-family:'Segoe UI','Roboto',Arial,sans-serif;background:#f7f9fb;padding:48px 20px;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:28px;">
      <h1 style="margin:0 0 8px;font-size:2em;color:#111;">Contáctanos</h1>
      <p style="margin:0;color:#666;">Estamos aquí para ayudarte. Envíanos un mensaje y te responderemos pronto.</p>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1.1fr;gap:24px;align-items:start;">
      <!-- Información de contacto -->
      <div style="display:grid;grid-template-rows:auto 1fr;gap:16px;">
        <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:20px;box-shadow:0 8px 24px rgba(0,0,0,0.06);">
          <h3 style="margin:0 0 16px;font-size:1.1em;color:#111;">Información de Contacto</h3>
          <?php $items = [
            ['Dirección','Av. Principal 123, Piso 5\nCiudad, País 12345','📍'],
            ['Teléfono','+1 (234) 567-8900\n+1 (234) 567-8901','📞'],
            ['Email','info@importapro.com\nsoporte@importapro.com','✉️'],
            ['Horario de Atención','Lunes - Viernes: 9:00 AM - 6:00 PM\nSábados: 9:00 AM - 1:00 PM\nDomingos: Cerrado','⏰'],
          ];
          foreach ($items as [$title,$desc,$icon]): ?>
            <div style="display:flex;gap:12px;margin-bottom:14px;">
              <div style="width:38px;height:38px;border-radius:10px;background:rgba(23,105,255,0.12);display:flex;align-items:center;justify-content:center;font-size:18px;"> <?php echo $icon; ?> </div>
              <div>
                <div style="font-weight:600;color:#111;margin-bottom:4px;"><?php echo $title; ?></div>
                <div style="color:#555;white-space:pre-line;font-size:0.95em;line-height:1.45;"><?php echo $desc; ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <div style="background:#eef1f7;border:1px dashed #c7cedd;border-radius:10px;min-height:180px;display:flex;align-items:center;justify-content:center;color:#666;font-size:0.95em;">
          Mapa de ubicación
        </div>
      </div>

      <!-- Formulario -->
      <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:20px 20px 24px;box-shadow:0 8px 24px rgba(0,0,0,0.06);">
        <h3 style="margin:0 0 18px;font-size:1.1em;color:#111;">Envíanos un Mensaje</h3>
        <form>
          <div style="display:grid;grid-template-columns:1fr;gap:14px;">
            <div>
              <label style="display:block;margin-bottom:6px;color:#333;font-weight:600;">Nombre completo *</label>
              <input type="text" placeholder="Tu nombre" style="width:100%;padding:10px 12px;border:1px solid #d8deea;border-radius:8px;font-size:0.96em;">
            </div>
            <div>
              <label style="display:block;margin-bottom:6px;color:#333;font-weight:600;">Email *</label>
              <input type="email" placeholder="tu@email.com" style="width:100%;padding:10px 12px;border:1px solid #d8deea;border-radius:8px;font-size:0.96em;">
            </div>
            <div>
              <label style="display:block;margin-bottom:6px;color:#333;font-weight:600;">Teléfono</label>
              <input type="text" placeholder="+1 234 567 890" style="width:100%;padding:10px 12px;border:1px solid #d8deea;border-radius:8px;font-size:0.96em;">
            </div>
            <div>
              <label style="display:block;margin-bottom:6px;color:#333;font-weight:600;">Asunto *</label>
              <select style="width:100%;padding:10px 12px;border:1px solid #d8deea;border-radius:8px;font-size:0.96em;background:#fff;">
                <option>Selecciona un asunto</option>
                <option>Información general</option>
                <option>Soporte</option>
                <option>Proyectos</option>
              </select>
            </div>
            <div>
              <label style="display:block;margin-bottom:6px;color:#333;font-weight:600;">Mensaje *</label>
              <textarea rows="5" placeholder="Escribe tu mensaje aquí..." style="width:100%;padding:10px 12px;border:1px solid #d8deea;border-radius:8px;font-size:0.96em;resize:vertical;"></textarea>
            </div>
            <div>
              <button type="button" style="width:100%;padding:12px 16px;background:#1769ff;color:#fff;border:none;border-radius:8px;font-weight:600;font-size:1em;cursor:pointer;box-shadow:0 8px 18px rgba(23,105,255,0.22);">
                Enviar Mensaje
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>
<?php include __DIR__ . '/layout/footer.php'; ?>
