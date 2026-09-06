<?php
/**
 * Logout
 */

require_once 'config/auth.php';

logoutUser();
header('Location: /');
exit;
