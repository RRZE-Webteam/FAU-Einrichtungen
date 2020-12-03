'use strict';
/* 
 * Gulp Builder for WordPress Theme FAU-Einrichtungen
 */
const
    {src, dest, watch, series, parallel} = require('gulp'),
    sass = require('gulp-sass'),
    postcss = require('gulp-postcss'),
    autoprefixer = require('autoprefixer'),
    uglify = require('gulp-uglify'),
    babel = require('gulp-babel'),
    bump = require('gulp-bump'),
    semver = require('semver'),
    info = require('./package.json'),
    wpPot = require('gulp-wp-pot'),
    touch = require('gulp-touch-cmd'),
    header = require('gulp-header'),
    cssnano = require('cssnano'),
    replace = require('gulp-replace'),
    rename = require('gulp-rename'),
    yargs = require('yargs'),
    cssvalidate = require('gulp-w3c-css'),
    map  = require('map-stream')
;



const clonetarget = yargs.argv.target;

/**
 * Template for banner to add to file headers
 */

var banner = [
    '/*!',
    'Theme Name: <%= info.name %>',
    'Version: <%= info.version %>',
    'Description: <%= info.description %>',
    'Theme URI: <%= info.repository.url %>',
    'GitHub Theme URI: <%= info.repository.url %>',
    'GitHub Issue URL: <%= info.repository.issues %>',
    'Author: <%= info.author.name %>',
    'Author URI: <%= info.author.url %>',
    'License: <%= info.license %>',
    'License URI: <%= info.licenseurl %>',
    'Tags: <%= info.tags %>',
    'Text Domain: <%= info.textdomain %>',
    '*/'].join('\n');


/**
 * Create Clone for a given theme
 */


