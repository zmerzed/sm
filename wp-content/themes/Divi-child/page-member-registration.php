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
					if(isset($_GET['alt_mr'])){
						echo do_shortcode('[swpm_registration_form level=6]');
					}else{
						echo do_shortcode('[swpm_registration_form level=2]');
					}
				 ?>
			</div>
		</div>
	</div>
</div>


<?php get_footer();