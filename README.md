# Telegram бот

## Установка

### Стандартная установка Laravel проекта

```
git clone 
```

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

```
 php artisan install-bot
```
