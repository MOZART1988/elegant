<?
// www.nazartokar.com
// www.dedushka.org
// icq: 167-250-811
// nazartokar@gmail.com

//адрес почты для отправки уведомления
//несколько ящиков перечисляются через запятую
$to = "disanti05@gmail.com";
//адрес, от которого придёт уведомление
$from = "noreply@".($_SERVER[«HTTP_HOST»]);

//далее можно не трогать

if( (strlen ($_GET['cname']) > 2) && ($_GET['cphone']) ) {

$title = "Перезвоните мне";
$mess =  "Телефон:\n-".substr(htmlspecialchars(trim($_GET['cphone'])), 0, 15)."\n\nИмя:\n".substr(htmlspecialchars(trim($_GET['cname'])), 0, 50);
$headers = "From: ".$from."\r\n";
$headers .= "Content-type: text/plain; charset=utf-8\r\n";

@mail($to, $title, $mess, $headers);

echo '<div class="c_success">Спасибо, сообщение отправлено.</div>';
        } else {
echo '<div class="c_error">Ошибка: заполните все поля.</div>';
}
?>