<!DOCTYPE HTML>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>SVG Chart Generator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
      .error {color: #FF0000;}
    </style>
  </head>
  <body>

    <?php
    // define variables and set to empty values
    $nameErr = $chartDataErr = "";
    $name = $chartType = $sortBy = $chartData = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (empty($_POST["name"])) {
        $nameErr = "Name is required";
      } else {
        $name = test_input($_POST["name"]);
      }

      $chartType = test_input($_POST["chartType"]);

      $sortBy = test_input($_POST["sortBy"]);

      if (empty($_POST["chartData"])) {
        $chartDataErr = "Chart Data is required";
      } else {
        $chartData = test_input($_POST["chartData"]);
      }
    }


    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
    $maxScore = $avgScore = 0;
    $minScore = 100;
    $sortedData = array();
    $lines  = explode(PHP_EOL, $chartData);

    foreach ($lines as $line) {
      $line = explode(',', $line);
      $sortedData[array_shift($line)] = array_shift($line);
    }

    foreach($sortedData as $x => $x_value ) {
      if($x_value > $maxScore){
        $maxScore = $x_value;
      }
      if($x_value < $minScore){
        $minScore = $x_value;
      }
      $avgScore = $avgScore + $x_value;
    }

    $avgScore = $avgScore / count($sortedData);
    ?>

    <h2>SVG Chart Generator</h2>
    <p><span class="error">* required field.</span></p>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      Chart Name: <input type="text" name="name" value="<?php echo $name;?>">
      <span class="error">* <?php echo $nameErr;?></span>
      <br><br>
      Chart Type: <select name="chartType" value="<?php echo $chartType;?>">
        <option value="stats">Stats</option>
        <option value="bar">Bar</option>
        <option value="line">Line</option>
        <option value="pie">Pie</option>
      </select>
      <br><br>
      Sort by: <select name="sortBy" value="<?php echo $sortBy;?>">
        <option value="none">None</option>
        <option value="score">Score</option>
        <option value="name">Name</option>
        <option value="lastName">Last Name</option>
      </select>
      <br><br>
      Chart Data: <textarea name="chartData" rows="5" cols="40"><?php echo $chartData;?></textarea>
      <span class="error">* <?php echo $chartDataErr;?></span>
      <br><br>
      <input type="submit" name="submit" value="Submit">
    </form>

    <?php
    echo "<h2>Your Input:</h2>";
    echo $name;
    echo "<br>";
    echo $chartType;
    echo "<br>";
    echo $sortBy;
    echo "<br>";
    echo $maxScore;
    echo "<br>";
    echo $minScore;
    echo "<br>";
    echo round($avgScore, 2);
    echo "<br>";

    if($sortBy == "none"){
      echo "<table class = \"table table-striped\"><tr><th>Name</th><th>Grade</th></tr>";
      foreach ($sortedData as $key => $value) {
        echo "<tr><td>$key</td><td>$value</td></tr>";
      }
      echo "</table>";
    }



    print_r($sortedData);
    ?>

  </body>
</html>
