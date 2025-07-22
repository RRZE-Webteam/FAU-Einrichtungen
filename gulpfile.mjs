"use strict";
/*
 * Gulp Builder for WordPress Theme FAU-Einrichtungen
 */
import { src, dest, watch, series, parallel } from 'gulp';
import { readFileSync } from 'fs';
import gulpSass from 'gulp-sass';
import * as sassCompiler from 'sass';

import sourcemaps from 'gulp-sourcemaps';
import uglify from 'gulp-uglify';
import bump from 'gulp-bump';
import semver from 'semver';
import wpPot from 'gulp-wp-pot';
import touch from 'gulp-touch-cmd';
import header from 'gulp-header';
import concat from 'gulp-concat';
import replace from 'gulp-replace';
import rename from 'gulp-rename';
import map from 'map-stream';
import debug from 'gulp-debug';
import file from 'gulp-file';

import yargs from 'yargs/yargs';
import { hideBin } from 'yargs/helpers';
const clonetarget = yargs(hideBin(process.argv)).argv.target;

const sass = gulpSass(sassCompiler);




// Lade die package.json-Datei und füge dessen Werte in die 
// globale Variable info ein.
let info;

function initialize() {
    const data = readFileSync('./package.json', 'utf-8');
    info = JSON.parse(data);
    console.log("Info erfolgreich geladen:", info); // Debug-Ausgabe
}

// Initialisierung direkt beim Laden des Skripts
initialize();


// Options for compiling Dart SASS in Dev Versions
const sassDevOptions = {
    indentWidth: 4, 
    quietDeps: true, 
    precision: 3, 
    sourceComments: true,  
    silenceDeprecations: ['legacy-js-api'] 
};

const sassProdOptions = {
    quietDeps: true, 
    outputStyle: 'compressed',
    silenceDeprecations: ['legacy-js-api'] 
};


/**
 * Template for banner to add to file headers
 */

var banner = [
  "/*!",
  "Theme Name: <%= info.name %>",
  "Version: <%= info.version %>",
  "Requires at least: <%= info.compatibility.wprequires %>",
  "Tested up to: <%= info.compatibility.wptestedup %>",
  "Requires PHP: <%= info.compatibility.phprequires %>",
  "Description: <%= info.description %>",
  "Theme URI: <%= info.repository.url %>",
  "GitHub Theme URI: <%= info.repository.url %>",
  "GitHub Issue URL: <%= info.repository.issues %>",
  "Author: <%= info.author.name %>",
  "Author URI: <%= info.author.url %>",
  "License: <%= info.license %>",
  "License URI: <%= info.licenseurl %>",
  "Tags: <%= info.tags %>",
  "Text Domain: <%= info.textdomain %>",
  "*/",
].join("\n");

var editorcssbanner = [
    "/*!",
    "* Editor CSS for Theme:",
    "* Theme Name: <%= info.name %>",
    "* Version: <%= info.version %>",
    "* GitHub Issue URL: <%= info.repository.issues %>",
    "*/",
  ].join("\n");
/**
 * Create Clone for a given theme
 */

