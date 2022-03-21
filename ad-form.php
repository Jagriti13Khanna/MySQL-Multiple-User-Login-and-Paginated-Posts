<?php
    if ($id)
    {
        $heading = "Update an Ad.";
    }
    else
    {
        $heading = "Add an Ad.";
    }
?>
<h2><?php echo $heading; ?></h2>
<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="POST"  class="ad-form form">   
    
    <div>
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?php echo $post_title; ?>">
    </div>
    
    <div>
        <label for="ad">Ad Description</label>
        <textarea name="ad" id="ad"><?php echo $post_ad; ?></textarea>
    </div>

    <div><input type="submit" name="ad-btn" value="Post my ad!"></div>

</form>