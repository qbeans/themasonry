var gulp = require('gulp'),
	gutil = require('gulp-util'),
	browserify = require('gulp-browserify'),
	compass = require('compass'),
	concat = require('gulp-concat');

var jsSources = [
	'components/scripts/template.js'
]

gulp.task('log', function(){
	gutil.log('Test log');
});

gulp.task('js', function() {
	gulp.src(jsSources)
		.pipe(concat('_scripts.js'))
		.pipe(browserify())
		.pipe(gulp.dest('builds/development/js'))
});