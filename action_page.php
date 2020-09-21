<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "PODDB";


try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $conn->prepare("INSERT INTO MyGuests (fname, email, phone, country, industry, experience, skills, picture)
  VALUES (:fname, :email, :phone, :country, :industry, :experience, :skills, :picture)");
  
  // use exec() because no results are returned
  $stmt->bindParam(':fname', $fname);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':country', $country);
  $stmt->bindParam(':industry', $industry);
  $stmt->bindParam(':experience', $experience);
  $stmt->bindParam(':skills', $skills);
  $stmt->bindParam(':picture', $picture);

  // insert a row
  $fname = $_POST["fname"];
  $email = $_POST["email"];
  $phone = $_POST["phone"];
  $country = $_POST["country"];
  $industry = $_POST["industry"];
  $experience = $_POST["experience"];
  $skills = $_POST["skills"];
  $picture = $_FILES["fileToUpload"];
  
  $target_dir = "uploads/";
  $filee = basename($_FILES["fileToUpload"]["name"]);
  
  $target_file = $target_dir.basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  // Check if image file is a actual image or fake image
  if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["picture"]["tmp_name"]);
    if ($check !== false) {
      echo "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
    } else {
      echo "File is not an image.";
      $uploadOk = 0;
    }
  }

  // Check if file already exists
  if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
  }


    // Compress
    function compress_image($source_url, $destination_url, $quality)
    {
        $info = getimagesize($source_url);
        if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source_url);
        elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source_url);
        elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source_url);
        imagejpeg($image, $destination_url, $quality);
        echo "<br>";
        echo "Image uploaded successfully.";
        echo "<br>";
    }
  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Here";
    compress_image($_FILES["fileToUpload"]["tmp_name"], "uploads/" . $_FILES["fileToUpload"]["name"], 50);

    // echo "Sorry, your file is too large.";
    echo "\nCOmpressing File";
    $uploadOk = 0;
  }

  // Allow certain file formats
  if (
    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif"
  ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }



  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
  } else {

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  }
  $picture = basename($_FILES["fileToUpload"]["name"]);
  
  if($uploadOk!=0){
  $stmt->execute();
  echo "New record created successfully";
  }
} catch(PDOException $e) {
  echo "<br>" . $e->getMessage();
}

$conn = null;
?>