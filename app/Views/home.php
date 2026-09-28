<!-- Home view -->
<main style="font-family:'Segoe UI','Roboto',Arial,sans-serif;">
  <!-- Hero Section -->
  <section style="background:linear-gradient(135deg,#1769ff 0%,#0a3fbf 100%);color:#fff;padding:80px 20px;text-align:left;">
    <div style="max-width:900px;margin:0 auto;">
      <p style="margin:0 0 12px;font-size:1.05em;opacity:0.95;font-weight:500;">Importa productos del mundo con confianza</p>
      <h1 style="margin:0 0 20px;font-size:2.4em;font-weight:700;line-height:1.25;">
        Simplificamos el proceso de importación para que puedas enfocarte en hacer crecer tu negocio
      </h1>
      <div style="display:flex;gap:16px;margin-top:28px;flex-wrap:wrap;">
        <a href="?action=products" style="display:inline-block;padding:14px 28px;background:#fff;color:#1769ff;border-radius:8px;font-weight:600;text-decoration:none;box-shadow:0 8px 20px rgba(0,0,0,0.15);transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
          Explorar Catálogo →
        </a>
        <a href="?action=register" style="display:inline-block;padding:14px 28px;background:transparent;color:#fff;border:2px solid #fff;border-radius:8px;font-weight:600;text-decoration:none;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.15)'" onmouseout="this.style.background='transparent'">
          Comenzar Ahora
        </a>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section style="background:#fff;padding:64px 20px;">
    <div style="max-width:1100px;margin:0 auto;text-align:center;">
      <h2 style="margin:0 0 12px;font-size:1.8em;color:#111;">¿Por qué ImportaPro?</h2>
      <p style="margin:0 0 40px;color:#666;font-size:1.05em;">Ofrecemos una solución integral para todas tus necesidades de importación</p>
      
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:32px;text-align:center;">
        <?php $features = [
          ['📦','Amplio Catálogo','Miles de productos de proveedores internacionales verificados'],
          ['📈','Precios Competitivos','Obtén las mejores tarifas con nuestro poder de negociación'],
          ['🛡️','Importación Segura','Nos encargamos de todos los trámites aduaneros y logísticos'],
          ['⏱️','Seguimiento en Tiempo Real','Monitorea tu pedido desde el origen hasta tu puerta'],
        ];
        foreach ($features as [$icon,$title,$desc]): ?>
          <div style="padding:20px;">
            <div style="width:72px;height:72px;margin:0 auto 16px;background:rgba(23,105,255,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:32px;"><?php echo $icon; ?></div>
            <h3 style="margin:0 0 8px;font-size:1.15em;color:#111;"><?php echo $title; ?></h3>
            <p style="margin:0;color:#666;line-height:1.5;font-size:0.95em;"><?php echo $desc; ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section style="background:#f7f9fb;padding:64px 20px;">
    <div style="max-width:1100px;margin:0 auto;text-align:center;">
      <h2 style="margin:0 0 12px;font-size:1.8em;color:#111;">Cómo Funciona</h2>
      <p style="margin:0 0 48px;color:#666;font-size:1.05em;">Un proceso simple en 4 pasos</p>
      
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:24px;">
        <?php $steps = [
          ['01','Solicita Cotización','Navega nuestro catálogo y solicita precios para los productos que necesitas'],
          ['02','Recibe Propuesta','Te enviamos una cotización detallada con todos los costos incluidos'],
          ['03','Confirmamos Pedido','Acepta la cotización y nosotros iniciamos el proceso de importación'],
          ['04','Recibe tu Producto','Realiza seguimiento en tiempo real hasta que llegue a tu dirección'],
        ];
        foreach ($steps as [$num,$title,$desc]): ?>
          <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:24px;text-align:left;box-shadow:0 4px 16px rgba(0,0,0,0.04);">
            <div style="color:#ccc;font-size:2em;font-weight:700;margin-bottom:12px;"><?php echo $num; ?></div>
            <h3 style="margin:0 0 8px;font-size:1.1em;color:#111;"><?php echo $title; ?></h3>
            <p style="margin:0;color:#666;line-height:1.5;font-size:0.95em;"><?php echo $desc; ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section style="background:linear-gradient(135deg,#1769ff 0%,#0a3fbf 100%);color:#fff;padding:64px 20px;text-align:center;">
    <div style="max-width:800px;margin:0 auto;">
      <h2 style="margin:0 0 12px;font-size:1.8em;font-weight:700;">¿Listo para comenzar?</h2>
      <p style="margin:0 0 28px;font-size:1.1em;opacity:0.95;">Regístrate hoy y obtén acceso a nuestro catálogo completo</p>
      <a href="?action=register" style="display:inline-block;padding:14px 32px;background:#fff;color:#1769ff;border-radius:8px;font-weight:600;text-decoration:none;font-size:1.05em;box-shadow:0 8px 24px rgba(0,0,0,0.2);transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        Crear Cuenta Gratuita →
      </a>
    </div>
  </section>
</main>