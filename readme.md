# Harvey

[![Build Status](https://travis-ci.com/HomeHero/harvey.svg?token=t5RVCNcUwCMu8zG3CPHE&branch=master)](https://travis-ci.com/HomeHero/harvey)

A web application to provide virtual consultations with state-licensed integrative physicians, in-home lab testing, diagnostics and progressive therapies to help patients address the root cause of their health conditions instead of just treating the symptoms.

## Requirements
 - PHP >= 7
 - PHP [mcrypt extension](http://php.net/manual/en/book.mcrypt.php)
 - Node 7
 - MySQL 5.7
 - [Composer](https://getcomposer.org/download/)

## Local Installation
 - Clone the repository locally
 - Install front-end dependencies with `npm install`
 - Generate front-end assets with `npm run dev`
 - Install back-end dependencies with `composer install`
 - Copy [.env.example](https://github.com/HomeHero/harvey/blob/master/.env.example) to `.env` and modify the contents to reflect your local environment
 - Run the database migrations and seed the database

```bash
php artisan migrate --seed
```

 - Run Laravel's built-in web server
```bash
php artisan serve
```

### Front End File Structure

- `./resources` should always be the working folder, while `./public` are generated or bundled files.

- We are using Laravel Mix as a wrapper of Webpack for module bundles and other front end build steps.  Config at `./webpack.mix.js`.

- For use cases Laravel Mix doesn't cover, we'll need to config directly from `./webpack.config.js`.

### Homestead

- run `./setup`

SSL Certificates were created using the following commands from the project root:

```
mkdir vagrant_files
cd vagrant_files
openssl genrsa -des3 -passout pass:x -out harvey.app.pass.key 2048
openssl rsa -passin pass:x -in harvey.app.pass.key -out harvey.app.key
rm harvey.app.pass.key
openssl req -new -key harvey.app.key -out harvey.app.csr
openssl x509 -req -sha256 -days 365 -in harvey.app.csr -signkey harvey.app.key -out harvey.app.crt
```

### Database Seeding
To populate your local database with fake data, run:
    `php artisan db:seed`

Database Seeding will provide you with these accounts:

#### Admin
- email: `admin@goharvey.com`
- password: `secret`

#### Patient
- email: `patient@goharvey.com`
- password: `secret`

#### Practitioner
- email: `practitioner@goharvey.com`
- password: `secret`

### Standardization Notes:
- Use response codes found in `Symfony\Component\HttpFoundation\Response`
- Methods should be written in camel-case: `getSomeThing()`
- Variables should be written in snake-case: `$some_variable_name`
- Test methods should be written in snake-case with "test" prepended: `test_it_allows_something_to_occur()`


# Nginx
- Procfile calls the nginx.conf file in the project root.