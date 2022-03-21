<?php

if(isset($_POST['ad-btn'])) {
    $post_title = trim($_POST['title']);    
    $post_ad = trim($_POST['ad']);

    if ($post_ad && $post_title)
    {
        if ($id)
        {
            $heading = "Update an Ad.";
            $sql = "UPDATE job_ads SET title = '$post_title', ad = '$post_ad' WHERE a_id = $id";
        }
        else
        {
            $heading = "Add an Ad.";
            $sql = "INSERT into job_ads (title, ad, u_id) VALUES ('$post_title', '$post_ad', '".$_SESSION['user_id']."')";

            echo $sql;
        }
        
        if ($conn->query($sql))
        {
           $message = "<p>Success!</p>";
           $post_ad = $post_title = ""; 
        }
        else
        {
            $message = "<p>Problem. $conn->error</p>";
        }
     } 
    else
    {
        if ($post_title == "")
        {
            $invalidPT = "<p class='valError'>*Job title cannot be empty. Please add a job title!*</p>";
        }
        else
        {
            $post_title= filter_var($post_title, FILTER_SANITIZE_STRING);
        }
        if ($post_ad == "")
        {
            $invalidPA = "<p class='valError'>*Job ad description cannot be empty. Please add a description!*</p>";
        }
        else
        {
            $post_ad= filter_var($post_ad, FILTER_SANITIZE_STRING);

            if ((strlen($post_ad) < 20))
            {
                $invalidPA = "<p class='valError'>*JDescription too short. Please add more!*</p>";
            }
        }
    }
}

?>