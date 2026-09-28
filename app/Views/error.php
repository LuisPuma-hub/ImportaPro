<!-- Mensaje de error reutilizable -->
<?php if (!empty($error_message)): ?>
  <div class="error" style="margin-bottom:16px;"> <?php echo htmlspecialchars($error_message); ?> </div>
<?php endif; ?>
