import numpy as np
import pandas as pd
from sklearn.metrics import mean_squared_error
from sklearn.preprocessing import MinMaxScaler
from tensorflow.keras.models import Sequential # type: ignore
from tensorflow.keras.layers import Dense, SimpleRNN, LSTM, Conv1D, MaxPooling1D, Flatten, Input # type: ignore
from sklearn.model_selection import ParameterGrid
import tensorflow as tf
import os
import random
from connection import connect

# Function to reset random seeds
def reset_random_seeds():
    os.environ['PYTHONHASHSEED'] = str(2)
    tf.random.set_seed(2)
    np.random.seed(2)
    random.seed(2)

# Reset seeds for reproducibility
reset_random_seeds()

def get_dataset(con, hardware, comp):

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

def create_dataframe(data):
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
    
def create_missing_dates(dataframe):
    dataframe['date'] = pd.to_datetime(dataframe['date'])

    dataframe.set_index('date', inplace=True)

    full_date = pd.date_range(start=dataframe.index.min(), end=dataframe.index.max(), freq='D')

    new_dates_df = dataframe.reindex(full_date)

    new_dates_df = new_dates_df.rename_axis('date').reset_index()

    return new_dates_df

def normalize_data(dataframe):
        scaler = MinMaxScaler()
        dataframe['temperature'] = scaler.fit_transform(dataframe[['temperature']])

        return dataframe, scaler
    
def train_test_data(dataframe):
    train_size = int(0.6 * len(dataframe))
    train_data, test_data = dataframe['temperature'][:train_size], dataframe['temperature'][train_size:]

    return train_data, test_data

def create_sequences(data, seq_length):
    sequences, target = [], []
    for i in range(len(data) - seq_length):
        sequences.append(data[i:i+seq_length])
        target.append(data[i+seq_length])
    return np.array(sequences), np.array(target)

def patch_null_values(dataframe):
    dataframe = dataframe.ffill().bfill()

    return dataframe

con = connect()
dataset = get_dataset(con, 'cpu', 'pc1')
df = create_dataframe(dataset)
df = create_missing_dates(df)
df = patch_null_values(df)

df.set_index('date', inplace=True)

dataframe, scaler = normalize_data(df)

train_data, test_data = train_test_data(df)

# Step 8: Create sequences for forecasting
# Define the sequence length for creating input sequences
seq_length = 30

# Generate sequences for training and testing
X_train, y_train = create_sequences(train_data.values, seq_length)
X_test, y_test = create_sequences(test_data.values, seq_length)

# Step 9: Reshape the data for CNN input
# CNN expects data in the shape (samples, time steps, features)
X_train = X_train.reshape((X_train.shape[0], X_train.shape[1], 1))
X_test = X_test.reshape((X_test.shape[0], X_test.shape[1], 1))

# Reshape for RNN, LSTM, CNN (not needed for DNN)
X_train_rnn = X_train.reshape(X_train.shape[0], X_train.shape[1], 1)
X_test_rnn = X_test.reshape(X_test.shape[0], X_test.shape[1], 1)

from tensorflow.keras.layers import Input

# Define model building functions
def build_dnn(units1, units2):
    model = Sequential()
    model.add(Input(shape=(seq_length,)))
    model.add(Dense(units1, activation='relu'))
    model.add(Dense(units2, activation='relu'))
    model.add(Dense(1))  # Output layer for regression
    model.compile(optimizer='adam', loss='mse')
    return model

def build_rnn(units):
    model = Sequential()
    model.add(Input(shape=(seq_length, 1)))
    model.add(SimpleRNN(units, activation='relu'))
    model.add(Dense(1))  # Output layer for regression
    model.compile(optimizer='adam', loss='mse')
    return model

def build_lstm(units):
    model = Sequential()
    model.add(Input(shape=(seq_length, 1)))
    model.add(LSTM(units, activation='relu'))
    model.add(Dense(1))  # Output layer for regression
    model.compile(optimizer='adam', loss='mse')
    return model

