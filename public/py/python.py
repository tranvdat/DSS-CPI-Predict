#!/usr/bin/env python
# coding: utf-8

# In[26]:


import itertools
import warnings
import os
import matplotlib.pyplot as plt
import numpy as np
import pandas as pd
import statsmodels.api as sm
from statsmodels.tsa.seasonal import seasonal_decompose
from statsmodels.tsa.stattools import adfuller
import matplotlib.pyplot as plt
import pandas as pd


# In[2]:


def test_stationarity(name, timeseries):
    # Determing rolling statistics
    rolmean = timeseries.rolling(window=12).mean()
    rolstd = timeseries.rolling(window=12).std()

    # Plot rolling statistics:
    # fig = plt.figure()
    # orig = plt.plot(timeseries, color='blue', label='Original')
    # mean = plt.plot(rolmean, color='red', label='Rolling Mean')
    # std = plt.plot(rolstd, color='black', label='Rolling Std')
    # plt.legend(loc='best')
    # plt.title('Rolling Mean & Standard Deviation')
    # plt.show()
    # Perform Dickey-Fuller test:
    print('\nResults of Dickey-Fuller Test:')
    dftest = adfuller(timeseries, autolag='AIC')
    dfoutput = pd.Series(dftest[0:4], index=['Test Statistic', 'p-value', '#Lags Used', 'Number of Observations Used'])
    for key, value in dftest[4].items():
        dfoutput['Critical Value (%s)' % key] = value
    # print(dfoutput)


# In[4]:


def forecast_accuracy(forecast, actual):
    mape = 100*np.mean(np.abs(forecast - actual)/np.abs(actual)) # MAPE
    me = np.mean(forecast - actual)             # ME
    mae = np.mean(np.abs(forecast - actual))    # MAE
    mpe = np.mean((forecast - actual)/actual)   # MPE
    rmse = np.mean((forecast - actual)**2)**.5  # RMSE
    corr = np.corrcoef(forecast, actual)[0,1]   # corr
    mins = np.amin(np.hstack([forecast[:,None],
                              actual[:,None]]), axis=1)
    maxs = np.amax(np.hstack([forecast[:,None],
                              actual[:,None]]), axis=1)
    minmax = 1 - np.mean(mins/maxs)             # minmax
    # acf1 = acf(fc-test)[1]                      # ACF1
    return(mape)  # , 'me':me, 'mae': mae,
            # 'mpe': mpe, 'rmse':rmse,# 'acf1':acf1,
            # 'corr':corr, 'minmax':minmax})


# In[5]:

# ------------------------------------------
# Read data file
df = pd.read_csv('C:/xampp/htdocs/DSS-CPI-Predict/storage/app/CPI.csv')
# df = df[:-1]
df.columns = ["date_time", "cpi"]
df['date_time'] = pd.to_datetime(df['date_time'], format='%Y-%m')
df.set_index(['date_time'], inplace=True)
df.index = pd.DatetimeIndex(df.index.values, freq=df.index.inferred_freq)
df['cpi'] = df['cpi'].astype('float32')
# df['cpi'].plot(title='Chỉ số giá tiêu dùng Việt Nam')


# In[6]:

script_dir = os.path.dirname(__file__)
results_dir = os.path.join(script_dir, 'Results/')
sample_file_name = "Seassion"

if not os.path.isdir(results_dir):
    os.makedirs(results_dir)

# Decomposition
decomposition = seasonal_decompose(df['cpi'], freq=6)
decomposition.plot()
plt.savefig(results_dir + sample_file_name)


# In[9]:


# Stationarity test
test_stationarity("", df['cpi'])
df['first_difference'] = df['cpi'].diff(1)

test_stationarity("_dff(1)", df['first_difference'].dropna(inplace=False))
df['seasonal_difference'] = df['cpi'].diff(12)

test_stationarity("_dff(12)", df['seasonal_difference'].dropna(inplace=False))
df['seasonal_first_difference'] = df['first_difference'].diff(12)

test_stationarity("_dff(1)+_diff(12)", df['seasonal_first_difference'].dropna(inplace=False))


