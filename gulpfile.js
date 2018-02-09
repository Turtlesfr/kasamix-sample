var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var sourcemaps = require('gulp-sourcemaps');
var cleanCSS = require('gulp-clean-css');
var del = require('del');
var less = require('gulp-less');
var merge = require('merge-stream');

var config = {
    dist: {
        root: 'web/bundles/kasamixbundle'
    },
    source: {
        root: 'src/KasamixBundle/Resources/public/app'
    },
    bower: {
        root: 'bower_components'
    },
    node: {
        root: 'node_modules'
    }
};

config = {
    dependency: {
        jquery: {
            js: config.bower.root + '/jquery/dist/jquery.js'
        },
        jquery_ui: {
            js: config.bower.root + '/jquery-ui/jquery-ui.js'
        },
        fontAwesome: {
            fonts: config.bower.root + '/font-awesome/fonts/**/*'
        },
        bootstrap: {
            js: config.bower.root + '/components-bootstrap/js/bootstrap.js',
            fonts: config.bower.root + '/components-bootstrap/fonts/*'
        },
        pannellum: {
            jsMin: config.bower.root + '/pannellum/js/l.min.js',
            js: config.bower.root + '/pannellum/js/pannellum.js',
            jsLib: config.bower.root + '/pannellum/js/libpannellum.js',
            jsStandalone: config.bower.root +'/pannellum/js/standalone.js',
            css: config.bower.root + '/pannellum/css/pannellum.css',
            img: config.bower.root + '/pannellum/images/**.*'
        },
        dataTable: {
            //js: config.bower.root + '/datatables.net/js/jquery.dataTables.js',
            js: config.bower.root + '/datatables.net-bs/js/**.*',
            css: config.bower.root + '/datatables.net-bs/css/**.*'
        },
        password: {
            js: config.node.root + '/password-strength-meter/dist/password.min.js',
            css: config.node.root + '/password-strength-meter/dist/password.min.css',
            img: config.node.root + '/password-strength-meter/dist/passwordstrength.jpg'
        },
        momentJs: {
            js: config.node.root + '/moment/min/moment-with-locales.js'
        }
    },

    dist: {
        images: config.dist.root + '/img/',
        favicons: config.dist.root + '/favicon/',
        styles: config.dist.root + '/css/',
        javascript: config.dist.root + '/js/',
        fonts: config.dist.root + '/fonts/'
    },

    source: {
        less: config.source.root + '/less/*.less',
        images: config.source.root + '/img/*',
        favicons: config.source.root + '/favicon/**/**',
        styles: config.source.root + '/styles/**/*.css',
        javascript: config.source.root + '/js/**/**'
    }
};

gulp.task('default', ['build:favicons', 'build:images', 'build:js', 'build:fonts', 'build:styles']);

gulp.task('clean', function() {
    // place code for your default task here
});

gulp.task('build:fonts', function() {
    return gulp.src([
        config.dependency.fontAwesome.fonts,
        config.dependency.bootstrap.fonts
    ])
        .pipe(gulp.dest(config.dist.fonts));
});


gulp.task('build:favicons', function() {
    return gulp.src(config.source.favicons)
        .pipe(gulp.dest(config.dist.favicons));
});

gulp.task('build:images', function() {
    return gulp.src([
        config.source.images,
        config.dependency.pannellum.img,
        config.dependency.password.img
    ])
        .pipe(gulp.dest(config.dist.images));
});

gulp.task('build:js', function() {
    return gulp.src(
            [
                config.dependency.jquery.js,
                config.dependency.jquery_ui.js,
                config.dependency.bootstrap.js,
                config.dependency.dataTable.js,
                config.dependency.password.js,
                config.dependency.momentJs.js,
                config.dependency.pannellum.jsStandalone,
                //config.dependency.pannellum.jsMin,
                //config.dependency.pannellum.js,
                //config.dependency.pannellum.jsLib,
                config.source.javascript
            ]
    )
        .pipe(sourcemaps.init())
        .pipe(uglify())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(config.dist.javascript));
});

gulp.task('build:styles', function() {
    var lessStream =  gulp.src(config.source.less)
        .pipe(less())
        .pipe(concat('all.less'));

    var cssStream = gulp.src([
        config.dependency.pannellum.css,
        config.dependency.password.css,
        config.dependency.dataTable.css,
        config.source.styles
    ])
        .pipe(concat('all.css'));

    return merge(lessStream, cssStream)
        .pipe(cleanCSS({compatibility: 'ie9'}))
        .pipe(concat('all.min.css'))
        .pipe(gulp.dest(config.dist.styles));
});

gulp.task('watch', function() {
    gulp.watch(config.source.images, ['build:images']);
    gulp.watch(config.source.javascript, ['build:js']);
    gulp.watch(config.source.styles, ['build:styles']);
});
