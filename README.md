# 模擬案件初級_フリマアプリ

---

## 環境構築

### Dockerビルド

1. git clone git@github.com:hiroshi-tak/mogi1-form.git
2. docker-compose up -d --build

### Laravel環境構築

1. docker-compose exec php bash
2. composer install
3. .env.exampleファイルから.envを作成し、環境変数を変更
4. php artisan key:generate
5. php artisan migrate
6. php artisan db:seed

## 開発環境

- 商品一覧画面:http://localhost/
- 会員登録:http://localhost/register
- phpMyAdmin:http://localhost:8080/

## 使用技術

- PHP 8.1.34
- Laravel 8.83.8
- jquery
- MySQL 8.0.26
- nginx 1.21.1

## ER図

![](mogi1.png)
