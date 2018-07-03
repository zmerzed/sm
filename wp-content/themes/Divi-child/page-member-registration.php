<?php
/*
* Template Name: Member Registration
*/

get_header();
?>
<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
				<?php 
					$ftri = "";
					$mem_level = "";
					$swpm_user = array();
					if(isset($_GET['member_id'])){
						$swpm_user = SwpmMemberUtils::get_user_by_id($_GET['member_id'])->membership_level;
						if($swpm_user == 4){
							$mem_level = "Trainer";
						}elseif($swpm_user == 2){
							$mem_level = "Gym";
						}
					}else{	
						$ftri = "Free Trial";						
						
						if(isset($_GET['alt_mr'])){
							$mem_level = "Trainer";
						}else{
							$mem_level = "Gym";
						}
					}
				?>
				<h2>Registration ( <?php echo $mem_level; ?> <?php echo $ftri; ?> )</h2>
				<?php
					if(isset($_GET['alt_mr'])){
						echo do_shortcode('[swpm_registration_form level=6]');
					}else{
						echo do_shortcode('[swpm_registration_form]');
					}
				 ?>
			</div>
		</div>
	</div>
</div>


<?php get_footer();