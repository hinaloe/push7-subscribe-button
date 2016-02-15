const
	g = require('gulp'),
	sass = require('gulp-sass'),
	tsc = require('gulp-typescript'),
	img = require('gulp-imagemin'),
	del = require('del'),
	uglify = require('gulp-uglify'),
	cssmin = require('gulp-cssmin'),
	rename = require('gulp-rename')
	;

g.task('sass', ()=>
	g.src('./src/sass/**/*.scss')
		.pipe(sass({
			outputStyle: 'expanded'
		}).on('error', sass.logError))
		.pipe(g.dest('./css/'))
		.pipe(cssmin())
		.pipe(rename({extname: '.min.css'}))
		.pipe(g.dest('./css/'))
);

g.task('ts', ()=> {
	const p = tsc.createProject('tsconfig.json');
	return p.src()
		.pipe(tsc(p))
		.js
		.pipe(g.dest('./js'))
		.pipe(uglify())
		.pipe(rename({extname: '.min.js'}))
		.pipe(g.dest('./js'));
});

g.task('img', ()=>
	g.src('./src/img/**/*.{png,svg,jpg}')
		.pipe(img({
			optimizationLevel: 7
		}))
		.pipe(g.dest('./img'))
);

g.task('clean', ()=>
	del([
		'{img,css,js}/**/*'
	])
);

g.task('default', ['ts', 'sass', 'img'])
