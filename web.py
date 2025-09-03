from flask import Flask, redirect, render_template, url_for, request, jsonify
from python.hdd_monitor import HDDMonitor
from python.cpu_monitor import CpuMonitor
from python.face_recog import get_result
from python.forecasting import Forecast
from python.connection import connect
from python.dt import datetimenow
import matplotlib.pyplot as plt
import pandas as pd
import numpy as np
import threading
import json
import time
import clr
import os

app = Flask(__name__)

open_hardware_monitor_path = 'lib\\OpenHardwareMonitorLib'
clr.AddReference(open_hardware_monitor_path)

from OpenHardwareMonitor.Hardware import Computer # type: ignore

computer = Computer()
computer.CPUEnabled = True
computer.HDDEnabled = True

computer.Open()

def get_hw_status():
    cpu_temp = []
    cpu_clock = []
    cpu_power = []
    hdd_temp = []
    hdd_used = []

    hdd = []

    for hardware in computer.Hardware:
        hardware.Update()  # Refresh the sensor data

        for sensor in hardware.Sensors:
            if sensor.Name == "CPU Package" and sensor.SensorType.ToString() == "Temperature":
                cpu_temp.append(sensor.Value)
                # print(f"{sensor.Name} | Value: {round(sensor.Value, 2)} °C")

            if sensor.Name == "CPU Core #1" and sensor.SensorType.ToString() == "Clock":
                cpu_clock.append(sensor.Value)
                # print(f"{sensor.Name} | Value: {round(sensor.Value/1000.0, 2)} GHz") 

            if sensor.Name == "CPU Package" and sensor.SensorType.ToString() == "Power":
                cpu_power.append(sensor.Value)
                # print(f"{sensor.Name} | Value: {round(sensor.Value, 2)} W")

            if sensor.Name == "Temperature":
                hdd_temp.append(sensor.Value)
                # print(f"HDD Temperature | Value: {round(sensor.Value, 2)} °C")
        
            if sensor.Name == "Used Space":
                hdd.append(sensor.Value)
                # print(f"HDD Used Space | Value: {round(sensor.Value, 0)}%")

    hdd_used.append(hdd[1])

    return cpu_temp, cpu_clock, cpu_power, hdd_temp, hdd_used

def insert_status(comp):
    while True:
        cpu_temp, cpu_clock, cpu_power, hdd_temp, hdd_used = get_hw_status()
        now = datetimenow()
        con = connect()

        cpu_status = CpuMonitor(con, now, cpu_temp, cpu_clock, cpu_power, comp)
        hdd_status = HDDMonitor(con, now, hdd_temp, hdd_used, comp)

        cpu_status.insert_cpu_temp()
        cpu_status.insert_cpu_clock()
        cpu_status.insert_cpu_power()
        hdd_status.insert_hdd_temp()
        hdd_status.insert_hdd_used()
 
        time.sleep(60)

@app.route('/')
def home():    
    return redirect(url_for("start"))

@app.route('/../capstone/templates/')
def start():
    return render_template('index.php')

@app.route('/fr-result')
def fr_result():
    # Get the absolute path to the known directory
    knownPath = os.path.join(app.root_path, 'templates', 'student_panel', 'img', 'known')
    unknownPath = os.path.join(app.root_path, 'templates', 'student_panel', 'img', 'unknown')

    result = get_result(knownPath, unknownPath)
    
    return redirect(f"http://localhost/capstone/templates/attendance/face_recog/index.php?sn={result[0]}")

@app.route('/monitor')
def monitor():
    if request.method == 'GET':
        comp = request.args.get('pc')
        
        if comp == 'stop':
            insert_stats = threading.Thread(target=insert_status, args=(comp,))
            insert_stats.start()

            return redirect("http://localhost/capstone/templates/attendance")
            # return "monitoring is running"
        else:
            return redirect("http://localhost/capstone/templates/attendance/")
    else:
        return "Not get bleh"

