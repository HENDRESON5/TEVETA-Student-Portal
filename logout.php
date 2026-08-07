<?php
require_once 'config/auth.php';
do_logout();
header("Location: login.php");
exit;
 