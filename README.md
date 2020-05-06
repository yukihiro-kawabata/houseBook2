# 自宅で使用するWebアプリ

![](https://img.shields.io/badge/laravel-6.2.*-orange)
![](https://img.shields.io/badge/PHP-7.2-brightgreen)
![](https://img.shields.io/badge/MySQL-5.7-green)
![](https://img.shields.io/badge/Vue-2.6-blueviolet)
![](https://img.shields.io/badge/npm-3.5.2-blueviolet)
![](https://img.shields.io/badge/node-v8.10.0-blueviolet)
[![MIT License](http://img.shields.io/badge/license-MIT-blue.svg?style=flat)](LICENSE)


## できること

- ![家計簿 (2020/05/01)](https://github.com/yukihiro-kawabata/houseBook2/blob/master/document/household_expenses/README.md)

## Learning Laravel
### Please set up laravel from the following

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## 最初に行うこと
```
$ composer install
$ npm install

# Vue Routerが無ければ実行（現在使ってないので不要 2020.05.01）
$ #### npm install --save vue-router

$ chmod -R 777 storage
$ chmod -R 777 bootstrap/cache

$ cp .env.example .env
$ vim .env　# DB設定をしてください

$ php artisan key:generate
$ php artisan ui vue
$ php artisan migrate
$ php artisan db:seed # テストデータ挿入

# ビルド実行
$ npm run dev

# 家計簿を使用するユーザ名を設定
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

## License

[MIT license](https://opensource.org/licenses/MIT).
