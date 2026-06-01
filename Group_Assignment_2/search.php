<?php
require_once "settings.php";

// Redirect to jobs page if accessed directly without search
if (!isset($_GET['search'])) {
    header('Location: jobs.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Job Search Results | Learnova">
    <meta name="keywords" content="Jobs, Search, Results, Learnova">
    <meta name="author" content="Nusrat Islam">
    <title>Search Results | Learnova</title>
    <link rel="stylesheet" href="styles/styling.css">

    <!-- Internal CSS -->
    <style>
        aside {
            float: right;
            width: 25%;
            border: #093c58 2px inset;
            margin: 0.5em;
            padding: 0.5em;
        }

        section {
            border: #093c58 solid 2px;
            border-radius: 5px;
            margin: 0.5em;
        }

        .no-results {
            margin: 1em 0.5em;
            color: #cc0000;
            font-style: italic;
        }
    </style>
</head>

<?php include 'header.inc'; ?>

<hr>

<!--Body of HTML Page-->
<body>

    <article>
        <h2>Search Results</h2>

        <?php
        $db_conn = @mysqli_connect($host, $user, $pwd, $sql_db);

        if ($db_conn) {

            // Sanitise search input
            $search = trim($_GET['search']);
            $search = stripslashes($search);
            $search = htmlspecialchars($search);
            $search_safe = mysqli_real_escape_string($db_conn, $search);

            // Search by job title and reference number

            $query = "SELECT * FROM jobs 
              WHERE job_title LIKE '%$search_safe%' 
              OR reference_no LIKE '%$search_safe%'";
            $result = mysqli_query($db_conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<p>Showing results for: <strong>" . htmlspecialchars($search) . "</strong></p>";
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
                echo "<p class='no-results'>No jobs found matching <strong>" . htmlspecialchars($search) . "</strong>. Try a different search term.</p>";
            }

            mysqli_close($db_conn);
        } else {
            echo "<p>Unable to connect to Database</p>";
        }
        ?>

        <br>
        <a href="jobs.php">Back to all jobs</a>

    </article>

<hr>

<?php include 'footer.inc'; ?>

</body>
</html>