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
	        <tr>
	            <td><img src="/wp-content/uploads/2018/03/gary-muscleton.png"></td>
	            <td>Trainer Name #1</td>
	            <td>9:30 am</td>
	            <td>4 Days Ago</td>
	            <td>4 Clients</td>
	        </tr>
	        <tr>
	            <td><img src="/wp-content/uploads/2018/03/larry-muscleton.png"></td>
	            <td>Trainer Name #1</td>
	            <td>2:45s</td>
	            <td>4 Days Ago</td>
	           <td>4 Clients</td>
	        </tr>
	        <tr>
	            <td><img src="/wp-content/uploads/2018/03/gary-muscleton.png"></td>
	            <td>Trainer Name #1</td>
	            <td>Tuesday<br>8:45 am</td>
	            <td>4 Days Ago</td>
	            <td>4 Clients</td>
	        </tr>
	        <tr>
	            <td><img src="/wp-content/uploads/2018/03/larry-muscleton.png"></td>
	            <td>Trainer Name #1</td>
	            <td>March 26th<br>9:45 am</td>
	            <td>4 Days Ago</td>
	            <td>4 Clients</td>
	        </tr>
	        <tr>
	            <td><img src="/wp-content/uploads/2018/03/gary-muscleton.png"></td>
	            <td>Trainer Name #1</td>
	            <td>8:15 am</td>
	            <td>4 Days Ago</td>
	            <td>5 Clients</td>
	        </tr>
	        <tr>
	            <td><img src="/wp-content/uploads/2018/03/larry-muscleton.png"></td>
	            <td>Trainer Name #1</td>
	            <td>Tomorrow<br>8:30 am</td>
	            <td>4 Days Ago</td>
	            <td>4 Clients</td>
	        </tr>
	    </tbody>
	</table>
	<?php endif; ?>
</div>