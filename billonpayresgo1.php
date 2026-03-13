<?php
// Permanent redirect to internal page 'xyz.php'
header("HTTP/1.1 301 Moved Permanently");
header("Location: /pbridge.php");
exit();
?>
