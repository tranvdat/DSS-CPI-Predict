#!/usr/bin/env python
# coding: utf-8

# In[1]:


from gensim.models import word2vec
from collections import Counter
from nltk.util import ngrams
from nltk.sentiment.util import *
from nltk.sentiment.vader import SentimentIntensityAnalyzer
import statsmodels.api as sm
from nltk.stem import PorterStemmer
import re
from nltk.corpus import stopwords
from nltk.tokenize import RegexpTokenizer
import string
from wordcloud import WordCloud, STOPWORDS
from subprocess import check_output
from PIL import Image
from os import path
import os
import random
import nltk
import warnings
import plotly.graph_objs as go
import pandas as pd
import numpy as np
import datetime as dt
import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.model_selection import train_test_split

from sklearn.naive_bayes import MultinomialNB
from sklearn.metrics import roc_curve, auc

from sklearn.metrics import confusion_matrix
import sklearn.metrics as mt
from plotly import tools
import plotly.offline as py
py.init_notebook_mode(connected=True)
#get_ipython().run_line_magic('matplotlib', 'inline')
warnings.filterwarnings('ignore')


# hình dung


# Đặt chủ đề lô
sns.set_palette([
    "#30a2da",
    "#fc4f30",
    "#e5ae38",
    "#6d904f",
    "#8b8b8b",
])

# chuẩn bị


# Warnings
warnings.filterwarnings('ignore')
df1 = pd.read_csv(
    'C:/xampp/htdocs/DSS/storage/app/file/Clothing.csv')
df = df1[['Review Text', 'Rating', 'Class Name', 'Age']]
# df1.info()
# df1.describe()
df1.head()

# In[2]:


# lấp đầy giá trị NaN
df['Review Text'] = df['Review Text'].fillna('')

# CountVectorizer () chuyển đổi một tập hợp các tài liệu văn bản thành một ma trận số lượng mã thông báo
vectorizer = CountVectorizer()
# gán tên ngắn hơn cho phân tích mã thông báo chuỗi
analyzer = vectorizer.build_analyzer()

words = ' '.join(df['Review Text'])
cleaned_word = " ".join([word for word in words.split()
                         if 'this' not in word
                         and 'is' not in word
                         and 'the' not in word
                         and 'to' not in word
                         and 'i' not in word
                         and 'are' not in word
                         and 'this' not in word
                         and 'that' not in word
                         and 'had' not in word
                         and 'ha' not in word
                         and 'wa' not in word
                         and 'was' not in word
                         and 'ware' not in word
                         and 'and' not in word
                         and 'for' not in word
                         and 'now' not in word
                         and 'of' not in word
                         and 'or' not in word
                         and 'make' not in word
                         and 'true' not in word
                         and 'up' not in word
                         and 'go' not in word
                         and 'got' not in word
                         and 'as' not in word
                         and 'sem' not in word
                         and 'the' not in word
                         and 'two' not in word
                         and 'an' not in word
                         and 'about' not in word
                         and 'at' not in word
                         and 'but' not in word
                         and 'try' not in word
                         and 'on' not in word
                         and 'always' not in word
                         and 'usually' not in word
                         and 'sometimes' not in word
                         and 'never' not in word
                         and 'rarely' not in word
                         and 'cut' not in word
                         and 'much' not in word
                         and 'so' not in word
                         and 'me' not in word
                         and 'say' not in word
                         and 'even' not in word
                         and 'day' not in word
                         and 'almost' not in word
                         and 'keep' not in word
                         and 'boxy' not in word
                         and 'xs' not in word
                         and 'put' not in word
                         and 'tee' not in word
                         and 'left' not in word
                         and 'though' not in word
                         and 'doe' not in word
                         and 'yet' not in word
                         and 'lot' not in word
                         and 'seem' not in word
                         and 'found' not in word
                         and 'my' not in word
                         and 'buy' not in word
                         and 'tt' not in word
                         and 'through' not in word
                         and 'well' not in word
                         and 'add' not in word
                         and 'run' not in word
                         and 'agree' not in word
                         and 'above' not in word
                         and 'year' not in word
                         and 'be' not in word
                         and 'back' not in word
                         and 'not' not in word
                         and 'sure' not in word
                         and 'already' not in word
                         and 'see' not in word
                         and 'saw' not in word
                         and '4' not in word
                         and '5' not in word
                         and '125' not in word
                         and 'take' not in word
                         and 'return' not in word
                         and 'bought' not in word
                         and 'bust' not in word
                         and 'these' not in word
                         and 'The' not in word
                         and 'lb' not in word
                         and 'am' not in word
                         ])


