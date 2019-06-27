<?php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["openLink"])){
    $link = $_POST["openLink"];
    $html = file_get_contents($link);
    echo $html;
    return;
}
$html_site1 = file_get_contents("https://dou.ua/lenta/");      // Загрузка страницы DOU.ua
$pattern1 = '/class="title">\s*<a href="(.*?)'.'>'.                     // Ссылка на статью
    '\s*(.*?)\s*<.*?'.                                                  // Заголовок статьи
    'class="announce-img"\s*src="(.*?)".*?'.                            //
    'class="date">(.*?)<span class="m\-hide">(.*?)<\/span.*?'.          // Время статьи
    'class="b-typo">\s*(.*?)(?:<\/p|;<a)/s';                            // Описание статьи

preg_match_all($pattern1, $html_site1, $match_site1);         // Прсинг страницы DOU.ua


$html_site2 = file_get_contents("https://habrahabr.ru");       // Загрузка страницы Хабрхабр
// Паттерн https://habrahabr.ru
$pattern2 = '/class="post__time_published">\s*(.*?)\s*<\/span>.*?'.     // Время статьи
    'class="post__title-arrow">.*?<a href="(.*?\/)"\s'.                 // Ссылка на статью
    'class="post__title_link">(.*?)<.*?'.                               // Заголовок статьи
    '<div class="content html_format">(.*?)<\/div>/s';                  // Описание статьи
preg_match_all($pattern2, $html_site2, $match_site2);         // Прсинг страницы Хабрхабр

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
</head>
<body>
<script type="text/javascript">

</script>
<div id="content">
    <!--<div  class="back"><div class="shelf"></div></div>-->
    <div id="overflow">
        <div>
            <div id="slides">
                <div class="slide">
                    <div id="border1">
                        <img src="img/border2.png">
                        <div class="container">
                            <?php for($i = 0; $i < count($match_site1[0]); $i++): ?>
                                <article>
                                    <span><?=$match_site1[4][$i].$match_site1[5][$i]?></span>
                                    <h3>
                                        <a href="#" class="link" data-link="<?=$match_site1[1][$i]?>"><?=$match_site1[2][$i]?></a>
                                    </h3>
                                    <img src="<?=$match_site1[3][$i]?>" style="float: left;">
                                    <p><?=$match_site1[6][$i]?></p>
                                </article>
                                <div style="clear:both;"></div>
                                <?php if(($i + 1) < count($match_site1[0])): ?>
                                    <hr>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <div class="slide">
                    <div id="border2">
                        <img src="img/border2.png">
                        <div class="container">
                            <?php for($i = 0; $i < count($match_site2[0]); $i++): ?>
                                <article>
                                    <span><?=$match_site2[1][$i]?></span>
                                    <h3>
                                        <a href="#" class="link" data-link="<?=$match_site2[2][$i]?>"><?=$match_site2[3][$i]?></a>
                                    </h3>
                                    <p><?=$match_site2[4][$i]?></p>
                                </article>
                                <div style="clear:both;"></div>
                                <?php if(($i + 1) < count($match_site2[0])): ?>
                                    <hr>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
    </div>
    <div id="menu">
        <nav>
            <a href="#" title="Меню" class="menuItem act">Лента новостей<br/><span>https://dou.ua</span></a>
            <a href="#" title="Меню" class="menuItem">Лента новостей<br/><span>https://habrahabr.ru</span></a>
        </nav>
    </div>
    <div id="framePanel">
        <iframe id="frameShowSite" src="" sandbox="allow-forms allow-popups allow-pointer-lock allow-same-origin allow-scripts allow-scripts"></iframe>
        <div id="clipUp" title="Спрятать">
            <img src="img/back.png">
        </div>
    </div>
    <div id="loading">
        <div></div>
    </div>
</div>
</body>
</html>