function cloneTheme(cb) {
    var targetdir = '';
    var farbfamilie = 'zuv';
    var theme = yargs.argv.theme;
    var builddir = yargs.argv.builddir;
    if (builddir===undefined) {
	builddir = '../build/';
    }
           
      
    switch(theme) {
	case 'zuv': 
	    targetdir = builddir + 'FAU-ZUV/';
	    farbfamilie =  'zuv';
	    break;
	case 'phil': 
	    targetdir = builddir + 'FAU-Philfak/';
	    farbfamilie =  'phil';
	    break;
	case 'med': 
	    targetdir = builddir + 'FAU-Medfak/';
	    farbfamilie =  'med';
	    break;
	case 'rw': 
	    targetdir = builddir + 'FAU-RWFak/';
	    farbfamilie =  'rw';
	    break;
	case 'tf': 
	    targetdir = builddir + 'FAU-Techfak/';
	    farbfamilie =  'tf';
	    break;
	case 'nat': 
	    targetdir = builddir + 'FAU-Natfak/';
	    farbfamilie =  'nat';
	    break;
	default:
	    console.log(`No valid theme defined. Please use argument:   gulp clone --theme=name   , with name=(phil|rw|tf|nat|med)`);
	    break;
    }
 
    if (targetdir === '') {	
	cb();
	return;
    }
    console.log(`Building theme for: ${theme}`);
    console.log(`   Target directory: ${targetdir}`);
    console.log(`   Color: ${farbfamilie}`);
   
    var sassdir = targetdir + 'css/sass/';
    var variablesfile =  sassdir + '_variables.scss';
    var constfile = targetdir + 'functions/constants.php';

    var helpercssbanner = [
    '/*!',
    '* Backend-CSS for Theme:',
    '* Theme Name: <%= info.themeClones.'+farbfamilie+'.name %>',
    '* Version: <%= info.version %>',
    '* GitHub Issue URL: <%= info.repository.issues %>',
    '*/'].join('\n');

    var themebanner = [
    '/*!',
    'Theme Name: <%= info.themeClones.'+farbfamilie+'.name %>',
    'Version: <%= info.version %>',
    'Description: <%= info.themeClones.'+farbfamilie+'.description %>',
    'Theme URI: <%= info.themeClones.'+farbfamilie+'.GitHubThemeURI %>',
    'GitHub Theme URI: <%= info.themeClones.'+farbfamilie+'.GitHubThemeURI %>',
    'GitHub Issue URL: <%= info.repository.issues %>',
    'Author: <%= info.author.name %>',
    'Author URI: <%= info.author.url %>',
    'License: <%= info.license %>',
    'License URI: <%= info.licenseurl %>',
    'Tags: <%= info.tags %>',
    'Text Domain: <%= info.textdomain %>',
    '*/'].join('\n');


    // Copy files 
    function copyprocess() {
	    console.log(`Starting copy files to ${targetdir}`);
	   return src(['**/*', 
		"!.git{,/**}",
		"!node_modules{,/**}",
		"!.babelrc",
		"!.DS_Store",
		"!.gitignore",
		"!README.md",
		"!package.json",
		"!gulpfile.babel.js",
		"!package-lock.json"
	    ])
	    .pipe(dest(targetdir));
    };
    
    // Update color family in variables.scss 
    function setcolorfamily() {
	console.log(`  - Update color family in ${variablesfile} to ${farbfamilie}`);	
	return src([variablesfile])
		    .pipe(replace(/farbfamilie: '(.*)'/g, 'farbfamilie: \''+farbfamilie+'\''))
		    .pipe(dest(sassdir));  
    }
    
    // Update theme config to the theme type
    function setwebsite_usefaculty() {
	// find this entry and change it:
	// 'website_usefaculty'		=> '',
	// phil, med, nat, rw, tf
	console.log(`  - Update constfile family ${constfile} to ${farbfamilie}`);	
	return src([constfile])
		    .pipe(replace(/'website_usefaculty'\s*=>\s*'(.*)'/g, '\'website_usefaculty\' => \''+farbfamilie+'\''))
		    .pipe(dest(targetdir + 'functions/'));
    }
    
    // Copy theme screenshot in the new base directory
    function copyscreenshot() {
	var screenshot = targetdir + 'img/screenshots/screenshot-' + farbfamilie + '.png';
	console.log(`  - Copy screenshot ${screenshot} to ${targetdir}`);	
	
	return src([screenshot])
		    .pipe(rename("screenshot.png"))
		    .pipe(dest(targetdir));
    
    }

    
    // compile sass, use autoprefixer and minify results
    function buildbackendstyles() {
	  return src([targetdir +'css/sass/admin.scss', targetdir +'css/sass/editor-style.scss', targetdir +'css/sass/gutenberg.scss'])
	    .pipe(header(helpercssbanner, { info : info }))
	    .pipe(sass().on('error',  sass.logError))
	    .pipe(postcss([
		autoprefixer(),
		cssnano()
	    ]))
	    .pipe(dest(targetdir + 'css/' ))
    	    .pipe(touch());

	    console.log(`  - Backend styles created in ${targetdir}css`);	

    }
    
    
     // compile sass, use autoprefixer and minify results
    function buildproductivestyle() {
	
	var inputscss = targetdir + 'css/sass/base.scss';
	console.log(`  - Creating new CSS from SCSS-File ${inputscss} in ${targetdir}style.css`);	
	return src([inputscss])
	    .pipe(header(themebanner, { info : info }))
	    .pipe(sass().on('error',  sass.logError))
	    .pipe(postcss([
		autoprefixer(),
		cssnano()
	    ]))
	    .pipe(rename("style.css"))
	    .pipe(dest(targetdir))
    	    .pipe(touch());

    }
     
   
    const dothis = series(copyprocess,parallel(setcolorfamily,setwebsite_usefaculty,copyscreenshot),buildbackendstyles,buildproductivestyle);	
    dothis();
    cb();
    return;
}





/* 
 * SASS and Autoprefix CSS Files, without clean
 */
