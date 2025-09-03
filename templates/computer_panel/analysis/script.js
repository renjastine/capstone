$(document).ready(function(){    
    function select_hardware(hardware){
        if(hardware == 'cpu'){
            $('#sensor_used').hide();
            $('#sensor_clock').show();
            $('#sensor_power').show();
        }
        else{
            $('#sensor_used').show();
            $('#sensor_clock').hide();
            $('#sensor_power').hide();
        }
    }

    function add_empty_sensor(hardware){    
        $('#sensor').empty();

        if(hardware == 'cpu'){
            $('#sensor').append("<option id='sensor_temp' value='temperature'>Temperature</option>");
            $('#sensor').append("<option id='sensor_clock' value='clock'>Clock</option>");
            $('#sensor').append("<option id='sensor_power' value='power'>Watts</option>");
        }
        else{
            $('#sensor').append("<option id='sensor_temp' value='temperature'>Temperature</option>");
            $('#sensor').append("<option id='sensor_used' value='used'>Used Space</option>");

        }
    }

    function graph(hardware, sensor, comp){
        $.ajax({
            url: 'fetch_data.php',
            type: 'POST',
            dataType: 'json',
            data: {
                comp: comp,
                hardware: hardware,
                sensor: sensor,
            },
            success: function(data){
                const months = data.map(items => items.month);
                const values = data.map(items => items.value);

                plot_time_series(months, values, sensor);
            },
        });
    }

    function plot_time_series(months, values, sensor){
        sensor = sensor.toUpperCase();
        if (sensor == 'TEMPERATURE'){
            sensor += ' °C';
        }
        else if(sensor == 'CLOCK'){
            sensor += ' GHz';
        }
        else if(sensor == "POWER"){
            sensor += ' W';
        }
        else if(sensor == "USED"){
            sensor += '%';
        }

        var plotData = [{
            x: months,
            y: values,
            type: 'line',
        }];

        var layout = {
            autosize: true,
            responsive: true,
            
            title: 'TIME SERIES (' + sensor + ')',
            xaxis: {
                title: 'MONTH'
            },
            yaxis: {
                title: sensor
            }
        };

        Plotly.newPlot('graph', plotData, layout);
    }

    function min_max_avg(hardware, sensor, comp){
        $.ajax({
            url: './min_max_avg.php',
            type: 'POST',
            dataType: 'json',
            data: {
                comp: comp,
                hardware: hardware,
                sensor: sensor,
            },
            success: function(data){
                let min = data.map(items => items.min);
                let max = data.map(items => items.max);
                let avg = data.map(items => items.avg);

                if(sensor == "temperature"){
                    min[0] += "°C";
                    max[0] += "°C";
                    avg[0] += "°C";
                }
                else if(sensor == "clock"){
                    min[0] += " GHz";
                    max[0] += " GHz";
                    avg[0] += " GHz";
                }
                else if(sensor == 'power'){
                    min[0] += " W";
                    max[0] += " W";
                    avg[0] += " W";
                }
                else if(sensor == 'used'){
                    min[0] += "%";
                    max[0] += "%";
                    avg[0] += "%";
                }

                $('#min').text(min[0]);
                $('#max').text(max[0]);
                $('#avg').text(avg[0]);
            },

        });
    }

    $('#hardware').on('change', function(){
        var hardware = $('#hardware').val();
        add_empty_sensor(hardware);
    });

    $('#hardware, #sensor').on("change", function(){
        var hardware = $('#hardware').val();
        var sensor = $('#sensor').val();
        var comp = $('#comp').val();

        if (sensor != 'temperature'){
            $('#forecast').hide(500);
        }
        else{
            $('#forecast').show(500);
        }
        
        select_hardware(hardware);
        graph(hardware, sensor, comp);
        min_max_avg(hardware, sensor, comp);
    });

    $('#forecast').click(function(){
        var hardware = $('#hardware').val();
        var comp = $('#comp').val();

        $.ajax({
            url: '/flaskapp/forecast',
            type: 'GET',
            dataType: 'json',
            data: {
                pc : comp,
                hd : hardware
            },
            beforeSend: function(){
                $('.loader').show();
            },
            success: function(data){
                if ('message' in data){
                    $('.loader').hide();
                    const msg = data.message;
                    $('#graph').html('<div id="message"> You need at least 3 months worth of data to perform forecasting.</div>');
                }else{
                    $('.loader').hide();
                    const actualDates = data.actual_data.actual_date;
                    const actualValues = data.actual_data.actual_value;

                    const predDates = data.predict_data.pred_date;
                    const predValue = data.predict_data.pred_value;

                    const futureDate = data.future_data.future_date;
                    const futureValue = data.future_data.future_value;

                    const confiInteUpper = data.confidence_interval.upper_bound;
                    const confiInteLower = data.confidence_interval.lower_bound;
                    
                    plot_forecast(actualDates, actualValues, predDates, predValue, futureDate, futureValue, confiInteUpper, confiInteLower);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('.loader').hide();  // Hide the loader
                console.error('Error fetching data:', textStatus, errorThrown);
                alert('An error occurred while fetching data. Please try again later.'); // Display an error message
            }
        });
    });

    function plot_forecast(actualDates, actualValues, predDates, predValue, futureDate, futureValue, confiInteUpper, confiInteLower){
        var actual = {
            x: actualDates,
            y: actualValues,
            type: 'line',
            mode: 'lines',
            name: 'Actual Data'
        };

        var predict = {
            x: predDates,
            y: predValue,
            type: 'line',
            mode: 'lines',
            name: 'Predicted Data'
        };

        var future = {
            x: futureDate,
            y: futureValue,
            type: 'line',
            mode: 'lines',
            name: 'Future Data'
        };

        var upperBound = {
            x: futureDate,
            y: confiInteUpper,
            type: 'scatter',
            mode: 'lines',
            name: 'Upper Bound',
            line: {color: 'transparent'},
            showlegend: false
        };

        var lowerBound = {
            x: futureDate,
            y: confiInteLower,
            type: 'scatter',
            mode: 'lines',
            name: 'Lower Bound',
            line: {color: 'transparent'},
            fill: 'tonexty',
            fillcolor: 'rgba(128, 128, 128, 0.2)',
            line: {color: 'transparent'},
        };

        var plotData = [actual, predict, future, upperBound, lowerBound];

        var layout = {
            autosize: true,
            responsive: true,
            
            title: 'CNN TIME SERIES FORECAST (TEMPERATURE)',
            xaxis: { title: 'MONTH' },
            yaxis: { title: 'TEMPERATURE' }
        }

        Plotly.newPlot('graph', plotData, layout);
    }

    $('.loader').hide();

    var hardware = $('#hardware').val();
    
    select_hardware(hardware);

    var sensor = $('#sensor').val();
    var comp = $('#comp').val();
    
    graph(hardware, sensor, comp);
    min_max_avg(hardware, sensor, comp);

});


