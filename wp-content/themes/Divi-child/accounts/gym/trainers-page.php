<div class="main-content matchHeight gym-trainer-page">
	<?php if($_GET['add']):
		get_template_part( 'accounts/gym/add-trainers', 'page' );
	else: ?>
	<div class="trainer-add-workout">
		<a href="<?php echo home_url(); ?>/gym/?data=trainers&add=1">+ Add Trainer</a>
	</div>
	<table id="table-sorter-logs" class="table table-striped table-bordered" style="width:100%">
	    <thead>
	        <tr>
	            <th>Photo</th>
	            <th>Name</th>
	            <th>Schedule</th>
	            <th>Last Activity</th>
	            <th># of Clients</th>
	        </tr>
	    </thead>
	    <tbody>
	        <!-- <tr>
	            <td><img src="/wp-content/uploads/2018/03/gary-muscleton.png"></td>
	            <td>Trainer Name #1</td>
	            <td>9:30 am</td>
	            <td>4 Days Ago</td>
	            <td>4 Clients</td>
	        </tr> -->
		<?php $trainers = get_user_meta(wp_get_current_user()->ID, 'trainers_of_gym', true);
			foreach($trainers as $trainer):
				$user_info = get_user_by('id', $trainer);
				$user_ava = get_avatar( $user_info->ID );
				if($user_info):
		?>
			<tr>
	            <td>
					<?php echo $user_ava; ?>			
				</td>
	            <td><?php echo $user_info->first_name . ' ' . $user_info->last_name;  ?></td>
	            <td>-:-- --</td>
	            <td>-- Days Ago</td>
	            <td>-- Clients</td>
	        </tr>
		<?php
			endif;
			endforeach; ?>			
	    </tbody>
	</table>
	<?php endif; ?>
</div>