def wordcounts(s):
    c = {}
    # mã hóa chuỗi và tiếp tục mã hóa nếu nó khong trống
    if analyzer(s):
        d = {}
        # tìm số lượng từ vựng và biến đổi thành mảng
        w = vectorizer.fit_transform([s]).toarray()
        # từ vựng và chỉ số
        vc = vectorizer.vocabulary_

        for k, v in vc.items():
            d[v] = k  # d -> index:word
        for index, i in enumerate(w[0]):
            c[d[index]] = i  # c -> word:count
    return c


# thêm cột mới và khung dữ liệu
df['Word Counts'] = df['Review Text'].apply(wordcounts)
df.head()


# In[4]:


# Phân phối ID quần áo để hiểu mức độ phổ biến của sản phẩm
f, axes = plt.subplots(1, 2, figsize=[14, 7])
num = 30
sns.countplot(y="Clothing ID", data=df1[df1["Clothing ID"].isin(df1["Clothing ID"].value_counts()[:num].index)],
              order=df1["Clothing ID"].value_counts()[:num].index, ax=axes[0])
axes[0].set_title("Frequency Count of Clothing ID\nTop 30")
axes[0].set_xlabel("Count")

sns.countplot(y="Clothing ID", data=df1[df1["Clothing ID"].isin(df1["Clothing ID"].value_counts()[num:60].index)],
              order=df1["Clothing ID"].value_counts()[num:60].index, ax=axes[1])
axes[1].set_title("Frequency Count of Clothing ID\nTop 30 to 60")
axes[1].set_ylabel("")
axes[1].set_xlabel("Count")

# thêm vào
script_dir = os.path.dirname(__file__)
results_dir = os.path.join(script_dir, 'Results/')
sample_file_name = "Frequency_ID"

if not os.path.isdir(results_dir):
    os.makedirs(results_dir)
plt.savefig(results_dir + sample_file_name)


print("Dataframe Dimension: {} Rows".format(df1.shape[0]))
df1[df1["Clothing ID"].isin([1078, 862, 1094])
    ].describe().T.drop("count", axis=1)
# nhận xét mô hình
# mô hình 1 : top 30  ID clothing có lượng đếm nhiều nhất (1078 : ~ 1000 lần)
# hình 2 : top 30-60 số ID được đếm ~ 200 lần


# In[4]:


# Class Name
script_dir = os.path.dirname(__file__)
results_dir = os.path.join(script_dir, 'Results/')
sample_file_name = "Class_Name"
plt.subplots(figsize=(9, 5))
sns.countplot(y="Class Name", data=df,
              order=df["Class Name"].value_counts().index)
plt.title("Frequency Count of Class Name")
plt.xlabel("Count")
plt.savefig(results_dir + sample_file_name)


# In[5]:


row_plots = ["Division Name", "Department Name"]
f, axes = plt.subplots(1, len(row_plots), figsize=(14, 4), sharex=False)

for i, x in enumerate(row_plots):
    sns.countplot(y=x, data=df1, order=df1[x].value_counts().index, ax=axes[i])
    axes[i].set_title("Count of Categories in {}".format(x))
    axes[i].set_xlabel("")
    axes[i].set_xlabel("Frequency Count")
axes[0].set_ylabel("Category")
axes[1].set_ylabel("")

# thêm vào
script_dir = os.path.dirname(__file__)
results_dir = os.path.join(script_dir, 'Results/')
sample_file_name = "Division_Name"
plt.savefig(results_dir + sample_file_name)
# nhận xét : hình 1 về các thể loại : bé, cỡ trung bình,


# In[6]:


