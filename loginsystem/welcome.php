<?php
session_start();
include "../partials/_dbconnect.php";
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
     header("location: login.php");
     exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

     $username = $_SESSION['username'];
     $dec = $_POST['dec'];
     $files = $_FILES["file"];

     $filename = $files['name'];
     $fileerror = $files['error'];
     $filetmp = $files['tmp_name'];

     $fileext = explode('.', $filename);
     $filecheck = strtolower(end($fileext));

     $fileextstored = array('png', 'jpg', 'jpeg');

     if (in_array($filecheck, $fileextstored)) {
          $destinationfile = '../imgupload' . $filename;
          move_uploaded_file($filetmp, $destinationfile);
          $sql = "INSERT INTO `userupload`(`username`, `image`, `dec`) VALUES ('$username', '$destinationfile', '$dec')";
          $query = mysqli_query($conn, $sql);
     }
}

$display = "SELECT * FROM userupload";
$result = mysqli_query($conn, $display);
$num = mysqli_num_rows($result);


?>
<!doctype html>
<html lang="en">

<head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
     <title>Welcome - <?php echo $username ?></title>
</head>

<body>
     <?php require '../partials/_nav.php' ?>

     <div class="container my-3">
          <div class="alert bg-dark text-light" role="alert">
               <h4 class="alert-heading">Welcome - <?php echo $_SESSION['username'] ?></h4>
               <p>Hey how are you doing? Welcome to ARTISTRY. You are logged in as <?php echo $_SESSION['username'] ?>. Aww yeah, you successfully read this important alert message. This example text is going to run a bit longer so that you can see how spacing within an alert works with this kind of content.</p>
               <!-- <hr> -->
               <!-- <p class="mb-0">Visit Your
                    <a href="profile.php" class="text-decoration-none text-info">Profile</a>
               </p> -->


          </div>
     </div>


     <div class="container my-4">

          <form action="welcome.php" method="post" enctype="multipart/form-data">
               <div class="form-group p-3">
                    <label for="file">Post Your Image</label>
                    <input type="file" class="form-control" id="file" name="file">
               </div>
               <div class="form-group p-3">
                    <label for="dec">Description</label>
                    <textarea class="form-control" id="dec" name="dec" required></textarea>
               </div>
               <button type="submit" class="btn btn-dark mx-3 ">Upload</button>
          </form>
     </div>
     <main>

          <section class="py-5 text-center container">
               <div class="row py-lg-5">
                    <div class="col-lg-6 col-md-8 mx-auto">
                         <h1 class="fw-light">Explore Artwork</h1>
                         <p class="lead text-muted">Click on cards to know more about artwork</p>
                    </div>
               </div>
          </section>
          <div class="album py-5 bg-transparent">
               <div class="container d-flex justify-content-center">

                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 ">
                         <?php
                         $count = 0;
                         while ($row = mysqli_fetch_array($result)) {
                              echo "
                         <img src='", $row['image'], "' class='img-fluid img-thumbnail w-25' data-bs-toggle='modal' data-bs-target='#exampleModal-$count'  width='200'>
                         <div class='modal fade' id='exampleModal-$count' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                              <div class='modal-dialog modal-dialog-centered'>
                                   <div class='col'>
                                        <div class='card shadow-sm rounded-3'>
                                        <div class='card-header'>",
                              $row['username'],
                              "</div>
                                             <img src='", $row['image'], "' class='card-img-top'/>
                                             <div class='card-body' >",
                              $row['dec'],
                              "</div>
                                        </div>
                                   </div>
                              </div>
                       </div>";
                              $count++;
                         } ?>

                    </div>
               </div>
          </div>
     </main>


     <!-- Optional JavaScript -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>