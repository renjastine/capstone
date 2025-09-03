from tensorflow.keras.layers import Conv1D, MaxPooling1D, Flatten, Dense, Input # type: ignore
from tensorflow.keras.models import Sequential # type: ignore
from sklearn.preprocessing import MinMaxScaler
from sklearn.metrics import mean_squared_error
import matplotlib.pyplot as plt
# from connection import connect
import tensorflow as tf
import pandas as pd
import numpy as np
import random
import json
import os

class Forecast:
    def __init__(self, con):
        self.con = con
    
    # Function to reset random seeds
    def reset_random_seeds(self):
        os.environ['PYTHONHASHSEED'] = str(2)
        tf.random.set_seed(2)
        np.random.seed(2)
        random.seed(2)

    def get_dataset(self, hardware, comp):
        con = self.con

        value = ''
        table = ''
        sensor = ''

        if hardware == 'cpu':
            value = 'cpu_value'
            table = 'cpu_status'
            sensor = 'cpu_sensor'
        else:
            value = 'hdd_value'
            table = 'hdd_status'
            sensor = 'hdd_sensor'

        myCursor = con.cursor()

        query = f"SELECT DATE_FORMAT(date_time, '%Y-%m-%d') as month, ROUND(AVG({value})) as value FROM `{table}` WHERE {sensor}='temperature' and comp_name='{comp}' GROUP BY month"

        myCursor.execute(query)

        dataset = myCursor.fetchall()

        return dataset
    
    def create_dataframe(self, data):
        date = []
        values = []

        for d in data:
            date.append(d[0])
            values.append(d[1])

        dataframe = {
            'date': date,
            'temperature': values
        }

        df = pd.DataFrame(dataframe)

        return df
    
    def create_missing_dates(self, dataframe):
        dataframe['date'] = pd.to_datetime(dataframe['date'])

        dataframe.set_index('date', inplace=True)

        full_date = pd.date_range(start=dataframe.index.min(), end=dataframe.index.max(), freq='D')

        new_dates_df = dataframe.reindex(full_date)

        new_dates_df = new_dates_df.rename_axis('date').reset_index()

        return new_dates_df

    def patch_null_values(self, dataframe):
        dataframe = dataframe.ffill().bfill()

        return dataframe
    
    def normalize_data(self, dataframe):
        scaler = MinMaxScaler()
        dataframe['temperature'] = scaler.fit_transform(dataframe[['temperature']])

        return dataframe, scaler
    
    def train_test_data(self, dataframe):
        train_size = int(0.7 * len(dataframe))
        train_data, test_data = dataframe['temperature'][:train_size], dataframe['temperature'][train_size:]

        return train_data, test_data
    
    def create_sequences(self, data, seq_length):
        sequences, target = [], []
        for i in range(len(data) - seq_length):
            sequences.append(data[i:i+seq_length])
            target.append(data[i+seq_length])
        return np.array(sequences), np.array(target)
    
    def build_cnn(self, seq_length):
        model = Sequential()
        model.add(Input(shape=(seq_length, 1)))
        model.add(Conv1D(filters=64, kernel_size=3, activation='relu'))
        model.add(MaxPooling1D(pool_size=2))
        model.add(Flatten())
        model.add(Dense(50, activation='relu'))
        model.add(Dense(1))  # Output layer for regression
        model.compile(optimizer='adam', loss='mse')
        return model
        
    def rmse_test(self, y_pred, y_test, scaler):
        rmse_test = np.sqrt(mean_squared_error(y_test, y_pred.flatten()))
        rmse_test_original_scale = np.sqrt(mean_squared_error(scaler.inverse_transform(y_test.reshape(-1, 1)), scaler.inverse_transform(y_pred.reshape(-1, 1))))

        return rmse_test, rmse_test_original_scale

    def prediction(self, model, seq_length, X_test):
        future_steps = 14
        future_input = X_test[-1]
        extended_predictions = []
        for i in range(future_steps):
            future_pred = model.predict(np.expand_dims(future_input, axis=0))  # Predict the next value
            extended_predictions.append(future_pred[0, 0])  # Append the prediction
            future_input = np.append(future_input[1:], future_pred)  # Update input sequence for next prediction
            future_input = future_input.reshape(seq_length, 1)  # Reshape for the next prediction
        
        return future_steps, extended_predictions

        

# con = connect()
# forecast = Forecast(con)
# forecast.reset_random_seeds()
# dataset = forecast.get_dataset('cpu', 'pc1')

# # # dataset with missing values and dates
# # # dataset = [
# # #     ('2024-07-01', 75.0),
# # #     ('2024-07-06', 69.0),
# # #     ('2024-07-09', 60.0),
# # #     ('2024-08-10', 80.0),
# # # ]