# Thể hiện mật độ của tên lớp, một số từ được chọn và tất cả các từ trong đánh giá bằng cách sử dụng WordCloud
#  chọn một số từ để kiểm tra chi tiết
selectedwords = ['awesome', 'great', 'fantastic', 'extraordinary', 'amazing', 'super',
                 'magnificent', 'stunning', 'impressive', 'wonderful', 'breathtaking',
                 'love', 'content', 'pleased', 'happy', 'glad', 'satisfied', 'lucky',
                 'shocking', 'cheerful', 'wow', 'sad', 'unhappy', 'horrible', 'regret',
                 'bad', 'terrible', 'annoyed', 'disappointed', 'upset', 'awful', 'hate']


def selectedcount(dic, word):
    if word in dic:
        return dic[word]
    else:
        return 0


dfwc = df.copy()
for word in selectedwords:
    dfwc[word] = dfwc['Word Counts'].apply(selectedcount, args=(word,))

word_sum = dfwc[selectedwords].sum()
print('Selected Words')
print(word_sum.sort_values(ascending=False).iloc[:5])

print('\nClass Names')
print(df['Class Name'].fillna("Empty").value_counts().iloc[:5])

fig, ax = plt.subplots(1, 2, figsize=(20, 10))
wc0 = WordCloud(background_color='white',
                width=450,
                height=400).generate_from_frequencies(word_sum)

wc0.to_file('py/Results/Selected_Words.png')

cn = df['Class Name'].fillna(" ").value_counts()
wc1 = WordCloud(background_color='white',
                width=450,
                height=400
                ).generate_from_frequencies(cn)
wc1.to_file('py/Results/Class_Names.png')

ax[0].imshow(wc0)
ax[0].set_title('Selected Words\n', size=25)
ax[0].axis('off')

ax[1].imshow(wc1)
ax[1].set_title('Class Names\n', size=25)
ax[1].axis('off')

rt = df['Review Text']
plt.subplots(figsize=(18, 6))
wordcloud = WordCloud(stopwords=STOPWORDS, background_color='white',
                      width=900,
                      height=300
                      ).generate(cleaned_word)
plt.imshow(wordcloud)
plt.title('All Words in the Reviews\n', size=25)
plt.axis('off')

script_dir = os.path.dirname(__file__)
results_dir = os.path.join(script_dir, 'Results/')
sample_file_name = "Words_Reviews"

if not os.path.isdir(results_dir):
    os.makedirs(results_dir)
plt.savefig(results_dir + sample_file_name)


# In[7]:


df1 = df['Rating'].value_counts().to_frame()
avgdf1 = df.groupby('Class Name').agg({'Rating': np.average})
avgdf2 = df.groupby('Class Name').agg({'Age': np.average})
avgdf3 = df.groupby('Rating').agg({'Age': np.average})

trace1 = go.Bar(
    x=avgdf1.index,
    y=round(avgdf1['Rating'], 2),
    marker=dict(
        color=avgdf1['Rating'],
        colorscale='RdBu')
)

trace2 = go.Bar(
    x=df1.index,
    y=df1.Rating,
    marker=dict(
        color=df1['Rating'],
        colorscale='RdBu')
)

trace3 = go.Bar(
    x=avgdf2.index,
    y=round(avgdf2['Age'], 2),
    marker=dict(
        color=avgdf2['Age'],
        colorscale='RdBu')
)

trace4 = go.Bar(
    x=avgdf3.index,
    y=round(avgdf3['Age'], 2),
    marker=dict(
        color=avgdf3['Age'],
        colorscale='Reds')
)

fig = tools.make_subplots(rows=2, cols=2, print_grid=False)

fig.append_trace(trace1, 1, 1)
fig.append_trace(trace2, 1, 2)
fig.append_trace(trace3, 2, 1)
fig.append_trace(trace4, 2, 2)

fig['layout']['xaxis1'].update(title='Class')
fig['layout']['yaxis1'].update(title='Average Rating')
fig['layout']['xaxis2'].update(title='Rating')
fig['layout']['yaxis2'].update(title='Count')
fig['layout']['xaxis3'].update(title='Class')
fig['layout']['yaxis3'].update(title='Average Age of the Reviewers')
fig['layout']['xaxis4'].update(title='Rating')
fig['layout']['yaxis4'].update(title='Average Age of the Reviewers')

