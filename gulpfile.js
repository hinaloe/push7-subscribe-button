const
	g = require('gulp'),
	sass = require('gulp-sass'),
	tsc = require('gulp-typescript'),
	img = require('gulp-imagemin'),
	del = require('del'),
	uglify = require('gulp-uglify'),
	cssmin = require('gulp-cssmin'),
	rename = require('gulp-rename'),
	runSequence = require('run-sequence'),
	{spawn} = require('child_process'),
	{log} = require('gulp-util')
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
		.pipe(p())
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

g.task('clean:apigen', ()=>
	del(['doc/**/*']));

g.task('apigen:build', _=>
	//execSync('apigen generate && cd apigen && php hook-docs.php', {stdio: [0, 1, 2]}));
	new Promise((resolve, reject)=> {
		const p = spawn('apigen', ['generate']);
		p.stdout.on('data', data=>log('' + data));
		//p.stderr.on('data', data=>log('' + data));
		p.on('close', (code)=> {
			log('built doc');
			resolve();
		});
	}).then(_=>new Promise((resolve, reject)=> {
		const p = spawn('php', ['hook-docs.php'], {cwd: './apigen/'});
		p.stdout.on('data', data=>log('' + data));
		p.stderr.on('data', (data)=>reject('' + data));
		p.on('close', (code)=>resolve());
	})));

g.task('apigen', _=>runSequence('clean:apigen', 'apigen:build', _));

g.task('watch', ()=> {
		g.watch('./src/ts/**/*.ts', ['ts']);
		g.watch('./src/sass/**/*.scss', ['sass']);
	}
);

g.task('default', ['ts', 'sass', 'img']);
g.task('ci', ['clean', 'clean:apigen'], _=>runSequence(['apigen:build', 'ts', 'sass', 'img'], _));
