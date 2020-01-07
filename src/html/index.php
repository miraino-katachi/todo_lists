<?php
try {
    // データベースに接続
    $dsn = 'mysql:dbname=todo_list;host=localhost;charset=utf8';
    $dbh = new PDO($dsn, 'root', 'root');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 登録済みのTODOリストを取得
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
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    var_dump($e);
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>TODOリスト</title>
    <link rel="stylesheet" href="./css/normalize.css">
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
                <?php foreach ($list as $v) : ?>
                    <tr>
                        <form action="action.php" method="POST">
                            <input type="hidden" name="id" value="<?= $v['id'] ?>">
                            <?php if ($v['is_completed'] == 1) : ?>
                                <td class="del"><?= $v['expiration_date'] ?></td>
                                <td class="del"><?= $v['todo_item'] ?></td>
                            <?php else : ?>
                                <td><?= $v['expiration_date'] ?></td>
                                <td><?= $v['todo_item'] ?></td>
                            <?php endif ?>
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