/* 
 * Gulp Builder for WordPress Theme FAU-Einrichtungen
 */

import { src, dest, series  } from 'gulp';
import yargs from 'yargs';
import sass from 'gulp-sass';
import cleanCss from 'gulp-clean-css';
import gulpif from 'gulp-if';
import rename from 'gulp-rename';
import postcss from 'gulp-postcss';
import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'autoprefixer';
import info from "./package.json";
import wpPot from "gulp-wp-pot";
import bump from "gulp-bump";
import semver from "semver";
import replace from "gulp-replace";

// const PRODUCTION = yargs.argv.prod;

    
/* 
 * Compile all styles with SASS and make sourcemaps. Without postfix
 */
export const stylessourcemaps = () => {
  return src(['css/sass/base.scss', 'css/sass/admin.scss', 'css/sass/editor-style.scss', 'css/sass/gutenberg.scss'])
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(sourcemaps.write())
    .pipe(dest('css'));
}

/* 
 * Only SASS and Autoprefix style.css File; Need for debugging
 */
export const autoprefix = () => {
  return src('css/sass/base.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss([ autoprefixer ]))
  //  .pipe(cleanCss({compatibility:'ie9'}))
    .pipe(dest('css'));
}

/* 
 * Compile all styles with SASS and also make the autoprefixer run on them without sourcemaps
 */
export const buildstyles = () => {
  return src(['css/sass/base.scss', 'css/sass/admin.scss', 'css/sass/editor-style.scss', 'css/sass/gutenberg.scss'])
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss([ autoprefixer ]))
    .pipe(cleanCss({compatibility:'ie9'}))
    .pipe(dest('css'));
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
    
  return src(['./package.json'])
    .pipe(bump({version: newVer}))
    .pipe(dest('./'));
};

export const upthemeversion = () => {
   var newVer = semver.inc(info.version, 'patch');
    
  return src(['style.css'])
    .pipe(bump({version: newVer}))
    .pipe(dest('./'));
};

/*
 * Update Version Number in Variables File.
 * DANGER ZONE!
 * If you run this job, this may force potential IDE watcher to recompile
 * the SASS files and so overwriting our styles again! In worst case,
 * style css will be written in the same time by two jobs.
 * Better do this manually at first part, before compiling SASS files anew.
 * 
 * Would be better if we remove the version number from the file later.
 */
export const upthemesassvarversion = () => {
  var newVer = semver.inc(info.version, 'patch');
    
  return src(['css/sass/_variables.scss'])
    .pipe(replace(/\$version: '(.*)'/g, '$version: \''+newVer+'\''))
    .pipe(dest('css/sass/'));
    
};


export const versionup = series(upversionpatch,upthemeversion);
export const devmaps = series(stylessourcemaps, copystyle);
export const dev = series(autoprefix, copystyle, pot);
export const build = series(buildstyles, copystyle,upversionpatch,upthemeversion);
export default dev;