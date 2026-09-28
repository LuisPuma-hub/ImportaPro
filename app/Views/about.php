<?php include __DIR__ . '/layout/header.php'; ?>
<main style="font-family: 'Segoe UI', Arial, sans-serif; color:#111; background:#f7f9fb;">
  <!-- Hero -->
  <section style="background:linear-gradient(135deg,#0062ff 0%,#0a3fbf 100%);color:#fff;padding:64px 20px;">
    <div style="max-width:1200px;margin:0 auto;">
      <p style="margin:0 0 12px;font-weight:600;opacity:0.9;">Sobre ImportaPro</p>
      <h1 style="margin:0 0 16px;font-size:2.1em;font-weight:700;">Somos una empresa líder en servicios de importación</h1>
      <p style="margin:0;font-size:1.05em;max-width:820px;line-height:1.6;opacity:0.95;">
        Facilitamos el comercio internacional para empresas y emprendedores, con procesos claros,
        eficientes y acompañamiento experto en cada paso.
      </p>
    </div>
  </section>

  <!-- Métricas -->
  <section style="background:#fff;padding:48px 20px;">
    <div style="max-width:1100px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:24px;text-align:center;">
      <?php $stats = [
        ['Clientes Satisfechos','5,000+','👥'],
        ['Años de Experiencia','15+','🎯'],
        ['Países de Origen','50+','🌎'],
        ['Tasa de Éxito','98%','📈'],
      ];
      foreach ($stats as [$label,$value,$icon]): ?>
        <div style="padding:20px 16px;">
          <div style="width:56px;height:56px;margin:0 auto 12px;border-radius:50%;background:rgba(23,105,255,0.12);display:flex;align-items:center;justify-content:center;font-size:20px;"><?php echo $icon; ?></div>
          <div style="font-size:1.4em;font-weight:700;color:#0f3fae;"><?php echo $value; ?></div>
          <div style="color:#666;font-size:0.95em;"><?php echo $label; ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Misión / Visión -->
  <section style="background:#f7f9fb;padding:48px 20px;">
    <div style="max-width:1100px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:32px;">
      <div style="background:#fff;border-radius:10px;padding:24px;border:1px solid #e5e9f2;box-shadow:0 6px 20px rgba(0,0,0,0.04);">
        <h3 style="margin:0 0 12px;font-size:1.2em;color:#0f3fae;">Nuestra Misión</h3>
        <p style="margin:0;color:#444;line-height:1.6;font-size:0.98em;">
          Democratizar el acceso al comercio internacional con servicios confiables,
          transparentes y eficientes que impulsen el crecimiento de nuestros clientes.
        </p>
      </div>
      <div style="background:#fff;border-radius:10px;padding:24px;border:1px solid #e5e9f2;box-shadow:0 6px 20px rgba(0,0,0,0.04);">
        <h3 style="margin:0 0 12px;font-size:1.2em;color:#0f3fae;">Nuestra Visión</h3>
        <p style="margin:0;color:#444;line-height:1.6;font-size:0.98em;">
          Ser la plataforma de importación más confiable e innovadora de la región,
          conectando mercados globales con tecnología y procesos optimizados.
        </p>
      </div>
    </div>
  </section>

  <!-- Valores -->
  <section style="background:#fff;padding:48px 20px;">
    <div style="max-width:1100px;margin:0 auto;">
      <div style="text-align:center;margin-bottom:24px;">
        <h2 style="margin:0 0 8px;font-size:1.6em;">Nuestros Valores</h2>
        <p style="margin:0;color:#666;">Los principios que guían nuestro trabajo diario</p>
      </div>
      <?php $values = [
        ['Transparencia','Información clara y detallada en cada etapa del proceso.'],
        ['Confiabilidad','Cumplimos compromisos y entregamos resultados consistentes.'],
        ['Innovación','Adoptamos tecnología para mejorar continuamente.'],
        ['Orientación al Cliente','Tu éxito es nuestro éxito, superamos expectativas.'],
        ['Integridad','Operamos con estándares éticos y profesionales.'],
        ['Eficiencia','Optimizamos procesos sin comprometer la calidad.'],
      ]; ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;">
        <?php foreach ($values as [$title,$desc]): ?>
          <div style="background:#fff;border:1px solid #e5e9f2;border-radius:10px;padding:18px;box-shadow:0 4px 14px rgba(0,0,0,0.04);">
            <h4 style="margin:0 0 8px;font-size:1.05em;color:#0f3fae;"><?php echo $title; ?></h4>
            <p style="margin:0;color:#555;line-height:1.5;font-size:0.95em;">&middot; <?php echo $desc; ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Equipo -->
  <section style="background:#f7f9fb;padding:48px 20px;">
    <div style="max-width:1100px;margin:0 auto;">
      <div style="text-align:center;margin-bottom:28px;">
        <h2 style="margin:0 0 8px;font-size:1.6em;">Nuestro Equipo</h2>
        <p style="margin:0;color:#666;">Profesionales dedicados a tu éxito</p>
      </div>
      <?php $team = [
        ['Ana García','CEO & Fundadora','https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=400&q=80'],
        ['Carlos Mendoza','Director de Operaciones','https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=crop&w=400&q=80'],
        ['María López','Gerente de Logística','https://images.unsplash.com/photo-1544723795-3fb0f9ae71e2?auto=format&fit=crop&w=400&q=80'],
        ['Roberto Silva','Director Comercial','https://images.unsplash.com/photo-1524504388940-1e1a90b24b66?auto=format&fit=crop&w=400&q=80'],
      ]; ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;align-items:start;">
        <?php foreach ($team as [$name,$role,$img]): ?>
          <div style="text-align:center;padding:12px;">
            <div style="width:160px;height:160px;margin:0 auto 12px;border-radius:50%;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,0.12);">
              <img src="<?php echo $img; ?>" alt="<?php echo $name; ?>" style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div style="font-weight:600;color:#111;margin-bottom:4px;"><?php echo $name; ?></div>
            <div style="color:#666;font-size:0.95em;"><?php echo $role; ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

<?php include __DIR__ . '/layout/footer.php'; ?>
