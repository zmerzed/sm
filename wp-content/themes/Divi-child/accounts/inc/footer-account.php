<!-- Optional JavaScript -->
  
  <script src='<?php echo get_stylesheet_directory_uri() .'/accounts/assets/js/moment.min.js';?>'></script>

  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js"></script>
  
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>

  <script src='<?php echo get_stylesheet_directory_uri() .'/accounts/assets/js/fullcalendar.min.js';?>'></script>
  <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>

  <script type="text/javascript">

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