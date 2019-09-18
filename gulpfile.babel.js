/* 
 * Gulp Builder for WordPress Theme FAU-Einrichtungen
 * Since Version 1.11.24
 */

import { src, dest, series } from 'gulp';
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


export const devmaps = series(stylessourcemaps, copystyle);
export const dev = series(autoprefix, copystyle);
export const build = series(buildstyles, copystyle);
export default dev;