<?php

require ("includes/connect.php");

include ("includes/header.php");


if (isset($_GET['id']) && is_numeric($_GET['id']))
{
    $u_id = $_GET['id'];

    if(!isset($_SESSION['minnislongrandomstring-1']))
    {
        header("Location: index.php?m=notloggedin");
    }
    else
    {
        $sql = "SELECT u_id FROM users WHERE u_id = $u_id AND u_id = " . $_SESSION['user_id'];
        $res = $conn->query($sql);
        
        if ($res->num_rows > 0)
        {
            if (isset($_POST['change-btn']))
            {
                $password = trim($_POST['password']);
                $newPassword = trim($_POST['newPassword']);

                if($password && $newPassword)
                {
                    if(password_verify($password, $hash))
                    {
                        $update_sql = "UPDATE INTO users SET password = $hash WHERE u_id = $u_id";

                        if($conn->query($update_sql))
                        {
                            header("Location: index.php?cp=pwdchanged");
                        }
                        else
                        {
                            $string = $conn->error;
                            $message = "<p class='valError'>There was a problem updating the password. Please try again! . $string </p>";
                        }
                    } 
                    else
                    {
                        $message = "<p class='valError'>Password does not match. Try again!</p>";
                    }          
                }
                else
                {
                    if($password == "")
                    {
                        $invalidOP = "<p class='valError'>*Please enter your old password*</p>";  
                    }

                    if($newPassword == "")
                    {
                        $invalidNP = "<p class='valError'>*Please enter a new password*</p>";  
                    }
                    else
                    {
                        if ((strlen($newPassword) < 6))
                        {
                            $invalidPswd = "<p class='valError'>*Password too short. Try again!*</p>";            
                        }

                        elseif (!preg_match("#[0-9]+#",$newPassword))
                        {
                            $invalidPswd = "<p class='valError'>*Your password must contain number(s). Try again!*</p>"; 
                        }

                        elseif (!preg_match("#[A-Z]+#",$newPassword)) 
                        {
                            $invalidPswd = "<p class='valError'>*Your password must contain at least 1 capital letter. Try again!*</p>";
                        }

                        elseif (!preg_match("#[a-z]+#",$newPassword))
                        {
                            $invalidPswd = "<p class='valError'>*Your password must contain at least 1 lowercase letter. Try again!*</p>";
                        }
                        else
                        {
                            $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                        }
                    }
                }
            } 
        }  
    } 
}      
?>

<div class="change-password">
    <h2>Change you Password</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="POST" class="change-password-form form">

        <?php if($message): ?>
            <div class="msg">
                <?php echo $message; ?>
            </div>
        <?php endif ; ?>
    
        <div>
            <label for="password">Current Password</label>
            <input type="text" id="password" name="password" value="<?php echo $password; ?>">
            <?php if ($invalidOP) echo $invalidOP; ?> 
        </div>
    
        <div>
            <label for="newPassword">New Password</label>
            <input type="text" id="newPassword" name="newPassword" value="<?php echo $newPassword; ?>">
            <?php if ($invalidNP) echo $invalidNP; ?> 
        </div>
    
        <!-- <input type="hidden" name="hidden_id" value="<?php if(isset($id)) echo $id; ?>"> -->
    
        <div>
            <input type="submit" name="change-btn" value="Change Password!">
        </div>
    </form>
</div>

<?php include ("includes/footer.php"); ?>