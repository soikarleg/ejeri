<div id="cookie-banner">
  <div class="p-4">
    <p class="titre_menu_item">Utilisation de cookies</p>
    <p class="mb-4">Nous utilisons des cookies afin d'améliorer l'utilisation de cette application.<br> En continuant, vous acceptez le dépôt de ces cookies dans votre navigateur.</p>
    <div class="text-muted">
      <p class="small text-primary">Infos système</p>
      <p class="small text-muted"><?php echo htmlspecialchars($_SERVER['HTTP_USER_AGENT']); ?></p>
      <p class="small text-muted mb-4"><?php echo htmlspecialchars(php_uname('s')); ?></p>
    </div>
    <p class="btn btn-mag-n text-success" onclick="acceptCookies()">Accepter le dépôt des cookies</p>
  </div>
</div>