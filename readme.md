#Harvey

####Back End: [Laravel 5.3](https://laravel.com/docs/5.3)
####Front End: [Vue 2.0](https://vuejs.org/v2/guide)

##For Local Development

###Back End
- Clone the Harvey repository:

		git clone git@github.com:HomeHero/harvey.git

- `cd` into the harvey directory
- run `composer update`
- Modify .env to suit your local config
- run `php artisan migrate`

###Front End
- Follow back end steps.

- run `yarn`

		If you do not have yarn installed, run:

		npm install -g yarn

- point gulpfile to your locally hosted site

		If your locally hosted site is at `localhost:8000`, modify gulpfile.js to read:

		.browserSync({proxy: 'localhost:8000'});

- run `npm run dev`

###Database Seeding
To populate your local database with fake data, run:

		php artisan db:seed

Database Seeding will provide you with these accounts:

#### Admin
email: `admin@goharvey.co`

password: `secret`

#### Patient
email: `patient@goharvey.co`

password: `secret`

#### Practitioner
email: `practitioner@goharvey.co`

password: `secret`

### Standardization Notes:
- Use response codes found in `Symfony\Component\HttpFoundation\Response`

###ToDo
- list 3rd party services
