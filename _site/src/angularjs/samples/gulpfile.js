var gulp = require('gulp')
var bower = require('gulp-bower')
var concat = require('gulp-concat')
var filter = require('gulp-filter')
var mainBowerFiles = require('main-bower-files')
var less = require('gulp-less')
var uglify = require('gulp-uglify')

var projectFiles = {
    'js': [
        'app/module/yandex/app.js',
        'app/module/yandex/run.js',
        'app/module/yandex/**/*.js',
        'app/module/app/app.js',
        'app/module/app/config.js',
        'app/module/app/**/*.js'
    ],
    'css': [
        'app/css/*.css'
    ]
}
gulp.task('scripts:application', function() {
    
    return gulp.src(projectFiles.js)
        .pipe(concat('app.js'))
        .pipe(gulp.dest('web/js'))
})

gulp.task('stylesheets:application', function() {
    
    return gulp.src(projectFiles.css)
        .pipe(concat('style.css'))
        .pipe(gulp.dest('web/css'))
})

gulp.task('scripts:vendor', ['bower'], function() {
    var vendors = mainBowerFiles()
    // Warning!
    // Some packages does not contain main key (which shows js and css files of package)
    // You should add information about files manually.
    vendors.push("bower_components/scalyr/scalyr.js");
    console.log(vendors)
    
    return gulp.src(vendors)
        .pipe(filter('**.js'))
        .pipe(concat('vendor.js'))
        .pipe(uglify())
        .pipe(gulp.dest('web/js'))
})


gulp.task('stylesheets:vendor', ['bower'], function() {
    var vendors = mainBowerFiles()
    
    return gulp.src(vendors)
        .pipe(filter('**.less'))
        .pipe(less())
        .pipe(concat('vendor.css'))
        .pipe(gulp.dest('web/css'))
})

gulp.task('bower', function() {
    return bower();
})

// watch task for rebuild on-the-fly
gulp.task('watch', function() {
  gulp.watch(projectFiles.js, ['scripts:application']);
  gulp.watch(projectFiles.css, ['stylesheets:application']);
});

// default task for build application
gulp.task('default', ['scripts:vendor', 'stylesheets:vendor', 'scripts:application', 'stylesheets:application'])