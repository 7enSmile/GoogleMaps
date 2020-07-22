<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
use Bitrix\Main\IO,Bitrix\Main\Application;
?>


<?foreach($arResult["ITEMS"] as $arItem):?>

<?
$url = SITE_TEMPLATE_PATH.'/js/routes.json';
$file = new IO\File(Application::getDocumentRoot().$url);
//полее Coordinates у инфоблока содержит информацию о маршрутах в виде JSON
$res = htmlspecialcharsBack($arItem['PROPERTIES']['Coordinates']['VALUE']);
// далее файл route.js перезаписыветься, потом это файл считывается и сторояться маршруты
$file->putContents($res);
?>

<?endforeach;?>
<!DOCTYPE html>
<html>

  <head>
    <title>Maps</title>
    <?php $APPLICATION->ShowHead();
    $arr=3;
    ?>

       <?php
   use Bitrix\Main\Page\Asset;

 Asset::getInstance()->addJS("https://code.jquery.com/jquery-1.12.4.js");
 Asset::getInstance()->addJS("https://code.jquery.com/ui/1.12.1/jquery-ui.js");
 Asset::getInstance()->addJS("https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js");


    ?>
    <!-- как с помощью api самого Битрикса подключать ассинфорнные скрипты не нашел -->
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAt6vc9eTwV1yP_DusrjLr-UkTZHwoLtyw&callback=initMap">
    </script>


    <style>
      #map {
          height: 650px;
          width: 100%;
       }
    </style>
  </head>
  <body>

    <div id="buttoms"></div>
    <div id="map"></div>



    <script type="text/javascript">
    var data = "";
    var map;
    var number=0;
    //храние всех маршрутов
    var routes=[];
    //храние всех ломаных
    var lines=[];
    //буфер для чтение из файла
    var data_routes;
    //с помощью ajax запроса получаем содержимое файла routes.json




    // инициализация карты
    function initMap() {
      $.ajax({
        url: "<?=SITE_TEMPLATE_PATH?>/js/routes.json",
        dataType : "json",
        success:the_function,
      })
      function the_function(response){
      data_routes=response;
      map = new google.maps.Map(document.getElementById('map'), {
        zoom: 14,
        center: {lat: 55.994358, lng: 92.797344}

      });

      if(data_routes!=undefined){
      // проход по всем марсшрутам
      for(var i=0;i<data_routes.length;i++)
      {
        var arr=[];
        for(var ix=0;ix<data_routes[i].length;ix++)
        arr.push({x:parseFloat(data_routes[i][ix].lat),y:parseFloat(data_routes[i][ix].lng)}); // заполниение массива в нужном формате с которым работает функия

        // функия добавление маркеров и ломаных на карту
        Markers(arr);

      }

      function Markers(arr){
        var route=[];
        var path=[];

        path.push({lat:arr[0].x,lng:arr[0].y});
        var marker = new google.maps.Marker({
          position: {lat:arr[0].x,lng:arr[0].y},

          map: map,
          icon:'https://chart.googleapis.com/chart?' +'chst=d_map_pin_letter&chld=O|0000FF|000000'
        });
        route.push(marker);
        for(var i=1;i<arr.length-1;i++)
        {

          var marker = new google.maps.Marker({
            position: {lat:arr[i].x,lng:arr[i].y},

            map: map,
            icon:'https://chart.googleapis.com/chart?' +'chst=d_map_pin_letter&chld=O|FFFF00|000000'
          });
          route.push(marker);
          path.push({lat:arr[i].x,lng:arr[i].y});
        }
        path.push({lat:arr[arr.length-1].x,lng:arr[arr.length-1].y});//координаты для ломаной
        var marker = new google.maps.Marker({
          position: {lat:arr[arr.length-1].x,lng:arr[arr.length-1].y},

          map: map,
          icon:'https://chart.googleapis.com/chart?' +'chst=d_map_pin_letter&chld=O|FF0000|000000'
        });
        route.push(marker);

        line = new google.maps.Polyline({
          path: path, //координаы точек
          strokeColor: "#FF0000",
          strokeOpacity: 1.0,
          strokeWeight: 3
        });
        lines.push(line);// заполнение массива ломаных, нужно для 3 задания
        line.setMap(map);
        // массивы для расчета длины марсшрутов
        var origins1=[]; // начальные
        var origins2=[]; // конечные
        for(var i=0;i<arr.length-1;i++)
        {
          origins1.push({lat:arr[i].x,lng:arr[i].y}); // заполнение начальных координат
        }

        for(var i=1;i<arr.length;i++)
        {
          origins2.push({lat:arr[i].x,lng:arr[i].y}); // заполнение конечных координат
        }

        var service = new google.maps.DistanceMatrixService;
        service.getDistanceMatrix({
          origins: origins1,
          destinations: origins2,
          travelMode: 'DRIVING',
          unitSystem: google.maps.UnitSystem.METRIC,
          avoidHighways: false,
          avoidTolls: false
        }, function(response, status) {
          var metric=0;
          //рачет длины марсшрута


          for(var i=0;i<response.rows.length;i++)
          {

            if(response.rows[i].elements[0].distance.value!=0)   //по какой-то причины длина марсштра може быть равна 0, это проверка на это
            {
              metric+=response.rows[i].elements[0].distance.value;

            }
            else {
              if(response.rows[i].elements.length>=2) // проверка на то, что другие марсшруты кроме нулевого существуют
              {
                metric+=response.rows[i].elements[1].distance.value;
              }
            }

          }
          console.log(metric); // вывод в консоль

        });


        routes.push(route);  // заполнение массива дорог, нужно для 3 задания
      }






    initButtoms();
    }
    else {
       $('#buttoms').append($('<p>Маршрутов нет!</p>')); // Вывод ошибки
    }

    }


    //Инициализация кнопок
    function initButtoms(){
    for(var i=0;i<data_routes.length;i++)
    {
      var ix=i+1;
       $('#buttoms').append($('<button onclick=\"Click('+i+')\">Маршрут '+ix+'</button><br>')); //вывод кнопок
    }
    }




  }
  // функия для расчета расстояние, аналогична прошлой
  function Metric(origins1,origins2)
  {
    var service = new google.maps.DistanceMatrixService;
    service.getDistanceMatrix({
      origins: origins1,
      destinations: origins2,
      travelMode: 'DRIVING',
      unitSystem: google.maps.UnitSystem.METRIC,
      avoidHighways: false,
      avoidTolls: false
    }, function(response, status) {
      var metric=0;


      for(var i=0;i<response.rows.length;i++)
      {
        if(response.rows[i].elements[0].distance.value!=0)
        {
          metric+=response.rows[i].elements[0].distance.value;

        }
        else {
          if(response.rows[i].elements.length>=2)
          {
            metric+=response.rows[i].elements[1].distance.value;
          }
        }

      }
      console.log(metric);


    });

  }
  // функиия по нажатию  кнопок
  function Click(index){
    for(var i=0;i<routes.length;i++)
    {

      for (var ix = 0; ix < routes[i].length; ix++) {
        if(i==index)
        {
          routes[i][ix].setMap(map); // только  маркеры index мрршрута устанавливается на карту

        }
        else {
          routes[i][ix].setMap(null); // остальные не устанавливаются

        }
      }
    }
    // только index ломаная устанавливаеться на карту, остальные нет
    for(var i=0;i<lines.length;i++)
    {
        if(i==index)
        {
          lines[i].setMap(map);
        }
        else {
          lines[i].setMap(null);
        }

    }
    var origins1=[];
    var origins2=[];
    //координаты для расчета расстояния
    for(var i=0;i<data_routes[index].length-1;i++)
    {
      origins1.push({lat:parseFloat(data_routes[index][i].lat),lng:parseFloat(data_routes[index][i].lng)});
    }

    for(var i=1;i<data_routes[index].length;i++)
    {
      origins2.push({lat:parseFloat(data_routes[index][i].lat),lng:parseFloat(data_routes[index][i].lng)});
    }
    //функия для расчета расстояние
    Metric(origins1,origins2)
  }
    </script>

  </body>
</html>
