<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="List of Job Positions and their Descriptions">
        <meta name="keywords" content="Jobs, Positions, Descriptions, Pay, Job Details">
        <meta name="authors" content="Kai Wigg">
        <title>Jobs | Learnova</title>
        <!--Stylesheet Links-->

        <link rel="stylesheet" href="styles/styling.css">

        <!--Internal Style-->
        <style>
        aside {
            float: right;
            width: 25%;
            border:#093c58 2px inset;
            margin: 0.5em;
            padding: 0.5em;
        }

        section {
            border: #093c58 solid 2px;
            border-radius: 5px;
            margin: 0.5em;
        }  
        
        </style>
    </head>

  <!--Body of HTML Page-->
  <body>

    <?php include 'header.inc'; ?>

    <hr>

        <article>
            <!--Jobs section-->
            <h2>Jobs Available</h2>
            
            <!--Search Form-->
            <form class="search-container" method="GET" action="jobs.php">
                <input type="text"
                name="search"
                placeholder="Search jobs"
                value="<?php echo isset($_GET['search'])? htmlspecialchars($_GET['search']):";?>">
            <button type="submit">Search</button>  
            <?php if (!empty($_GET['search'])): ?>
                    <a href="jobs.php">Clear</a>
                <?php endif; ?>
            </form>
            <?php
                require_once "settings.php";
                $db_conn = @mysqli_connect($host,$user,$pwd,$sql_db);
                if ($db_conn) {
                    if (!empty($_GET['search'])) {
                        $search = trim($_GET['search']);
                        $search = stripslashes($search);
                        $search = htmlspecialchars($search);
                        $search_safe = mysqli_real_escape_string($db_conn, $search);
                    $query = "SELECT * FROM jobs"
                    WHERE job_title LIKE '%$search_safe%' 
                                  OR job_description LIKE '%$search_safe%'
                                  OR salary LIKE '%$search_safe%'
                                  OR reference_no LIKE '%$search_safe%'
                                  OR responsibilities LIKE '%$search_safe%'
                                  OR reqs_and_prefs LIKE '%$search_safe%'
                                  OR word_from LIKE '%$search_safe%'";
                    } else {
                        $query = "SELECT * FROM jobs";
                    }
                    $result = mysqli_query($db_conn,$query);
                    if($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                        echo "<section>";
                        echo "<h3>" . $row['job_title'] . "</h3>";
                        echo "<aside>";
                        echo "<h3>A Word from fellow " . $row['job_title'] . "s:</h3>";
                        echo "<ul>";
                        echo $row['word_from'];
                        echo "</ul>";
                        echo "</aside>";
                        echo "<h3>Reference Number: " . $row['reference_no'] . "</h3>";
                        echo "<p>" . $row['job_description'] . "</p>";
                        echo "<h4><strong>Salary:</strong></h4>";
                        echo "<p>" . $row['salary'] . " / year</p>";
                        echo "<h4><strong>Key Responsibilities:</strong></h4>";
                        echo "<ul>";
                        echo $row['responsibilities'];
                        echo "</ul>";
                        echo "<h4><strong>Requirements and Preferences:</strong></h4>";
                        echo "<ul>";
                        echo $row['reqs_and_prefs'];
                        echo "</ul>";
                        echo "</section>";
                        }
                    } else {
                        if (!empty($_GET['search'])) {
                            echo "<p class='no-results'>No jobs found matching <strong>" . htmlspecialchars($_GET['search']) . "</strong>. Try a different search term.</p>";
                        } else {
                            echo "<p>No jobs currently available.</p>";
                        }
                    }
                    mysqli_close($db_conn);
                }
                else echo "<p>Unable to connect to Database</p>";
            ?>

        </article>
    <hr>

    <?php include 'footer.inc'; ?>

    </body>
</html>