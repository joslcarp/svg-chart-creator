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
    $chartNameErr = $chartDataErr = "";
    $chartName = $chartType = $sortBy = $chartData = $chartInsert = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (empty($_POST["chartName"])) {
        $chartNameErr = "Name is required";
      } else {
        $chartName = test_input($_POST["chartName"]);
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
      $sortedData[array_shift($line)] = (int)array_shift($line);
    }


    foreach($sortedData as $x => $x_value ) {
      if(intval($x_value) > $maxScore){
        $maxScore = intval($x_value);
      }
      if(intval($x_value) < $minScore){
        $minScore = intval($x_value);
      }
      $avgScore = $avgScore + $x_value;
    }
    $avgScore = $avgScore / count($sortedData);
    ?>

    <h1><center>SVG Chart Generator</center></h1>
    <div class="container">
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <div class="form-group">
        <label for="chartName2">Chart Name</label>
          <input type="text" class ="form-control" name="chartName" id="chartName2" value="<?php echo $chartName;?>">
          <span class="error"><?php echo $chartNameErr;?></span>
      </div>

      <div class="form-group">
        <label for="select1">Chart Type:</label>
        <select class ="form-control" id="select1" name="chartType" value="<?php echo $chartType;?>">
          <option <?php if (isset($chartType) && $chartType=="stats") echo "selected";?> value="stats">Stats</option>
          <option <?php if (isset($chartType) && $chartType=="bar") echo "selected";?> value="bar">Bar</option>
          <option <?php if (isset($chartType) && $chartType=="line") echo "selected";?> value="line">Line</option>
          <option <?php if (isset($chartType) && $chartType=="pie") echo "selected";?> value="pie">Pie</option>
        </select>
      </div>

      <div class="form-group">
        <label for="select2">Sort by:</label>
        <select class="form-control" id="select2" name="sortBy" value="<?php echo $sortBy;?>">
          <option <?php if (isset($sortBy) && $sortBy=="none") echo "selected";?> value="none">None</option>
          <option <?php if (isset($sortBy) && $sortBy=="score") echo "selected";?> value="score">Score</option>
          <option <?php if (isset($sortBy) && $sortBy=="firstName") echo "selected";?> value="firstName">First Name</option>
          <option <?php if (isset($sortBy) && $sortBy=="lastName") echo "selected";?> value="lastName">Last Name</option>
        </select>
      </div>

      <div class="form-group">
        <label for="textArea">Chart Data:</label>
        <textarea class="form-control" id="textArea" rows="4" name="chartData" rows="5" cols="40"><?php echo $chartData;?></textarea>
        <span class="error"><?php echo $chartDataErr;?></span>
      </div>
      <button class="btn btn-primary" type="submit">Submit</button>
    </form>
  </div>

    <?php
    if($sortBy == "score"){
      arsort($sortedData);
    }
    elseif($sortBy == "firstName"){
      ksort($sortedData);
    }
    elseif($sortBy == "lastName"){
      function lastNameSort($a, $b) {
        $aLast = end(explode(' ', $a));
        $bLast = end(explode(' ', $b));

        return strcasecmp($aLast, $bLast);
      }
      uksort($sortedData, 'lastNameSort');
    } ?>

    <?php
    echo '<div class="container"><h1>';
    echo $chartName;
    echo '</h1>';

    if($chartType == "stats"){
        echo '<table class="table table-striped"><tr><th>Name</th><th>Grade</th><th>Chart</th></tr>';
        foreach ($sortedData as $key => $value) {
            echo '<tr><td>$key</td><td>$value</td><td>';
            $astercount = $value/10;
            echo str_repeat("*", $astercount);
            echo '</td></tr>';
        }
        echo '</table>';
    }
    elseif ($chartType == "bar") {
        echo '<table class="table table-striped"><tr><th>Name</th><th>Grade</th><th>Chart</th></tr>';
        foreach ($sortedData as $key => $value) {
            echo "<tr><td>$key</td><td>$value</td><td><svg width=\"400\" height=\"30\"><rect width=\"";
            echo $value * 4;
            echo '" height="30" style="fill:red;stroke:black;stroke-width:5;opacity:0.5"/></svg></td></tr>';
        }
        echo '</table>';
    }
    ?>

    <h4>
      Max Score: <?php echo $maxScore; ?>
      <br>
      Min Score: <?php echo $minScore; ?>
      <br>
      Avg Score: <?php echo $avgScore; ?>
    </h4>
  </div>

  </body>
</html>
