'use-strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');

var scssFilename = ''; // path
var originalCss = ''; // path
var minifiedCss = ''; // path
var JSFilename = ''; // path
var minifiedJS = ''; // path

gulp.task('css', function () {
	return gulp.src(scssFilename)
		.pipe(sass().on('error', sass.logError))
		.pipe(concat('index.css'))
		.pipe(gulp.dest(originalCss))
		.pipe(sass({outputStyle: 'compressed'}))
		.pipe(concat('index.min.css'))
		.pipe(gulp.dest(minifiedCss));
});

gulp.task('js', function () {
	return gulp.src(JSFilename)
		.pipe(uglify())
		.pipe(gulp.dest(minifiedJS));
});

gulp.task('default', ['css', 'js']);