<?php
	global $wpdb;
	$activity_query = 'SELECT * FROM workout_activity_logs WHERE user_id = "'.get_current_user_id().'" ORDER BY id DESC';
	$get_activity = $wpdb->get_results($activity_query);
	
	$results_per_page = 10;
	$number_of_results = count($get_activity);	
	$number_of_pages = ceil($number_of_results / $results_per_page);
	$cur_page_ = 1;
	
	if(!isset($_GET['page_'])){
		$page_ = 1;
	}else{
		$page_ = $_GET['page_'];
	}
	
	$this_page_first_result = ($page_ - 1)*$results_per_page;	
	$activity_query_2 = 'SELECT * FROM workout_activity_logs WHERE user_id = "'.get_current_user_id().'" ORDER BY id DESC LIMIT ' . $this_page_first_result . ',' . $results_per_page;
	$get_activity_2 = $wpdb->get_results($activity_query_2);
	$this_page_last_result = count($get_activity_2);		

?>

<div class="main-content matchHeight logs-page">
	<table id="table-sorter-logs" class="table table-striped table-bordered" style="width:100%">
	    <thead>
	        <tr>
				<th>Activity</th>
				<th>User</th>
				<th>Date</th>
				<th>Time</th> 
	        </tr>
	    </thead>
	    <tbody>
			<?php foreach($get_activity_2 as $act_info):
				$user_info = get_userdata($act_info->user_id);
				$newDate = date_create($act_info->created_at);
			?>
				<tr>
					<td><?php echo $act_info->log_description; ?></td>
					<td><?php echo $user_info->first_name . ' ' . $user_info->last_name; ?></td>	
					<td><?php echo date_format($newDate, "F d, Y"); ?></td>	
					<td><?php echo date_format($newDate, "g:i a"); ?></td>	
					
													
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
	<div class="col-lg-6 col-md-6 col-sm-12 logs-paginate-info">
		<?php
			$last_res = $this_page_first_result + $this_page_last_result;
			if($last_res != $this_page_first_result+1){
				echo 'Showing <b>'. ($this_page_first_result+1) . ' to '. $last_res .'</b> of '. $number_of_results. ' entries';
			}else{
				echo 'Showing <b>'. ($this_page_first_result+1) . '</b> of '. $number_of_results. ' entries';
			}						
		?>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12">
		<ul class="custom-paginate">		
		<li><a href="<?php echo ($page_ <= 1) ? 'javascript:void(0);' : home_url().'/trainer/?data=logs&page_=' . ($page_-1); ?>">Prev</a></li>	
		<?php 
			for($page__=1;$page__<=$number_of_pages;$page__++){
				
				if(isset($_GET['page_'])){
					$a_page = $_GET['page_'];
					if($a_page == $page__){
						echo '<li class="current-page-link"><a href="javascrip:void(0);">'. $page__ .'</a></li>';
					}else{
						echo '<li><a href="'.home_url().'/trainer/?data=logs&page_='. $page__ .'">'. $page__ .'</a></li>';
					}
				}else{
					if($cur_page_ == $page__){
						echo '<li class="current-page-link"><a href="javascrip:void(0);">'. $page__ .'</a></li>';
					}else{
						echo '<li><a href="'.home_url().'/trainer/?data=logs&page_='. $page__ .'">'. $page__ .'</a></li>';
					}
				}								
			}
		?>
		<li><a href="<?php echo ($page_ < $number_of_pages) ? home_url().'/trainer/?data=logs&page_=' . ($page_+1) :  'javascript:void(0);'; ?>">Next</a></li>
		</ul>		
	</div>	
</div>