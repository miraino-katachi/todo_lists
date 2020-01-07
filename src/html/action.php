<?php
// POSTデータがないときは、トッページにリダイレクト
if (empty($_POST)) {
    header('Location: ./');
}

try {
    // データベースに接続
    $dsn = 'mysql:dbname=todo_list;host=localhost;charset=utf8';
    $dbh = new PDO($dsn, 'root', 'root');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 完了の処理
    $sql = '';
    $sql .= 'update todo_items set ';
    $sql .= 'is_completed=:value ';
    $sql .= 'where id=:id';

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
    $stmt->bindValue(':value', $_POST['is_completed'], PDO::PARAM_INT);
    $stmt->execute();

    // 削除の処理
    if (isset($_POST['is_deleted'])) {
        $sql = '';
        $sql .= 'update todo_items set ';
        $sql .= 'is_deleted=1 ';
        $sql .= 'where id=:id';

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
        $stmt->execute();
    }

    // 処理が完了したら、トップページへリダイレクト
    header('Location: ./');
} catch (Exception $e) {
    var_dump($e);
    exit;
}