function cloneTheme(cb) {
      var targetdir = "";
      var farbfamilie = "zuv";
      var theme = yargs(hideBin(process.argv)).argv.theme;
      var builddir = yargs(hideBin(process.argv)).argv.builddir;
      var themedatadir = info.source.theme_data;
      if (builddir === undefined) {
	builddir = "../build/";
      }

      switch (theme) {
	case "zuv":
	  targetdir = builddir + "FAU-ZUV/";
	  farbfamilie = "zuv";
	
	  break;
	case "phil":
	  targetdir = builddir + "FAU-Philfak/";
	  farbfamilie = "phil";
	  themedatadir = info.themeClones.phil.theme_data;
	  break;
	case "med":
	  targetdir = builddir + "FAU-Medfak/";
	  farbfamilie = "med";
	   themedatadir = info.themeClones.med.theme_data;
	  break;
	case "rw":
	  targetdir = builddir + "FAU-RWFak/";
	  farbfamilie = "rw";
	   themedatadir = info.themeClones.rw.theme_data;
	  break;
	case "tf":
	  targetdir = builddir + "FAU-Techfak/";
	  farbfamilie = "tf";
	   themedatadir = info.themeClones.tf.theme_data;
	  break;
	case "nat":
	  targetdir = builddir + "FAU-Natfak/";
	  farbfamilie = "nat";
	  themedatadir = info.themeClones.nat.theme_data;

	  break;
	default:
	  console.log(
	    `No valid theme defined. Please use argument:   gulp clone --theme=name   , with name=(phil|rw|tf|nat|med)`
	  );
	  break;
      }

      if (targetdir === "") {
	cb();
	return;
      }
      console.log(`Building theme for: ${theme}`);
      console.log(`   Target directory: ${targetdir}`);
      console.log(`   Color: ${farbfamilie}`);

      var sassdir = targetdir + info.source.sass;
      var variablesfile = sassdir + "_variables.scss";
      var constfile = targetdir + "functions/constants.php";
     

      var editorcssbanner = [
	"/*!",
	"* Editor CSS for Theme:",
	"* Theme Name: <%= info.themeClones." + farbfamilie + ".name %>",
	"* Version: <%= info.version %>",
	"* GitHub Issue URL: <%= info.repository.issues %>",
	"*/",
      ].join("\n");
      var helpercssbanner = [
	"/*!",
	"* Backend-CSS for Theme:",
	"* Theme Name: <%= info.themeClones." + farbfamilie + ".name %>",
	"* Version: <%= info.version %>",
	"* GitHub Issue URL: <%= info.repository.issues %>",
	"*/",
      ].join("\n");

      var themebanner = [
	"/*!",
	"Theme Name: <%= info.themeClones." + farbfamilie + ".name %>",
	"Version: <%= info.version %>",
	"Requires at least: <%= info.compatibility.wprequires %>",
	"Tested up to: <%= info.compatibility.wptestedup %>",
	"Requires PHP: <%= info.compatibility.phprequires %>",
	"Description: <%= info.themeClones." + farbfamilie + ".description %>",
	"Theme URI: <%= info.themeClones." + farbfamilie + ".GitHubThemeURI %>",
	"GitHub Theme URI: <%= info.themeClones." +
	  farbfamilie +
	  ".GitHubThemeURI %>",
	"GitHub Issue URL: <%= info.repository.issues %>",
	"Author: <%= info.author.name %>",
	"Author URI: <%= info.author.url %>",
	"License: <%= info.license %>",
	"License URI: <%= info.licenseurl %>",
	"Tags: <%= info.tags %>",
	"Text Domain: <%= info.textdomain %>",
	"*/",
      ].join("\n");


     var readme_banner = [
	"=== Theme Name: <%= info.themeClones." + farbfamilie + ".name %> ===",
	"Version: <%= info.version %>",
	"Requires at least: <%= info.compatibility.wprequires %>",
	"Tested up to: <%= info.compatibility.wptestedup %>",
	"Requires PHP: <%= info.compatibility.phprequires %>",
	"Theme URI: <%= info.themeClones." + farbfamilie + ".GitHubThemeURI %>",
	"GitHub Theme URI: <%= info.themeClones." +
	  farbfamilie +
	  ".GitHubThemeURI %>",
	"GitHub Issue URL: <%= info.repository.issues %>",
	"Author: <%= info.author.name %>",
	"Author URI: <%= info.author.url %>",
	"License: <%= info.license %>",
	"License URI: <%= info.licenseurl %>",
	"Tags: <%= info.tags %>",
	"Text Domain: <%= info.textdomain %>",
	"",
	"<%= info.themeClones." + farbfamilie + ".description %>"
      ].join("\n");


	// Funktion, um die Textdatei zu erstellen und zu befüllen
	function createReadmeTxt() {
	    var readmetextfile = info.target.readme_txt;
	    console.log(`  - creating ${readmetextfile} in ${targetdir}`);
	    return file(readmetextfile, '', { src: true }) // Erstellt eine leere Readme.txt
		.pipe(header(readme_banner, { info: info }))      // Fügt den Text-Banner ein
		.pipe(dest(targetdir));   // Speichert die Datei im "target"-Verzeichnis
	}
	// Copy static readme Markdown in the new base directory
	function copyReadmeMD() {
	    var readmemd = themedatadir + info.source.readme_md;
	    console.log(`  - Copy static Readme ${readmemd} to ${targetdir}`);

	    return src([readmemd])
		.pipe(dest(targetdir))
		.pipe(touch());;
	}

	// Copy files
	function copyprocess() {
	  console.log(`Starting copy files to ${targetdir}`);
	  return src([
	    "**/*",
	    "!.git{,/**}",
	    "!node_modules{,/**}",
	    "!.babelrc",
	    "!.DS_Store",
	    "!.gitignore",
	    "!README.md",
	    "!package.json",
	    "!package-lock.json",
	  ], { encoding: false })
		  .pipe(dest(targetdir));
	}

	// Update color family in variables.scss
	function setcolorfamily() {
	  console.log(
	    `  - Update color family in ${variablesfile} to ${farbfamilie}`
	  );
	  return src([variablesfile])
	    .pipe(
	      replace(/farbfamilie: '(.*)'/g, "farbfamilie: '" + farbfamilie + "'")
	    )
	    .pipe(dest(sassdir));
	}

	// Update theme config to the theme type
	function setwebsite_usefaculty() {
	  // find this entry and change it:
	  // 'website_usefaculty'		=> '',
	  // phil, med, nat, rw, tf
	  console.log(`  - Update constfile family ${constfile} to ${farbfamilie}`);
	  return src([constfile])
	    .pipe(
	      replace(
		/'website_usefaculty'\s*=>\s*'(.*)'/g,
		"'website_usefaculty' => '" + farbfamilie + "'"
	      )
	    )
	    .pipe(dest(targetdir + "functions/"));
	}

	// Copy theme screenshot in the new base directory
	function copyscreenshot() {
	  var screenshot = themedatadir + info.source.screenshot;
	  console.log(`  - Copy screenshot ${screenshot} to ${targetdir}`);

	  return src([screenshot], {encoding: false})
	    .pipe(dest(targetdir));
	}

	// Copy social media icons in the new base directory
	function copysocialmedia() {
	  var srcsocialmedia = themedatadir + info.source.favicons + "**";
	  var targetsocialmedia = targetdir + info.target.favicons;

	  console.log(
	    `  - Copy Social Media Icons ${srcsocialmedia} to ${targetsocialmedia}`
	  );
	  return src([srcsocialmedia], { encoding: false }).pipe(dest(targetsocialmedia));
	}

	// Create Backend Styles 
	function buildbackendstyles() {
	  return src([targetdir + info.source.sass + "fau-theme-admin.scss"])
	    .pipe(header(helpercssbanner, { info: info }))
	    .pipe(sass(sassProdOptions).on("error", sass.logError))
	    .pipe(dest(targetdir + info.target.css))
	    .pipe(touch());

	  console.log(`  - Backend styles created in ${targetdir}css`);
	}
	 // Create  Block Editor Styles
	function buildblockeditorstyles() {    
	  return src([targetdir + info.source.sass + "fau-theme-blockeditor.scss"])
	  .pipe(header(editorcssbanner, { info: info }))
	  .pipe(sass(sassProdOptions).on("error", sass.logError))
	  .pipe(dest(targetdir + info.target.css))
	  .pipe(touch());

	  console.log(`  - Block editor styles created in ${targetdir}css`);
	}

	// Classic Editor Styles 
	function buildclassiceditorstyles() {

	  return src([targetdir + info.source.sass + "fau-theme-classiceditor.scss"])
	    .pipe(header(editorcssbanner, { info: info }))
	    .pipe(sass(sassProdOptions).on("error", sass.logError))
	    .pipe(dest(targetdir + info.target.css))
	    .pipe(touch());

	    console.log(`  - Classic editor styles created in ${targetdir}css`);
	}


     // Create Backend Styles for dev
      function buildproductivestyle() {
	var inputscss = targetdir + info.source.sass + "fau-theme-style.scss";
	console.log(
	  `  - Creating new CSS from SCSS-File ${inputscss} in ${targetdir}style.css`
	);
	return src([inputscss])
	  .pipe(header(themebanner, { info: info }))
	  .pipe(sass(sassProdOptions).on("error", sass.logError))
	  .pipe(rename(info.maincss))
	  .pipe(dest(targetdir))
	  .pipe(touch());
      }

      const dothis = series(
	copyprocess,	
	setcolorfamily,
	setwebsite_usefaculty,
	copyscreenshot,
	copysocialmedia,
	createReadmeTxt,
	copyReadmeMD,

	buildbackendstyles,
	buildproductivestyle,
	buildblockeditorstyles,
	buildclassiceditorstyles
      );

      dothis();
      cb();
      return;
}

