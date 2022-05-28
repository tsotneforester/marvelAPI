   <!DOCTYPE html>
   <html lang="en">
   <head>
     <meta charset="UTF-8">
     <meta property="og:type" content="website" />
    <meta property="og:url" content="GPX Bitcamp" />
    <meta property="og:title" content="GPX Bitcamp" />
    <meta property="og:description" content="Junior_PHP" />
    <meta property="og:image" content="https://gpx.ge/root/img/main.png" />
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <script src="https://cdn.tailwindcss.com"></script>
     <link rel="stylesheet" href="style/style.css">
     <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
     <title>Marvel</title>
   </head>
   <body>
    <?php if (empty($_POST)) { ?>


      <!-- <form action="card.php" method="post"> -->
      <form action="" method="post">
        <input list="characters" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-200 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter Character" required>
          <datalist id="characters" required>
          <!-- <option disabled selected value style="display:none"> -->
            <option value="Hulk">Hulk</option>
            <option value="Thor">Thor</option>
            <option value="Captain America">Captain America</option>
            <option value="Black Widow">Black Widow</option>
            <option value="Vision">Vision</option>
            <option value="Doctor Strange">Doctor Strange</option>
            <option value="Nick Fury">Nick Fury</option>
            <option value="Iron Man">Iron Man</option>
            <option value="Red Skull">Red Skull</option>
          </datalist>
        <input type="submit" name="submit"  value="submit" class="text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center m-5">

      </form>
    <?php }  ?>
     
    <?php
          if (isset($_POST["submit"])) {
            $caracter_name = $_POST["name"];
            $conn = mysqli_connect("localhost", "root", "", "junior");
            $sql = "SELECT * FROM marvel WHERE name='$caracter_name'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) === 1) {
                while ($DataRows = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $Fl_name = $DataRows['name'];
                    $Comment = $DataRows['comment'];
                    $Image_format = $DataRows['format'];
                }

            } else 
            {
                $ts = time();
                $public_key = '224353a2fddbcd580b237ffa5545fb04';
                $private_key = '9a6fc6ce2d36536cd777d5900b46ac9cf10d98dc';
                $hash = md5($ts . $private_key . $public_key);

                $api_url = "https://gateway.marvel.com/v1/public/characters?ts=" . $ts . "&apikey="  . $public_key .  "&hash=" . $hash . "&name=" . rawurlencode($caracter_name);
                include 'include/function.php';
                $data = json_decode(parseJson($api_url), true);

                if ($data['data']['total'] > 0) {
                  # code...
              

                $fl_name =  $data['data']['results']['0']['name'];
                $comment = addslashes($data['data']['results']['0']['description']);
                $image_format = "." . $data['data']['results']['0']['thumbnail']['extension'];
                $image_url = $data['data']['results']['0']['thumbnail']['path']. $image_format;
                download_image($image_url, $fl_name, "vault");

                // echo "<pre>";
                // print_r($data);
                // echo "</pre>";

                $sql = "INSERT INTO `marvel` VALUES ('0','$fl_name', '$image_format', '$comment' )";
                mysqli_query($conn, $sql);



                $sqlView = "SELECT * FROM marvel WHERE name='$caracter_name'";
                $Execute = mysqli_query($conn, $sqlView);
                while ($DataRows = mysqli_fetch_array($Execute, MYSQLI_ASSOC)) {
                  $Fl_name = $DataRows['name'];
                  $Comment = $DataRows['comment'];
                  $Image_format = $DataRows['format'];
                }
              } else {

                $Fl_name = "No Such Character";
                $Comment = "";
              }

            }

        }
      ?>


    <?php if (!empty($_POST)) { ?>

      <div class="card">
       <a href="index.php" class="text-2xl float-right absolute text-black m-2 right-2"><i class='bx bx-x-circle'></i></a>
        <div class="image">
          <img src="vault/<?=$Fl_name . $Image_format ?>" class="h-full">
        </div>
        <div class="name">
              <p><?=$Fl_name?></p>
        </div>
        <div class="info">
              <p><?=$Comment?></p>
        </div>
      </div>
    <?php }?>


    <?php if(empty($_POST)) { ?>
      <div id="flexbox" class="flex shrink-0 flex-wrap">
    <?php  
      $conn = mysqli_connect("localhost", "root", "", "junior");
      $sql = "SELECT * FROM marvel";
      $result = mysqli_query($conn, $sql);

      while ($DataRows = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          $Fl_name = $DataRows['name'];
          $Comment = $DataRows['comment'];
          $Image_format = $DataRows['format'];

     ?>
      
      

    <div class="card">
      <div class="image">
        <img src="vault/<?=$Fl_name . $Image_format ?>" class="h-full">
      </div>
      <div class="name">
            <p><?=$Fl_name?></p>
      </div>
      <div class="info">
            <p><?=$Comment?></p>
      </div>
    </div>
  <?php }?>
 </div>
<?php }?>
   </body>
   </html>