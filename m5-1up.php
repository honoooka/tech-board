<?php
    //接続
        $dsn = 'mysql:dbname=*****;host=localhost';
        $user = '*****';
        $password = 'PASSWORD';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        //table作成
        $sql = "CREATE TABLE IF NOT EXISTS board"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date TEXT,"
        . "pass char(32)"
        .");";
        $stmt = $pdo->query($sql);
        
    if(isset($_POST["name"],$_POST["string"],$_POST["pass"]) || isset($_POST["delete"],$_POST["pass_d"]) || isset($_POST["editer"],$_POST["pass_e"])){
        $nam = $_POST["name"];
        $str = $_POST["string"];
        $setpass = $_POST["pass"];
        $editer = $_POST["editer"];
        $pass_e = $_POST["pass_e"];
        $d = date("Y/m/d H:i:s");
        if(!empty($editer) && strlen($pass_e)){
            //form表示処理
            $ne = $editer;
            $sql = 'SELECT * FROM board';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row){
                if(($ne == $row['id']) && ($pass_e == $row['pass'])){
                    $na = $row['name'];
                    $st = $row['comment'];
                }
            }
        }
        $n_e = $_POST["nnnnn"];
        if((strlen($nam)) && (strlen($str)) && (strlen($setpass)) && empty($n_e)){
            //新規投稿
            $sql = $pdo -> prepare("INSERT INTO board (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $name = $nam;
            $comment = $str;
            $date = $d;
            $pass = $setpass;
            $sql -> execute();
                
        }else if((strlen($nam)) && (strlen($str)) && (!empty($n_e))){
            $id = $n_e; //変更する投稿番号
            //編集
            $name = $_POST["name"];
            $comment = $_POST["string"];
            $sql = 'UPDATE board SET name=:name,comment=:comment,pass =:pass WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $setpass, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    
    
        $delete = $_POST["delete"];
        $pass_d = $_POST["pass_d"];
        //削除
        if(strlen($delete) && strlen($pass_d)){
            $id = $delete;
            $sql = 'SELECT * FROM board';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row){
                if(($delete == $row['id']) && ($pass_d == $row['pass'])){
                    $sql = 'delete from tbtest_2 where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }
        
    }
    ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <form action = "" method = "post">
        [ 投稿フォーム ]<br>
        名前 : <input type = "text" name = "name" value = "<?php if(isset($na)){echo $na;}else{echo "";} ?>"><br>
        コメント:<input type = "text" name = "string" value = "<?php if(isset($st)){echo $st;}else{echo "";} ?>"><br>
        パスワード : <input type = "password" name = "pass"><br>
        <input type = "hidden" name = "nnnnn" value = "<?php if(!empty($editer)){echo $ne;}else{echo "0";} ?>">
        <input type = "submit" name = "submit"><br><br>
        [ 削除フォーム ]<br>
        投稿番号 : <input type = "number" name = "delete"><br>
        パスワード : <input type = "password" name = "pass_d"><br>
        <input type = "submit" name = "submit1" value="削除"><br><br>
        [ 編集フォーム ]<br>
        投稿番号 : <input type = "number" name = "editer"><br>
        パスワード : <input type = "password" name = "pass_e"><br>
        <input type = "submit" name = "submit2" value="編集">
    </form>
    <?php
    //エラー表示処理
    if(isset($_POST["submit"])){
        if(!strlen($nam)){
            echo "!---------------!<br>";
            echo "Error: Name is Empty<br>";
            echo "!---------------!<br>";
        }else if(strlen($nam) && !strlen($str)){
            echo "!---------------!<br>";
            echo "Error: Comment is Empty<br>";
            echo "!---------------!<br>";
        }else if(strlen($nam) && strlen($str) && !strlen($setpass)){
            echo "!---------------!<br>";
            echo "Error: Password is Empty<br>";
            echo "!---------------!<br>";
        }
    }else if(isset($_POST["submit1"])){
        if(!strlen($delete)){
            echo "!---------------!<br>";
            echo "Error: Delete-Number is Empty<br>";
            echo "!---------------!<br>";
        }else if(strlen($delete) && !strlen($pass_d)){
            echo "!---------------!<br>";
            echo "Error: Password is Empty<br>";
            echo "!---------------!<br>";
        }else if(strlen($delete) && strlen($pass_d)){
            $sql = 'SELECT * FROM board';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row){
                if(($delete == $row['id']) && ($pass_d != $row['pass'])){
                    echo "!---------------!<br>";
                    echo "Error: Password is invalid<br>";
                    echo "!---------------!<br>";
                }
            }
        }
    }else if(isset($_POST["submit2"])){
        if(!strlen($editer)){
            echo "!---------------!<br>";
            echo "Error: Edit-Number is Empty<br>";
            echo "!---------------!<br>";
        }else if(strlen($editer) && !strlen($pass_e)){
            echo "!---------------!<br>";
            echo "Error: Password is Empty<br>";
            echo "!---------------!<br>";
        }else if(strlen($editer) && strlen($pass_e)){
            $sql = 'SELECT * FROM board';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row){
                if(($editer == $row['id']) && ($pass_e != $row['pass'])){
                    echo "!---------------!<br>";
                    echo "Error: Password is invalid<br>";
                    echo "!---------------!<br>";
                }
            }
        }
    }
    
    //投稿表示
    echo "---------------<br>";
    echo "[投稿一覧]<br>";
    echo "---------------<br>";
    $sql = 'SELECT * FROM board';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].' ';
        echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['date'];
        //echo $row['pass'].'<br>';
        echo "<hr>";
    }
    ?>
</body>
</html>