<?php 
require("./dbconnect.php");
session_start();
$name = "";
$password = "";
$password = filter_input(INPUT_POST,'password');
$name = filter_input(INPUT_POST,'name');
if (!empty($_POST)) {
    /* 入力情報のエラーを検知
              ↓ */
    if ($password === "") {
        $error['mail'] = "blank";
    }
    if ($password === "") {
        $error['password'] = "blank";
    }
    if (strlen($password) <= 8){
        $error['password'] = "blank";
    }

    /*ユーザ名の重複を検知
                ↓ */
    if (!isset($error)) {
        $mails = $db->prepare('SELECT COUNT(*) as cnt FROM users WHERE name=?');
        $mails->execute(array(
            $_POST['name']
        ));
        $record = $mails->fetch();
        if ($record['cnt'] > 0) {
            $error['name'] = 'duplicate';
        }
    }
    
    /* メールアドレスを重複を検知 
              ↓ */
    if (!isset($error)) {
        $mails = $db->prepare('SELECT COUNT(*) as cnt FROM users WHERE mail=?');
        $mails->execute(array(
            $_POST['mail']
        ));
        $record = $mails->fetch();
        if ($record['cnt'] > 0) {
            $error['mail'] = 'duplicate';
        }
    }

    /* エラーがなければ次のページへ 
                ↓ */
    if (!isset($error)) {
        $_SESSION['join'] = $_POST;   // フォームの内容をセッションで保存
        header('Location: check.php');   // check.php移動
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <title>メール会員新規登録</title>
    <link href="https://unpkg.com/sanitize.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
</head>
</head>
<body>
    <div class="content">
        <form action="" method="POST">
            <h1>メール会員新規登録</h1>
            <p class="warning">当サービスをご利用するために、次のフォームに必要事項をご記入ください。</p>
            <br>

            <div class="control">
                <label for="name">ユーザー名<span class="required">必須</span></label>
                <input id="name" type="text" name="name" placeholder="例) 山田　太郎" required>
                <?php if (!empty($error["name"]) && $error['name'] === 'blank'): ?>
                    <p class="error">＊ユーザ名を入力してください</p>
                <?php elseif (!empty($error["name"]) && $error['name'] === 'duplicate'): ?>
                    <p class="error">＊このユーザ名はすでに登録済みです</p>
                <?php endif ?>
            </div>

            <div class="control">
                <label for="mail">メールアドレス<span class="required">必須</span></label>
                <input id="mail" type="mail" name="mail" placeholder="例) xxxx@xxxxxx" required>
                <?php if (!empty($error["mail"]) && $error['mail'] === 'blank'): ?>
                    <p class="error">＊メールアドレスを入力してください</p>
                <?php elseif (!empty($error["mail"]) && $error['mail'] === 'duplicate'): ?>
                    <p class="error">＊このメールアドレスはすでに登録済みです</p>
                <?php endif ?>
            </div>

            <div class="control">
                <label for="password">パスワード<span class="required">必須</span></label>
                <input id="password" type="password" name="password"  required placeholder="例) test@@2129.AR" required>
                <?php if (!empty($error["password"]) && $error['password'] === 'blank'): ?>
                    <p class="error">＊パスワードを入力してください(パスワードは8文字以上で入力してください)</p>
                <?php endif ?>
            </div>

            <div class="control">
                <label for="address">住所<span class="required">必須</span></label>
                <input id="address" type="text" name="address"  placeholder="例) 香川県〇〇" required>
            </div>

            <div class="control">
                <label for="tel">電話番号<span class="required">必須</span></label>
                <input id="tel" type="text" name="tel" placeholder="例) 090xxxxxxxx" required>
            </div>

            <div class="control">
                <label for="secretword">秘密の言葉<span class="required">必須</span></label>
                <input id="secretword" type="text" name="secretword" placeholder="例) 好きな食べ物　りんご" required>
            </div>

            <div class="control">
                <button type="submit" class="btn">確認する</button>
            </div>
        </form>
    </div>
    <div>
        <img src="top.png" alt="" class="top_img">
        <img src="top2.png" alt="" class="top_img2">
    </div>
</body>
</html>