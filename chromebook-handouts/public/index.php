
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Chromebook Checkout System</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.js"></script>
    <script src="js/bootstrap.min.js" charset="utf-8"></script>
  </head>
  <body class="bg-primary text-white">
    <section id="main" class="container top-buffer mx-auto text-center">
      <h1>Welcome to the Chromebook Checkout System!</h1>
      <h3>Thank you for your time and assistance to the Technology Department!</h3>
    </section>
    <!-- Chromebook Pickup organized into 9 Stations, plus one overflow/
          troubleshooting Station. This section will allow volunteers to pick their station -->
    <section id="station-picker" class="container">

      <div class="row">
          <img src="images/lenovo-n23.png" alt="Lenovo N23 Chromebook" class="mx-auto col-auto">
      </div>

      <form class="station-selection" action="station_landing.php" method="post">
        <fieldset>
            <label for="date_select" class="form-control-label">Select the Date:</label>
            <select class="form-control" name="date_select">
                <option value="2017-08-01">August 1</option>
                <option value="2017-08-02">August 2</option>
                <option value="2017-08-03">August 3</option>
                <option value="2017-08-07">August 7</option>
                <option value="2017-08-08">August 8</option>
                <option value="2017-08-09">August 9</option>
                <option value="2017-08-10">August 10</option>
            </select>
            <label for="station_select" class="form-control-label">Select Your Station Below:</label>
            <select class="form-control" name="station_select">
                <?php
                for($i=1; $i<10; $i++){
                echo "<option value='Station {$i}'>Station {$i}</option>";
                }
                ?>
                <<option value="overflow">Overflow</option>
            </select>
        </fieldset>
        <input type="submit" name="submit" value="Submit" class="btn btn-default top-buffer">
      </form>

    </section>

    <footer class="ml-2 fixed-bottom">Made by Evan McCullough</footer>
  </body>
</html>
