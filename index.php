<?PHP 
    $host = 'localhost';  // Хост, у нас все локально
    $user = 'id8784704_test';    // Имя созданного вами пользователя
    $pass = 'test123'; // Установленный вами пароль пользователю
    $db_name = 'id8784704_test';   // Имя базы данных
    $link = mysqli_connect($host, $user, $pass, $db_name); // Соединяемся с базой

echo "
<html>
<head>
	<meta charset=\"utf-8\" />
	<title>HTML Document</title>
<script type=\"text/javascript\">
<!--
function validate_form ( )
{
	valid = true;
        if (document.new.new_user.value == \"\" )
        {
            alert (\"Пожалуйста заполните поле 'Новый пользователь'.\");
            valid = false;
        }
        return valid;
}
//-->
</script>
</head>
<body>
<form name=\"new\" method=\"post\" onsubmit=\"return validate_form ( );\">
	Добавить нового пользователя <input type=\"text\" name=\"new_user\">
	<input type=\"submit\" value=\"Добавить\">
</form>";
// Ругаемся, если соединение установить не удалось
if (!$link) {
    echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
    exit;
}	
$date = new DateTime(); 
$d = $date->format('Y-m-d');
if (isset($_POST["new_user"])) {
    $sql = mysqli_query($link, "SELECT max(`id`) as `max` FROM `users`");
    $result = mysqli_fetch_array($sql);
    $id = $result['max'] + 1;
    //Вставляем данные, подставляя их в запрос
    $sql = mysqli_query($link, "INSERT INTO `users` (`id`, `login`, `created`) VALUES ('{$id}', '{$_POST['new_user']}', '{$d}')");
    //Если вставка прошла успешно
    if ($sql) {
      	echo '<p>Данные успешно добавлены в таблицу.</p>';
    } else {
      	echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
    }
}
if (isset($_GET['del'])) {
      $sql = mysqli_query($link, "UPDATE `users` SET `deleted` = '{$d}' WHERE `id` = {$_GET['del']}");
      if ($sql) {
        echo "<p>Пользователь удален.</p>";
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }
echo "
<table border=\"1\">
    <tr>
        <td>Номер</td>
        <td>Имя</td>
        <td>Дата создания</td>
        <td>Действие</td>
    </tr>
";
$sql = mysqli_query($link, 'SELECT `id`, `login`, `created` FROM `users` where `deleted` is null');
while ($result = mysqli_fetch_array($sql)) {
    echo "<tr><td>{$result['id']}</td><td>{$result['login']}</td><td>{$result['created']}</td><td><a href='?del={$result['id']}'>Удалить</a></td></tr>";
  }
echo "
</table>
</body>
</html>";
?>