/*
 * SASS and Autoprefix CSS Files, without clean
 */
function devbuildbackendstyles() {
    return src([info.source.sass + "fau-theme-admin.scss"])
      .pipe(header(editorcssbanner, { info: info }))
      .pipe(sass(sassDevOptions).on("error", sass.logError))
      .pipe(dest(info.target.css))
      .pipe(touch());
}

/*
 * Compile all styles with SASS and clean them up
 */
function buildbackendstyles() {
    return src([info.source.sass + "fau-theme-admin.scss"])
	.pipe(header(editorcssbanner, { info: info }))
	.pipe(sass(sassProdOptions).on("error", sass.logError))
	.pipe(dest(info.target.css))
	.pipe(touch());
}

/*
 *  Main Styles for prod
 */
function buildmainstyle() {
    return src([info.source.sass + "fau-theme-style.scss"])
	.pipe(header(banner, { info: info }))
	.pipe(sass(sassProdOptions).on("error", sass.logError))
	.pipe(rename(info.maincss))
	.pipe(dest("./"))
	.pipe(touch());
}

/*
 * Main Styles for Dev
 */
function devbuildmainstyle() {
    return src([info.source.sass + "fau-theme-style.scss"])
	.pipe(header(banner, { info: info }))
	.pipe(sourcemaps.init())
	.pipe(sass(sassDevOptions).on("error", sass.logError))
	.pipe(sourcemaps.write())
	.pipe(rename(info.maincss))
	.pipe(dest("./"))
	.pipe(touch());
}

