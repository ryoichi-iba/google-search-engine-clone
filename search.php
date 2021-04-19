<?php

if (isset($_GET["term"])) {
  $term = $_GET["term"];
} else {
  exit("You must enter a search term");
}

$type = isset($_GET["type"]) ? $type = $_GET["type"] : $type =  "sites";

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/style.css">
  <title>Welcome to Doodle</title>
</head>

<body>

  <div class="wrapper">
    <div class="header">
      <div class="headerContent">
        <div class="logoContainer">
          <a href="index.php">
            <img src="assets/images/doodle.png" alt="">
          </a>
        </div>

        <div class="searchContainer">
          <form action="search.php" method="GET">

            <div class="searchBarContainer">
              <input type="text" class="searchBox" name="term">
              <button class="searchBtn"> Search</button>
            </div>


          </form>
        </div>
      </div>

      <div class="tabsContainer">
        <ul class="tabList">
          <li class="<?php echo $type == 'sites' ? 'active' : ''  ?>">
            <a href='<?php echo "search.php?term=$term&type=sites" ?>'>Sites</a>
          </li>
          <li class="<?php echo $type == 'images' ? 'active' : ''  ?>">
            <a href='<?php echo "search.php?term=$term&type=images" ?>'>Images</a>
          </li>


        </ul>
      </div>
    </div>

  </div>

</body>

</html>