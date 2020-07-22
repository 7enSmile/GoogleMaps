<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
die();
?>

<head>
   <?php $APPLICATION->ShowHead() ?>
      <?php
  use Bitrix\Main\Page\Asset;

Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/css/style.css');
   ?>

</head>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
<header class="header">


  <div style="display:inline-block"><img src="<?=SITE_TEMPLATE_PATH?>/img/1.png"></div>
  <div style="display:inline-block"><p>21.07.20</p></div>


</header>