/*
 * Print Styles 
 */
function buildprintstyle() {
    return src([info.source.sass + "fau-theme-print.scss"])
	.pipe(header(banner, { info: info }))
	.pipe(sass(sassProdOptions).on("error", sass.logError))
	.pipe(rename(info.printcss))
	.pipe(dest("./"))
	.pipe(touch());
}

// Block Editor Styles for Prod
function buildeditorstyles() {

  return src([info.source.sass + "fau-theme-blockeditor.scss"])
    .pipe(header(editorcssbanner, { info: info }))
    .pipe(sass(sassProdOptions).on("error", sass.logError))
    .pipe(dest(info.target.css))
    .pipe(touch());
}
// Block Editor Styles for Dev
function devbuildeditorstyles() {

    return src([info.source.sass + "fau-theme-blockeditor.scss"])
    .pipe(header(editorcssbanner, { info: info }))
    .pipe(sass(sassDevOptions).on("error", sass.logError))
    .pipe(dest(info.target.css))
    .pipe(touch());
}
// Classic Editor Styles for Prod
function buildclassiceditorstyles() {

    return src([info.source.sass + "fau-theme-classiceditor.scss"])
    .pipe(header(editorcssbanner, { info: info }))
    .pipe(sass(sassProdOptions).on("error", sass.logError))
    .pipe(dest(info.target.css))
    .pipe(touch());
}
// Classic Editor Styles for Dev
function devbuildclassiceditorstyles() {

    return src([info.source.sass + "fau-theme-classiceditor.scss"])
    .pipe(header(editorcssbanner, { info: info }))
    .pipe(sass(sassDevOptions).on("error", sass.logError))
    .pipe(dest(info.target.css))
    .pipe(touch());
}

// Main JS
function bundleadminjs() {
    return src([
      info.source.js + "admin/admin.js",
      //	info.source.js + 'admin/banner-logo-link-widget.js',
      info.source.js + "admin/classic-editor-templateswitch.js"
    ])
    .pipe(concat(info.adminjs))
    .pipe(uglify())
    .pipe(dest(info.target.js))
    .pipe(touch());
}

// we depart customizerjs from admin js, due to js conflicts
function makecustomizerjs() {
    return src([info.source.js + "admin/customizer-range-value-control.js"])
      .pipe(uglify())
      .pipe(rename("fau-theme-customizer-range-value-control.min.js"))
      .pipe(dest(info.target.js))
      .pipe(touch());
}
// we depart wplink from admin js, due to needed extra vals
function makewplinkjs() {
    return src([info.source.js + "admin/rrze-wplink.js"])
      .pipe(uglify())
      .pipe(rename("fau-theme-wplink.min.js"))
      .pipe(dest(info.target.js))
      .pipe(touch());
}
function bundlemainjs() {
    return src([
      info.source.js + "main/jquery.fancybox.js",
      info.source.js + "main/console-errors.js",
      info.source.js + "main/shortcode-organigram.js",
      info.source.js + "main/main.js",
    ])
    .pipe(concat(info.mainjs))
    .pipe(uglify())
    .pipe(dest(info.target.js))
    .pipe(touch());
}
function makeslickjs() {
    return src([info.source.js + "main/slick.js"])
      .pipe(uglify())
      .pipe(rename("fau-theme-slick.min.js"))
      .pipe(dest(info.target.js))
      .pipe(touch());
}

function makecustomblockjs() {
    return src([info.source.js + "main/fau-costum-image-block.js"])
      .pipe(uglify())
      .pipe(rename("fau-costum-image-block.min.js"))
      .pipe(dest(info.target.js))
      .pipe(touch());
}

