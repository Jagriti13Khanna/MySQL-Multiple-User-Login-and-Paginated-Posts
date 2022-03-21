<?php
$page_title="Job Bank";
session_start();

$limit = 6;
$page = 1;
$orderby = "date_posted";
// echo $orderby;
$order = " DESC ";
$start_position = 0;
$limit_string = " LIMIT $start_position , $limit ";

require ("includes/connect.php");



if (isset($_GET)) {
    extract($_GET);
}

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
        $message = "<p>Unable</p>";
    }
}

//count of records to paginate
$count_sql = "SELECT COUNT(*) AS row_count FROM job_ads INNER JOIN users ON job_ads.u_id = users.u_id  WHERE deleted_yn = 'N' and job_filled_yn = 'N' $search_part ";
$count_result = $conn->query($count_sql) or die ($conn->error);

// echo $count_sql;

if ($count_sql > 0)
{
    echo "no ads.";
}



// SELECT a_id, title, ad, date_posted, user_name, users.u_id, job_filled_yn FROM job_ads INNER JOIN users ON job_ads.u_id = users.u_id WHERE deleted_yn = 'N' AND job_filled_yn = 'N' $search_part ORDER BY $orderBy $order $limit_string 
if ($count_result->num_rows > 0)
{
    $count_row = $count_result->fetch_assoc();
    // extract($count_row);
    // var_dump($count_row);
    $count_of_records = $count_row["row_count"];

    // echo $count_of_records;
    // echo $row_count;
}



// echo $count_of_records;

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


<?php require ("includes/header.php"); ?>

<div class="main-flex-container">
    <div class="search-section">
        <div class="search">
            <?php include ("search-form.php"); ?>
        </div>
        <?php
            $list_sql = "SELECT a_id, title, ad, date_posted, user_name, users.u_id, job_filled_yn FROM job_ads INNER JOIN users ON job_ads.u_id = users.u_id WHERE deleted_yn = 'N' AND job_filled_yn = 'N' $search_part ORDER BY $orderby $order $limit_string ";
    
            // echo $list_sql;
    
            $list_result = $conn->query($list_sql); ?>

        
    </div> 
    <div class="ads-section">
    <?php if ($list_result->num_rows > 0): ?>
        <h2>Job Ads</h2>
                <div class="ads">
                    
                    <?php while($row = $list_result->fetch_assoc()): ?>
                        <?php extract($row); ?>
                        <div class="ads-single">
                            <h3><?php echo $title; ?></h3>
                            <div>
                                <p>Date posted : <?php echo date("M d, Y g:i a", strtotime($date_posted)) ?></p>
                                <p>Advertiser : <?php echo $user_name; ?></p>
                            </div>
                            <p><?php echo mb_strimwidth("$ad", 0, 100, "..."); ?></p>
    
                            <?php if ($_SESSION['user_id'] == $u_id): ?>
                                <div class="ads-single-flex">
                                    <a href="index.php?id=<?php echo $a_id; ?>">Edit</a>
                                    <!-- <a href="index.php?id=<?php echo $a_id; ?>">Delete</a> -->
                                
    
                                <?php if ($job_filled_yn == 'N'): ?>
                                    <a href="mark-job-filled.php?id=<?php echo $a_id; ?>">Mark Job as Filled</a>
                                <?php endif ?>
                                </div>
    
                            <?php endif ?>
                        </div>
                    <?php endwhile ?>                    
                </div>
                <!-- pagination -->
                <?php  if($count_of_records > $limit): ?>
                        
                    
                    <ul class="pagination">                            <li>Pages</li>
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
</div>



<?php require ("includes/footer.php"); ?>