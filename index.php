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

      <form action="" method="post" class="relative top-10 block w-56 my-0 mx-auto text-center">
        <input list="characters" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-200 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter Character" required>
          <datalist id="characters" required>
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
            $caracter_name = ucwords($_POST["name"]);
            $conn = mysqli_connect("localhost", "root", "", "junior");
            $sql = "SELECT * FROM marvel WHERE name='$caracter_name'";
            $result = mysqli_query($conn, $sql);

            //checks input name status in database 
            if (mysqli_num_rows($result) === 1) { //fetches info for db as input name exists
                while ($DataRows = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $Fl_name = $DataRows['name'];
                    $Comment = $DataRows['comment'];
                    $Image_format = $DataRows['format'];
                }
            } 
            else 
            { //connect to API
                $ts = time();
                $public_key = '224353a2fddbcd580b237ffa5545fb04';
                $private_key = '9a6fc6ce2d36536cd777d5900b46ac9cf10d98dc';
                $hash = md5($ts . $private_key . $public_key);

                $api_url = "https://gateway.marvel.com/v1/public/characters?ts=" . $ts . "&apikey="  . $public_key .  "&hash=" . $hash . "&name=" . rawurlencode($caracter_name);
                include 'include/function.php';
                $data = json_decode(parseJson($api_url), true);

                if ($data['data']['total'] > 0) { //fetches info from API and sends it to db, as intup was found, than returnes it from bd
                $fl_name =  $data['data']['results']['0']['name'];
                $comment = addslashes($data['data']['results']['0']['description']);
                $image_format = "." . $data['data']['results']['0']['thumbnail']['extension'];
                $image_url = $data['data']['results']['0']['thumbnail']['path']. $image_format;
                download_image($image_url, $fl_name, "vault");


                $sql = "INSERT INTO `marvel` VALUES ('0','$fl_name', '$image_format', '$comment' )";
                mysqli_query($conn, $sql);



                $sqlView = "SELECT * FROM marvel WHERE name='$caracter_name'";
                $Execute = mysqli_query($conn, $sqlView);
                while ($DataRows = mysqli_fetch_array($Execute, MYSQLI_ASSOC)) {
                  $Fl_name = $DataRows['name'];
                  $Comment = $DataRows['comment'];
                  $Image_format = $DataRows['format'];
                }
              } else {//input name not found neither in db nor in API
                $Fl_name = "No Such Character";
                $Comment = "";
              }
            }
        }
      ?>


    <?php if (!empty($_POST)) { ?> <!-- shows Input Results from db-->
      <div id="card" class="relative w-[340px] h-[560px] top-14 my-20 mx-auto bg-no-repeat bg-contain">
       <a href="index.php" class="text-2xl float-right absolute text-black m-2 right-2"><i class='bx bx-x-circle'></i></a>
        <div id="image" class="absolute top-2 left-[-20px] w-[156px] h-[156px] -rotate-[30deg] rounded-[12px] border-white border-4 ">
          <img src="vault/<?=$Fl_name . $Image_format ?>"  class="h-full w-full rounded-xl">
        </div>
        <div id="name" class="absolute top-[129px] left-[169px] w-[141px] h-[26px] text-2xl font-bold ">
              <p><?=$Fl_name?></p>
        </div>
        <div id="info" class="absolute top-[229px] left-[62px] w-[258px] h-[252px] text-lg font-bold italic overflow-auto">
              <p><?=$Comment?></p>
        </div>
      </div>
    <?php }?>


    <?php if(empty($_POST)) { ?><!-- shows aggregrated results from db -->
      <div id="flexbox" class="flex shrink-0 flex-wrap">
    <?php  
      $conn = mysqli_connect("localhost", "root", "", "junior");
      $sql = "SELECT * FROM marvel ORDER BY id DESC";
      $result = mysqli_query($conn, $sql);

      while ($DataRows = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          $Fl_name = $DataRows['name'];
          $Comment = $DataRows['comment'];
          $Image_format = $DataRows['format'];

     ?>
      
      

      <div id="card" class="relative w-[340px] h-[560px] top-14 my-20 mx-auto bg-no-repeat bg-contain">
    <div id="image" class="absolute top-2 left-[-20px] w-[156px] h-[156px] -rotate-[30deg] rounded-[12px] border-white border-4 ">
          <img src="vault/<?=$Fl_name . $Image_format ?>"  class="h-full w-full rounded-xl">
        </div>
      <div id="name" class="absolute top-[129px] left-[169px] w-[141px] h-[26px] text-2xl font-bold ">
              <p><?=$Fl_name?></p>
        </div>
      <div id="info" class="absolute top-[229px] left-[62px] w-[258px] h-[252px] text-lg font-bold italic overflow-auto">
              <p><?=$Comment?></p>
        </div>
    </div>
  <?php }?>
 </div>
<?php }?>
   </body>
   </html>