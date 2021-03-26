let checkbox = document.querySelector("#toggle");
let onButton = document.querySelector("#valOn");
let offButton = document.querySelector("#valOff");

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
        //title: 'Basic Time Series',
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
            t: 10,
        },
        xaxis: {
            showline: true,
            linecolor: '#636363',
            linewidth: 0,
        },
        yaxis: {
            showline: true,
            linecolor: '#636363',
            linewidth: 0,
        },
    };

    var config = {
        responsive: true,
        displayModeBar: false
    }

    var data = [trace1,trace2,trace3];
    Plotly.newPlot('chart', data, layout, config);
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
    console.log(buttonOn);

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





