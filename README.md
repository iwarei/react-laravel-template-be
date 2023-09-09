### 環境構築
1. `git clone https://github.com/iwarei/react-laravel-template-be.git`
2. `cd react-laravel-template-be`
3. `php -r "readfile('https://getcomposer.org/installer');" | php`
4. `php composer.phar install`
5. `php artisan sail:install`
6. `./vendor/bin/sail up -d`

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
4. `cp .env.example .env`
5. `./vendor/bin/sail up -d`
※シェルエイリアスの設定をすると、以後`sail up -d`で立ち上げることができるようになるので、便利。
