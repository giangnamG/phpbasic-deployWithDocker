<?php
class user{
    protected $users;
    protected $userCurrent;
    
    public function __construct()
    {
        $this->userCurrent = $_SESSION['username'];
        $users = [];
        $conn  = mysqli_connect("db","ngn","ngn@ngn","week4");
        $sql   = "select `username`, `active`,`permission`,`ipAddress` from `login-info`";
        $result = mysqli_query($conn,$sql);
        while($row = mysqli_fetch_assoc($result)){
            $tmp = array('username'=>$row['username'], 'active'=>$row['active'], 'permission'=>$row['permission'], 'ipAddress'=>$row['ipAddress']);
            array_push($users,$tmp);
        }
        $this->users = $users;
    }
    function search($users){
        if(isset($_GET['search'])){
            $keyWord = $_GET['search'];
            $keyWord = strtolower($keyWord);
            foreach($this->users as $user){
                if($keyWord==strtolower($user['username'])){
                    // var_dump($user);
                    return $user;
                }
            }
        return array('status'=>'0');        
        }
    }
}