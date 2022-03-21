<?php

$page_title="My Ads";


session_start();

$limit = 6;
$page = 1;
$orderby = "date_posted";
// echo $orderby;
$order = " DESC ";
$start_position = 0;
$limit_string = " LIMIT $start_position , $limit ";



if (isset($_GET)) {
    extract($_GET);
}

require ("includes/connect.php");
include("messages.php");
include("session-time-check.php");
include("login-post.php"); 
include("ad-post.php"); 


include("search-post.php");

if ($id) 
{
    $get_sql = "SELECT title, ad FROM job_ads WHERE a_id = $id AND u_id = " . $_SESSION['user_id'];
    $get_res = $conn->query($get_sql);
    if ($get_res->num_rows > 0)
    {
        $get_row = $get_res->fetch_assoc();
        $post_ad = $get_row['ad'];
        $post_title = $get_row['title'];
    }
    else
    {
        $message = "<p>Unable to retrieve information.</p>";
    }
}

//count of records to paginate
$count_sql = "SELECT COUNT(*) AS row_count FROM job_ads  WHERE deleted_yn = 'N' AND job_filled_yn = 'N' $seach_part ORDER BY $orderBy $order $limit_string";
$count_res = $conn->query($count_sql);

if ($count_result->num_rows > 0)
{
    $count_row = $count_result->fetch_assoc();
    // extract($count_row);
    // var_dump($count_row);
    $count_of_records = $count_row["row_count"];

    // echo $count_of_records;
    // echo $row_count;
}

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
// echo $count_of_records;
?>


<?php require ("includes/header.php"); ?>

<div class="main-flex-container">
    <div class="search-section">
        <div class="search">
            <?php include ("search-form.php"); ?>
        </div>
        <?php
            $list_sql = "SELECT a_id, title, ad, date_posted, user_name, users.u_id, job_filled_yn FROM job_ads INNER JOIN users ON job_ads.u_id = users.u_id WHERE deleted_yn = 'N' AND job_filled_yn = 'Y' AND  users.u_id = " .$_SESSION['user_id']." $search_part ORDER BY $orderby $order $limit_string ";
    
            echo $list_sql;
    
            $list_result = $conn->query($list_sql);
        ?>        
    </div> 
    
    <div class="ads-section">
        <?php if ($list_result->num_rows > 0): ?>
            <h2>My Ads</h2>            
                <div class="ads">  
                    <?php while($row = $list_result->fetch_assoc()): ?>
                        <?php extract($row); ?>
                        <div class="ads-single">
                            <h3><?php echo $title; ?></h3>
                            <div>
                                <p>Date posted : <?php echo date("M d, Y g:i a", strtotime($date_posted)) ?></p>
                                <p>Advertiser : <?php echo $user_name; ?></p>
                            </div>
                            <p><?php echo $ad; ?></p>
                            <div class="ads-single-flex">
                                <?php if ($_SESSION['user_id'] == $u_id):?>
                                    <a href="myads.php?id=<?php echo $ad_id; ?>">Edit</a>
                                    <?php if ($job_filled_yn == 'Y'):?>
                                        <a href="mark-job-unfilled.php?id=<?php echo $ad_id;?>">Job Available</a>
                                    <?php else: ?>
                                        <a href="mark-job-filled.php?id=<?php echo $ad_id;?>">Job Filled</a>
                                    <?php endif ?>
                                        <a href="delete.php?id=<?php echo $ad_id; ?>">Delete Job Permanently</a>
                                <?php endif ?>
                            </div>    
                        </div>
                    <?php endwhile ?>  
                </div>
        <?php endif ?>                  
    </div>
                
    <!-- pagination -->
    <?php  if($count_of_records > $limit): ?>                       
        <ul class="pagination">
            <li>Pages</li>
                <?php
                    $next_page = $page + 1;
                    $previous_page = $page - 1;
                    $anchor_string = THIS_PAGE."?search=$search&orderby=$orderby&order=$order&limit=$limit&page="; ?>
    
                    <?php if ($page > 1): ?>
                    <li><a href="<?php echo $anchor_string.$previous_page; ?>"><< Prev</a></li>
                    <?php endif ?>
    
                    <?php for ($i=1; $i <= $num_pages ; $i++): ?>
                        <?php if ($i != $page): ?>
                            <li><a href="<?php echo $anchor_string.$i; ?>"><?php echo $i; ?></a></li>
                        <?php else: ?>
                            <li><?php echo $i; ?></li>
                        <?php endif ?>
                    <?php endfor ?>
    
                    <?php if ($page < $num_pages): ?>
                                    <li><a href="<?php echo $anchor_string.$next_page; ?>">Next >></a></li>
                    <?php endif ?>
        </ul>
    <?php endif ?>  
</div>
    
<div class="form-section-mobile">
    <?php if ($message): ?>
        <div>
            <?php echo $message; ?>
        </div>
    <?php endif ?>
    
    <?php if ($_SESSION['user_id']): ?>
        <?php include ("ad-form.php"); ?>
    <?php else : ?>
        <?php include ("login-form.php"); ?>
    <?php endif ?>
</div>



<?php require ("includes/footer.php"); ?>