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

## アクセス
````
http://localhost/cash/list
````
