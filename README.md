# Telegram бот

## Установка

### Клонировать репозиторий 

```
git clone https://github.com/potkot/laravel-telegram-bot.git
```

### Запуск окружения docker

В папке docker скопировать .env.example в .env. При необходимости поменять значения настроек

В папке docker выполнить команду

```
docker-compose up -d --build
```

Войти в контейнер **task_test_php**

```
docker exec -it task_test_php /bin/sh
```

### Стандартная установка Laravel проекта внутри контейнера «task_test_php»

```
composer install
```

Настроить конфиг базы данных и выполнить команды

```
php artisan migrate 
```

```
php artisan db:seed --class=AddInitDataSeeder
```

### Настройка telegram бота

Создать бота и получить его «token to access»

Для создания используйте [BotFather](https://t.me/BotFather)

В конфиге config/telegram.php заполните:

* TELEGRAM_BOT_WATER_TOKEN - это token to access
* TELEGRAM_BOT_WATER_REQUEST_SECRET_TOKEN - это секрет для запросов webhook

Запустить команду внутри контейнера «task_test_php»

```
 php artisan install-bot
```
