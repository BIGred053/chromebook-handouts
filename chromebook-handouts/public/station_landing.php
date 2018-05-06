<?php
  // Landing page for volunteers at a given station. Based on the station number, appointments are laid out in a grid of boxes with appointment time,
  // name of the parent who made the appointment, and student name(s) for Chromebooks to be picked up
    require_once('../includes/initialize.php');

    if(isset($_POST['submit']) or isset($_SESSION['station'])){

      if(isset($_POST['submit'])){
        if($_POST['station_select']=="overflow"){
          redirect_to("overflow.php");
        }
          $station = $_POST['station_select'];
          $date = $_POST['date_select'];
          $_SESSION['station']=$station;
          $_SESSION['date'] = $date;
      } else{
          $station = $_SESSION['station'];
          $date = $_SESSION['date'];
      }

      // Use this station number for DB call to pull scheduled pickups
      // Will include Pickup Name, Time, Email

      $stmt = "SELECT * FROM pickups ";
      $stmt .= "WHERE date=:date ";
      $stmt .= "AND station_id=:station ";
      $stmt .= "ORDER BY time";

      $params = array(":date"=>$date, ":station"=>$station);

      $station_pickups_today = $database->query($stmt, $params);
    } else {
      redirect_to("index.php");
    }

 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <title><?php echo $station; ?></title>
     <link rel="stylesheet" href="css/bootstrap.min.css">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.js"></script>
     <script src="js/bootstrap.min.js" charset="utf-8"></script>
     <script type="text/javascript">
        $(document).ready(function(){
          var total_checked = 0;

          $(".station").click(function() {
            var parent = $(this).closest('div');
            var hiddenCheckbox = parent.find(':checkbox');
            if(!parent.hasClass("station-selected")){
              parent.addClass("station-selected");
              hiddenCheckbox.attr("checked", "checked");
              total_checked++;
            } else {
              parent.removeClass("station-selected")
              hiddenCheckbox.removeAttr("checked");
              total_checked--;
            }

            if(total_checked > 0){
              $(":submit").removeAttr("disabled");
            } else {
              $(":submit").attr("disabled", "disabled");
            }
          });

        });
     </script>
   </head>
   <body class="bg-primary text-white">
     <header class="container">
       <?php if(isset($_SESSION['message'])){ echo "<h2 class='text-danger'>{$_SESSION['message']}</h2>"; } ?>
     </header>
     <section id="main" class="top-buffer station-grid">
       <div class="row mb-4">
          <h2 class="offset-1 col-3"><u><?php echo $station; ?> Schedule</u></h2>
       </div>

       <form class="family-picker" action="pickup.php" method="post">
         <div class="row mb-2">
           <label for="family_picker" class="form-control-label offset-4 col-4">Select the appointment(s) below for the current parent/student:</label>
           <input type="submit" name="submit_family" value="Next>" class="btn btn-danger offset-1 col-1" disabled>
         </div>

         <?php
            $i = 0;
            foreach($station_pickups_today as $station_pickup){
                // Set up important values for each pickup
                $time = $station_pickup['time'];
                $formatted_time = date("g:i:a", strtotime($time));
                $parent = $station_pickup['parent_name'];
                $students = $station_pickup['students'];
                $student_ids = explode(",", $students);
                $student_names = array();
                $id = $station_pickup['id'];

                foreach($student_ids as $stud_id){
                    $stmt = "SELECT * FROM students ";
                    $stmt .= "WHERE id=:id ";
                    $stmt .= "LIMIT 1";
                    $params = array(":id"=>$stud_id);

                    $student_result = $database->query($stmt,$params);
                    array_push($student_names, $student_result[0]['name']);
                }


                if($i==0){
                    echo "<div class='row row-buffer'>";
                    echo "<div id='{$i}-1' class='offset-2 col-2 station'>";
                } elseif ($i % 4 == 0) {
                    echo "<div class='row row-buffer'>";
                    echo "<div id='{$i}-1' class='offset-2 col-2 station'>";
                } else{
                    echo "<div id='{$i}-1' class='col-2 station'>";
                }


                echo "<h4>{$formatted_time}</h4>";
                echo "<h6 class='mb-2 text-muted'>{$parent}</h6>";
                echo "<p>";
                $j=0;
                foreach($student_names as $stud_name){
                    echo $stud_name;
                    if($j != (count($student_names)-1)){
                        echo ", ";
                    }
                    $j++;
                }
                echo "</p>";
                echo "<input type='checkbox' name='pickup[]'' id='pickup-{$i}-1' value='{$id}' hidden>";
                echo "</div>";
                if($i % 4 == 3){
                    echo "</div>";
                }
                $i++;
            }
         ?>

       </form>

     </section>

     <footer class="ml-2 footer fixed-bottom">Made by Evan McCullough</footer>
   </body>
 </html>
