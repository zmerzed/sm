<div class="main-content matchHeight">
	<table id="table-sorter-logs" class="table table-striped table-bordered" style="width:100%">
	    <thead>
	        <tr>
	            <th>Photo</th>
	            <th>Name</th>
	            <th>Purpose</th>
	            <th>Trainer</th>
	            <th>Last Activity</th>
	            <th>Goal</th>
	        </tr>
	    </thead>
	    <tbody>
			<?php 
				$trainers = get_user_meta(wp_get_current_user()->ID, 'trainers_of_gym', true);
				$clientsArr = array();
				foreach($trainers as $trainer){
					$clients = get_user_meta($trainer, 'clients_of_trainer', true);
					$tempArr = array();
					foreach($clients as $client){
						$tempArr[] = $client;						
					}
					
					$clientsArr[$trainer] = $tempArr;
				}
				
				foreach($clientsArr as $client_ => $value):					
					$trainerInfo = get_user_by('id', $client_);					
					foreach($value as $client__):
						$user_info = get_user_by('id', $client__);
						$user_ava = get_avatar( $user_info->ID, 32 );
						if($user_info):
			?>
				<tr>
					<td><?php echo $user_ava; ?></td>
					<td><?php echo $user_info->first_name . ' ' . $user_info->last_name; ?></td>
					<td> -- </td>
					<td><?php echo $trainerInfo->first_name; ?></td>
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
					endforeach;
				endforeach;
			?>
	        <!-- <tr>
	            <td><img src="<?php echo get_stylesheet_directory_uri().'/accounts/images/client-2.png';?>"></td>
	            <td>Client Name #1</td>
	            <td>Fat Loss</td>
	            <td>4 Days Ago</td>
	            <td>
	            	<div class="progress client-goals-percentage">
						<div class="progress-bar" style="width: 80%;">
							<span class="indicator"><small>80%</small></span>
						</div>
					</div>
	            </td>
	        </tr>
			-->
	    </tbody>
	</table>
</div>