<?php
// コーディングのヒント
// できるだけPHPのコードとHTMLのコードは分けて書く。
// 自分が見ても、他の人が見ても、わかりやすいように、適切にコメントを書く。

// サニタイジングを行う
// HTMLやJavascriptとして意味のある文字を別の文字（HTMLエンティティ）に変換する。
// https://www.php.net/manual/ja/function.htmlspecialchars.php
$post = array();
foreach ($_POST as $k => $v) {
    $post[$k] = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    // $post[$k] = $v;
}

try {
    // データベースに接続するための文字列（DSN 接続文字列）
    $dsn = 'mysql:dbname=todo_list;host=localhost;charset=utf8';

    // PDOクラスのインスタンスを作る
    // 引数は、上記のDSN、データベースのユーザー名、パスワード
    // XAMPPの場合はデフォルトでパスワードなし、MAMPの場合は「root」
    $dbh = new PDO($dsn, 'root', 'root');

    // エラーが起きたときのモードを指定する
    // 「PDO::ERRMODE_EXCEPTION」を指定すると、エラー発生時に例外がスローされる
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

    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);

    // SQL文の該当箇所に、変数の値を割り当て（バインド）する
    $stmt->bindValue(':expiration_date', $post['expiration_date'], PDO::PARAM_STR);
    $stmt->bindValue(':todo_item', $post['todo_item'], PDO::PARAM_STR);

    // SQLを実行する
    $stmt->execute();

    // 処理が完了したらトップページ（index.php）へリダイレクト
    header('Location: ./');
} catch (Exception $e) {
    // 例外発生時の処理

    var_dump($e);
    exit;
}
