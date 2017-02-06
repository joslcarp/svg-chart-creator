<!DOCTYPE HTML>
<html>
<head>
<title>SVG Chart Generator</title>
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

function multiexplode ($delimiters,$string) {

    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

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
//echo chartData;
echo "<br>";

//$dataArray = preg_split('/[,.;\\n]+/', $chartData);

$dataArray = multiexplode(array(",","\n"),$chartData);

print_r($dataArray);
?>

</body>
</html>
