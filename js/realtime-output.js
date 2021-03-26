//TODO THERMOMETER

const configuration = {
    minTemp: -20,
    maxTemp: 50,
    unit: "Celcius"
};

// Change min and max temperature values

const tempValueInputs = document.querySelectorAll("input[type='text']");

tempValueInputs.forEach((input) => {
    input.addEventListener("change", (event) => {
        const newValue = event.target.value;

        if(isNaN(newValue)) {
            return input.value = configuration[input.id];
        } else {
            configuration[input.id] = input.value;
            range[input.id.slice(0, 3)] = configuration[input.id]; // Update range
            //return setTemperature(); // Update temperature
        }
    });
});


// // Change temperature
//
// const range = document.querySelector("input[type='range']");
// const temperature = document.getElementById("temperature");
//
// function setTemperature() {
//     temperature.style.height = (range.value - config.minTemp) / (config.maxTemp - config.minTemp) * 100 + "%";
//     temperature.dataset.value = range.value + "°C";
// }
//
// range.addEventListener("input", setTemperature);
// setTimeout(setTemperature, 1000);


//TODO SEGMENT DISPLAY

var display = new SegmentDisplay("display");
display.pattern         = "###.#";
display.displayAngle    = 0;
display.digitHeight     = 25.5;
display.digitWidth      = 14.5;
display.digitDistance   = 4.6;
display.segmentWidth    = 2.1;
display.segmentDistance = 0.6;
display.segmentCount    = 7;
display.cornerType      = 0;
display.colorOn         = "#48E98A";//"#48E98A";
display.colorOff        = "#1b2239";//"#3b3b3b";
display.draw();

animate("   . ");

function animate(value) {
    display.setValue(value);
    //window.setTimeout('animate()', 300);
}

//TODO GAUGE


var data = [{
        type: "indicator",
        mode: "gauge+number",
        value: 420,
        gauge: {
            axis: {
                range: [null, 800],
                tickwidth: 5,
                tickcolor: "#6164C1",
            },
            bar: {
                color: "#6164C1",
            },
            borderwidth: 1,
            bgcolor: "#152036",
        },

    }
];

var layout = {
    width: 350,
    height: 300,
    margin: { t: 0, r: 0, l: 0, b: 0 },
    paper_bgcolor: "#1B2A47",
    font: {
        color: '#FBFBFB',
        family: 'Roboto, sans-serif',
    },
};
Plotly.newPlot('gauge', data, layout, {displayModeBar: false });

//TODO UPDATE

function updateTemperature(val){
    var floatValue = parseFloat(val);
    const temperature = document.getElementById("temperature");
    temperature.style.height = (floatValue - configuration.minTemp) / (configuration.maxTemp - configuration.minTemp) * 100 + "%";
    temperature.dataset.value = floatValue + "°C";
}
function updateHumidity(value){
    var valH = " "+value;
    animate(valH);
}
function updateIlluminance(val){

    // data.value = val;
    //
    // Plotly.newPlot('gauge', data, layout, {displayModeBar: false });

    //TODO val colors
    // data: [{
    //     value: val,
    //     gauge: {
    //         bar: {
    //             color: "#6164C1",
    //         },
    //     }
    // }],
    var hex = "#6164C1";

    if(val > 600){
        hex = "#FFF980";
    }else if(val > 400){
        hex = "#D3E874";
    }else if(val > 200){
        hex = "#80FF97";
    }

    Plotly.animate('gauge', {
        data: [{
            value: val,
            gauge: {
                bar: {
                    color: hex,
                },
            },
        }],
        traces: [0],
        layout: {}
    }, {
        transition: {
            duration: 0,
            easing: 'elastic-in-out'
        },
        frame: {
            duration: 0,
        }
    })

}