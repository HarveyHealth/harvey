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

###Front End
- Follow back end steps.

- run `yarn`

		If you do not have yarn installed, run:

		npm install -g yarn

- point gulpfile to your locally hosted site

		If your locally hosted site is at `localhost:8000`, modify gulpfile.js to read:

		.browserSync({proxy: 'localhost:8000'});

- run `gulp watch`


###Database Seeding
To populate your local database with fake data, run:

		php artisan db:seed

Database Seeding will provide you with this admin account:

email: `test@harvey.com`

password: `secret`


###ToDo
- homestead repo
- list 3rd party services