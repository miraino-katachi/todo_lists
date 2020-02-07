<?php
// コーディングのヒント
// できるだけPHPのコードとHTMLのコードは分けて書く。
// 自分が見ても、他の人が見ても、わかりやすいように、適切にコメントを書く。

// POSTデータがないときは、トッページにリダイレクト
if (empty($_POST)) {
    header('Location: ./');
}

try {
    // データベースに接続するための文字列（DSN・接続文字列）
    $dsn = 'mysql:dbname=todo_list;host=localhost;charset=utf8';

    // PDOクラスのインスタンスを作る
    // 引数は、上記のDSN、データベースのユーザー名、パスワード
    // XAMPPの場合はデフォルトでパスワードなし、MAMPの場合は「root」
    $dbh = new PDO($dsn, 'root', 'root');

    // エラーが起きたときのモードを指定する
    // 「PDO::ERRMODE_EXCEPTION」を指定すると、エラー発生時に例外がスローされる
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 完了の処理
    $sql = '';
    $sql .= 'update todo_items set ';
    $sql .= 'is_completed=:value ';
    $sql .= 'where id=:id';

    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);

    // SQL文の該当箇所に、変数の値を割り当て（バインド）する
    $stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
    $stmt->bindValue(':value', $_POST['is_completed'], PDO::PARAM_INT);

    // SQLを実行する
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
