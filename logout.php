<?php
session_start();
session_unset();
session_destroy();
header("Location: p_index.php");
exit;
?>
