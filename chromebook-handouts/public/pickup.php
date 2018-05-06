<?php
  // This page displays information for each specific scheduled pick-up. Includes fields to record Chromebook and student data, and also includes resources for
  // volunteers to verify student information and instructions to provide guidance for the checkout process.
    require_once("../includes/initialize.php");

    if(isset($_POST['submit_family'])){
      $selected_pickups = array();

      // Use this station number for DB call to pull scheduled pickups
      // Will include Pickup Name, Time, Email
    } else {
      redirect_to("station_landing.php");
    }

 ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Family Pickup</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.js"></script>
    <script src="js/bootstrap.min.js" charset="utf-8"></script>
    <script type="text/javascript">
      $(document).ready(function() {

      $(window).keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
      });


        $("input").blur(function() {
          var isValid = true;
          $("input").each(function() {
            var element = $(this);
            if (element.val() == "") {
               isValid = false;
             }
          });
          //
          // if(isValid){
          //   $(":submit").removeAttr("disabled");
          // } else {
          //   $(":submit").attr("disabled","disabled")
          // }

        });

      });
    </script>

  </head>
  <body class="bg-primary text-white">

    <section id="main" class="top-buffer mb-4">
      <div class="row">
        <div id="pickup-info" class="offset-1 col-4">
          <?php
              if(isset($_POST['pickup'])){
                $selected_pickups = $_POST['pickup'];
                $pickup_results = array();
                foreach ($selected_pickups as $pickup_id) {
                    // Use pickup_id to perform SQL query to find the Pickup
                    // We will use the address information from the result Pickup
                    // for verification, and we will also pull student_id's from
                    // the Pickup
                    $stmt = "SELECT * FROM pickups ";
                    $stmt .= "WHERE id=:id";
                    $params = array(':id'=>$pickup_id);
                    $pick_result = $database->query($stmt, $params);
                    array_push($pickup_results, $pick_result[0]);
                }
              }

          ?>
          <form name="assignment_data" action="pickup_confirmation.php" method="post">

              <?php
                $i=0;
                foreach($pickup_results as $pickup){
                    $students_string = $pickup['students'];
                    $student_ids = explode(",", $students_string);
                    $students = array();

                    foreach($student_ids as $stud_id){
                        $stmt = "SELECT * FROM students ";
                        $stmt .= "WHERE id=:id ";
                        $stmt .= "LIMIT 1";
                        $params = array(":id"=>$stud_id);

                        $student_result = $database->query($stmt,$params);
                        array_push($students, $student_result[0]);
                    }
                    $num_students = count($students);
                    echo "<input type='text' name='total_students' value='{$num_students}' hidden>";
                    foreach($students as $curr_stud){
                        $curr_name = $curr_stud['name'];
                        $curr_dist_id = $curr_stud['district_id'];
                        $curr_dob = $curr_stud['dob'];

                        echo "<div class='row'>";
                            echo "<div id='student-{$i}' class='offset-1 col-10 station form-group'>";
                                echo "<h4>{$curr_name}</h4>";
                                echo "<h6 class='mb-2 text-muted'>Student ID: {$curr_dist_id}</h6>";
                                echo "<input type='text' name='student_id{$i}' placeholder='Student ID' id='student{$i}' class='form-control'>";
                  ?>
                  <script type="text/javascript">
                    $("#student<?php echo $i; ?>").keyup(function() {

                      var outer_div = $(this).closest('div');
                      //alert(stud_id_h6.text);
                        if($(this).val() == <?php echo "$curr_dist_id"; ?>){
                          if($(this).hasClass("form-control-danger")){
                            $(this).removeClass("form-control-danger");
                            outer_div.removeClass("has-danger");
                          }
                          $(this).addClass("form-control-success");
                          outer_div.addClass("has-success");
                        } else{
                          if($(this).hasClass("form-control-success")){
                            $(this).removeClass("form-control-success");
                            outer_div.removeClass("has-success");
                          }
                          outer_div.addClass("has-danger");
                          $(this).addClass("form-control-danger");
                        }
                    });
                  </script>
                  <?php
                                echo "<p>DOB: {$curr_dob}</p>";

                                echo "<fieldset class='asset-tag'>";
                                    echo "<label for='asset_tag{$i}'' class='form-control-label'>Granville Asset Tag:</label>";
                                    echo "<input type='text' name='asset_tag{$i}' placeholder='Asset Tag #' class='form-control'>";
                                echo "</fieldset>";
                                echo "<fieldset class='service-tag'>";
                                    echo "<label for='service_tag{$i}'' class='form-control-label'>Lenovo Service Tag #:</label>";
                                    echo "<input type='text' name='service_tag{$i}' placeholder='Service Tag #' class='form-control'>";
                                echo "</fieldset>";
                            echo "</div>"; // Close student div
                        echo "</div>"; // Close row div
                        $i++;
                    } // End foreach student

                }
              ?>

            <div class="row row-buffer mb-2">
                <input type="submit" name="submit_pickup" value="Submit" class="btn btn-danger offset-9 col-2">
            </div>

          </form>

        </div>
        <div id="address" class="col-2 top-buffer">
            <h5>Home Address</h5>
            <?php
                echo "<span>" . $pickup_results[0]['address'] ."</span><br>";
                echo "<span>" . $pickup_results[0]['city'] . ", " . $pickup_results[0]['state'] . " ". $pickup_results[0]['zip'] . "</span>";
            ?>
        </div>
        <div id="instructions" class="col-4 top-buffer">
          <h4 style="text-align: center;"><u>Pickup Instructions</u></h4>
          <ol>
            <li class="top-buffer">Verify parent/student identity with home address, student ID, or student DOB.</li>
            <li class="row-buffer">Place student name label on bottom of Chromebook.</li>

            <li class="row-buffer">Use scanner to scan in asset tag # (blue barcode).</li>
            <li class="row-buffer">Use scanner to input device service tag # (hover here for picture)</li>

            <li class="row-buffer">Have student attempt sign-in on Chromebook. Issues should proceed to overflow station.</li>
            <li class="row-buffer">Repeat for each student in family.</li>
            <li class="row-buffer">Place all Chromebooks and chargers in bag.</li>
            <li class="row-buffer">Press Submit button to left, review inputs, confirm submission to move on to next pickup.</li>
          </ol>
        </div>
      </div>

    </section>

    <footer class="ml-2 footer fixed-bottom">Made by Evan McCullough</footer>
  </body>
</html>
