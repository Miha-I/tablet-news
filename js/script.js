$(document).ready(function () {
    // Перемещение фрейма вверх
    $("#framePanel").stop().animate({top: -100 + "vh"}, 0);
    var positions;

    // Расчёт ширины сладов
    function slidesWidth() {
        positions = new Array();
        var totWidth = 0, $j = 1;
        $('#slides .slide').each(function(i){
            positions[i]= totWidth;
            totWidth += $(this).width();
            if(totWidth < (700 * $j))
                totWidth = 700 * $j;
            $j++;
        });
    }
    slidesWidth();

    // Клик по меню
    $("#menu nav a").click(function (e){
        $("a.menuItem").removeClass("act");
        $(this).addClass("act");
        // Определение номера слайда
        var pos = $(".act").prevAll(".menuItem").length;
        // Анимация перемещения слайда
        $("#slides").animate({marginLeft: -positions[pos] + "px"}, 450, function () {
            // Перемещение вверх окна
            $('body,html').animate({scrollTop: 0}, 800);
        });
        // Обработка события (для остановки дальнейшего расспространения)
        e.preventDefault();
    });

    // Изменение размеров окна
    $(window).resize(function(){
        // Расчитать ширину слайдера
        slidesWidth();
        // Определение номера слайда
        var pos = $(".act").prevAll(".menuItem").length;
        // Перемещение слайда
        $('#slides').css({marginLeft: -positions[pos] + "px"});
    });

    // Нажатие на ссылку новости
    $("a.link").click(function () {
        // Показать окно загрузки
        $("#loading").fadeIn(200);
        // запрос серверу для загрузки страницы
        var link = $(this).data("link");
        $.ajax({
            type:"POST",
            url:"index.php",
            data:{openLink: link}
        }).done(function (html) {
            var doc = $("#frameShowSite")[0].contentWindow.document;
            // Загрузка страницы во фрейм
            doc.open();
            doc.write(html);
            doc.close();
        });
        return false;
    });

    // Показать фрейм
    $("#frameShowSite")[0].onload = function () {
        // Добавление функции обработки ссылок
        $("#frameShowSite")[0].contentWindow.document["body"].addEventListener("click", openLink, true);
        // Спрятать окно загрузки
        $("#loading").fadeOut(200);
        // Спрятать скрол
        $("html").addClass("hideScroll");
        // Показать фрейм
        $("#framePanel").stop().animate({top: 0}, 1300, "easeOutBounce");
    };

    // Нажатие на кнопку спрятать фрейм
    $("#clipUp,img").click(function () {
        // Спрятать фрейм
        $("#framePanel").stop().animate({top: -100 + "vh"}, 500, "easeInCubic", function () {
            // Показать скрол
            $("html").removeClass("hideScroll");
        });
    });

    // Обработка ссылок во фрейме
    function openLink(e) {
        if(e.target.localName == "a"){
            var link = e.target.href.substr(0, e.target.href.indexOf("?"));
            var result = confirm("Вы действительно хотите перейти по внешней ссылке " + link + "?");
            if(result != true) {
                e.preventDefault();
            }
            else{
                window.open(e.target);
                e.preventDefault();
            }
        }
    }
});
