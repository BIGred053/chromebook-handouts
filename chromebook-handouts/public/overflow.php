<?php
  // Overflow station page. Since this station did not have scheduled appointments, this allows the volunteer to look up any scheduled appointment by name,
  // and to pull up necessary data to confirm student identity. This can be used for unscheduled drop-ins, alleviating congestion at pick-up stations, or
  // other edge cases.
    require_once("../includes/initialize.php");
    $_SESSION['station'] = "Overflow";

    if(isset($_POST['submit_pickup'])){
      $stmt = "SELECT * FROM chromebooks WHERE student_id=:student_id";
      $params = array(":student_id"=>$_POST['student_id']);
      $uniq_stud_check = $database->query($stmt, $params);

      $stmt = "SELECT * FROM chromebooks WHERE asset_tag=:asset_tag";
      $params = array(":asset_tag"=>$_POST['asset_tag']);
      $uniq_asset_check = $database->query($stmt, $params);

      $stmt = "SELECT * FROM chromebooks WHERE service_tag=:service_tag";
      $params = array(":service_tag"=>$_POST['service_tag']);
      $uniq_service_check = $database->query($stmt, $params);

      if(count($uniq_stud_check)>0){
        $_SESSION['message'] = "Error: Student already has assigned Chromebook ({$_POST['student_id']})";
        redirect_to("overflow.php");
      }

      if(count($uniq_asset_check)>0){
        $_SESSION['message'] = "Error: Asset tag already assigned ({$_POST['asset_tag']})";
        redirect_to("overflow.php");
      }

      if(count($uniq_service_check)>0){
        $_SESSION['message'] = "Error: Service tag already assigned({$_POST['service_tag']})";
        redirect_to("overflow.php");
      }

      $stmt = "INSERT INTO chromebooks ";
      $stmt .= "(asset_tag, service_tag, student_id) ";
      $stmt .= "VALUES (:asset_tag, :service_tag, :student_id)";

      $params = array(":asset_tag"=>$_POST['asset_tag'], ":service_tag"=>$_POST['service_tag'], ":student_id"=>$_POST['student_id']);

      $result = $database->cud_query($stmt, $params);

      $_SESSION['message'] = "";
      redirect_to("index.php");
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
});
    </script>

  </head>
  <body class="bg-primary text-white">
    <header class="container">
      <?php if(isset($_SESSION['message'])){ echo "<h2 class='text-danger'>{$_SESSION['message']}</h2>"; } ?>
    </header>
    <section id="main" class="top-buffer mb-4">

        <form name="assignment_data" action="overflow.php" method="post">
          <div class="row">
          <div id="pickup-info" class="offset-1 col-4">

            <div class='row'>
                <div id='student-1' class='offset-1 col-10 station form-group'>
                  <fieldset>
                    <label for="student_id" class="form-control-label">Enter the student's ID:</label>
                    <input type="text" name="student_id" placeholder="Student ID" class="form-control">
                  </fieldset>
                  <fieldset>
                    <label for="asset_tag" class="form-control-label">Organization Asset Tag:</label>
                    <input type="text" name="asset_tag" placeholder="Asset Tag #" class="form-control">
                  </fieldset>
                  <fieldset>
                    <label for="service_tag" class="form-control-label">Lenovo Service Tag:</label>
                    <input type="text" name="service_tag" placeholder="Service Tag #" class="form-control">
                  </fieldset>
                </div>
            </div>

            <div class="row row-buffer mb-2">
                <input type="submit" name="submit_pickup" value="Submit" class="btn btn-danger offset-9 col-2">
            </div>
          </div>
          <div id="sis_info" class="col-6">
              <div class="row row-buffer">
                  <div class="offset-1 col-11">
                      <h4><u>SIS Potential Matches</u></h4>
                      <input type='text' name='sis_search' placeholder='sis Search' class='form-control col-8'>
                      <input type="submit" name="submit_sis" value="Search" class="btn btn-danger offset-6 col-2">
                      <?php

                        if(isset($_POST['submit_sis'])){
                            $sis_name = $_POST['sis_search'];
                            // Search sis table for name match on student last name, parent last name, email
                            $stmt = "SELECT * FROM sis ";
                            $stmt .= "WHERE student_ln=:student_ln";

                            $params = array(":student_ln"=>$sis_name);

                            // Execute query
                            $sis_results = $database->query($stmt, $params);
                            $j=1;
                            if(!empty($sis_results)){

                                $num_sis_results = count($sis_results);
                                echo "<input type='text' name='sis_matches' value='{$num_sis_results}' hidden>";

                                foreach($sis_results as $found){
                                    echo "<fieldset id='sis-{$j}'";
                                    $student_name = $found['student_fn'] ." ". $found['student_ln'];
                                    echo "<span class='top-buffer'>Student: {$student_name}</span><div class='w-100'></div>";
                                    $student_id = $found['district_id'];
                                    echo "<span class='top-buffer'>Student ID: {$student_id}</span><div class='w-100'></div>";
                                    $student_dob = $found['dob'];
                                    echo "<span class='mb-4'>DOB: {$student_dob}</span><div class='w-100'></div>";

                                    $address = $found['address'] . "<br>" . $found['city'] . ", ". $found['state'] . " " . $found['zip'];
                                    echo "<span class='top-buffer'>Address: {$address}</span><div class='w-100'></div>";

                                    $stud_id = $found['district_id'];
                                    echo "<input type='text' name='student_id_{$j}' value='{$stud_id}' hidden>"; // Hidden field with student district number to look back up on submit
                                    echo "<input type='checkbox' name='sis_select[]' value='{$j}'> &nbsp; Use this record";
                                    // If they choose this record, we will use $sis_results[value] to pull sis data and insert into pickup info
                                    echo "<hr style='border-color: white;' class='col-4 ml-0'>";
                                    echo "</fieldset>";

                                    $j++;
                                }
                            }
                          }
                    ?>
                  </div>
                </div>
          </form>







    </section>

    <footer class="ml-2 footer fixed-bottom">Made by Evan McCullough</footer>
  </body>
</html>
