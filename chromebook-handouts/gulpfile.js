var gulp = require('gulp');
var concatCss = require('gulp-concat-css');

// This gulp task takes all css files and concatenates them into a single file.
gulp.task('default', function () {
  return gulp.src('public/css/*.css')
    .pipe(concatCss("public/css/style.css"))
    .pipe(gulp.dest('out/'));
});
