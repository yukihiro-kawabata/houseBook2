# 自宅で使用するWebアプリ

- laravel 6.2.*
- php = 72
- MySQL = 56
- Vue.js = 2.6

## できること

- ![家計簿 (2020/05/01)](https://github.com/yukihiro-kawabata/houseBook2/blob/master/document/household_expenses/README.md)

## Learning Laravel
### Please set up laravel from the following

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## 最初に行うこと（抜粋）

```
$ composer install
$ npm install

# Vue Routerが無ければ実行（現在使ってないので不要 2020.05.01）
$ #### npm install --save vue-router

$ chmod -R 777 storage
$ chmod -R 777 bootstrap/cache

$ cp .env.example .env
$ vim .env
````
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hoge
DB_USERNAME=hoge
DB_PASSWORD=hoge
````

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
```

## アクセス
````
http://localhost/cash/list
````

## License

[MIT license](https://opensource.org/licenses/MIT).
