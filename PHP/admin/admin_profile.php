<?php /* include("../conn.php");
session_start();
?>
<?php $active6 = "active"; ?>
<?php
$fName = $_SESSION['fName'];
$lName = $_SESSION['lName'];
$email = $_SESSION['email'];
$id = $_SESSION['id'];


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../CSS/profile.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Profile</title>
</head>

<body>

  <header>
    <link rel="stylesheet" href="../../CSS/header.css">
    <div class="headerbar">
      <h3>The #1 Site for Remote Jobs</h3>
    </div>
  </header>

  <?php include_once("admin_navbar.php") ?>
  <?php
  $id=$_SESSION['id'];
  $sql_user = "SELECT count(*) as user_count FROM users WHERE userid='$id';";
  $result_user = mysqli_query($conn, $sql_user);
  $row_user = mysqli_fetch_assoc($result_user);

  $sql_job = "SELECT count(*) as job_count FROM jobtable WHERE userid='$id'; ";
  $result_job = mysqli_query($conn, $sql_job);
  $row_job = mysqli_fetch_assoc($result_job);

  ?>

  <div class="asd">
    <div class="www item1">
      <h2 id="name"><?php echo $fName . ' ' . $lName; ?></h2>
    </div>
    <div class="www item2">Apply Job: <span style="color: red;"><?php echo $row_job['job_count']; ?></span></div>
    <div class="www item3">Post Job: <span style="color: red;"><?php echo $row_job['job_count']; ?></span></div>

    <div class="www item7" style="background-image: url(../user/UploadImage/<?php echo $_SESSION['image']; ?>);">

    </div>
    <div class="www item5">
      <div class="lable">
        <label>User ID : <span>ww0<?php echo $id; ?></span></label>
      </div>
      <br>
      <div class="lable">
        <label>First Name : <span><?php echo $fName; ?></span></label>
      </div>
      <br>
      <div class="lable">
        <label>Last Name : <span><?php echo $lName; ?></span></label>
      </div>
      <br>
      <div class="lable">
        <label>Email : <span><?php echo $email; ?></span></label>
      </div>


    </div>
    <div class="www item4"><button onclick="location.href='../user/editprofile.php'"><i class="fa fa-edit" style="font-size:36px"></i></button></div>

    <div class="www item6"><button>Delete Account</button></div>
  </div>
</body>

</html>
*/

// admin_profile.php
include("../conn.php");
session_start();

// If user is not logged in, redirect to login (change path as needed)
if (!isset($_SESSION['id'])) {
    header('Location: ../user/login.php'); // adjust path to your login page
    exit;
}

// Use null coalescing to safely get session values
$fName = $_SESSION['fName'] ?? '';
$lName = $_SESSION['lName'] ?? '';
$email = $_SESSION['email'] ?? '';
$id = (int)($_SESSION['id'] ?? 0); // cast to int for safety

// Optional: if id is 0 (invalid), also redirect
if ($id <= 0) {
    header('Location: ../user/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../../CSS/profile.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
  <title>Profile</title>
</head>
<body>
  <header>
    <link rel="stylesheet" href="../../CSS/header.css">
    <div class="headerbar">
      <h3>The #1 Site for Remote Jobs</h3>
    </div>
  </header>

  <?php include_once("admin_navbar.php"); ?>

  <?php
  // Use prepared statements ideally; for quick fix cast id to int (done above)
  // Count of job posts by this user
  $sql_job = "SELECT COUNT(*) AS job_count FROM jobtable WHERE userid = $id";
  $result_job = mysqli_query($conn, $sql_job);
  $row_job = $result_job ? mysqli_fetch_assoc($result_job) : ['job_count' => 0];

  // If you intended "applied jobs" count, you need a different table (e.g. applications)
  // For now we show the same job_count for both places because that's what original code did
  $job_count = (int)$row_job['job_count'];
  ?>

  <div class="asd">
    <div class="www item1">
      <h2 id="name"><?php echo htmlspecialchars($fName . ' ' . $lName); ?></h2>
    </div>

    <div class="www item2">Apply Job: <span style="color: red;"><?php echo $job_count; ?></span></div>
    <div class="www item3">Post Job: <span style="color: red;"><?php echo $job_count; ?></span></div>

    <?php
    // Build image URL only if session image exists and file likely exists
    $imageName = $_SESSION['image'] ?? '';
    $imagePath = '';
    if (!empty($imageName) && file_exists(__DIR__ . "/user/UploadImage/$imageName")) {
        // path relative to this file — adjust if needed
        $imagePath = "user/UploadImage/" . rawurlencode($imageName);
    }
    ?>

    <div class="www item7" <?php if ($imagePath) echo 'style="background-image: url(' . htmlspecialchars($imagePath) . ');"'; ?>></div>

    <div class="www item5">
      <div class="lable">
        <label>User ID : <span>ww0<?php echo htmlspecialchars($id); ?></span></label>
      </div>
      <br>
      <div class="lable">
        <label>First Name : <span><?php echo htmlspecialchars($fName); ?></span></label>
      </div>
      <br>
      <div class="lable">
        <label>Last Name : <span><?php echo htmlspecialchars($lName); ?></span></label>
      </div>
      <br>
      <div class="lable">
        <label>Email : <span><?php echo htmlspecialchars($email); ?></span></label>
      </div>
    </div>

    <div class="www item4"><button onclick="location.href='../user/editprofile.php'"><i class="fa fa-edit" style="font-size:36px"></i></button></div>
    <div class="www item6"><button>Delete Account</button></div>
  </div>
</body>
</html>
