/* 
 * Gulp Builder for WordPress Theme FAU-Einrichtungen
 */

import { src, dest, series  } from 'gulp';
import yargs from 'yargs';
import sass from 'gulp-sass';
import rename from 'gulp-rename';
import postcss from 'gulp-postcss';
import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'autoprefixer';
import info from "./package.json";
import wpPot from "gulp-wp-pot";
import bump from "gulp-bump";
import semver from "semver";
import replace from "gulp-replace";
import concat from "gulp-concat";
import cssnano from "cssnano";
import header from 'gulp-header';


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

   
   
    // Copy files 
    function copyprocess() {
	return new Promise(function(resolve,reject) {
	    console.log(`Starting copy files to ${targetdir}`);
	
	    src(['**/*', 
		"!.git{,/**}",
		"!node_modules{,/**}",
		"!.babelrc",
		"!.DS_Store",
		"!.gitignore",
		"!gulpfile.babel.js",
		"!package-lock.json"
	    ])
	    .on('error', reject)
	    .pipe(dest(targetdir))
	    .on('end', resolve);
    })};
    
    // Update color family in variables.scss 
    
    function setcolorfamily() {
	return new Promise(function(resolve, reject) {
	    src([variablesfile])
		    .pipe(replace(/farbfamilie: '(.*)'/g, 'farbfamilie: \''+farbfamilie+'\''))
		    .on('error', reject)
		    .pipe(dest(sassdir))
		    .on('end', resolve);

	    console.log(`  - Updatet color family in ${variablesfile} to ${farbfamilie}`);	
	});
    }
    
    // Update theme config to the theme type
    function setwebsite_usefaculty() {
	// find this entry and change it:
	// 'website_usefaculty'		=> '',
	// phil, med, nat, rw, tf

	return new Promise(function(resolve, reject) {
	    src([constfile])
		    .pipe(replace(/'website_usefaculty'\s*=>\s*'(.*)'/g, '\'website_usefaculty\' => \''+farbfamilie+'\''))
		    .on('error', reject)
		    .pipe(dest(targetdir + 'functions/'))
		    .on('end', resolve);

	    console.log(`  - Updatet constfile family ${constfile} to ${farbfamilie}`);	
	});
    }
    
    // Copy theme screenshot in the new base directory
    function copyscreenshot() {
	var screenshot = targetdir + 'img/screenshots/screenshot-' + farbfamilie + '.png';
	
	
	return new Promise(function(resolve, reject) {
	    src([screenshot])
		    .pipe(rename("screenshot.png"))
		    .pipe(dest(targetdir))
		    .on('end', resolve);

	    console.log(`  - Copy screenshot ${screenshot} to ${targetdir}`);	
	});
    }
    
     // compile sass, use autoprefixer and minify results
    function buildproductivestyles() {
	return new Promise(function(resolve, reject) {
	    var plugins = [
		autoprefixer(),
		cssnano()
	    ];
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

    var helpercssbanner = [
    '/*!',
    '* Backend-CSS for Theme:',
    '* Theme Name: <%= info.themeClones.'+farbfamilie+'.name %>',
    '* Version: <%= info.version %>',
    '* GitHub Issue URL: <%= info.repository.issues %>',
    '*/'].join('\n');
	    
	  src([targetdir +'css/sass/admin.scss', targetdir +'css/sass/editor-style.scss', targetdir +'css/sass/gutenberg.scss'])
	    .on('error', reject)
	    .pipe(header(helpercssbanner, { info : info }))
	    .pipe(sass().on('error',  sass.logError))
	    .pipe(postcss(plugins))
	    .pipe(dest(targetdir + 'css/' ))
	    .on('end', resolve);	
    
    
     console.log(`  - Styles created in ${targetdir}css`);	
     
     
     src([targetdir + 'css/sass/base.scss'])
	    .on('error', reject)
	    .pipe(header(themebanner, { info : info }))
	    .pipe(sass().on('error',  sass.logError))
	    .pipe(postcss(plugins))
	    .pipe(rename("style.css"))
	    .pipe(dest(targetdir))
	    .on('end', resolve);	
    
	    console.log(`  - style.css created in ${targetdir}`);	
	});
    }
    
    
    copyprocess()
    .then(function() {
	setcolorfamily();
	setwebsite_usefaculty();
	copyscreenshot();
	return;
    }).then(function() {
	buildproductivestyles();
	return;
    }).catch(function(error) {
	console.log(error);
    }).then(function() {
	console.log(`Create new theme ${farbfamilie}, Version ${info.version} in ${targetdir}`);
    });
    
    	
    cb();
    return;
}





/* 
 * SASS and Autoprefix CSS Files, without clean
 */
export const sassautoprefix = () => {
    var plugins = [
        autoprefixer()
    ];
  return src(['css/sass/base.scss', 'css/sass/admin.scss', 'css/sass/editor-style.scss', 'css/sass/gutenberg.scss'])
    .pipe(header(banner, { info : info }))
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss(plugins))
    .pipe(dest('./css'));
}


/* 
 *SASS and Autoprefix CSS Files, without clean and make sourcemaps. 
 */
export const sassautoprefixmaps = () => {
     var plugins = [
        autoprefixer()
    ];
  return src(['css/sass/base.scss', 'css/sass/admin.scss', 'css/sass/editor-style.scss', 'css/sass/gutenberg.scss'])
    .pipe(sourcemaps.init())
    .pipe(header(banner, { info : info }))
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss(plugins))
    .pipe(sourcemaps.write())
    .pipe(dest('./css'));
}


/* 
 * Compile all styles with SASS and clean them up 
 */
export const buildstyles = () => {
    var plugins = [
        autoprefixer(),
        cssnano()
    ];
  return src(['css/sass/base.scss', 'css/sass/admin.scss', 'css/sass/editor-style.scss', 'css/sass/gutenberg.scss'])
   .pipe(header(banner, { info : info }))
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss(plugins))
    .pipe(dest('./css'));
}

/*
 * Copy style.css to the theme root
 */
export const copystyle = () => {
  return src('css/base.css')
    .pipe(rename('style.css'))
    .pipe(dest('./'));
}

export const pot = () => {
  return src("**/*.php")
  .pipe(
      wpPot({
        domain: info.textdomain,
        package: info.name,
	team: info.author
 
      })
    )
  .pipe(dest(`languages/${info.textdomain}.pot`));
};

/*
 * Update Version on Patch-Level
 *  (All other levels we are doing manually; This level has to update automatically on each build)
 */

export const upversionpatch = () => {
  var newVer = semver.inc(info.version, 'patch');
    
  return src(['./package.json','./style.css'])
    .pipe(bump({version: newVer}))
    .pipe(dest('./'));
};



/*
 * Update DEV Version on prerelease level.
 *  Reason: in the Theme function, we will recognise the prerelease version by its syntax. 
 *  This will allow the theme automatically switch to the non-minified-files instead of
 *   the minified versions.
 *   In other words: If we use dev, the theme wil load script files without ".min.".  
 */
export const devversion = () => {
   var newVer = semver.inc(info.version, 'prerelease');
    
  return src(['./package.json','./style.css'])
    .pipe(bump({version: newVer}))
    .pipe(dest('./'));
};



export const clone = cloneTheme;
export const maps = series(sassautoprefixmaps, copystyle, devversion);
export const dev = series(sassautoprefix, copystyle, devversion);
export const build = series(buildstyles, copystyle,upversionpatch);
export default dev;