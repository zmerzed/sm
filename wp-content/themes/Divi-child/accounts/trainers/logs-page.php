<?php
	global $wpdb;
	$activity_query = 'SELECT * FROM workout_activity_logs WHERE user_id = "'.get_current_user_id().'" ORDER BY id DESC';
	$get_activity = $wpdb->get_results($activity_query);
	
	
	/* echo "<pre>";
	print_r($get_activity);
	echo "</pre>"; */
?>

<div class="main-content matchHeight">
	<table id="table-sorter-logs" class="table table-striped table-bordered" style="width:100%">
	    <thead>
	        <tr>
				<th>Date</th>
	            <th>Activity</th>
	            <th>User</th>
	            
	        </tr>
	    </thead>
	    <tbody>
			<?php foreach($get_activity as $act_info):
				$user_info = get_userdata($act_info->user_id);
				$newDate = date_create($act_info->created_at);
			?>
				<tr>
					<td><?php echo date_format($newDate, "F d, Y - g:i a"); ?></td>	
					<td><?php echo $act_info->log_description; ?></td>
					<td><?php echo $user_info->first_name . ' ' . $user_info->last_name; ?></td>									
				</tr>
			<?php endforeach; ?>
	       <!--  <tr>
	            <td style="width: 15%;">Workout Completed</td>
	            <td style="width: 15%;"><i>Self</i></td>
	            <td style="width: 12%;">March 13, 2018</td>
	            <td style="width: 17%;">3:45 PM</td>
	        </tr> -->
	    </tbody>
	</table>
</div>