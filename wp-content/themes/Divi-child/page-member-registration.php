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
				<h2>Registration ( <?php echo(isset($_GET['alt_mr'])) ? "Trainer" : "Gym"; ?> Free Trial )</h2>
				<?php
					if(isset($_GET['alt_mr'])){
						echo do_shortcode('[swpm_registration_form level=6]');
					}else{
						echo do_shortcode('[swpm_registration_form level=3]');
					}
				 ?>
			</div>
		</div>
	</div>
</div>


<?php get_footer();