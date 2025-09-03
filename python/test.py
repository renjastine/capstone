import json
from flask import Flask, redirect, render_template, url_for, request, jsonify
from hdd_monitor import HDDMonitor
from cpu_monitor import CpuMonitor
from face_recog import get_result
from forecasting import Forecast
from connection import connect
from dt import datetimenow
import matplotlib.pyplot as plt
import pandas as pd
import numpy as np
import threading
import time
import clr
import os

app = Flask(__name__)

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

    datasetSize = len(dataset)
    print(datasetSize)

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
        return "You need at least 3 months worth of data to perform forecasting."

    model = forecast.build_cnn(seq_length)
    model.fit(X_train, y_train, epochs=100, batch_size=16, validation_data=(X_test, y_test), verbose=2)
    y_pred = model.predict(X_test)

    rmse_test, rmse_test_original_scale = forecast.rmse_test(y_pred, y_test, scaler)

    residuals = y_test - y_pred.flatten()
    std_error = np.std(residuals)
    z_score = 1.96  # For an 95% confidence interval
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
    # return 
    
def convert_to_float(data):
    new_data = []
    for d in data:
        new_float = float(d)
        new_data.append(new_float)

    return new_data

if __name__ == "__main__":
    app.run(debug=True)