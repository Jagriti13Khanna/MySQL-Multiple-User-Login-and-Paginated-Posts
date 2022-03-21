<?php

include("connect.php");
include("config.php");


//defaults
$orderBy = "course_code";
$order = " ASC";

//if clicked the link these will be set
if(isset($_GET['orderBy'])) {
    $orderBy = $_GET['orderBy'];    
}

if(isset($_GET['order'])) {
    $order = $_GET['order'];    
}

echo "Order by : $orderBy | Order : $order";

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
    <nav>
        <ul>
            <li><a href="<?php echo THIS_PAGE; ?>?orderBy=course_name&order=DESC">course name desc</a></li>
            <li><a href="<?php echo THIS_PAGE; ?>?orderBy=course_name&order=ASC">course name asc</a></li>
            <li><a href="<?php echo THIS_PAGE; ?>?orderBy=course_code&order=ASC">course code asc</a>
            </li>
            <li><a href="<?php echo THIS_PAGE; ?>?orderBy=course_code&order=DESC">course code desc</a></li>
        </ul>
    </nav>  
    <main>
        <?php
            
            $sql = "SELECT * from `courses_EDT` where deletedYN = 'N' ORDER BY $orderBy $order";

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
    </main>  
</body>
</html>