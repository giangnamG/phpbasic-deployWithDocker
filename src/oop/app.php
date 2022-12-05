<?php
    session_start();

    if(!isset($_SESSION['logged'])){
        echo "<script>alert('you are not logged in')</script>";
        echo "<script>window.location='./index.php'</script>";
    }
    require './users/user.php';
    require './users/admin.php';
    require './users/client.php';
    $admin = new admin();
    $client = new client();

    function logout(){
        if(isset($_GET['logout'])){
            unset($_SESSION['logged']);
            $conn  = mysqli_connect("db","ngn","ngn@ngn","week4");
            $username = $_SESSION['username'];
            $sql = "update `login-info` set `active`=0 where `login-info`.`username`= '$username'";
            mysqli_query($conn,$sql);
            unset($_SESSION['username']);
            echo "<script>window.location='./index.php'</script>";
        }
    }
    $ipClient = $admin->get_client_ip();                            //\\
    /////////////////////////////////////////////////////////  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    $users = $admin->users;
  
    ///////////////////////////////////////////////////////      \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    logout();
    /////////////////////////////////////////////////////          \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    $admin->Delete(); 
    ///////////////////////////////////////////////////             \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    $admin->kick();
    /////////////////////////////////////////////////                 \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    $admin->permission();
    ///////////////////////////////////////////////                     \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    $keyWord = $admin->search($users);
//   var_dump($keyWord);

    // echo $userCurrent.'<br>';
    // print_r($users);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['username']?></title>
    <link rel="shortcut icon" href="./assets/favicon.jpg">
    <link rel="stylesheet" href="./assets/admin.css"/>
    <style>
        .col-3 {
            width: 20%;
            text-align: center;
            float: right;
        }
        * { padding: 0; margin: 0; }
        .content {
            float: right;
            width: 55%;
            margin-top: 40px;
            margin-right: 20px;
        }

        thead { 
            float: right;
            margin-right: 20px;
            position: fixed;
            top: 50px;
            /* left: 0; */
            
            width: 55%;
            /* background-color: white;  */
        }
        th {
            height: 50px;
            background-color: #04AA6D;
            color: white;
        }
        th, td {
            padding: 15px;
        }
        td {
            background-color: #696969;
            color: white;
        }
        body{
            background-color: #556B2F;
        }
        .logout{
            margin-left: 30px;
        }
       
    </style>
