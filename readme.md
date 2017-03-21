# Harvey

#### Back End: [Laravel 5.4](https://laravel.com/docs/5.4)
#### Front End: [Vue 2.0](https://vuejs.org/v2/guide)

## For Local Development

### Back End
1. Clone the Harvey repository:

	`git clone git@github.com:HomeHero/harvey.git`

- `cd` into the harvey directory
- run `composer update`
- Modify the .env file to suit your local config
- run `php artisan migrate`

### Front End
1. Follow back end steps.

- run `yarn`

	If you do not have yarn installed, run:

	`npm install -g yarn`

	Or

	`brew install yarn`

- point the gulpfile to your locally hosted site

	If your locally hosted site is at `localhost:8000`, modify gulpfile.js to read:

	`.browserSync({proxy: 'localhost:8000'});`

- run `npm run dev`

### Front End File Structure

- Public and logged in pages are separate apps, but
    - they both extend `./resources/views/_layouts/main.blade.php`
    - they both include `./resources/assets/js/bootstrap.js` for dependency injection and setup
    - they both include `./resources/assets/sass/all.scss` for shared css

- Entry points (Public)
    `./resources/views/_layouts/public.blade.php`
    `./resources/assets/js/app_public.js`
    `./resources/assets/sass/app_public.scss`

- Entry points (Logged in)
    `./resources/views/_layouts/logged_in.blade.php`
    `./resources/assets/js/app_logged_in.js`
    `./resources/assets/sass/app_logged_in.scss`

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

### ToDo
- list 3rd party services

