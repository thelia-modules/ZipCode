var gulp = require("gulp");
var babel = require("gulp-babel");
var sass = require('gulp-sass');
var watch = require('gulp-watch');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');

gulp.task("babel", function () {
    return gulp.src(["node_modules/whatwg-fetch/fetch.js", "assets/src/zipcode.js"])
        .pipe(babel())
        .pipe(concat('zipcode.js'))
        .pipe(uglify())
        .pipe(gulp.dest("assets/dist/"));
});

gulp.task("scss", function () {
    return gulp.src("assets/src/zipcode.scss")
        .pipe(sass())
        .pipe(gulp.dest('assets/dist'));
});

gulp.task('default', ['babel','scss']);

gulp.task('watch', function(){
    gulp.watch('assets/src/**/*.scss', ['scss']);
    gulp.watch('assets/src/**/*.js', ['babel']);
});