fig['layout'].update(height=800, width=900, showlegend=False)
# fig.update_layout({'plot_bgcolor': 'rgba(0,0,0,0)',
#                   'paper_bgcolor': 'rgba(0,0,0,0)'})
# fig['layout'].update(plot_bgcolor='rgba(0,0,0,0)')
# fig['layout'].update(paper_bgcolor='rgba(0,0,0,0)')
fig.write_image('py/Results/Fig.jpeg')


# In[8]:


cv = df['Class Name'].value_counts()

trace = go.Scatter3d(x=avgdf1.index,
                     y=avgdf1['Rating'],
                     z=cv[avgdf1.index],
                     mode='markers',
                     marker=dict(size=10, color=avgdf1['Rating']),
                     hoverinfo="text",
                     text="Class: "+avgdf1.index+" \ Average Rating: "+avgdf1['Rating'].map(
                         ' {:,.2f}'.format).apply(str)+" \ Number of Reviewers: "+cv[avgdf1.index].apply(str)
                     )

data = [trace]
layout = go.Layout(title="Average Rating & Class & Number of Reviewers",
                   scene=dict(
                       xaxis=dict(title='Class'),
                       yaxis=dict(title='Average Rating'),
                       zaxis=dict(title='Number of Sales'),),
                   margin=dict(l=30, r=30, b=30, t=30))
fig = go.Figure(data=data, layout=layout)
fig.write_image('py/Results/3D_scarter.jpeg')


# In[9]:


# Xếp hạng 4 hoặc cao hơn -> tích cực, trong khi những người có
# Xếp hạng 2 hoặc thấp hơn -> âm
# Đánh giá 3 -> trung tính
df = df[df['Rating'] != 3]
df['Sentiment'] = df['Rating'] >= 4

# chia dữ liệu
train_data, test_data = train_test_split(df, train_size=0.8, random_state=0)
# chọn các cột và
# chuẩn bị dữ liệu cho các mô hìnhs
X_train = vectorizer.fit_transform(train_data['Review Text'])
y_train = train_data['Sentiment']
X_test = vectorizer.transform(test_data['Review Text'])
y_test = test_data['Sentiment']


# In[10]:


start = dt.datetime.now()
nb = MultinomialNB()
nb.fit(X_train, y_train)
print('Elapsed time: ', str(dt.datetime.now()-start))


# In[11]:


# xác định khung dữ liệu cho các dự đoán
df2 = train_data.copy()

df2['Naive Bayes'] = nb.predict(X_train)

df2.head()


# In[12]:


pred_nb = nb.predict_proba(X_test)[:, 1]
fpr_nb, tpr_nb, _ = roc_curve(y_test.values, pred_nb)
roc_auc_nb = auc(fpr_nb, tpr_nb)

plt.figure()
plt.xlim([-0.01, 1.00])
plt.ylim([-0.01, 1.05])
plt.plot(fpr_nb, tpr_nb, color='darkred', lw=2,
         label='ROC curve (area = {:0.2f})'.format(roc_auc_nb))
plt.xlabel('False Positive Rate', fontsize=10)
plt.ylabel('True Positive Rate', fontsize=10)
plt.title('Naive Bayes', fontsize=10)
plt.legend(loc='lower right', fontsize=12)
plt.plot([0, 1], [0, 1], color='navy', lw=1, linestyle='--')
plt.axes().set_aspect('equal')

script_dir = os.path.dirname(__file__)
results_dir = os.path.join(script_dir, 'Results/')
sample_file_name = "roc_curve"
plt.savefig(results_dir + sample_file_name)


# In[13]:


nb_cm = confusion_matrix(y_test.values, nb.predict(X_test))
plt.figure(figsize=(15, 12))
plt.suptitle("Confusion Matrices", fontsize=24)
plt.subplot(2, 2, 2)
plt.title("Naive Bayes")
sns.heatmap(nb_cm, annot=True, cmap="Greens", cbar=False)

script_dir = os.path.dirname(__file__)
results_dir = os.path.join(script_dir, 'Results/')
sample_file_name = "Confusion_Matrices"
plt.savefig(results_dir + sample_file_name)


# In[14]:
f = open('py/Results/metrics_classification.text', 'w')
f.write("\n")
metrics_classification = mt.classification_report(y_test, nb.predict(X_test))
f = open('py/Results/metrics_classification.text', 'a')
f.write(metrics_classification)


# In[ ]:
