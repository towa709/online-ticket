# Online Ticket Management App

Laravel を用いて開発したオンラインチケット販売・予約管理アプリです。
公演情報・日程・チケット種別の管理、予約受付、予約一覧管理、CSVエクスポートなどを実装しています。

## 環境構築

### Dockerビルド

1. リポジトリをクローン

```bash
git clone git@github.com:towa709/online-ticket.git
```

2. プロジェクトディレクトリへ移動

```bash
cd online-ticket
```

3. DockerDesktop アプリを起動

4. Docker コンテナを作成・起動

```bash
docker-compose up -d --build
```

上記の手順は任意の作業ディレクトリで実行可能です。

例：

* Linux / WSL

```bash
/home/ユーザー名/coachtech/online-ticket
```

* Windows

```bash
C:\Users\ユーザー名\coachtech\online-ticket
```

---

### Laravel環境構築

1. PHPコンテナへ入る

```bash
docker-compose exec php bash
```

2. Composer パッケージをインストール

```bash
composer install
```

3. `.env` ファイルを作成

```bash
cp .env.example .env
```

4. `.env` の DB 設定を変更

```text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

5. アプリケーションキーを作成

```bash
php artisan key:generate
```

6. マイグレーション実行

```bash
php artisan migrate
```

7. シーディング実行（必要な場合）

```bash
php artisan db:seed
```

8. ストレージリンク作成

```bash
php artisan storage:link
```

---

### Permission denied エラーが出る場合

```bash
docker-compose exec php bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

---

## URL

* 開発環境：http://localhost
* phpMyAdmin：http://localhost:8080
* MailHog：http://localhost:8025
