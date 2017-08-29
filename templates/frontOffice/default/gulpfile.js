var gulp = require("gulp");
var babel = require("gulp-babel");
var sass = require('gulp-sass');
var watch = require('gulp-watch');

gulp.task("babel", function () {
    return gulp.src("assets/src/zipcode.js")
        .pipe(babel())
        .pipe(gulp.dest("assets/dist"));
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