<?php
//JF: Front page! 
include_once "bj_package.php";
session_start();
Htmller::print_html_header();
Htmller::trigger_get_messages();
Htmller::check_login();
Htmller::check_register();

echo "<h3>Please login or register</h3>";
Htmller::print_login_form();
echo "<br>";
Htmller::print_create_user_form();
Htmller::print_html_footer();



?>