function makeblockeditorjs() {
    return src(["src/js/blockeditor/deregister-blockstyles.js"])
      .pipe(uglify())
      .pipe(rename("fau-theme-register-blockstyles.min.js"))
      .pipe(dest(info.target.js))
      .pipe(touch());
}

function updatepot() {
  return src(["**/*.php", "!vendor/**/*.php"])
    .pipe(
      wpPot({
        domain: info.textdomain,
        package: info.name,
        team: info.author,
        bugReport: info.repository.issues,
        ignoreTemplateNameHeader: true,
      })
    )
    .pipe(dest("languages/" + info.textdomain + ".pot"))
    .pipe(touch());
}

// Set debugmode true
function set_debugmode() {
    // find this entry and change it:
    // 'website_usefaculty'		=> '',
    // phil, med, nat, rw, tf
    var constfile = "./functions/constants.php";
    console.log(`  - Set Debugmode true in constfile ${constfile}`);
    return src([constfile])
      .pipe(replace(/'debugmode'\s*=>\s*(.*),/g, "'debugmode' => true,"))
      .pipe(dest("./functions/"));
}

// Set debugmode true
function unset_debugmode() {
    // find this entry and change it:
    // 'website_usefaculty'		=> '',
    // phil, med, nat, rw, tf
    var constfile = "./functions/constants.php";
    console.log(`  - Set Debugmode false in constfile ${constfile}`);
    return src([constfile])
      .pipe(replace(/'debugmode'\s*=>\s*(.*),/g, "'debugmode' => false,"))
      .pipe(dest("./functions/"));
}

/*
 * Update Version on Patch-Level
 *  (All other levels we are doing manually; This level has to update automatically on each build)
 */
function upversionpatch() {
    var newVer = semver.inc(info.version, "patch");
    return src([
      "./package.json",
      "./README.md",
      "./readme.txt",
      "./" + info.maincss,
    ])
    .pipe(
	bump({
	  version: newVer,
	})
    )
    .pipe(dest("./"))
    .pipe(touch());
}

/*
 * Update DEV Version on prerelease level.
 *  Reason: in the Theme function, we will recognise the prerelease version by its syntax.
 *  This will allow the theme automatically switch to the non-minified-files instead of
 *   the minified versions.
 *   In other words: If we use dev, the theme wil load script files without ".min.".
 */
function devversion() {
    var newVer = semver.inc(info.version, "prerelease");
    return src([
      "./package.json",
      "./README.md",
      "./readme.txt",
      "./" + info.maincss,
    ])
    .pipe(
      bump({
        version: newVer,
      })
    )
    .pipe(dest("./"))
    .pipe(touch());
}

// Export der einzelnen Funktionen
export { 
    updatepot as pot,
    devversion,
    bundlemainjs,
    bundleadminjs,
    makeslickjs,
    makecustomblockjs,
    makeblockeditorjs,
    makecustomizerjs,
    makewplinkjs,
    cloneTheme as clone,
    buildmainstyle,
    buildprintstyle,
    devbuildmainstyle,
    set_debugmode as debugmode,
    unset_debugmode as nodebug
};

// Definition von `js` und `dev` als Variablen
const js = series(
    bundlemainjs,
    makeslickjs,
    makecustomblockjs,
    makeblockeditorjs,
    bundleadminjs,
    makecustomizerjs,
    makewplinkjs
);

const dev = series(
    devbuildbackendstyles,
    devbuildmainstyle,
    buildprintstyle,
    devbuildeditorstyles,
    devbuildclassiceditorstyles,
    js,
    devversion
);



// Export weiterer Tasks
export const cssdev = series(
    devbuildbackendstyles,
    devbuildeditorstyles,
    devbuildclassiceditorstyles,
    devbuildmainstyle,
    buildprintstyle
);

export const css = series(
    devbuildbackendstyles,
    devbuildeditorstyles,
    devbuildclassiceditorstyles,
    devbuildmainstyle,
    buildprintstyle
);

export { js, dev };

export const build = series(
    buildbackendstyles,
    buildmainstyle,
    buildprintstyle,
    buildeditorstyles,
    buildclassiceditorstyles,
    js,
    upversionpatch
);

// Temporärer Watch-Task
export function watchTask() {
  watch(
    info.source.sass + "**/*.scss",
    { ignoreInitial: false },
    buildprintstyle
  );
}

export { watchTask as watch };

// Export der Standardaufgabe
export default dev;
