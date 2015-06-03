<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link href='/assets/images/favicon.gif' rel='SHORTCUT ICON' />

<title><?php echo $page_title; ?></title>

<meta name="title" content="Leave management system for the Ministry of science and computer, Lagos State, Nigeria.">
<meta name="keywords" content="Leave Management System, Leave, Ministry of science, Ministry of computer, Lagos State, Nigeria.">
<meta name="description" content="A governmental application for managing leave applications in the Lagos State Ministry of Science and Tech">
<meta name="author" content="Abimbola Hassan">

<?php foreach ( $common_css_files as $css ): ?>
	<link rel="stylesheet" type="text/css" media="screen,projection" href="<?php echo WEB_ROOT; ?>/assets/css/<?php echo $css; ?>" />
<?php endforeach; ?>
<?php foreach ( $font_awesome_files as $font_awesome ): ?>
  <link rel="stylesheet" type="text/css" media="screen,projection" href="<?php echo WEB_ROOT; ?>/assets/fontawesome/css/<?php echo $font_awesome; ?>" />
<?php endforeach; ?>
<?php foreach ( $page_css_files as $page_css ): ?>
  <link rel="stylesheet" type="text/css" media="screen,projection" href="<?php echo WEB_ROOT; ?>/home/assets/css/<?php echo $page_css; ?>" />
<?php endforeach; ?>

</head>
<body>
	<div id="gen_container">
    	<div id="header">
        	<div><h1>Leave Management System</h1></div>
           <a href="<?php echo WEB_ROOT; ?>/?logout=yes" class="button" style="float:right;"><i class="icon-off"></i> Logout</a>
           <div class="clear_both"></div>
        </div>
        <div id="sidebar_left">
        	<ul class="navigation">
            	<li><a href="<?php echo WEB_ROOT; ?>/home/my_data.php">Profile</a></li>
                <li><a href="<?php echo WEB_ROOT; ?>/home/leavemgt.php">Apply for leave</a></li>
                <li><a href="<?php echo WEB_ROOT; ?>/home/leavemgt.php?my_lvs=all">Leave Status</a></li>
                <li><a href="<?php echo WEB_ROOT; ?>/home/chng_pwd.php">Change Password</a></li>
	            <?php if($_SESSION['emp_tp']==2 || $_SESSION['emp_tp']==3){ ?>
            	<li><a href="<?php echo WEB_ROOT ?>/home/leavemgt.php?sn=1">Approve Leave</a></li>
	            <?php } ?>
	            <?php if($_SESSION['emp_tp']==3){ ?>
            	<li><a href="<?php echo WEB_ROOT; ?>/home/crt_emp.php">Employee Management</a></li>
            	<li><a href="<?php echo WEB_ROOT; ?>/home/crt_lv_tp.php">Create Leave Type</a></li>
            	<li><a href="<?php echo WEB_ROOT; ?>/home/leavemgt.php?sn=1">Generate Report</a></li>
	            <?php } ?>
            </ul>
        </div>
        <div id="main_content">