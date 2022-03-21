<?php

include("connect.php");
include("config.php");

//defaults
$limit = 5;
$page = 1;
$start_position = 1;
$limit_string = " LIMIT $start_position , $limit ";

if (isset($_GET['page']))
{
    $page = $_GET['page'];
}

$count_sql = "SELECT COUNT(*) AS row_count FROM `courses_EDT` WHERE deletedYN = 'N'";
$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();
// extract($count_row);
// var_dump($count_row);
$count_of_records = $count_row["row_count"];

// echo $count_of_records;
// echo $row_count;

if ($count_of_records > $limit)
{
    // end = 13 / 5 remainder = 3
    $end = round($count_of_records % $limit, 0);
    // splits = 13 - 3 / 5 =2
    $splits = round(($count_of_records - $end) / $limit, 0);
    // num pages = 2
    $num_pages = $splits;
    if ($end != 0)
    {
        // num pages = 3
        $num_pages++;
    }

    // in groups of limit (5) where records are being retrieved from
    $start_position = ($page * $limit) - $limit;
    $limit_string =  " LIMIT $start_position , $limit ";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
    <main>
        <?php
            
            $sql = "SELECT * from `courses_EDT` where deletedYN = 'N' ORDER BY course_code $limit_string";
            echo $sql;

            $result  = $conn->query($sql);

            if($result->num_rows > 0 )
            {
                while ($row = $result->fetch_assoc())
                {
                    $code = $row['course_code'];
                    $name = $row['course_name'];

                    echo "<div>";
                    echo "<p>Name : $name</p>";
                    echo "<p>Code : $code</p>";
                    echo "</div>";
                }
            }

        ?>

        <?php if($count_of_records > $limit)
        {
            $next_page = $page + 1;
            $previous_page = $page - 1;

            echo "<ul";

            if ($page > 1) {
                echo "<li><a href=' ". THIS_PAGE."?page=$previous_page'><< PREVIOUS </a></li>";
            }

            for ($i=1; $i <= $num_pages ; $i++)
            {
                if ($i != $page)
                {
                    echo "<li><a href=' ". THIS_PAGE."?page=$next_page'>$i</a></li>";
                }
                else 
                {
                    //makes current page non-clickable
                    echo "<li>$i</li>";
                }
            }

            
            if ($page < $num_pages) {
                echo "<li><a href=' ". THIS_PAGE."?page=$next_page'>NEXT >></a></li>";
            }

            echo "</ul>";
        }

        ?>
    </main>
</body>
</html>