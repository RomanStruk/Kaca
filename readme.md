# Kaca
[![Release](https://img.shields.io/badge/Release-v1.0.0--beta-green?style=flat-square)](https://github.com/RomanStruk/Kaca/releases)

Функціонал касового апарату для Laravel додатків, на основі API checkbox.ua

## Installation

Via Composer

``` bash
$ composer require romanstruk/kaca
```
## Usage

Run artisan command
```bash
php artisan kaca:install tailwind
```
Змінити таблицю міграції якщо таблиця користувачів відрізняється від `users`. 
А також в сервіс провайдері вказати модель і поле імені яка відповідає за авторизацію.

Run migrations
```bash
php artisan migrate
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email romanuch4@gmail.com instead of using the issue tracker.

## License

MIT. Please see the [license file](license.md) for more information.