@app.route('/forecast')
def forecast():
    con = connect()
    forecast = Forecast(con)

    if request.method == 'GET':
        comp = request.args.get('pc')
        hardware = request.args.get('hd')

    forecast.reset_random_seeds()
    dataset = forecast.get_dataset(hardware, comp)

    dataframe = forecast.create_dataframe(dataset)
    dataframe = forecast.create_missing_dates(dataframe)
    dataframe = forecast.patch_null_values(dataframe)

    dataframe.set_index('date', inplace=True)

    dataframe, scaler = forecast.normalize_data(dataframe)

    train_data, test_data = forecast.train_test_data(dataframe)

    seq_length = 30

    X_train, y_train = forecast.create_sequences(train_data.values, seq_length)
    X_test, y_test = forecast.create_sequences(test_data.values, seq_length)

    try: 
        X_train = X_train.reshape((X_train.shape[0], X_train.shape[1], 1))
        X_test = X_test.reshape((X_test.shape[0], X_test.shape[1], 1))
    except:
        # msg = 'You need at least 3 months worth of data to perform forecasting.'
        result = {
            'message': 'Insufficient Data'
        }
        return jsonify(result)

    model = forecast.build_cnn(seq_length)
    model.fit(X_train, y_train, epochs=100, batch_size=16, validation_data=(X_test, y_test), verbose=2)
    y_pred = model.predict(X_test)

    rmse_test, rmse_test_original_scale = forecast.rmse_test(y_pred, y_test, scaler)

    residuals = y_test - y_pred.flatten()
    std_error = np.std(residuals)
    z_score = 1.96  # For a 95% confidence interval
    margin_error = z_score * std_error

    future_steps, extended_predictions = forecast.prediction(model, seq_length, X_test)

    last_date = dataframe.index.max()
    future_dates = pd.date_range(last_date, periods=future_steps + 1, freq='D')[1:]

    upper_bound = np.array(extended_predictions) + margin_error
    lower_bound = np.array(extended_predictions) - margin_error

    extended_predictions_original_scale = scaler.inverse_transform(np.array(extended_predictions).reshape(-1, 1)).flatten()
    upper_bound_original_scale = scaler.inverse_transform(np.array(upper_bound).reshape(-1, 1)).flatten()
    lower_bound_original_scale = scaler.inverse_transform(np.array(lower_bound).reshape(-1, 1)).flatten()

    actual_dates = test_data.reset_index()
    actual_dates = actual_dates['date'][seq_length:]
    actual_dates = actual_dates.dt.strftime('%Y-%m-%d')
    actual_dates = tuple(actual_dates)

    new_y_test = tuple(scaler.inverse_transform(y_test.reshape(-1, 1)).flatten())
    new_y_pred = tuple(scaler.inverse_transform(y_pred.reshape(-1, 1)).flatten())

    new_y_pred = convert_to_float(new_y_pred)

    future_dates = future_dates.strftime('%Y-%m-%d')
    future_dates = tuple(future_dates)

    extended_predictions_original_scale = tuple(extended_predictions_original_scale)

    new_extended_pred = convert_to_float(extended_predictions_original_scale)

    upper_bound_original_scale = tuple(upper_bound_original_scale)
    new_upper_bound = convert_to_float(upper_bound_original_scale)
    lower_bound_original_scale = tuple(lower_bound_original_scale)
    new_lower_bound = convert_to_float(lower_bound_original_scale)

    result = {
            'actual_data': {
                'actual_date' : actual_dates,
                'actual_value' : new_y_test
                },

            'predict_data' : {
                'pred_date' : actual_dates,
                'pred_value': new_y_pred
            },

            'future_data' : {
                'future_date': future_dates,
                'future_value' : new_extended_pred 
            },

            'confidence_interval' : {
                'upper_bound' : new_upper_bound,
                'lower_bound' : new_lower_bound
            }
        }

    return jsonify(result)
    # return f'{comp}, {hardware}'

def convert_to_float(data):
    new_data = []
    for d in data:
        new_float = float(d)
        new_data.append(new_float)

    return new_data
    
if __name__ == "__main__":
    app.run(debug=True)