function sassautoprefixhelperfiles() {
    var plugins = [
        autoprefixer()
    ];
  return src(['css/sass/admin.scss', 'css/sass/editor-style.scss', 'css/sass/gutenberg.scss'])
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss(plugins))
    .pipe(dest('./css'))
    .pipe(touch());
    
   
}


/* 
 * SASS and Autoprefix CSS Files, without clean
 */
function sassautoprefixmainstyle() {
    var plugins = [
        autoprefixer()
    ];
  return src(['css/sass/base.scss'])
    .pipe(header(banner, { info : info }))
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss(plugins))
    .pipe(rename('style.css'))
    .pipe(dest('./'))
    .pipe(touch());
}




/* 
 * Compile all styles with SASS and clean them up 
 */
function buildhelperstyles() {
    var plugins = [
        autoprefixer(),
        cssnano()
    ];
  return src(['css/sass/admin.scss', 'css/sass/editor-style.scss', 'css/sass/gutenberg.scss'])
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss(plugins))
    .pipe(dest('./css'))
    .pipe(touch());
}


/* 
 * Compile all styles with SASS and clean them up 
 */
function buildmainstyle() {
    var plugins = [
        autoprefixer(),
        cssnano()
    ];
  return src(['css/sass/base.scss'])
   .pipe(header(banner, { info : info }))
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss(plugins))
    .pipe(rename('style.css'))
    .pipe(dest('./'))
    .pipe(touch());
}

function js() {
    return src(['./src/js/*.js','!./src/js/**/*.min.js'])
	.pipe(babel({
            presets: ['@babel/env']
	}))
	.pipe(uglify())
	.pipe(rename({ suffix: '.min' }))
	.pipe(dest(info.jsdir))
	.pipe(touch());
}


function updatepot()  {
  return src(['**/*.php', '!vendor/**/*.php'])
  .pipe(
      wpPot({
        domain: info.textdomain,
        package: info.name,
	team: info.author,
 	bugReport: info.repository.issues,
	ignoreTemplateNameHeader: true
      })
    )
  .pipe(dest(`languages/${info.textdomain}.pot`))
  .pipe(touch());;
};

/*
 * Update Version on Patch-Level
 *  (All other levels we are doing manually; This level has to update automatically on each build)
 */


function upversionpatch() {
    var newVer = semver.inc(info.version, 'patch');
    return src(['./package.json', './' + info.maincss])
        .pipe(bump({
            version: newVer
        }))
        .pipe(dest('./'))
	.pipe(touch());
};


/*
 * Update DEV Version on prerelease level.
 *  Reason: in the Theme function, we will recognise the prerelease version by its syntax. 
 *  This will allow the theme automatically switch to the non-minified-files instead of
 *   the minified versions.
 *   In other words: If we use dev, the theme wil load script files without ".min.".  
 */
function devversion() {
    var newVer = semver.inc(info.version, 'prerelease');
    return src(['./package.json', './' + info.maincss])
        .pipe(bump({
            version: newVer
        }))
	.pipe(dest('./'))
	.pipe(touch());;
};



/* 
 * CSS Validator
 */


function validatecss() { 
  return src(['./style.css'])
    .pipe(cssvalidate())
    .pipe(map(function(file, done) {
      if (file.contents.length == 0) {
        console.log('Success: ' + file.path);
        console.log('No errors or warnings\n');
      } else {
        var results = JSON.parse(file.contents.toString());
        results.errors.forEach(function(error) {
	    console.log('Error: ' + error.errorType + ', line ' + error.line);
	    console.log('  Kontext: ' + error.context);
	    console.log('  ' + error.message);
        });
       
      }
      done(null, file);
    }));
};


exports.js = js;
exports.pot = updatepot;
exports.devversion = devversion;
exports.validatecss = validatecss;


exports.clone = cloneTheme;
var dev = series(sassautoprefixhelperfiles, sassautoprefixmainstyle, devversion, validatecss);
exports.dev = dev;
exports.build = series(buildhelperstyles, buildmainstyle, upversionpatch);

exports.default = dev;
