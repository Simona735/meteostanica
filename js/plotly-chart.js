let checkbox = document.querySelector("#toggle");
let onButton = document.querySelector("#valOn");
let offButton = document.querySelector("#valOff");

let corX0 = document.querySelector("#x0-cor");
let corY0 = document.querySelector("#y0-cor");
let corX1 = document.querySelector("#x1-cor");
let corY1 = document.querySelector("#y1-cor");

checkbox.addEventListener('change', switchButton );

var temperatureData = phpdata.map(value => value.temperature);
var humidityData = phpdata.map(value => value.humidity);
var illuminanceData = phpdata.map(value => value.illuminance);
var timestampData = phpdata.map(value => value.timestamp);

let buttonOn = -1;
$(document).ready(function() {
    buttonOn = parseInt(settings.insert_to_db);
    if (buttonOn){
        checkbox.checked = true;
        offButton.classList.remove("off");
        onButton.classList.add("on");
    }else{
        checkbox.checked = false;
        onButton.classList.remove("on");
        offButton.classList.add("off");
    }

    var trace1 = {
        x: timestampData,
        y: illuminanceData,
        type: 'scatter',
        mode: 'lines',
        name: 'Svietivosť',
        line: {
            shape: 'spline',
            color: '#FBFBFB'
        }
    };

    var trace2 = {
        x: timestampData,
        y: temperatureData,
        type: 'scatter',
        mode: 'lines',
        name: 'Teplota',
        line: {
            shape: 'spline',
            color: '#4E509A'
        }
    };
    var trace3 = {
        x: timestampData,
        y: humidityData,
        type: 'scatter',
        mode: 'lines',
        name: 'Vlhkosť',
        line: {
            shape: 'spline',
            color: '#23B8C5'
        }
    };


    var layout = {
        title: '',
        plot_bgcolor: '#1B2A47',
        paper_bgcolor: '#1B2A47',
        font: {
            color: '#FBFBFB',
            family: 'Roboto, sans-serif',
        },
        margin: {
            l: 30,
            r: 5,
            b: 34,
            t: 25,
        },
        xaxis: {
            showline: true,
            linecolor: '#636363',
            linewidth: 0,
            title: '',
        },
        yaxis: {
            showline: true,
            linecolor: '#636363',
            linewidth: 0,
            title: '',
        },
        shapes: [{
            'type': 'line',
            'x0': "2021-03-25 15:00:00",
            'y0': 100,
            'x1': "2021-03-28 14:30:00",
            'y1': 600,
            'line': {
                'color': 'red',
                'width': 3,
            }
            },
            ]
    };
    setShapeCoordinates();

    var config = {
        responsive: true,
        displayModeBar: false,
        editable: true
    }

    var data = [trace1,trace2,trace3];
    Plotly.newPlot('chart', data, layout, config);

    var myPlot = document.querySelector("#chart");

    myPlot.on('plotly_relayout', function(data){
        setShapeCoordinates();
    });

// realtime chart
    setInterval(function() {

        $.get( "realtime.php", function( data ) {
            phpdata = $.parseJSON(data);
        });
        var illuminanceData = phpdata.map(value => value.illuminance);
        var temperatureData = phpdata.map(value => value.temperature);
        var humidityData = phpdata.map(value => value.humidity);
        var timestampData = phpdata.map(value => value.timestamp);
        Plotly.extendTraces('chart', {
            x: [[timestampData[timestampData.length-1]], [timestampData[timestampData.length-1]], [timestampData[timestampData.length-1]]],
            y: [[illuminanceData[illuminanceData.length-1]], [temperatureData[temperatureData.length-1]], [humidityData[humidityData.length-1]]]
        }, [0, 1, 2]);

    }, 2000);


    function setShapeCoordinates(){
        corX0.innerHTML = customDateFormat(layout["shapes"][0]["x0"]);
        corY0.innerHTML = layout["shapes"][0]["y0"].toFixed(2);
        corX1.innerHTML = customDateFormat(layout["shapes"][0]["x1"]);;
        corY1.innerHTML = layout["shapes"][0]["y1"].toFixed(2);
    }

} );



function switchButton(){
    if (buttonOn) {
        onButton.classList.remove("on");
        offButton.classList.add("off");
        buttonOn = 0;

    } else {
        offButton.classList.remove("off");
        onButton.classList.add("on");
        buttonOn = 1;

    }

    let dataVal = {
        element: buttonOn.toString()
    };
    const url = '/meteostanica/update.php'
    const request = new Request(url, {
        method: 'POST',
        body: JSON.stringify(dataVal)
    });

    fetch(request)
        .then(response => response.json())
        .then(data => { console.log(data); })
}

// $.post( "ajax/test.html", {insert: "1"}); $_POST["insert"]  //musi to byt string

function customDateFormat(dateValue){
    var data = new Date(dateValue);
    var stringDate = '';
    stringDate += data.getDate() + ".";
    stringDate += ((data.getMonth() + 1).toString()).padStart(2, "0") + ". ";
    stringDate += ((data.getHours()).toString()).padStart(2, "0") + ":";
    stringDate += ((data.getMinutes()).toString()).padStart(2, "0") + ":";
    stringDate += ((data.getSeconds()).toString()).padStart(2, "0");
    return stringDate;
}



