<?php
// サニタイジングを行う
// HTMLやJavascriptとして意味のある文字を別の文字（HTMLエンティティ）に変換する。
// https://www.php.net/manual/ja/function.htmlspecialchars.php
$post = array();
foreach ($_POST as $k => $v) {
    $post[$k] = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    // $post[$k] = $v;
}

try {
    // データベースに接続
    $dsn = 'mysql:dbname=todo_list;host=localhost;charset=utf8';
    $dbh = new PDO($dsn, 'root', 'root');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // POSTデータをデータベースにインサートする
    $sql = '';
    $sql .= 'insert into todo_items (';
    $sql .= 'expiration_date,';
    $sql .= 'todo_item';
    $sql .= ') values (';
    $sql .= ':expiration_date,';
    $sql .= ':todo_item';
    $sql .= ')';

    $stmt = $dbh->prepare($sql);

    // bindValue()の使い方は、下記を参照のこと
    // https://www.php.net/manual/ja/pdostatement.bindvalue
    // 「例1 名前付けされたプレースホルダを用いてプリペアドステートメントを実行する」のところ
    $stmt->bindValue(':expiration_date', $post['expiration_date'], PDO::PARAM_STR);
    $stmt->bindValue(':todo_item', $post['todo_item'], PDO::PARAM_STR);
    $stmt->execute();

    // 処理が完了したらトップページ（index.php）へリダイレクト
    header('Location: ./');
} catch (Exception $e) {
    // 例外発生時の処理

    var_dump($e);
    exit;
}
