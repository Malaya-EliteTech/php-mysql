 
<?php
// admin_home.php
session_start();
require_once('../conn.php');

// AUTH CHECK:
// Redirect to login if user is not logged in OR not an admin
if (!isset($_SESSION['id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Optional: get session values safely (if admin_navbar uses them)
$adminId = (int)($_SESSION['id'] ?? 0);
$adminName = htmlspecialchars(($_SESSION['fName'] ?? '') . ' ' . ($_SESSION['lName'] ?? ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Home - WorkWise</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../CSS/index.css">
    <style>
      /* minor inline styles to avoid missing CSS breaking the layout during testing */
      .jobctg_div { padding: 20px; }
      .job_row { display:flex; gap: 16px; flex-wrap:wrap; }
      .job1 { width: calc(20% - 16px); box-sizing:border-box; text-align:center; }
      .job_img img { max-width:100%; height:auto; display:block; margin:0 auto 8px; }
      @media (max-width:800px){ .job1{ width:45%; } }
      @media (max-width:480px){ .job1{ width:100%; } }
    </style>
</head>
<body>

    <!-- admin_navbar: keep include (it will use session values if needed) -->
    <?php include_once('admin_navbar.php'); ?>

    <main>
        <section class="div_flex">
            <div class="flex_left">
                <h2>Find the right <span id="freel">freelance </span> service, right away</h2>
                <h3>The #1 Site for Remote Jobs</h3><br>
                <button id="doctor-button" name="doctor-button"><span>Find Job</span></button>
            </div>
            <div class="flex_right"></div>
        </section>

        <div id="i" class="imagebar"></div>
        <br>
        <h1 id="text2">Popular job categories</h1>

        <div class="jobctg_div">
            <div class="job_row">
                <?php
                // Query: top 5 categories by count
                $sql = "SELECT category, COUNT(*) AS category_count
                        FROM jobtable
                        GROUP BY category
                        ORDER BY category_count DESC
                        LIMIT 5";

                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $rawCategory = $row['category'];
                        // Map short category names to display labels
                        switch ($rawCategory) {
                            case "Graphics":   $c1 = 'Graphics & Design'; break;
                            case "Programming":$c1 = 'Programming & Tech'; break;
                            case "Digital":    $c1 = 'Digital Marketing'; break;
                            case "Video":      $c1 = 'Video & Animation'; break;
                            case "Writing":    $c1 = 'Writing & Translation'; break;
                            case "Music":      $c1 = 'Music & Audio'; break;
                            case "Business":   $c1 = 'Business'; break;
                            case "AI":         $c1 = 'AI Services'; break;
                            default:           $c1 = htmlspecialchars($rawCategory ?: 'New Job category'); break;
                        }

                        // Build safe URLs and image filename
                        $ctgParam = rawurlencode($rawCategory);
                        $imageFile = "../../Image/FT/" . htmlspecialchars($rawCategory) . ".png";
                        // Fallback to a default image if file missing
                        if (!file_exists(__DIR__ . "/../../Image/FT/" . $rawCategory . ".png")) {
                            $imageFile = "../../Image/FT/coming.png"; // ensure coming.png exists
                        }
                ?>
                        <div class="job1">
                            <a href="admin_job_category.php?ctg=<?php echo $ctgParam; ?>">
                                <div class="job_img">
                                    <img src="<?php echo $imageFile; ?>" alt="<?php echo htmlspecialchars($c1); ?>">
                                </div>
                                <span><?php echo htmlspecialchars($c1); ?></span>
                            </a>
                        </div>
                <?php
                    } // end while
                } else {
                    // No categories: show 5 "coming soon" placeholders
                    for ($count = 0; $count < 5; $count++) { ?>
                        <div class="job1">
                            <a href="#">
                                <div class="job_img">
                                    <img src="../../Image/FT/coming.png" alt="coming soon">
                                </div>
                                <span>coming soon</span>
                            </a>
                        </div>
                    <?php }
                }

                // close connection if you don't need DB further (optional)
                $conn->close();
                ?>
            </div>
        </div>

        <br>
        <section class="foruser_div">
            <h1 id="ftyw_text">Find great <br> work</h1>
            <p id="ftyw_text2">
                Meet clients you’re excited to work with and take
                your career or business to new heights.
            </p>
            <br>
            <p><a href="find_job.php">Find Work </a></p>
        </section>

        <section class="div3">
            <div class="left_div3"></div>
            <div class="right_div3">
                <h1 id="ftyw_text">Find talent <br> your way</h1>
                <p id="ftyw_text2">
                    Work with the largest network of independent
                    professionals and get things done from quick
                    turnarounds to big transformations.
                </p>
                <br>
                <p><a href="find_freelancer.Notlogin.php">Post Your Job</a></p>
            </div>
        </section>
    </main>

    <?php include_once('../footer.php'); ?>

</body>
</html>

