<?php					
	$udata = wp_get_current_user();		
	$scheData = getSchedData($udata);
	/* jabs($udata) */
?>
<div class="modal fade" id="workoutModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Your workout(s)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p></p>
      </div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Optional JavaScript -->
  <script src='<?php echo get_stylesheet_directory_uri() .'/accounts/assets/js/moment.min.js';?>'></script>
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <!-- <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/js/jquery-3.2.1.slim.min.js"></script> -->
  <script src='<?php echo get_stylesheet_directory_uri() .'/accounts/assets/js/jquery-3.3.1.min.js';?>'></script>
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/js/responsive-calendar.min.js"></script>
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/js/popper.min.js"></script>
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/js/bootstrap.min.js"></script>
  <!-- <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/js/jquery.matchHeight-min.js"></script>  --> 
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/js/dataTables.bootstrap.min.js"></script>

  <!-- <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() .'/accounts/assets/js/fullcalendar.min.js';?>"></script> -->
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/js/jquery.canvasjs.min.js"></script>  

  <script type="text/javascript">
   var workoutDates = <?php echo json_encode($scheData); ?>,
   themedir = "<?php echo get_stylesheet_directory_uri(); ?>";
   dateToday = "<?php echo date('Y-m-d'); ?>";
   function pullWorkout(date){
		var thisWork = workoutDates[date];
		$('#workoutModal').modal();
		$('#workoutModal .modal-title').html('Workout(s) for ' + date);
		htmlContent = '<ul class="workout-lists trainer-workouts-lists">';	  
		$.each(thisWork, function(i, v){
			htmlContent += '<li class="workout-list-item"><div class="workout-wrapper"><span><img src="'+themedir+'/accounts/images/workout.png"></span><span class="wdname">' + v[0]['wdname'] + '</span>';
			if(dateToday == date){
				htmlContent += '&nbsp;<a href="'+v[0]['daylink']+'"><img src="'+themedir+'/accounts/images/workout-play.png" /></a>';
			}			
			htmlContent += '<a href="#"><img src="'+themedir+'/accounts/images/workout-note.png"></a></div></li>';
		});
		htmlContent += '</ul>';
		$('#workoutModal .modal-body').html(htmlContent);
   }

  jQuery('#table-sorter').DataTable({
    "lengthMenu": [[8, 16, 24, -1], [8, 16, 24, "All"]]
  });

  jQuery('#table-sorter-logs').DataTable({
	"paging": false,
	"info": false,
	"order": []
  });
  
  /* jQuery('.matchHeight').matchHeight(); */
  /* jQuery('.trainer-schedule-wrapper').matchHeight(); */
  
  
  // Assign active class in the navigation
  var sPageURL = window.location.search.substring(1);
  var sURLVariables = sPageURL.split('&');
  for (var i = 0; i < sURLVariables.length; i++){
      var sParameterName = sURLVariables[i].split('=');
      jQuery('.main-navigation ul li a').each(function () {
        if( jQuery(this).attr('menu-item') === sParameterName[1] ){
          jQuery(this).addClass('active');
        }
      });
  } 

	jQuery(document).ready(function () {
		if($("#myModal").length != 0){
			$("#myModal").on('hidden.bs.modal', function (e) {
				$("#myModal iframe").attr("src", "");
			});
		}
		function alz(num) {
			if (num < 10) {
				return "0" + num;
			} else {
				return "" + num;
			}
		}
		if($('.responsive-calendar').length != 0){
			$(".responsive-calendar").responsiveCalendar({
				onDayClick: function(events) {
					var key = $(this).data('year')+'-'+alz( $(this).data('month') )+'-'+alz( $(this).data('day'));										
					if(events[key]){
						pullWorkout(key);
					}
				},
				events: {
				<?php
					$ctrTemp = 0;
					$nctr = count($scheData);
					foreach($scheData as $k=>$v):
						$ctrTemp++;					
						echo '"'.$k.'": {"number": '.count($v).',"badgeClass": "badge-primary", "url": ""}';
						echo ($ctrTemp == $nctr) ? "" : ","; 
					endforeach;
				?>
				}	
			});		
		}
		
		if(jQuery('.chartContainer').length != 0){
			jQuery(".chartContainer").CanvasJSChart({
				title: {
					text: ""
				},
				axisY: {
					title: "",
					includeZero: false
				},
				axisX: {
					interval: 10
				},
				data: [
					{
						type: "line", //try changing to column, area
						toolTipContent: "{label}: {y} mm",
						dataPoints: [
							{ label: "Jan",  y: 5.28 },
							{ label: "Feb",  y: 3.83 },
							{ label: "March",y: 6.55 },
							{ label: "April",y: 4.81 },
							{ label: "May",  y: 2.37 },
							{ label: "June", y: 2.33 },
							{ label: "July", y: 3.06 },
							{ label: "Aug",  y: 2.94 },
							{ label: "Sep",  y: 5.41 },
							{ label: "Oct",  y: 2.17 },
							{ label: "Nov",  y: 2.17 },
							{ label: "Dec",  y: 2.80 }
						]
					}
				]
			});  		  
		}       
	});
	</script>	
  </body>
</html>