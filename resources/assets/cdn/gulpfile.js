'use-strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');

gulp.task('css', function () {
	return gulp.src('./web/styles/index.scss')
		.pipe(sass().on('error', sass.logError))
		.pipe(concat('client.css'))
		.pipe(gulp.dest('./assets/css'))
		.pipe(sass({outputStyle: 'compressed'}))
		.pipe(concat('client.min.css'))
		.pipe(gulp.dest('./assets/dist'));
});

gulp.task('js', function () {
	return gulp.src('./web/styles/index.scss')
		.pipe(uglify())
		.pipe(gulp.dest('./assets/dist'));
});

gulp.task('default', ['css', 'js']);