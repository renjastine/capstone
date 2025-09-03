import numpy as np
import pandas as pd
from sklearn.metrics import mean_squared_error
from sklearn.preprocessing import MinMaxScaler
from tensorflow.keras.models import Sequential # type: ignore
from tensorflow.keras.layers import Dense, SimpleRNN, LSTM, Conv1D, MaxPooling1D, Flatten, Input # type: ignore
from sklearn.model_selection import ParameterGrid
import tensorflow as tf
import os
import matplotlib.pyplot as plt
import random

def reset_random_seeds():
    os.environ['PYTHONHASHSEED'] = str(2)
    tf.random.set_seed(2)
    np.random.seed(2)
    random.seed(2)

# Reset seeds for reproducibility
reset_random_seeds()

dataset = pd.read_csv('D:\My Documents\Downloads\python for data science\MIDTERM DATASET\Coffee Shop.csv')
dataset['date'] = pd.to_datetime(dataset['date'], format='%d/%m/%Y')
date = dataset['date']
dataset.set_index(date, inplace=True)
money = dataset['money']

scaler = MinMaxScaler()
dataset['money'] = scaler.fit_transform(dataset[['money']])

train_size = int(0.8 * len(dataset))
train_data, test_data = dataset['money'][:train_size], dataset['money'][train_size:]

def create_sequences(data, seq_length):
    sequences, target = [], []
    for i in range(len(data) - seq_length):
        sequences.append(data[i:i+seq_length])
        target.append(data[i+seq_length])
    return np.array(sequences), np.array(target)

seq_length = 30
X_train, y_train = create_sequences(train_data.values, seq_length)
X_test, y_test = create_sequences(test_data.values, seq_length)

# CNN
X_train = X_train.reshape((X_train.shape[0], X_train.shape[1], 1))
X_test = X_test.reshape((X_test.shape[0], X_test.shape[1], 1))

X_train_rnn = X_train.reshape(X_train.shape[0], X_train.shape[1], 1)
X_test_rnn = X_test.reshape(X_test.shape[0], X_test.shape[1], 1)

from tensorflow.keras.layers import Input # type: ignore

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


