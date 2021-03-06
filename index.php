<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css
" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <title>Arduino realtime panel</title>
</head>
<body>
<h1>Arduino realtime panel</h1>

<h2 id="hot"></h2>
<h2 id="hum"></h2>
<h2 id="lux"></h2>

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

            document.getElementById('hot').innerText = snapshot.val().temperature;
            document.getElementById('hum').innerText = snapshot.val().humidity;
            document.getElementById('lux').innerText = snapshot.val().illuminance;
        });
    }, 500);


</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>

</body>
</html>