<?php
require_once __DIR__ . '/php/admin_auth.php';
logout_admin();
header('Location: admin_login.php');
exit;
