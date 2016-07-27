'use strict';

var gulp = require('gulp');
var less = require('gulp-less');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var sourcemaps = require('gulp-sourcemaps');

var files = {
    js: [
        'src/js/polyfills/*.js',
        'src/js/angular/app.js',
        'src/js/angular/appConfig.js',
        'src/js/angular/**/*Module.js',
        'src/js/angular/**/*Config.js',
        'src/js/angular/**/*.js'
    ],
    less: [
        'src/less/master.less'
    ],
    jsLib: [
        'vendor/bower/angular/angular.min.js',
        'vendor/bower/ng-tags-input/ng-tags-input.min.js',
        'vendor/bower/moment/min/moment.min.js',
        'vendor/bower/moment/locale/fr.js',
        'vendor/bower/moment/locale/de.js',
        'vendor/bower/moment/locale/it.js',
        'vendor/bower/angular-moment/angular-moment.min.js',
        'vendor/bower/lodash/dist/lodash.min.js',
        'vendor/bower/jspdf/dist/jspdf.min.js',
        'vendor/bower/retina.js/dist/retina.min.js'
    ]
};

gulp.task('js', function() {
    return gulp.src(files.js)
        .pipe(concat('app.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('build/'));
});

gulp.task('jsDev', function() {
    return gulp.src(files.js)
        .pipe(concat('app.min.js'))
        .pipe(gulp.dest('build/'));
});

gulp.task('jsLib', function() {
    return gulp.src(files.jsLib)
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(concat('lib.min.js'))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('build/'));
});

gulp.task('less', function() {
    return gulp.src(files.less)
        .pipe(less())
        .pipe(gulp.dest('build/'));
});