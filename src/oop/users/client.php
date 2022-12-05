<?php

class client extends user {
    public function __construct()
    {
        $existInDb = 0;
        foreach($this->users as $usr){
            if($usr['username']==$this->userCurrent){
                $existInDb = 1;
                if($usr['active']==0){
                    unset($_SESSION['logged']);
                    echo "<script>alert('you got kicked by admin permission')</script>";
                    echo "<script>window.location='./index.php'</script>";
                }
            }
        }
        if(!$existInDb){
            unset($_SESSION['logged']);
            echo "<script>alert('you were deleted by admin permission')</script>";
            echo "<script>window.location='./index.php'</script>";
        }
    }
}