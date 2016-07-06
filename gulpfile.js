'use strict';

var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var sourcemaps = require('gulp-sourcemaps');

var files = {
    js: [
        'src/js/polyfills/*.js',
        'src/js/angular/**/*Module.js',
        'src/js/angular/**/*Config.js',
        'src/js/angular/**/*.js'
    ],
    less: [
        'src/less/**/*.js'
    ],
    jsLib: [
        'vendor/bower/angular/angular.min.js',
        'vendor/bower/ng-tags-input/ng-tags-input.min.js',
        'vendor/bower/jspdf/dist/jspdf.min.js',
        'vendor/bower/retina.js/dist/retina.min.js'
    ],
    cssLib: [
        'vendor/bower/ng-tags-input/ng-tags-input.min.css'
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

gulp.task('cssLib', function() {
    return gulp.src(files.cssLib)
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(concat('lib.min.css'))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('build/'));
});

gulp.task('lib', ['jsLib', 'cssLib']);