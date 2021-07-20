var gulp = require('gulp');
var mainBowerFiles = require('main-bower-files');
var jsmin = require('gulp-jsmin');
var rename = require('gulp-rename');
var concat = require('gulp-concat');

gulp.task('bower', function() {
    return gulp.src(mainBowerFiles())
        .pipe(concat('requirements.js'))
        .pipe(gulp.dest('js'));
});

gulp.task('app', function() {
    return gulp.src('app/**/*')
        .pipe(concat('app.js'))
        .pipe(gulp.dest('js'));
});

gulp.task('build', ['app', 'bower'], function() {
    // pass
});