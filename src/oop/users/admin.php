<?php

class admin extends user{
    public function Delete(){
        if(isset($_GET['delete'])){
            $username = $_GET['delete'];
            $conn  = mysqli_connect("db","ngn","ngn@ngn","week4");
            $sql = "delete from `login-info` where username='$username'";
            mysqli_query($conn,$sql);
            echo "<script>window.location='./app.php'</script>";
        }
    }
    public function kick(){
        $conn  = mysqli_connect("db","ngn","ngn@ngn","week4");
        if(isset($_GET['kick'])){
            $username = $_GET['kick'];
            echo 'kick: '.$username;
            $sql   = "update `login-info` set `active`=0 where `login-info`.`username`='$username'";
            mysqli_query($conn,$sql);
            echo "<script>window.location='./app.php'</script>";
        }
        
    }
    public function permission(){
        function admin(){
            $conn  = mysqli_connect("db","ngn","ngn@ngn","week4");
            if(isset($_GET['change'])){
                if(isset($_GET['permission'])){
                    $userAndPermission = $_GET['permission'];
                    function findIndex($string){
                        for($i=0; $i<strlen($string); $i++)
                            if($string[$i]=='+')
                                return $i;
                    }
                    $index = findIndex($userAndPermission);
                    $username = substr($userAndPermission,0,$index);
                    $length = strlen($userAndPermission);
                    $permission = substr($userAndPermission,$index+1,$length);
                    mysqli_query($conn,"update `login-info` set `permission`='$permission' where `login-info`.`username`='$username'");
                    echo "<script>window.location='./app.php'</script>";
                }
            }
        }
        function client($users){
            $username = $_SESSION['username'];
            foreach($users as $user){
                if($user['username']==$username)
                    return $user['permission'];
            }
            return ;
        }
        admin();
    }
    public function get_client_ip() {
        $ipAddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipAddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipAddress = 'UNKNOWN';
        $conn  = mysqli_connect("db","ngn","ngn@ngn","week4");
        $username = $_SESSION['username'];
        mysqli_query($conn,"update `login-info` set `ipAddress`='$ipAddress' where `login-info`.`username`='$username'");
        return $ipAddress;
    }
    
}