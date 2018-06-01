<!-- Optional JavaScript -->
  
  <script src='<?php echo get_stylesheet_directory_uri() .'/accounts/assets/js/moment.min.js';?>'></script>

  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/js/jquery-3.2.1.slim.min.js"></script>
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/js/popper.min.js"></script>
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/js/jquery.matchHeight-min.js"></script>  
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/js/dataTables.bootstrap.min.js"></script>

  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() .'/accounts/assets/js/fullcalendar.min.js';?>"></script>
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/accounts/assets/js/jquery.canvasjs.min.js"></script>

  <script type="text/javascript">
	$(document).ready(function(){
		if($("#myModal").length != 0){
			$("#myModal").on('hidden.bs.modal', function (e) {
				$("#myModal iframe").attr("src", "");
			});
		}		
	});

  jQuery('#table-sorter').DataTable({
    "lengthMenu": [[8, 16, 24, -1], [8, 16, 24, "All"]]
  });

  jQuery('#table-sorter-logs').DataTable();
  
  jQuery('.matchHeight').matchHeight();
  jQuery('.trainer-schedule-wrapper').matchHeight();

  jQuery('#calendar').fullCalendar({
    defaultDate: '2018-03-12',
    columnHeaderFormat: 'dddd',
    eventLimit: true, // allow "more" link when too many events
    events: [
      {
        title: 'workout NAME #1',
        start: '2018-03-23',
        className: 'workoutclass'
      }
    ]
  });

  // Assign active class in the navigation
  var sPageURL = window.location.search.substring(1);
  var sURLVariables = sPageURL.split('&');

  for (var i = 0; i < sURLVariables.length; i++)
  {
      var sParameterName = sURLVariables[i].split('=');

      jQuery('.main-navigation ul li a').each(function () {
        if( jQuery(this).attr('menu-item') === sParameterName[1] ){
          jQuery(this).addClass('active');
        }
      });
  }

  
  jQuery(document).ready(function () {

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

      
      
  });

  </script>

  </body>

</html>