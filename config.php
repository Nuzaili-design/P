<?php
session_start();
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$lang = $_SESSION['lang'] ?? 'en';
$lang_file = __DIR__ . "/lang/{$lang}.php";
$translations = file_exists($lang_file) ? include($lang_file) : include(__DIR__ . "/lang/en.php");
?>
