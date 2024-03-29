<?php

    date_default_timezone_set("Asia/Tokyo");

    $comment_array = array();
    $pdo = null;
    $stmt = null;
    $error_messages = array();


      //DB接続
      try {
        $pdo = new PDO('mysql:host=localhost;dbname=bbs-yt', 'root', 'ryuuki92');
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    //フォームを打ち込んだ時
    if(!empty($_POST["submitbtn"])){

        //名前のチェック
        if(empty($_POST["username"])){
            echo "名前を入力してください。";
            $error_messages["username"] = "名前を入力してください。";
        }

        if (empty($_POST["comment"])) {
            echo "コメントを入力してください。";
            $error_messages["comment"] = "コメントを入力してください。";
        }

        if(empty($error_messages)){
            $postDate = date("Y-m-d H:i:s");
        try {
            $stmt = $pdo->prepare("INSERT INTO `bbs-table` (`username`, `comment`, `postDate`) VALUES (:username, :comment, :postDate);");
            $stmt->bindParam(':username', $_POST["username"]);
            $stmt->bindParam(':comment', $_POST["comment"]);
            $stmt->bindParam(':postDate', $postDate);
    
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        }
    }

    //DBからコメントデータを取得する
    $sql = "SELECT `id`, `username`, `comment`, `postDate` FROM `bbs-table` ;";
    $comment_array = $pdo->query($sql);

    //DBの接続を閉じる
    $pdo = null;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP掲示板</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1 class="title">PHPで掲示板アプリ</h1>
    <hr>
    <div class="boardWrapper">
        <section>
            <?php foreach ($comment_array as $comment): ?>
            <article>
                <div class="nameArea">
                    <span>名前:</span>
                    <p class="username"><?php echo $comment["username"]; ?></p>
                    <time>:<?php echo $comment["postDate"]; ?></time>
                </div>
                <p class="comment"></p><?php echo $comment["comment"]; ?>
            </article>
            <?php endforeach; ?>
        </section>
        <form class="formWrapper" method="POST">
            <div>
                <input type="submit" value="書き込む" name="submitbtn">
                <label for="">名前</label>
                <input type="text" name="username">
            </div>
            <div>
                <textarea class="commentTextArea" name="comment"></textarea>
            </div>
        </form>
    </div>
</body>
</html>