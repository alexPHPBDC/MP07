<?php 
require_once("includes/db.php");
require_once("facelog.php");
// exit if uninstall constant is not defined
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

// remove plugin options
facelog_deleteOptions();

// ..etc., based on what needs to be removed
facelog_deleteTables();
facelog_deletePages();
facelog_deleteImages();


// Més info: https://digwp.com/2019/11/wordpress-uninstall-php/