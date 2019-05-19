<?php
//JF: Front page! 
include_once "bj_package.php";
session_start();
session_destroy();
Htmller::print_html_header();

echo "<h1>Thanks for playing Blackjack!<h1>";
echo "<h3><a href='index.php'>Go to Login Page</a></h3>";

echo "<br>";




?>