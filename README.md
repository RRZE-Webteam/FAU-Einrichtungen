# FAU-Einrichtungen

Wordpress-Theme für zentrale Einrichtungen der Friedrich-Alexander-Universität Erlangen-Nürnberg (FAU)

Eine Dokumentation kann unter https://wordpress.rrze.fau.de  gefunden werden.

## Download 

GITHub-Repo: https://github.com/RRZE-Webteam/FAU-Einrichtungen


## Autor 
RRZE-Webteam , http://www.rrze.fau.de

## Copryright

GNU General Public License (GPL) Version 2 


## Verwendete Libraries und Sourcen

* Font Awesome 4.7 by Dave Gandy - http://fontawesome.io. 
  License - http://fontawesome.io/license (Font: SIL OFL 1.1, CSS: MIT License)
* Font Roboto, https://www.fontsquirrel.com/license/roboto
  Apache License, Version 2.0, January 2004
* fancyBox v2.1.5 fancyapps.com 
* jQuery carouFredSel 6.2.1, https://dev7studios.com/
* jQuery FlexSlider v2.3.0
* hoverIntent v1.8.0
* Bootstrap 3.3.7, http://getbootstrap.com/



## Feedback

Please use github for submitting new features or bugs:
 https://github.com/RRZE-Webteam/FAU-Einrichtungen/issues

or send an email to 
 webmaster@rrze.fau.de



## Entwickler-Hinweise

### CSS-Anweisungen

Die CSS Anweisungen werden mittels SASS erzeugt. Hierzu werden im Verzeichnis
  /css/sass/
alle notwendigen SASS und SCSS Dateien abgelegt.
Die zentrale CSS-Datei style.css wird bei der SASS-Compilierung im 
Hauptverzeichnis des Themes abgelegt. Die CSS-Datei für das Backend wird
dagegen im Unterverzeichnis /css abfelegt.

SASS-Watcher:
1. Eingabequelle:   /css/sass/base.scss    -   Ausgabeort:     /style.css

2. Eingabequelle:  /css/sass/  -   Ausgabeort:     /css

Mit Compiler-Option soll im prdokutiven Betrieb die erzeigte CSS-Datei kompimiert 
sein. Außerdem sind Source-Map Dateien nicht benötigt. Die dafür notwendige 
Compiler-Argumente sind daher:
   --style compressed  --sourcemap=none

 