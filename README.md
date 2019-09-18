# FAU-Einrichtungen

Wordpress-Theme für zentrale Einrichtungen der Friedrich-Alexander-Universität Erlangen-Nürnberg (FAU)


## Download 

GITHub-Repo: https://github.com/RRZE-Webteam/FAU-Einrichtungen


## Autor 
RRZE-Webteam , http://www.rrze.fau.de

## Copryright

GNU General Public License (GPL) Version 3


## Verwendete Libraries und Sourcen

* Font Awesome 4.7 by Dave Gandy - http://fontawesome.io. 
  License - http://fontawesome.io/license (Font: SIL OFL 1.1, CSS: MIT License)
* Font Roboto, https://www.fontsquirrel.com/license/roboto
  Apache License, Version 2.0, January 2004
* Slick Slider v1.9
* Bootstrap 3.3.7, http://getbootstrap.com/



## Feedback

Please use github for submitting new features or bugs:
 https://github.com/RRZE-Webteam/FAU-Einrichtungen/issues

or send an email to 
 webmaster@rrze.fau.de

## Dokumentation

Eine Dokumentation des THemes und dessen Funktionen findet sich unter der Adresse https://wordpress.rrze.fau.de  


## Entwickler-Hinweise

## Testportal

Testseiten und Hinweise zur Neuentwicklung finden sich auf dem Testportal https://www.beta.wordpress.rrze.fau.de/ Dort finden sich auch weitere Hinweise zur Entwicklung.

### Gulp Skript zur Compilierung

Im Projekt befindet sich eine ```package.json``` und ein Gulpskript ```gulpfile.babel.js```.
Durch Ausführung mit NodeJS können 
- die SASS-Dateien zu den notwendigen CSS compiliert werden. 
- Vendor-Prefixes in den CSS-Dateien ergönzt werden,
- CSS-Dateien minifiziert werden,
- die zentrale SPrachdatei fau.pot aktualisiert werden,
- die Versionsnummer des Projektes (automatisch) erhöht werden,
sowie weitere Tasks ausgeführt weren.

Übliche Startprozesse sind:

```node run (option)```
mit (option)=

- "dev" (Erstellung der CSS Dateien aus SASS plus Autoprefixer; Zusätzlich wird die POT-File in /languages aktualisiert.)
- "maps" (Erstellung der CSS Dateien aus SASS plus SourceMap, ohne Autoprefixer)
- "build" (Erstellung der CSS Dateien aus SASS plus Autoprefixer und Minifizierer. Zusätzlich wird die Patch-Version des Projektes um eine Nummer erhöht)
- "pot" (Aktualisierung der POT-File in /languages )



### SASS-Compiler

ZUr Compilierung der SASS Dateien befindet sich ein Gulp-Skript im Basisordner. Dieses Skript compiliert die CSS-Dateien aus den SASS, ergänzt notwendige Vendor-Prefixes und minifiziert die Ausgaben.

Es ist jedoch abseits des Gulp-Skriptes möglich, SASS auch mit einer eigenen IDE zu compilieren. Hierzu können die folgenden Parameter verwendet werden:

Im Verzeichnis  ```/css/sass/``` wurden alle notwendigen SASS und SCSS Dateien abgelegt.
Die zentrale CSS-Datei style.css wird bei der SASS-Compilierung im  Hauptverzeichnis des Themes unter den Namen ```style.css``` abgelegt. Die CSS-Dateien für das Backend werden dagegen im Unterverzeichnis ```/css``` abfelegt.

#### SASS-Watcher:

1. Basis Stylesheet
    Eingabequelle:   ```/css/sass/base.scss```   
    Ausgabeort:  ```/style.css```

2. Sonstiges Styles
    Eingabequelle:  ```/css/sass/```  
    Ausgabeort:     ```/css```

Mit der Compiler-Option ```--style compressed``` sollen die im produktiven Betrieb erzeugten CSS-Dateien komprimiert sein. Source-Map Dateien werden nicht benötigt.


## Hinweis zu Vendor-Prefixes

In den SASS-Dateien befinden sich teilweise noch Vendor-Prefixes. Diese wurde in älteren Versionen in das Theme eingestellt, als es noch keinen Autoprefixer
gab. Diese Vendor-Prefixes sollen in den nächsten Versionen des Themes entfernt werden.

Neue Vendor-Prefixes sollen  -sofern sie noch benötigt werden- nur noch durch einen Autoprefixer auf das style.css ergänzt werden. 
Das beigelegte Gulp-Skript mit den darin befindlichen Modules ergänzt die CSS-Dateien um Vendor-Prefixes.
