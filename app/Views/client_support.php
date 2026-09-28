<?php include __DIR__ . '/layout/header.php'; ?>
<!-- Client Support View -->
<main style="font-family:'Segoe UI','Roboto',Arial,sans-serif;background:#f7f9fb;padding:32px 20px;min-height:80vh;">
  <div style="max-width:1200px;margin:0 auto;">
    <!-- Header -->
    <div style="margin-bottom:28px;">
      <h1 style="margin:0 0 8px;font-size:1.8em;color:#111;">Centro de Soporte</h1>
      <p style="margin:0;color:#666;">¿Necesitas ayuda? Te estamos aquí para asistirte</p>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:28px;">
      <!-- Preguntas Frecuentes -->
      <div>
        <h2 style="margin:0 0 16px;font-size:1.3em;color:#111;">Preguntas Frecuentes</h2>
        <div style="display:grid;gap:12px;">
          <details style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:0;overflow:hidden;">
            <summary style="padding:16px;cursor:pointer;font-weight:600;color:#111;background:#fafbfc;display:flex;justify-content:space-between;align-items:center;">
              ¿Cómo puedo solicitar una cotización?
              <span style="font-size:1.2em;">+</span>
            </summary>
            <div style="padding:16px;color:#666;border-top:1px solid #e5e9f2;">
              <p style="margin:0;">Desde tu panel de control, ve a "Mis Cotizaciones" y haz clic en el botón "Nueva Cotización". Completa el formulario con los productos que necesitas importar, las cantidades y la fecha estimada.</p>
            </div>
          </details>

          <details style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:0;overflow:hidden;">
            <summary style="padding:16px;cursor:pointer;font-weight:600;color:#111;background:#fafbfc;display:flex;justify-content:space-between;align-items:center;">
              ¿Cuánto tiempo tarda en llegar mi pedido?
              <span style="font-size:1.2em;">+</span>
            </summary>
            <div style="padding:16px;color:#666;border-top:1px solid #e5e9f2;">
              <p style="margin:0;">El tiempo de entrega depende del origen y destino de tu importación. En promedio, nuestros servicios toman entre 15 a 30 días desde que se aprueba la cotización. Puedes monitorear el estado en la sección "Mis Pedidos".</p>
            </div>
          </details>

          <details style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:0;overflow:hidden;">
            <summary style="padding:16px;cursor:pointer;font-weight:600;color:#111;background:#fafbfc;display:flex;justify-content:space-between;align-items:center;">
              ¿Qué métodos de pago aceptan?
              <span style="font-size:1.2em;">+</span>
            </summary>
            <div style="padding:16px;color:#666;border-top:1px solid #e5e9f2;">
              <p style="margin:0;">Aceptamos transferencias bancarias y depósitos directos. Encontrarás los datos bancarios en la sección "Pagos" de tu cuenta. También aceptamos pagos en línea a través de nuestro sistema de facturación.</p>
            </div>
          </details>

          <details style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:0;overflow:hidden;">
            <summary style="padding:16px;cursor:pointer;font-weight:600;color:#111;background:#fafbfc;display:flex;justify-content:space-between;align-items:center;">
              ¿Cómo descargo mis documentos?
              <span style="font-size:1.2em;">+</span>
            </summary>
            <div style="padding:16px;color:#666;border-top:1px solid #e5e9f2;">
              <p style="margin:0;">En "Mis Pedidos", cada orden tiene un botón "Documentos" que muestra la cantidad de archivos disponibles. Haz clic para descargarlos. Los documentos incluyen facturas, guías de envío y permisos aduanales.</p>
            </div>
          </details>

          <details style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:0;overflow:hidden;">
            <summary style="padding:16px;cursor:pointer;font-weight:600;color:#111;background:#fafbfc;display:flex;justify-content:space-between;align-items:center;">
              ¿Qué pasa si mi cotización es rechazada?
              <span style="font-size:1.2em;">+</span>
            </summary>
            <div style="padding:16px;color:#666;border-top:1px solid #e5e9f2;">
              <p style="margin:0;">Si tu cotización es rechazada, recibirás una notificación con el motivo. Puedes contactar a nuestro equipo para aclarar dudas o solicitar una nueva cotización con ajustes.</p>
            </div>
          </details>
        </div>
      </div>

      <!-- Contacto Directo -->
      <div>
        <h2 style="margin:0 0 16px;font-size:1.3em;color:#111;">Contacta con Nosotros</h2>
        
        <!-- Support Tickets -->
        <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:20px;margin-bottom:16px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
          <h3 style="margin:0 0 12px;font-size:1.05em;color:#111;">Mis Tickets</h3>
          <div style="display:grid;gap:12px;margin-bottom:16px;">
            <div style="background:#f9f9f9;border:1px solid #e5e9f2;border-radius:8px;padding:12px;display:flex;justify-content:space-between;align-items:center;">
              <div>
                <div style="font-weight:600;color:#111;margin-bottom:2px;">No hay tickets activos</div>
                <div style="font-size:0.9em;color:#999;">Tus solicitudes de soporte aparecerán aquí</div>
              </div>
            </div>
          </div>
          <button style="width:100%;padding:10px;background:#1769ff;color:#fff;border:none;border-radius:6px;font-weight:600;font-size:0.95em;cursor:pointer;transition:background 0.2s;" onmouseover="this.style.background='#0055cc'" onmouseout="this.style.background='#1769ff'">
            Crear Nuevo Ticket
          </button>
        </div>

        <!-- Contact Info -->
        <div style="background:#e3f2fd;border:1px solid #90caf9;border-radius:10px;padding:20px;">
          <h3 style="margin:0 0 16px;font-size:1.05em;color:#1565c0;font-weight:600;">Información de Contacto</h3>
          <div style="display:grid;gap:12px;">
            <div style="display:flex;gap:12px;align-items:flex-start;">
              <div style="width:40px;height:40px;background:#90caf9;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2em;flex-shrink:0;">📞</div>
              <div>
                <div style="font-weight:600;color:#1565c0;margin-bottom:2px;">Teléfono</div>
                <div style="color:#666;">+34 900 123 456</div>
                <div style="color:#999;font-size:0.9em;">Lun - Vie, 09:00 - 18:00</div>
              </div>
            </div>
            <div style="display:flex;gap:12px;align-items:flex-start;">
              <div style="width:40px;height:40px;background:#90caf9;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2em;flex-shrink:0;">✉️</div>
              <div>
                <div style="font-weight:600;color:#1565c0;margin-bottom:2px;">Email</div>
                <div style="color:#666;">soporte@importapro.com</div>
                <div style="color:#999;font-size:0.9em;">Respuesta en 24 horas</div>
              </div>
            </div>
            <div style="display:flex;gap:12px;align-items:flex-start;">
              <div style="width:40px;height:40px;background:#90caf9;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2em;flex-shrink:0;">💬</div>
              <div>
                <div style="font-weight:600;color:#1565c0;margin-bottom:2px;">Chat en Vivo</div>
                <div style="color:#666;">Disponible en tu panel</div>
                <div style="color:#999;font-size:0.9em;">Lun - Vie, 10:00 - 17:00</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Documentation Links -->
    <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:24px;">
      <h2 style="margin:0 0 16px;font-size:1.2em;color:#111;">Documentación y Guías</h2>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:16px;">
        <a href="#" style="padding:16px;border:1px solid #e5e9f2;border-radius:8px;text-decoration:none;color:#111;transition:all 0.2s;display:flex;align-items:center;gap:12px;" onmouseover="this.style.background='#f9f9f9';this.style.borderColor='#1769ff'" onmouseout="this.style.background='transparent';this.style.borderColor='#e5e9f2'">
          <span style="font-size:1.8em;">📘</span>
          <div>
            <div style="font-weight:600;color:#111;">Guía de Inicio Rápido</div>
            <div style="font-size:0.9em;color:#666;">Aprende lo básico en 5 minutos</div>
          </div>
        </a>
        <a href="#" style="padding:16px;border:1px solid #e5e9f2;border-radius:8px;text-decoration:none;color:#111;transition:all 0.2s;display:flex;align-items:center;gap:12px;" onmouseover="this.style.background='#f9f9f9';this.style.borderColor='#1769ff'" onmouseout="this.style.background='transparent';this.style.borderColor='#e5e9f2'">
          <span style="font-size:1.8em;">📋</span>
          <div>
            <div style="font-weight:600;color:#111;">Términos y Condiciones</div>
            <div style="font-size:0.9em;color:#666;">Políticas de nuestro servicio</div>
          </div>
        </a>
        <a href="#" style="padding:16px;border:1px solid #e5e9f2;border-radius:8px;text-decoration:none;color:#111;transition:all 0.2s;display:flex;align-items:center;gap:12px;" onmouseover="this.style.background='#f9f9f9';this.style.borderColor='#1769ff'" onmouseout="this.style.background='transparent';this.style.borderColor='#e5e9f2'">
          <span style="font-size:1.8em;">🛡️</span>
          <div>
            <div style="font-weight:600;color:#111;">Privacidad y Seguridad</div>
            <div style="font-size:0.9em;color:#666;">Cómo protegemos tus datos</div>
          </div>
        </a>
        <a href="#" style="padding:16px;border:1px solid #e5e9f2;border-radius:8px;text-decoration:none;color:#111;transition:all 0.2s;display:flex;align-items:center;gap:12px;" onmouseover="this.style.background='#f9f9f9';this.style.borderColor='#1769ff'" onmouseout="this.style.background='transparent';this.style.borderColor='#e5e9f2'">
          <span style="font-size:1.8em;">🚀</span>
          <div>
            <div style="font-weight:600;color:#111;">Mejores Prácticas</div>
            <div style="font-size:0.9em;color:#666;">Tips para optimizar tus importaciones</div>
          </div>
        </a>
      </div>
    </div>
  </div>
</main>

<script>
document.querySelectorAll('details').forEach(detail => {
  detail.addEventListener('click', (e) => {
    if (e.target.tagName !== 'A') {
      e.currentTarget.open = !e.currentTarget.open;
      const summary = e.currentTarget.querySelector('summary span');
      if (summary) {
        summary.textContent = e.currentTarget.open ? '−' : '+';
      }
    }
  });
});
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
