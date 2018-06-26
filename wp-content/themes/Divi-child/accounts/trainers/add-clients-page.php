 <?php 

	global $wpdb;     
    $errors = array();  
   
    if( $_SERVER['REQUEST_METHOD'] == 'POST' ) 
      {  
   
        // Check username is present and not already in use  
        $username = $wpdb->escape($_REQUEST['username']);  
        if ( strpos($username, ' ') !== false )
        {   
            $errors['username'] = "Sorry, no spaces allowed in usernames";  
        }  
        if(empty($username)) 
        {   
            $errors['username'] = "Please enter a username";  
        } elseif( username_exists( $username ) ) 
        {  
            $errors['username'] = "Username already exists, please try another";  
        }  
   
        // Check email address is present and valid  
        $email = $wpdb->escape($_REQUEST['email']);  
        if( !is_email( $email ) ) 
        {   
            $errors['email'] = "Please enter a valid email";  
        } elseif( email_exists( $email ) ) 
        {  
            $errors['email'] = "This email address is already in use";  
        }  
		
		//Check Firstname
		if($_POST['fname'] == ""){
			$errors['firstname'] = "Please enter First Name.";
		}
   
        // Check password is valid  
        if(0 === preg_match("/.{6,}/", $_POST['password']))
        {  
          $errors['password'] = "Password must be at least six characters";  
        }  
   
        // Check password confirmation_matches  
        if(0 !== strcmp($_POST['password'], $_POST['password_confirmation']))
         {  
          $errors['password_confirmation'] = "Passwords do not match";  
        }   
		
        if(0 === count($errors)) 
         {  
   
            $password = $_POST['password']; 	
            $fname = $_POST['fname']; 	
            $lname = $_POST['lname']; 	
   
            $new_user_id = wp_create_user( $username, $password, $email );
			$user_id_role = new WP_User($new_user_id);
			$user_id_role->set_role('client');
			
			update_user_meta($user_id_role->ID, 'first_name', $fname);
			update_user_meta($user_id_role->ID, 'last_name', $lname);
			
			assignClientToTrainer($user_id_role, wp_get_current_user());
            $success = 1;
			echo "Successfully added new Client.";
?>
		<script>window.location =  "<?php echo home_url() . "/trainer/?data=clients"; ?>";</script>
<?php
        }   
    }	
?> 

<ul>
	<?php foreach($errors as $error): ?>
		<li><?php echo $error; ?></li>
	<?php endforeach; ?>	
</ul>
  
<form id="wp_signup_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post"> 
		<div class="row">
			<div class="col-lg-6">
				 <label for="username">Username</label><br>  
				<input type="text" name="username" id="username">
			</div>
			<div class="col-lg-6">
				 <label for="email">Email address</label><br>
				<input type="email" name="email" id="email"> 
			</div> 
		</div> 
		<hr>
		<div class="row">
			<div class="col-lg-6">
				<label for="fname">First Name</label><br> 
				<input type="text" name="fname" id="fname">
			</div>
			<div class="col-lg-6">
				<label for="lname">Last Name</label><br> 
				<input type="text" name="lname" id="lname">  
			</div> 
		</div> 
		<hr>		
		<div class="row">
			<div class="col-lg-6">
				<label for="password">Password</label><br>  
				<input type="password" name="password" id="password"> 
			</div>
			<div class="col-lg-6">
				<label for="password_confirmation">Confirm Password</label><br>  
				<input type="password" name="password_confirmation" id="password_confirmation">   
			</div> 
		</div>          
		<br><br>
  
        <input type="submit" id="submitbtn" name="submit" value="Add Client" />  
		<a href="<?php echo home_url().'/trainer/?data=clients'; ?>">See all Clients</a>
</form> 