<?php
  global $current_user;
  $userdata = get_currentuserinfo();
?>
<!doctype html>
<html lang="en">
  <head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,800,900" rel="stylesheet">
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

  <?php
    global $current_user;
    $userdata = get_currentuserinfo();	
	$member_type = bp_get_member_type($userdata->data->ID);
    $data_request = $_GET['data'];

    if( $data_request === 'notes' || $data_request === 'logs' ||  $data_request === null || $data_request === 'exercises' || $data_request === 'clients'){
  ?>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/css/dataTables.bootstrap.min.css" rel="stylesheet">
  <?php } ?>

  <link href='<?php echo get_stylesheet_directory_uri() .'/accounts/assets/css/fullcalendar.min.css'; ?>' rel='stylesheet' />
  <link href='<?php echo get_stylesheet_directory_uri() .'/accounts/assets/css/fullcalendar.print.min.css'; ?>' rel='stylesheet' media='print' />
  
  <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri() .'/accounts/bootstrap/css/account-style.css'; ?>">
  <title>My Account - <?php echo $userdata->data->user_login; ?></title>

  </head>

  <body class="<?php echo ($member_type == 'gym') ? 'gym-page' : ''; ?>">

  <div class="header-section">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-6">
			<?php if($member_type == "gym"): ?>
				<a href="#"><img id="logo" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/images/gym-plus-logo.png"></a>
			<?php else: ?>
				<a href="#"><img id="logo" src="<?php echo home_url(); ?>/wp-content/uploads/2018/02/sm-logov2-wht.svg"></a>
			<?php endif; ?>
        </div>
        <div class="col-lg-6 col-md-6">
          <a id="logout_btn" href="<?php echo wp_logout_url(); ?>">Logout</a>
        </div>
      </div>
    </div>
  </div>