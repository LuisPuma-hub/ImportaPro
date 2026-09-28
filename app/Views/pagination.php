<!-- Paginación reutilizable -->
<?php if ($totalPages > 1): ?>
<nav style="margin:24px 0;text-align:center;">
  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?action=products&page=<?php echo $i; ?><?php if (!empty($_GET['search'])) echo '&search=' . urlencode($_GET['search']); ?>" class="btn<?php echo $i == $currentPage ? ' primary' : ''; ?>" style="margin:0 4px;"> <?php echo $i; ?> </a>
  <?php endfor; ?>
</nav>
<?php endif; ?>