# # In[10]:



# # Caculate ACF and PACF
# fig = plt.figure()
# ax1 = fig.add_subplot(211)
# fig = sm.graphics.tsa.plot_acf(df['seasonal_first_difference'].iloc[13:], lags=40, ax=ax1)
# ax2 = fig.add_subplot(212)
# fig = sm.graphics.tsa.plot_pacf(df['seasonal_first_difference'].iloc[13:], lags=40, ax=ax2)
# plt.show()


# # In[11]:


# # Preferences
# p = q = range(0, 2)
# d = range(0, 1)
# q = range(0, 4)
# pdq = list(itertools.product(p, d, q))
# #
# best_score, best_cfg = float("inf"), None
# best_scfg = float("inf"), None
# #
# seasonal_pdq = [(x[0], x[1], x[2], 12) for x in list(itertools.product(p, d, q))]
# warnings.filterwarnings("ignore")
# fw = open(module_path + "/param.txt", "w")

# for param in pdq:
#     for param_seasonal in seasonal_pdq:
#         try:
#             mod = sm.tsa.statespace.SARIMAX(df['cpi'],
#                                             order=param,
#                                             seasonal_order=param_seasonal,
#                                             enforce_stationarity=True,
#                                             enforce_invertibility=False)

#             results = mod.fit()
#             print('SARIMAX{}x{}12 - AIC:{}\n'.format(param, param_seasonal, results.aic))
#             #
#             if results.aic < best_score:
#                 best_score, best_cfg, best_scfg = results.aic, param, param_seasonal

#             #
#         except:
#             continue
#         #
# print('Best SARIMAX{}x{}12 - AIC:{}\n'.format(best_cfg,best_scfg, best_score))


# In[13]:


# ------------------------------------------
# Modeling
warnings.filterwarnings("ignore")
model = sm.tsa.statespace.SARIMAX(df['cpi'], trend='n', order=(1, 0, 2), seasonal_order=(1, 0, 1, 12))
results = model.fit()
print(results.summary())


# In[14]:


# ------------------------------------------
# Model diagnostics
# results.plot_diagnostics(figsize=(8, 7))
# plt.show()


# In[25]:

start,end  = int(len(df)*0.75), int(len(df))
# Prediction
df['forecast'] = results.predict(start=start, end=end, dynamic=False)
# print(df[['cpi', 'forecast']][start:end])

i = 0
info = "0 \n"
f = open('py/Results/CPI_predict.csv', 'w')
f.write(info)
for i in range(len(df['forecast'])):
    info = ("%.3f" '\n' %df['forecast'][i] )
    f = open('py/Results/CPI_predict.csv', 'a')
    f.write(str(info))
    f.close()

# ------------------------------------------
# Caculate error

mape = forecast_accuracy(df['forecast'][start:], df['cpi'][start:])
f = open('py/Results/saiso.text', 'w')
f.write('MAPE: ')
f.write(str("%.5f" %mape))
f.close()

# In[19]:


# Get forecast 12 steps ahead in future

plt.figure()
y = df['cpi']
pred_uc = results.get_forecast(steps=12)
print(pred_uc.predicted_mean)

f = open('py/Results/predict_of_year.text', 'w')

for i in range(len(pred_uc.predicted_mean)):
    info = ("%.3f" '\n' %pred_uc.predicted_mean[i] )
    f = open('py/Results/predict_of_year.text', 'a')
    f.write(str(info))
    print(str(pred_uc.predicted_mean[i]))


# Get confidence intervals of forecasts
# pred_ci = pred_uc.conf_int()
# ax = y.plot(label='Observed', figsize=(20, 15))
# pred_uc.predicted_mean.plot(ax=ax, label='Forecast')
# # ax.fill_between(pred_ci.index,
# #                 pred_ci.iloc[:, 0],
# #                 pred_ci.iloc[:, 1], color='k', alpha=.15)
# ax.set_xlabel('Date')
# ax.set_ylabel('CPI')
# plt.legend(loc='upper left')
# plt.show()


# In[ ]:




