<?php
// コーディングのヒント
// できるだけPHPのコードとHTMLのコードは分けて書く。
// 自分でも、他の人が見ても、わかりやすいように、適切にコメントを書く。

try {
    // データベースに接続
    $dsn = 'mysql:dbname=todo_list;host=localhost;charset=utf8';
    // データベースに接続するためのユーザー名・パスワードは、自分の開発環境のMySQLの設定を確認して編集する
    // XAMPPの場合は、デフォルトでパスワードなし
    // MAMPの場合は、デフォルトでパスワードは「root」
    $dbh = new PDO($dsn, 'root', 'root');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 削除済みを除く登録済みのTODOリストを全件取得
    $sql = '';
    $sql .= 'select ';
    $sql .= 'id, ';
    $sql .= 'expiration_date, ';
    $sql .= 'todo_item, ';
    $sql .= 'is_completed ';
    $sql .= 'from ';
    $sql .= 'todo_items ';
    $sql .= 'where ';
    $sql .= 'is_deleted=0 ';
    $sql .= 'order by expiration_date, id';

    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    // 取得したレコードを連想配列として変数に代入する
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // 例外発生時の処理
    var_dump($e);
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>TODOリスト</title>
    <!-- ブラウザの種類によって見え方の差異をなくすためのCSS -->
    <link rel="stylesheet" href="./css/normalize.css">
    <!-- 　TODOリストの見栄えを変えるためのCSS -->
    <link rel="stylesheet" href="./css/main.css">
</head>

<body>
    <div class="container">
        <h1>TODOリスト</h1>
        <form action="add.php" method="post">
            <input type="date" name="expiration_date" value="<?= date('Y-m-d') ?>">
            <input type="text" name="todo_item" value="" class="item">
            <input type="submit" value="追加">
        </form>
        <?php if (count($list) > 0) : ?>
            <table class="list">
                <tr>
                    <th>期限日</th>
                    <th>項目</th>
                    <th>未完了</th>
                    <th>完了</th>
                    <th>削除</th>
                    <th></th>
                </tr>
                <!-- 取得したレコード分繰り返し処理を行う -->
                <?php foreach ($list as $v) : ?>
                    <tr>
                        <form action="action.php" method="POST">
                            <!-- 画面に表示しないformの項目は、「type="hidden"」にする。 -->
                            <input type="hidden" name="id" value="<?= $v['id'] ?>">
                            <?php if ($v['is_completed'] == 1) : ?>
                                <!-- is_completed＝1のときは、打ち消し線が入るようにCSSのクラスを付与する -->
                                <td class="del"><?= $v['expiration_date'] ?></td>
                                <td class="del"><?= $v['todo_item'] ?></td>
                            <?php else : ?>
                                <!-- is_completed=0のときは、そのまま表示する -->
                                <td><?= $v['expiration_date'] ?></td>
                                <td><?= $v['todo_item'] ?></td>
                            <?php endif ?>
                            <!-- ラジオボタンは、is_completed=0のときは「未完了」が選択状態に、is_completed=1のときは「完了」が選択状態になるようにする -->
                            <td class="center"><input type="radio" name="is_completed" value="0" <?php if ($v['is_completed'] == 0) echo ' checked' ?>></td>
                            <td class="center"><input type="radio" name="is_completed" value="1" <?php if ($v['is_completed'] == 1) echo ' checked' ?>></td>
                            <td class="center"><input type="checkbox" name="is_deleted"></td>
                            <td><input type="submit" value="実行"></td>
                        </form>
                    </tr>
                <?php endforeach ?>
            </table>
        <?php endif ?>
    </div>
</body>

</html>