def build_cnn(filters, kernel_size):
    model = Sequential()
    model.add(Input(shape=(seq_length, 1)))
    model.add(Conv1D(filters=filters, kernel_size=kernel_size, activation='relu'))
    model.add(MaxPooling1D(pool_size=2))
    model.add(Flatten())
    model.add(Dense(50, activation='relu'))
    model.add(Dense(1))  # Output layer for regression
    model.compile(optimizer='adam', loss='mse')
    return model

# Define hyperparameter grid
param_grid = {
    'DNN': {
        'units1': [64, 128],
        'units2': [32, 64],
        'epochs': [100, 300],
        'batch_size': [16, 32]
    },
    'RNN': {
        'units': [30, 50],
        'epochs': [100, 300],
        'batch_size': [16, 32]
    },
    'LSTM': {
        'units': [30, 50],
        'epochs': [100, 300],
        'batch_size': [16, 32]
    },
    'CNN': {
        'filters': [32, 64],
        'kernel_size': [3, 5],
        'epochs': [100, 300],
        'batch_size': [16, 32]
    }
}

# Store RMSE scores
rmse_scores = {}

# Iterate through hyperparameter combinations
for name, params in param_grid.items():
    grid = ParameterGrid(params)
    for param_comb in grid:
        print(f"Testing {name} with params: {param_comb}")
        if name == 'DNN':
            model = build_dnn(param_comb['units1'], param_comb['units2'])
        elif name == 'RNN':
            model = build_rnn(param_comb['units'])
        elif name == 'LSTM':
            model = build_lstm(param_comb['units'])
        elif name == 'CNN':
            model = build_cnn(param_comb['filters'], param_comb['kernel_size'])
        
        model.fit(X_train if name == 'DNN' else X_train_rnn, y_train,
                  epochs=param_comb['epochs'], batch_size=param_comb['batch_size'],
                  validation_data=(X_test if name == 'DNN' else X_test_rnn, y_test), verbose=2)
        y_pred = model.predict(X_test if name == 'DNN' else X_test_rnn)
        
        rmse_test = np.sqrt(mean_squared_error(y_test, y_pred.flatten()))
        rmse_scores[f"{name}_params_{param_comb}"] = rmse_test
        
        print(f"{name} with params {param_comb} - RMSE: {rmse_test}")

# Print all RMSE scores
print("All RMSE Scores:")
for model_name, rmse_score in rmse_scores.items():
    print(f"{model_name}: {rmse_score}")

# Initialize dictionaries to store the best RMSE for each model type
best_rmse = {'DNN': float('inf'), 'RNN': float('inf'), 'LSTM': float('inf'), 'CNN': float('inf')}
best_params = {'DNN': None, 'RNN': None, 'LSTM': None, 'CNN': None}

# Extract the best RMSE and corresponding parameters for each model type
for model_name, rmse_score in rmse_scores.items():
    # Determine model type from the model name
    if model_name.startswith('DNN'):
        model_type = 'DNN'
    elif model_name.startswith('RNN'):
        model_type = 'RNN'
    elif model_name.startswith('LSTM'):
        model_type = 'LSTM'
    elif model_name.startswith('CNN'):
        model_type = 'CNN'
    else:
        continue  # Skip if model type is unknown
    
    # Update best RMSE and parameters if the current score is better
    if rmse_score < best_rmse[model_type]:
        best_rmse[model_type] = rmse_score
        best_params[model_type] = model_name

# Print the best model for each type
print("Best Model for Each Architecture:")
for model_type, params_name in best_params.items():
    print(f"{model_type}: {params_name} - RMSE: {best_rmse[model_type]}")

# Identify the overall best model
overall_best_model = min(best_rmse, key=best_rmse.get)
print(f"\nOverall Best Model: {best_params[overall_best_model]} - RMSE: {best_rmse[overall_best_model]}")



