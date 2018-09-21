var gulp = require('gulp');
var sass = require('gulp-sass');
var cleanCSS = require('gulp-clean-css');
var sassDataURI = require('lib-sass-data-uri');

gulp.task('parse-template-scss', function() {
    return gulp.src('src/templates/siwecos/scss/template.scss')
        .pipe(sass({
            errLogToConsole: true,
            functions: Object.assign(sassDataURI, {other: function() {}})
        }))
        .pipe(cleanCSS({compatibility: 'ie10'}))
        .pipe(gulp.dest('src/templates/siwecos/css'));
});

gulp.task('parse-critical-scss', function() {
    return gulp.src('src/templates/siwecos/scss/critical.scss')
        .pipe(sass({
            errLogToConsole: true,
            functions: Object.assign(sassDataURI, {other: function() {}})
        }))
        .pipe(cleanCSS({compatibility: 'ie10'}))
        .pipe(gulp.dest('src/templates/siwecos/css'));
});

gulp.task('watch', function() {
    gulp.watch('src/templates/siwecos/scss/**/*.scss', ['parse-template-scss']);
    gulp.watch('src/templates/siwecos/scss/**/*.scss', ['parse-critical-scss']);
});

gulp.task('default', function() {
    gulp.run('watch');
});

