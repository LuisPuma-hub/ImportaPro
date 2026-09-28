<!-- Detalle de Producto -->
<main class="container">
  <div class="card" style="max-width:600px;margin:auto;">
    <h2><?php echo htmlspecialchars($product['title']); ?></h2>
    <p><strong>Categoría:</strong> <?php echo htmlspecialchars($product['category_name'] ?? ''); ?></p>
    <p><strong>País de Origen:</strong> <?php echo htmlspecialchars($product['country'] ?? ''); ?></p>
    <p><strong>Precio FOB:</strong> $<?php echo htmlspecialchars($product['price_cents'] / 100); ?></p>
    <p><strong>Stock:</strong> <?php echo htmlspecialchars($product['stock']); ?> unidades</p>
    <p><strong>Descripción:</strong> <?php echo nl2br(htmlspecialchars($product['description'] ?? '')); ?></p>
    <a href="?action=products" class="btn">Volver al listado</a>
  </div>
</main>
