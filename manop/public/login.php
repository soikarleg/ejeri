<?php
?>
<form method="POST">
  <input name="username" required>
  <input type="password" name="password" required>
  <input type="text" name="website" style="display:none"> <!-- Honeypot -->
  <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
  <button>Connexion</button>
</form>

