import warnings
warnings.filterwarnings("ignore")
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import statsmodels.api as sm # thu vien arima
from sklearn.model_selection import train_test_split
from statsmodels.tsa.arima_model import ARIMA
from sklearn.metrics import mean_squared_error, mean_absolute_error
import math
import os


data = pd.read_csv('C:/xampp/htdocs/DSS/storage/app/CPI.csv')

#chọn cột date và sales để dự đoán
data=data[['date_time','cpi']]

#đặt Date là index
data.set_index('date_time', inplace=True)

#chia dữ liệu
tra, tes = data[0:int(len(data)*0.75)], data[int(len(data)*0.75):]

#kiểm tra chuỗi dừng:
res=sm.tsa.adfuller(data['cpi'].dropna(),regression='ct')
chuoidung = ("{:.5f}".format(res[1]))
f = open('py/Results/chuoi_dung.text', 'w')
f.write(chuoidung)


train_arima = tra['cpi']
test_arima = tes['cpi']

history = [x for x in train_arima]
y = test_arima
# make first prediction
predictions = list()
model = ARIMA(history, order=(4,0,6))
model_fit = model.fit(disp=0)
yhat = model_fit.forecast()[0]
predictions.append(yhat)
history.append(y[0])
# rolling forecasts
for i in range(1, len(y)):
    # predict
    model = ARIMA(history, order=(4,0,6))
    model_fit = model.fit(disp=0)
    yhat = model_fit.forecast()[0]
    # invert transformed prediction
    predictions.append(yhat)
    # observation
    obs = y[i]
    history.append(obs)

i = 0
info = "0 \n"
f = open('py/Results/CPI_predict.csv', 'w')
f.write(info)
for value in predictions:
    i = i +1
    info = ("%.3f" '\n' %value )
    f = open('py/Results/CPI_predict.csv', 'a')
    f.write(str(info))

#Sai số bình phương trung bình MSE (Mean Squared Error)
mse = mean_squared_error(y, predictions)
f = open('py/Results/saiso.text', 'w')
f.write('MSE: ')
f.write(str("%.5f" %mse))
f.write('\n')
#Sai số tuyệt đối trung bình MAE (Mean Absolute Error)
mae = mean_absolute_error(y, predictions)
f = open('py/Results/saiso.text', 'a')
f.write('MAE: ')
f.write(str("%.5f" %mae))
f.write('\n')
#Căn của sai số bình phương trung bình RMSE (Root Mean Squared Error)
rmse = math.sqrt(mean_squared_error(y, predictions))
f = open('py/Results/saiso.text', 'a')
f.write('RMSE: ')
f.write(str("%.5f" %rmse))

#dự đoán cho 6 tháng tiếp theo
predict_six_month = list()
for i in range(0, 6):
    # predict
    model = ARIMA(history, order=(4,0,6))
    model_fit = model.fit(disp=0)
    yhat = model_fit.forecast()[0]
    # invert transformed prediction
    predict_six_month.append(yhat)
    # observation
    history.append(yhat)

for value in predict_six_month:
    i = i +1
    info = ("%.3f" '\n' %value )
    f = open('py/Results/CPI_predict.csv', 'a')
    f.write(str(info))

f = open('py/Results/predict_six_month.text', 'w')
for value in predict_six_month:
    i = i + 1
    info = ("%.3f" '\n' %value )
    f = open('py/Results/predict_six_month.text', 'a')
    f.write(str(info))



# arima = sm.tsa.statespace.SARIMAX(tra,order=(4,0,6),enforce_stationarity=False, enforce_invertibility=False,).fit()
# f = open('py/Results/arima.text', 'w')
# f.write(str(arima.summary()))

