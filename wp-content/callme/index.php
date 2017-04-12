<?

$to = "elegant-life1122@yandex.ru";

$from = "noreply@".($_SERVER[«HTTP_HOST»]);

if( (strlen ($_GET['cname']) > 2) && ($_GET['cphone']) ) {

$title = "Перезвоните мне";
$mess =  "Телефон:\n-".substr(htmlspecialchars(trim($_GET['cphone'])), 0, 15)."\n\nИмя:\n".substr(htmlspecialchars(trim($_GET['cname'])), 0, 50)."\n\nДополнительная инфа:\n".substr(htmlspecialchars(trim($_GET['csubj'])), 0, 50);
$headers = "From: ".$from."\r\n";
$headers .= "Content-type: text/plain; charset=utf-8\r\n";

@mail($to, $title, $mess, $headers);

echo '<div class="c_success">Спасибо, сообщение отправлено.</div>';
        } else {
echo '<div class="c_error">Ошибка: заполните необходимые поля.</div>';
}
?>