# dataframe = forecast.create_dataframe(dataset)
# dataframe = forecast.create_missing_dates(dataframe)
# dataframe = forecast.patch_null_values(dataframe)

# dataframe.set_index('date', inplace=True)

# dataframe, scaler = forecast.normalize_data(dataframe)

# train_data, test_data = forecast.train_test_data(dataframe)

# # Step 8: Create sequences for forecasting
# # Define the sequence length for creating input sequences
# seq_length = 30

# # Generate sequences for training and testing
# X_train, y_train = forecast.create_sequences(train_data.values, seq_length)
# X_test, y_test = forecast.create_sequences(test_data.values, seq_length)

# # Step 9: Reshape the data for CNN input
# # CNN expects data in the shape (samples, time steps, features)
# X_train = X_train.reshape((X_train.shape[0], X_train.shape[1], 1))
# X_test = X_test.reshape((X_test.shape[0], X_test.shape[1], 1))

# # Initialize and train the CNN model
# model = forecast.build_cnn(seq_length)
# model.fit(X_train, y_train, epochs=5, batch_size=16, validation_data=(X_test, y_test), verbose=2)
# y_pred = model.predict(X_test)

# rmse_test, rmse_test_original_scale = forecast.rmse_test(y_pred, y_test, scaler)

# # Step 12: Calculate residuals and confidence interval
# residuals = y_test - y_pred.flatten()
# std_error = np.std(residuals)
# z_score = 1.96  # For an 95% confidence interval
# margin_error = z_score * std_error

# # Step 13: Extend predictions for future steps
# future_steps, extended_predictions = forecast.prediction(model, seq_length, X_test)

# # Step 14: Create a date range for future predictions
# last_date = dataframe.index.max()
# future_dates = pd.date_range(last_date, periods=future_steps + 1, freq='D')[1:]

# # # Calculate upper and lower bounds
# upper_bound = np.array(extended_predictions) + margin_error
# lower_bound = np.array(extended_predictions) - margin_error

# # Inverse transform predictions and bounds
# extended_predictions_original_scale = scaler.inverse_transform(np.array(extended_predictions).reshape(-1, 1)).flatten()
# upper_bound_original_scale = scaler.inverse_transform(np.array(upper_bound).reshape(-1, 1)).flatten()
# lower_bound_original_scale = scaler.inverse_transform(np.array(lower_bound).reshape(-1, 1)).flatten()

# actual_dates = test_data.reset_index()
# actual_dates = actual_dates['date'][seq_length:]
# actual_dates = actual_dates.dt.strftime('%Y-%m-%d')
# actual_dates = tuple(actual_dates)

# new_y_test = tuple(scaler.inverse_transform(y_test.reshape(-1, 1)).flatten())
# new_y_pred = tuple(scaler.inverse_transform(y_pred.reshape(-1, 1)).flatten())

# def convert_to_float(data):
#     new_data = []
#     for d in data:
#         new_float = float(d)
#         new_data.append(new_float)

#     return new_data

# new_y_pred = convert_to_float(new_y_pred)

# future_dates = future_dates.strftime('%Y-%m-%d')
# future_dates = tuple(future_dates)

# extended_predictions_original_scale = tuple(extended_predictions_original_scale)

# new_extended_pred = convert_to_float(extended_predictions_original_scale)

# upper_bound_original_scale = tuple(upper_bound_original_scale)
# new_upper_bound = convert_to_float(upper_bound_original_scale)
# lower_bound_original_scale = tuple(lower_bound_original_scale)
# new_lower_bound = convert_to_float(lower_bound_original_scale)

# result = {
#         'actual_data': {
#             'actual_date' : actual_dates,
#             'actual_value' : new_y_test
#             },

#         'predict_data' : {
#             'pred_date' : actual_dates,
#             'pred_value': new_y_pred
#         },

#         'future_data' : {
#             'future_date': future_dates,
#             'future_value' : new_extended_pred 
#         },

#         'confidence_interval' : {
#             'upper_bound' : new_upper_bound,
#             'lower_bound' : new_lower_bound
#         }
#     }

# r = json.dumps(new_y_pred)
# print(r)  

# plt.figure(figsize=(12, 6))
# plt.plot(test_data.index[seq_length:], scaler.inverse_transform(y_test.reshape(-1, 1)), label="Actual Data")
# plt.plot(test_data.index[seq_length:], scaler.inverse_transform(y_pred), label="CNN Predicted")
# plt.plot(future_dates, extended_predictions_original_scale, label='Future Forecast')
# plt.fill_between(future_dates, lower_bound_original_scale, upper_bound_original_scale, color='gray', alpha=0.2, label='95% Confidence Interval')
# plt.plot()
# plt.show()


    







