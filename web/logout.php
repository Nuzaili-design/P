<?php
session_start();
session_destroy(); // Invalidate the session so no values will be present in session
echo "<script>alert('Logout successful!'); window.location.href='index.php';</script>";
exit;
?>

