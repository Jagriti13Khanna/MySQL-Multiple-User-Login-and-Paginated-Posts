<?php



    
    if (isset($_POST['change-btn']))
    {
        $password = trim($_POST['password']);
        $newPassword = trim($_POST['newPassword']);

        // $pass_go = true;

        if ($newPassword && $password )
        {
            //validation


            $hash = password_hash($password, PASSWORD_DEFAULT);

            if (password_verify($password, $hash))
            {
                $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

                $update_sql = "UPDATE users set password = '$newHash' WHERE u_id = " . $_SESSION['user_id'];

                if ($conn->query($update_sql))
                {
                    $message = "<p>Success!</p>";
                    $password = $newPassword = ""; 
                }
                else
                {
                    $message = "<p>Problem. $conn->error</p>";
                }
            }
            else
            {
                $message = "<p>All required</p>";
            } 
        }
    }
 

?>