</head>
<body>
    <div class="container">
        <div class="row bg_3">
            <h2><i><a href="https://www.facebook.com/ATTT.PTIT">ISPClub</a></i></h2>
            <div class="col-3 input-effect">
            <form method="get">
                <input class="effect-17" type="text" name="search" placeholder="">
                <div id="content-search"></div>
                <?php if(isset($keyWord['status'])): echo '<br>'."Not found!"?>
                <?php elseif(isset($keyWord['username'])):?>
                    <script>
                        var content_search =`<ul>
                                                <li>Username: <?php echo $keyWord['username']?></li>
                                                <li>Status: <?php echo (!$keyWord['active'] ? 'dead' : 'active')?></li>
                                                <li>Permission: <?php echo $keyWord['permission']?></li>
                                                <li>IpAddress: <?php echo $keyWord['ipAddress']?></li>
                                            </ul>`
                        document.getElementById('content-search').innerHTML = content_search
                    </script>
                <?php endif?>
                <label>Search Task ...</label>
                <span class="focus-border"></span>
            </form>
        </div>
        <div class="content">
           
            <table id="table-content" width="100%" border="1"></table>
            
            <script>
                var index = 1
                var content = `<tr>
                                <th width="5%"><center>STT</center></th>
                                <th width="30%"><center>Task</center></th>
                                <th width="10%"><center>Status</center></th>
                                <th width="25%"><center>Action</center></th>
                                <th width="5%"><center>Permission</center></th>
                                <th width="25%">IpAddress</th>
                            </tr>`
                document.getElementById('table-content').innerHTML = content
            </script>
            
        </div>
    </div>
    <div class="logout">
        <form method="get">
            <h3>Hi, 
                <?php 
                    echo $_SESSION['username'].'<br>'; 
                    if($_SESSION['username']!='admin')
                        echo "You are not admin!!".
                        '<br>'."Get 'admin' and 'hackerlord' for you";
                ?>
            </h3>
            <input type="submit" name="logout" value="logout">
        </form>
        <br><br>
        <ul>
            <li style="color: #8B0000;"><?php echo "Your IP address is: " . $ipClient?></li>
            <li>editor không thể thao tác admin</li>
            <li>editor có thể thao tác nhau</li>
            <li>delete, kick không thể thao tác editor,admin</li>
            <li>delete, kick có thể thao tác nhau</li>
            <li>viewer: người xem</li>
        </ul>
    </div>
    <?php if($_SESSION['username']=='admin'): ?>
        <?php foreach($users as $key): ?>
            <?php if($key['username']=='admin'){continue;}?>
            <script>
                    content += `<tr>
                                    <td width=""><center>${index++}</center></td>
                                    <td width=""><center><?php echo $key['username']?></center></td>
                                    <td width=""><center><?php echo(!$key['active'] ? 'dead' : 'active')?></center></td>
                                    <td width=""><center>
                                        <form method="get">
                                            <button name="delete" value="<?php echo $key['username']?>">Delete</button>
                                            <button name="kick" value="<?php echo $key['username']?>">Kick</button>
                                            <select name="permission">
                                                <option value="<?php echo $key['username']?>+viewer">viewer(default)</option>
                                                <option value="<?php echo $key['username']?>+editor">editor</option>
                                                <option value="<?php echo $key['username']?>+kick">kick</option>
                                                <option value="<?php echo $key['username']?>+delete">delete</option>
                                            </select>
                                            <input type="submit" name="change" value="change"/>
                                        </form>
                                    </center></td>
                                    <td width="10%"><center><?php echo $key['permission']?></center></td>
                                    <td width="10%"><center><?php echo $key['ipAddress']?></center></td>
                                </tr>`
            </script>
        <?php endforeach?>
    <?php endif?>
    <?php $yourPermission=client($users); if($_SESSION['username']!='admin'): ?>
        <?php foreach($users as $key): ?>
            <?php if($key['username']=='admin'):?>
                <script>
                    if(index==1)
                        content += `<tr>
                                        <td><center>${index++}</center></td>
                                        <td><center>admin</center></td>
                                        <td><center><?php echo(!$key['active'] ? 'dead' : 'active')?></center></td>
                                        <td><center></center></td>
                                        <td><center>admin</center></td>
                                        <td><center><?php echo $key['ipAddress']?></center></td>
                                    </tr>`
                </script>
            <?php elseif($key['username']==$_SESSION['username']): ?>
                <script>
                        content += `<tr>
                                        <td><center style="color: #FF8C00;">${index++}</center></td>
                                        <td><center style="color: #FF8C00;"><?php echo $_SESSION['username']?></center></td>
                                        <td><center style="color: #FF8C00;"><?php echo(!$key['active'] ? 'dead' : 'active')?></center></td>
                                        <td><center></center></td>
                                        <td><center style="color: #FF8C00;"><?php echo $yourPermission?></center></td>
                                        <td><center><?php echo $key['ipAddress']?></center></td>
                                    </tr>`
                </script>
            <?php else:?>
                <?php if($yourPermission=='viewer'): ?>
                    <script>
                            content += `<tr>
                                            <td><center>${index++}</center></td>
                                            <td><center><?php echo $key['username']?></center></td>
                                            <td><center><?php echo(!$key['active'] ? 'dead' : 'active')?></center></td>
                                            <td><center></center></td>
                                            <td><center><?php echo $key['permission']?></center></td>
                                            <td><center><?php echo $key['ipAddress']?></center></td>
                                        </tr>`
                    </script>
                <?php elseif($yourPermission=='delete'):?>
                    <?php if($key['permission']=='editor'):?>
                        <script>
                            content += `<tr>
                                            <td><center>${index++}</center></td>
                                            <td><center><?php echo $key['username']?></center></td>
                                            <td><center><?php echo(!$key['active'] ? 'dead' : 'active')?></center></td>
                                            <td><center></center></td>
                                            <td><center><?php echo $key['permission']?></center></td>
                                            <td><center><?php echo $key['ipAddress']?></center></td>
                                        </tr>`
                        </script>
                    <?php else:?>
                        <script>
                            content += `<tr>
                                            <td><center>${index++}</center></td>
                                            <td><center><?php echo $key['username']?></center></td>
                                            <td><center><?php echo(!$key['active'] ? 'dead' : 'active')?></center></td>
                                            <td><center><form method="get"><button name="delete" value="<?php echo $key['username']?>">delete</button></form></center></td>
                                            <td><center><?php echo $key['permission']?></center></td>
                                            <td><center><?php echo $key['ipAddress']?></center></td>
                                        </tr>`
                        </script>
                    <?php endif?>
                <?php elseif($yourPermission=='kick'):?>
                    <?php if($key['permission']=='editor'):?>
                        <script>
                            content += `<tr>
                                            <td><center>${index++}</center></td>
                                            <td><center><?php echo $key['username']?></center></td>
                                            <td><center><?php echo(!$key['active'] ? 'dead' : 'active')?></center></td>
                                            <td><center></center></td>
                                            <td><center><?php echo $key['permission']?></center></td>
                                            <td><center><?php echo $key['ipAddress']?></center></td>
                                        </tr>`
                        </script>
                    <?php else:?>
                        <script>
                            content += `<tr>
                                            <td><center>${index++}</center></td>
                                            <td><center><?php echo $key['username']?></center></td>
                                            <td><center><?php echo(!$key['active'] ? 'dead' : 'active')?></center></td>
                                            <td><center><form method="get"><button name="kick" value="<?php echo $key['username']?>">kick</button></form></center></td>
                                            <td><center><?php echo $key['permission']?></center></td>
                                            <td><center><?php echo $key['ipAddress']?></center></td>
                                        </tr>`
                        </script>
                    <?php endif?>
                <?php elseif($yourPermission=='editor'):?>
                    <script>
                            content += `<tr>
                                            <td><center>${index++}</center></td>
                                            <td><center><?php echo $key['username']?></center></td>
                                            <td><center><?php echo(!$key['active'] ? 'dead' : 'active')?></center></td>
                                            <td><center>
                                                <form method="get">
                                                    <button name="delete" value="<?php echo $key['username']?>">Delete</button>
                                                    <button name="kick" value="<?php echo $key['username']?>">Kick</button>
                                                    <select name="permission">
                                                        <option value="<?php echo $key['username']?>+viewer">viewer(default)</option>
                                                        <option value="<?php echo $key['username']?>+editor">editor</option>
                                                        <option value="<?php echo $key['username']?>+kick">kick</option>
                                                        <option value="<?php echo $key['username']?>+delete">delete</option>
                                                    </select>
                                                    <input type="submit" name="change" value="change"/>
                                                </form></center>
                                            </td>
                                            <td><center><?php echo $key['permission']?></center></td>
                                            <td><center><?php echo $key['ipAddress']?></center></td>
                                        </tr>`
                    </script>
                <?php endif?>
            <?php endif?>
        <?php endforeach?>
    <?php endif?>
    <script>
        document.getElementById('table-content').innerHTML = content
    </script>
</body>
</html>