// Get access to gulp plugins
var gulp         = require('gulp');
var sass         = require('gulp-sass');
var watch        = require('gulp-watch');
var uglify       = require('gulp-uglify');
var concat       = require('gulp-concat');
var autoprefixer = require('gulp-autoprefixer');
var plumber      = require('gulp-plumber');
var browserify   = require('gulp-browserify');

var themeFolder  = 'public/wp-content/themes/salient';
var buildFolder  = themeFolder + '/build';

// convert SASS to compressed css
gulp.task('sass', function () {
    gulp.src([
            themeFolder + '/scss/index.scss'
        ])
        .pipe(sass().on('error', sass.logError))
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(concat('master.css'))
        .pipe(autoprefixer({
            browsers: ['last 5 versions'],
            cascade:  false
        }))
        .pipe(gulp.dest(buildFolder));
});

// convert SASS to compressed css
gulp.task('adminSass', function () {
    gulp.src([
            themeFolder + '/scss/admin.scss'
        ])
        .pipe(sass().on('error', sass.logError))
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(concat('admin.css'))
        .pipe(autoprefixer({
            browsers: ['last 5 versions'],
            cascade:  false
        }))
        .pipe(gulp.dest(buildFolder));
});

//frontend js
gulp.task('scripts', function () {
    // Single entry point to browserify
    gulp.src(themeFolder + '/js/custom/master.js')
        .pipe(plumber())
        .pipe(browserify({
            insertGlobals: true
        }))
        .pipe(uglify())
        .pipe(plumber.stop())
        .pipe(gulp.dest(buildFolder))
});

//admin js
gulp.task('adminScripts', function () {
    // Single entry point to browserify
    gulp.src(themeFolder + '/js/custom/admin.js')
        .pipe(plumber())
        .pipe(browserify({
            insertGlobals: true
        }))
        .pipe(uglify())
        .pipe(plumber.stop())
        .pipe(gulp.dest(buildFolder))
});

// Watcher

gulp.task('watch', function () {
    // Watch .scss files
    gulp.watch(themeFolder + '/scss/*/*.scss', ['sass', 'adminSass']);
    gulp.watch(themeFolder + '/scss/*.scss', ['sass', 'adminSass']);
    gulp.watch(themeFolder + '/js/custom/*.js', ['scripts', 'adminScripts']);
    gulp.watch(themeFolder + '/js/custom/*/*.js', ['scripts', 'adminScripts']);
});

gulp.task('build', function () {
    console.log('Gulp build succesful!');
});