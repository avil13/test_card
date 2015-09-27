var gulp = require('gulp');
var coffee = require('gulp-coffee');
var sourcemaps = require('gulp-sourcemaps');
var gulpif = require('gulp-if');
var notify = require('gulp-notify');
var plumber = require('gulp-plumber');
var concat = require('gulp-concat');

var src = {
    js: [
        'public/content/bwr/sweetalert/dist/sweetalert.min.js',
        'public/content/bwr/angular/angular.js',
        'public/content/bwr/angular-locale-ru/angular-locale_ru.js',
        'public/content/bwr/angular-ui-router/release/angular-ui-router.min.js',
        'public/content/bwr/angucomplete-alt/dist/angucomplete-alt.min.js',

        'public/content/js/src/app.js',

        'public/content/js/src/factorys/*.js',
        'public/content/js/src/directives/**/*.js',
        'public/content/js/src/controllers/**/*.js',
        'public/content/js/src/controllers/**/*.coffee'
    ]
};


gulp.task('js', function() {
    gulp.src(src.js)
        .pipe(plumber({
            errorHandler: notify.onError("Error:\n<%= error %>")
        }))
        .pipe(sourcemaps.init())
        .pipe(gulpif(/[.]coffee$/, coffee({
            bare: true
        })))
        .pipe(concat('script.js'))
        .pipe(sourcemaps.write('../maps'))
        .pipe(gulp.dest('./public/content/js'));
});

gulp.task('default', ['js']);

gulp.task('watch', ['default'], function () {
    gulp.watch(src.js, ['js']);
});
