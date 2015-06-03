<?php
/*
* Include necessary files
*/
include_once 'core/init.inc.php';
/*
* Set up the page title and CSS files
*/
$page_title = ":: Home &rsaquo;&rsaquo; Leave management system ::";
$common_css_files = array('jquery-ui-1.8.21.custom.css','common.css');
$page_css_files = array('general.css');
$font_awesome_files = array('font-awesome.css', 'prettify.css');
$general_js_files = array('jquery-1.7.2.min.js', 'jquery-ui-1.8.21.custom.min.js', 'slides.min.jquery.js');
$page_js_files = array('general.js', 'common.js');

/*
* Include the header
*/
include_once 'assets/common/header.inc.php';
?>

<!-- Body content here -->
<?php
/*
* Include the footer
*/
include_once 'assets/common/footer.inc.php';
?>