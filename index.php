<?php
require_once "config.php";

try {
    $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM data";
    $stmt = $db->query($query);
    $data = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));


    $query = "SELECT * FROM settings";
    $stmt = $db->query($query);
    $settings = json_encode($stmt->fetch(PDO::FETCH_ASSOC));
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!doctype html>
<html class="no-js" lang="sk">

<head>
    <meta charset="utf-8">
    <!--<meta http-equiv="x-ua-compatible" content="ie=edge">-->
    <title>Senzory</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="icon" type="image/png" href="img/favi.png">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- font thing CSS
		============================================ -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Thermometer CSS
		============================================ -->
    <link rel="stylesheet" href="css/thermometer.css">
    <!-- Fancy button CSS
		============================================ -->
    <link rel="stylesheet" href="css/button.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="css/style.css">
</head>
<!--box-shadow: 1px 2px 4px 0 rgba(0,0,0,0.25);-->
<body>
<!-- Start Welcome area -->
<div class="all-content-wrapper">
    <div class="header-advance-area">
        <div class="breadcome-area">
            <div class="container-fluid text-center">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="breadcome-list">
                            <div class="row">
                                <h1>Arduino senzory - teplota, vlhkosť, svietivosť</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="traffic-analysis-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mg-t-30">
                    <div class="white-box tranffic-als-inner">
                        <h3 class="box-title">Teplota</h3>
                        <h4 id="hot"></h4>
                        <div id="wrapper" class="componentWrapper1">
                            <div id="termometer">
                                <div id="temperature" style="height:0" data-value="0°C"></div>
                                <div id="graduations"></div>
                            </div>
                            <div id="playground">
                                <div id="range">
                                    <input class="rangeVal" type="range" min="-20" max="50" value="42">
                                </div>
<!--                                <p id="unit">Celcius C°</p>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="white-box tranffic-als-inner mg-t-30">
                        <h3 class="box-title">Vlhkosť</h3>
                        <h4 id="hum"></h4>
                        <div id="displayWrapper" class="componentWrapper1">
                            <canvas id="display">
                                Your browser is unfortunately not supported.
                            </canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div class="white-box tranffic-als-inner mg-t-30">
                        <h3 class="box-title">Svietivosť</h3>
                        <h4 id="lux"></h4>
                        <div id="gaugeWrapper" class="componentWrapper1">
                            <div id="gauge">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="product-sales-area mg-t-30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                    <div class="white-box analytics-info-cs mg-b-30">
                        <h3 class="box-title">Dáta</h3>
                        <div id="graphWrapper" class="componentWrapper3">
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                    <div class="white-box analytics-info-cs mg-b-30">
                        <h3 class="box-title">Upload to DB</h3>
                        <div id="buttonWrapper" class="componentWrapper2">
                            <p id="valOff" class="toggleVal offLabel ">OFF</p>
                            <input type="checkbox" name="checkbox" id="toggle" />
                            <label for="toggle" class="toggleWrapper">
                                <div class="toggle"></div>
                            </label>
                            <p id="valOn" class="toggleVal onLabel">ON</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-copyright-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer-copy-right ">
                        <p>Copyright © 2021 - Richterová</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<!-- plotly library
        ============================================ -->
<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<!-- plotly chart JS
        ============================================ -->
<script>var phpdata = <?php echo $data; ?>, settings = <?php echo $settings; ?></script>
<script src="js/plotly-chart.js"></script>
<!-- realtime outputs JS
        ============================================ -->
<script type="text/javascript" src="js/segment-display.js"></script>
<script src="js/realtime-output.js"></script>


<script src="https://www.gstatic.com/firebasejs/8.2.9/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.9/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.9/firebase-database.js"></script>
<script>
    var firebaseConfig = {
        apiKey: "AIzaSyC3pFnKS3u19PKMwwWMqMvrYpOhzDo0V3c",
        authDomain: "meteostanica-c1a4c.firebaseapp.com",
        databaseURL: "https://meteostanica-c1a4c-default-rtdb.firebaseio.com",
        projectId: "meteostanica-c1a4c",
        storageBucket: "meteostanica-c1a4c.appspot.com",
        messagingSenderId: "1034796815571",
        appId: "1:1034796815571:web:e279b9dfc33496431bf026",
        measurementId: "G-R864WT4SJY"
    };

    firebase.initializeApp(firebaseConfig);

    setInterval(function(){
        firebase.database().ref('realtime_data').on('value', function (snapshot){
            document.getElementById('hot').innerText = snapshot.val().temperature + "°C";
            document.getElementById('hum').innerText = snapshot.val().humidity+"%";
            document.getElementById('lux').innerText = snapshot.val().illuminance + "lux";
            updateTemperature(snapshot.val().temperature);
            updateHumidity(snapshot.val().humidity);
            updateIlluminance(snapshot.val().illuminance)


        });
    }, 2000);


</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>


</html>