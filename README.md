# 勤怠管理アプリ

一般ユーザーの出勤・退勤・休憩の打刻、勤怠情報の確認・修正申請、および管理者による勤怠管理を行うための勤怠管理アプリケーションです。

## 環境構築

### Dockerビルド

1. リポジトリをクローンします。

    git clone <リポジトリURL>

2. プロジェクトディレクトリへ移動します。

    cd kintai-app

3. Dockerコンテナをビルド・起動します。

    docker compose up -d --build

### Laravel環境構築

1. PHPコンテナに入ります。

    docker compose exec php bash

2. Composerパッケージをインストールします。

    composer install

3. `.env.example` をコピーして `.env` を作成します。

    cp .env.example .env

4. アプリケーションキーを生成します。

    php artisan key:generate

5. マイグレーションとシーディングを実行します。

    php artisan migrate:fresh --seed

以上で環境構築は完了です。

## 使用技術

- PHP 8.1.34
- Laravel 8.83.8
- MySQL 8.0.26
- nginx 1.21.1
- Docker / Docker Compose

## URL

- 開発環境：http://localhost/
- 一般ユーザーログイン：http://localhost/login
- 管理者ログイン：http://localhost/admin/login
- phpMyAdmin：http://localhost:8080/

## ログイン情報

シーディングを実行すると、以下のユーザーが作成されます。

### 一般ユーザー1

- メールアドレス：user1@example.com
- パスワード：password

### 一般ユーザー2

- メールアドレス：user2@example.com
- パスワード：password

### 管理者ユーザー

- メールアドレス：user3@example.com
- パスワード：password

## ダミーデータ

以下のコマンドを実行することで、ユーザー・勤怠・休憩のダミーデータが作成されます。

    php artisan migrate:fresh --seed

作成されるユーザーは以下の通りです。

- 一般ユーザー：2名
- 管理者ユーザー：1名

また、各ユーザーに勤怠情報および休憩情報が作成されます。

## PHPUnitテスト

PHPコンテナ内で以下のコマンドを実行します。

    php artisan test

全テストが成功することを確認しています。

## ER図

![ER図](./docs/er-diagram.png)


## 主な機能

### 一般ユーザー

- 会員登録
- メール認証
- ログイン・ログアウト
- 出勤
- 退勤
- 休憩開始・終了
- 勤怠一覧表示
- 勤怠詳細表示
- 勤怠修正申請
- 修正申請一覧表示
- 修正申請詳細表示

### 管理者

- 管理者ログイン・ログアウト
- 日次勤怠一覧表示
- 勤怠詳細確認・修正
- スタッフ一覧表示
- スタッフ別月次勤怠一覧表示
- 修正申請一覧表示
- 修正申請詳細表示・承認
- CSV出力

## データベース

データベース名：

    laravel_db

phpMyAdminからデータベースを確認できます。

    http://localhost:8080/