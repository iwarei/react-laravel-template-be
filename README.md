### Docker上で環境構築を行う場合 (WSL Ubuntu / Laravel sail)
phpをインストールしたくない、phpのバージョンを変えたくないなど、既存環境を壊したくない場合に。
PHP, Composerを含むDockerコンテナを作成し、依存関係を解決する。
1. `git clone https://github.com/iwarei/react-laravel-template-be.git`
2. `cd react-laravel-template-be`
3. 下記コマンドを実行。Laravel10のSailではPHP8.2, 8.1, 8.0から選択できるので必要に応じて書き換える。
    ```console
   docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
   ```
4. `.env.example`の`DB_DATABASE`を開発するアプリ名に変更する。
5. `cp .env.example .env`
6. `./vendor/bin/sail up -d`
※シェルエイリアスの設定をすると、以後`sail up -d`で立ち上げることができるようになるので、便利。
7. `sail artisan key:generate`
8. `sail artisan migrate`


※トラブルシューティング
#### `sail artisan key genrate`でエラーが発生した場合
1. 下記のようなエラーが発生した場合。
``` powershell
   Illuminate\Database\QueryException 

  SQLSTATE[HY000] [2002] Connection refused 
```
`env`を下記のように修正。
```
DB_HOST=mysql
```
再度、`sail artisan key:generate`を実行。


2. 下記のようなエラーが発生した場合。
``` powershell
  Illuminate\Database\QueryException 

  SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo for mysql failed: Temporary failure in name resolution
```
基本発生しないはずだが、`.env`を下記のようになっていることを確認。
```
DB_USERNAME=sail
DB_PASSWORD=password
```
`docker-compose down --volumes`もしくは`sail down --rmi all -v`を実行。
その後`sail up --build`を行い、`http://localhost`にアクセスできることを確認したら`CTRL+C`でいったん中断。手順6から行う。

---

### 環境構築 (XAMPP/非推奨)
1. `git clone https://github.com/iwarei/react-laravel-template-be.git`
2. `cd react-laravel-template-be`
3. `php -r "readfile('https://getcomposer.org/installer');" | php`
4. `php composer.phar install`
5. `php artisan key:generate`
6. `php artisan migrate`

