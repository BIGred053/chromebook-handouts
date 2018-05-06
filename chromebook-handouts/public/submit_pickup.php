<?php
  function redirect_to($location){
    header("Location: {$location}");
  }

  if(isset($_POST['submit_confirmation'])){
      // Do all of the DB INSERTs Here
      redirect_to('station_landing.php');
  } else {
    redirect_to('station_landing.php');
  }


?>
