<?php include __DIR__ . '/layout/header.php'; ?>
<!-- Gestión de Usuarios -->
<main class="container">
  <h2 style="margin-top:24px;">Gestión de Usuarios</h2>
  <p class="muted">Administra clientes y personal</p>
  <div class="card" style="margin-bottom:24px;">
    <form method="get" style="display:flex;gap:16px;align-items:center;">
      <input type="text" name="search" placeholder="Buscar por nombre o email..." style="flex:1;padding:10px 16px;border-radius:8px;border:1px solid #e3e8ee;">
      <select name="role" style="padding:10px 16px;border-radius:8px;border:1px solid #e3e8ee;">
        <option value="">Todos</option>
        <option value="cliente">Cliente</option>
        <option value="operador">Operador</option>
        <option value="admin">Administrador</option>
      </select>
      <button class="btn primary" type="submit">Buscar</button>
      <a href="?action=new_user" class="btn primary" style="margin-left:auto;">+ Nuevo Usuario</a>
    </form>
  </div>
  <div class="card">
    <table style="width:100%;border-collapse:collapse;">
      <thead>
        <tr style="background:#f6f8fc;">
          <th style="padding:10px 8px;text-align:left;">USUARIO</th>
          <th style="padding:10px 8px;text-align:left;">CONTACTO</th>
          <th style="padding:10px 8px;text-align:left;">ROL</th>
          <th style="padding:10px 8px;text-align:left;">ESTADO</th>
          <th style="padding:10px 8px;text-align:left;">PEDIDOS</th>
          <th style="padding:10px 8px;text-align:left;">REGISTRO</th>
          <th style="padding:10px 8px;text-align:left;">ACCIONES</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
          <td><?php echo htmlspecialchars($u['display_name'] ?? $u['email']); ?><br><span style="color:#888;font-size:0.95em;">Mi Empresa S.A.</span></td>
          <td>
            <span>📧 <?php echo htmlspecialchars($u['email']); ?></span><br>
            <span>📞 <?php echo htmlspecialchars($u['phone'] ?? '+1 234 567 8901'); ?></span>
          </td>
          <td><span style="background:#e3e8ee;color:#1769ff;padding:2px 8px;border-radius:8px;font-size:0.95em;">Cliente</span></td>
          <td><span style="background:#e6ffed;color:#12b76a;padding:2px 8px;border-radius:8px;font-size:0.95em;">Activo</span></td>
          <td><?php echo htmlspecialchars($u['orders'] ?? '0'); ?></td>
          <td><?php echo htmlspecialchars($u['created_at'] ?? '2024-01-01'); ?></td>
          <td>
            <a href="?action=edit_user&id=<?php echo $u['id']; ?>" style="color:#1769ff;font-size:1.2em;margin-right:8px;">✏️</a>
            <a href="?action=delete_user&id=<?php echo $u['id']; ?>" style="color:#d32f2f;font-size:1.2em;">🗑️</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
<?php include __DIR__ . '/layout/footer.php'; ?>
