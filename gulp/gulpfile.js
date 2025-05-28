//gulp default js js1
//gulp default js

const gulp = require('gulp');
const { src, dest, task } = require('gulp');
const uglify = require('gulp-uglify');
const concat = require('gulp-concat');
const minifyCSS = require('gulp-minify-css');
const autoprefixer = require('gulp-autoprefixer');
const rename = require('gulp-rename');
const ngAnnotate = require('gulp-ng-annotate')

gulp.task('default', (done) => {

	var files = [
		'../public/bootstrap3/css/bootstrap.min.css',
		'../public/assets/font-awesome/css/font-awesome.min.css',
		'../public/plugins/bootstrap-datepicker/css/datepicker3.css',
		'../public/assets/css/selectize.css',
		'../public/assets/css/custom.css',
			
	];

	gulp
	.src(files)
	.pipe(minifyCSS())
	.pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9'))
	.pipe(concat('web.min.css'))
	.pipe(dest('../public/assets/dist'))
	
	console.log("Default done!");

  	done();
});

function js(done) {
	var files = [
		'../public/assets/scripts/jquery.min.js',
		'../public/bootstrap3/js/bootstrap.min.js',
		'../public/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
		'../public/assets/scripts/selectize.min.js',
		'../public/assets/scripts/angular.min.js',
		'../public/assets/scripts/ng-file-upload.min.js',
		'../public/assets/scripts/angular-selectize.js',
		'../public/assets/scripts/jcs-auto-validate.js',
		'../public/assets/scripts/core/custom.js',
		'../public/assets/scripts/core/app.js',
		'../public/assets/scripts/core/services.js',
		'../public/assets/scripts/core/controller.js',
	];
  	gulp
  	.src(files)
  	.pipe(concat('web.min.js'))
	.pipe(uglify())
	.pipe(dest('../public/assets/dist'));
	
	console.log("Web plugins done!");

  	done();
};
exports.js = js;

// function js1(done) {
// 	var files = [
// 		'../public/assets/scripts/core/custom.js',
// 		'../public/assets/scripts/core/app.js',
// 		'../public/assets/scripts/core/services.js',
// 		'../public/assets/scripts/core/controller.js',
		
// 	];
//   	gulp
//   	.src(files)
//   	.pipe(concat('web.min.js'))
// 	.pipe(uglify())
// 	.pipe(dest('../public/assets/dist'));
	
// 	console.log("Web Custom done!");

//   	done();
// };
// exports.js1 = js1;

