# Libremo

Libremo is a back end API for a bookstore shopping website built using [Lumen](https://lumen.laravel.com/).
There is a React front end in development at the repository [libremo-frontend](https://github.com/arturjzapater/libremo-frontend).

## Prerequisites

- PHP (>= 7.2)
- OpenSSL PHP extension
- PDO PHP extension
- Mbstring PHP extension
- Composer
- MySQL
- SQLite (for testing)

To develop this project I used [Homestead](https://laravel.com/docs/7.x/homestead), which satisfies all the requirements.

## Set Up

Clone the project and install its dependencies

```bash
git clone git@github.com:arturjzapater/libremo.git
cd libremo
composer install
```

Create a copy of the [.env.example](.env.example) file, rename it as .env and generate a new key

```bash
cp -a .env.example .env
php artisan key:generate
```

In your .env, update the following variables with your databse settings: `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

Run the database migrations and, if you want, seed it

```bash
php artisan migrate
php artisan db:seed
```

## Testing

In your development environment, run:

```bash
phpunit
```
