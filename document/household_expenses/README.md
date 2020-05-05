# 家計簿のドキュメント

## イメージ
![image.gif](https://github.com/yukihiro-kawabata/houseBook2/blob/master/document/household_expenses/cash_list.gif)

## できること
- 1人〜n人の家計管理
- 1ヶ月ごとのお金の流れを把握
- グラフで金額推移を可視化

## 使い方
### 使用するユーザ名を設定
````
$ vim config/cash_const.php
````

### Slack通知を行う場合は設定
![Slack Web APIの公式サイト](https://api.slack.com/web)
````
$ vim .env
SLACK_WEB_API_URL=http://
````

### 仕様
#### 家計簿にデータ登録するときに、タグ付けして登録すると自動で集計される。
````
# こちらから集計科目などを確認できる
http://localhost/kamoku/list
````
![kamoku_master.png](https://github.com/yukihiro-kawabata/houseBook2/blob/master/document/household_expenses/kamoku_master.png)

#### 一覧のグラフは、本日から過去1年の推移を表している
![list_graph.png](https://github.com/yukihiro-kawabata/houseBook2/blob/master/document/household_expenses/list_graph.png)

#### 一括登録の方法
家賃など定期的に発生するものを登録しておくと便利
````
# 一括登録設定
http://localhost/cash/index?constant=true

# 自動登録された一覧
http://localhost/cash/constant/list
````
下記のコマンドで、一括して登録ができる
````
# 一括登録コマンド
php artisan command:cash_regist
````

## 初回アクセス
````
http://localhost/cash/list
````
