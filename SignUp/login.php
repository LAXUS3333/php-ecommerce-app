<?php

if (isset($_POST['Login'])) {


    require 'database.php';

    $mailuid=$_POST['mailuid'];
    $password=$_POST['pwd'];

    if (empty($mailuid) || empty($password)) {
        header("Location:../Login.php?error=emptyfields");
        exit();
    }

    else {
        $sql="SELECT * FROM users WHERE uiDUsers=? OR emailUsers=?";
        $stmt=mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt,$sql)) {
            header("Location: ../Login.php?error=sqlerror");
            exit();
        }

        else{
            mysqli_stmt_bind_param($stmt, "ss", $mailuid, $mailuid);
            mysqli_stmt_execute($stmt);
            $result=mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                $pwdCheck= password_verify($password,$row['pwdUsers']);

                if ($pwdCheck==false) {
                    header("Location: ../Login.php?error=wrongpwd");
                    exit();
                }

                else if ($pwdCheck==true) {

                    
                    session_start();
                    $_SESSION['userId']=$row['idUsers'];
                    $_SESSION['userUid']=$row['uidUsers'];
                    if ($row['uidUsers']=="admin"){

                        header("Location: ../Admin/index.php?login=success");

                    }
                    else{

                    header("Location: ../index.php?login=success");
                    exit();
                    }
                }

                else {
                    header("Location: ../Login.php?error=wrongpwd");
                    exit();
                }
            }

            else {
                header("Location: ../Login.php?error=nouser");
                exit();
            }
    }


}

}

else{
    header("Location: ../Login.php");
    exit();
}