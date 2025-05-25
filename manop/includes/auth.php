<?php
session_start();

function isLoggedIn() {
  return isset($_SESSION['user']);
}

function requireRole($roles) {
  if (!isLoggedIn() || !in_array($_SESSION['user']['role'], (array)$roles)) {
    header('Location: /login.php'); exit;
  }
}

