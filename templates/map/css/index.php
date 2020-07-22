<?php
if(isset($_COOKIE['user']))
{
$mysql=new mysqli('localhost','root','','11_lab');
$name =$_COOKIE['user'];
$result=$mysql->query("SELECT * FROM `settings` WHERE `name`='$name'");
$users=$result->fetch_assoc();
if(count($users)==0)
{
  $flag=0;
  $articles = [
    [
      'title' => 'Главная',
      'sources'=> 'index.html'


    ],
    [
      'title' => 'Наши услуги',
      'sources'=> 'about.html'


    ],
    [
      'title' =>  'Наши цены',
      'sources'=> 'cost.html'

    ],
    [
      'title' =>  'Просмотр товаров',
      'sources'=> 'view.php'

    ],
    [
      'title' =>  'Поиск товаров',
      'sources'=> 'find.php'

    ]
  ];

}
else {
  $flag=1;
  $pos=$users["por"];
  $articles[$pos[0]]=[    'title' => 'Главная',    'sources'=> 'index.html'];
  $articles[$pos[1]]=[    'title' => 'Наши услуги',   'sources'=> 'about.html'];
  $articles[$pos[2]]=[    'title' =>  'Наши цены',    'sources'=> 'cost.html'  ];
  $articles[$pos[3]]=[    'title' =>  'Просмотр товаров',  'sources'=> 'view.php' ];
  $articles[$pos[4]]=[    'title' =>  'Поиск товаров',    'sources'=> 'find.php'];

}
}
else {

$flag=0;
$articles = [
  [
    'title' => 'Главная',
    'sources'=> 'index.html'


  ],
  [
    'title' => 'Наши услуги',
    'sources'=> 'about.html'


  ],
  [
    'title' =>  'Наши цены',
    'sources'=> 'cost.html'

  ],
  [
    'title' =>  'Просмотр товаров',
    'sources'=> 'view.php'

  ],
  [
    'title' =>  'Поиск товаров',
    'sources'=> 'find.php'

  ]
];
}
?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=10">
  <title>SMILE PC</title>
  <link rel="stylesheet" href="css.css"> </head>
  <header class="header">
    <p class="title">SMILE PC</p>

  </header>
</head>
<menu class="menu">
<?php

for($i=0;$i<count($articles);$i++)
if($flag)
{
  if($articles[$i]["title"]=="Главная"and $users["main"]=="1")
    echo "<a href=\"/index.php?id=".$i."\">".$articles[$i]["title"]." </a>";
    if($articles[$i]["title"]=="Наши услуги"and $users["usl"]=="1")
      echo "<a href=\"/index.php?id=".$i."\">".$articles[$i]["title"]." </a>";
      if($articles[$i]["title"]=="Наши цены"and $users["cost"]=="1")
        echo "<a href=\"/index.php?id=".$i."\">".$articles[$i]["title"]." </a>";
        if($articles[$i]["title"]=="Просмотр товаров"and $users["view"]=="1")
        echo "<a href=\"/index.php?id=".$i."\">".$articles[$i]["title"]." </a>";
        if($articles[$i]["title"]=="Поиск товаров"and $users["fint"]=="1")
        echo "<a href=\"/index.php?id=".$i."\">".$articles[$i]["title"]." </a>";



}
else {
  echo "<a href=\"/index.php?id=".$i."\">".$articles[$i]["title"]." </a>";
}

 ?>



  <?php

  if(!isset($_COOKIE['user']))
  {
  echo "<a href=\"/index.php?id=35\">Вход</a>";
}
  else
  {
  echo "<a href=\"/index.php?id=90\">Настройки  </a>";
  echo "<a href=\"/index.php?id=50\">БД </a>";
  echo "<a href=\"/index.php?id=41\">Галерея </a>";
  echo "<a href=\"/index.php?id=40\">Загрузка </a>";
  echo "<a href=\"/index.php?id=37\">Выход </a>";
}
?>

</menu>
<?php
// Если id нет в URL - отображаем главную страницу
if(!isset($_GET['id']))
header("Location: /index.php?id=0");
else
if($_GET['id']==66)
require 'check_view.php';
else
if($_GET['id']==77)
require 'check_find.php';
else
if($_GET['id']==35)
require 'autorization.html';
else
if($_GET['id']==36)
require 'check_autorization.php';
else
if($_GET['id']==37)
require 'exit.php';
else
if($_GET['id']==68)
require 'registration.html';
else
if($_GET['id']==69)
require 'check_registration.php';
else
if($_GET['id']==70)
require 'mail2.php';
else
if($_GET['id']==71)
require 'mail.php';
else
if($_GET['id']==40)
require 'upload.php';
else
if($_GET['id']==41)
require 'view_img.php';
else
if($_GET['id']==80)
require 'del_img.php';
else
if($_GET['id']==50)
require 'load_XML.php';
else
if($_GET['id']==90)
require 'settings.html';
else
if($_GET['id']==91)
require 'check_settings.php';








// Если id есть, но нет статьи с таким id - показываем ошибку
elseif(!isset($articles[$_GET['id']]))
echo '<h1>Ошибка: страница не существует.</h1>';

// Если id есть и статья с таким id существует - выводим статью
else
{
  $article = $articles[$_GET['id']];


  require $article['sources'];

}
?>
<footer class="footer"> <a href="https://e.sfu-kras.ru/user/profile.php?id=106780">Автор</a> </footer>
