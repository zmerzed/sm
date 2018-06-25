<div class="main-content matchHeight">
	<?php if($_GET['add']):
		get_template_part( 'accounts/trainers/add-clients', 'page' );
	else: ?>
	<div class="trainer-add-workout">
		<a href="<?php echo home_url(); ?>/trainer/?data=clients&add=1">+ Add Client</a>
	</div>
	<table id="table-sorter-logs" class="table table-striped table-bordered" style="width:100%">
	    <thead>
	        <tr>
	            <th>Photo</th>
	            <th>Name</th>
	            <th>Purpose</th>
	            <th>Last Activity</th>
	            <th>Goal</th>
	        </tr>
	    </thead>
	    <tbody>
	       <!-- <tr>
	            <td><img src="<?php echo get_stylesheet_directory_uri().'/accounts/images/client-1.png';?>"></td>
	            <td>Client Name #1</td>
	            <td>Fat Loss</td>
	            <td>4 Days Ago</td>
	            <td>
	            	<div class="progress client-goals-percentage">
						<div class="progress-bar" style="width: 100%;">
							<span class="indicator"><small>100%</small></span>
						</div>
					</div>
	            </td>
	        </tr> -->
	       <?php $clients = get_user_meta(wp_get_current_user()->ID, 'clients_of_trainer', true);
				foreach($clients as $client):
					$user_info = get_user_by('id', $client);
					$user_ava = get_avatar( $user_info->ID, 50);
					if($user_info):
			?>
				<tr>
					<td>
						<?php echo $user_ava; ?>			
					</td>
					<td><?php echo $user_info->first_name . ' ' . $user_info->last_name;  ?></td>
					<td> -- </td>
					<td>-- Days Ago</td>
					<td>
						<div class="progress client-goals-percentage">
							<div class="progress-bar" style="width: 0%;">
								<span class="indicator"><small>0%</small></span>
							</div>
						</div>
					</td>
				</tr>
			<?php
					endif;
				endforeach; ?>	
	    </tbody>
	</table>
	<?php endif; ?>
</div>