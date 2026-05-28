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

            
            <?php
                require_once "settings.php";
                $db_conn = @mysqli_connect($host,$user,$pwd,$sql_db);
                if ($db_conn) {
                    $query = "SELECT * FROM jobs";
                    $result = mysqli_query($db_conn,$query);
                    if($result) {
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