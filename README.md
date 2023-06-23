# FAU-Einrichtungen

Wordpress-Theme für zentrale Einrichtungen der Friedrich-Alexander-Universität Erlangen-Nürnberg (FAU), https://www.fau.de

## Version

Version: 2.3.24

## Download 

GitHub-Repo: https://github.com/RRZE-Webteam/FAU-Einrichtungen

## Autor 

RRZE-Webteam , http://www.rrze.fau.de

## Copyright

GNU General Public License (GPL) Version 3


## Feedback

Bitte verwenden Sie GitHub um Issues oder Feedback zu geben:
 https://github.com/RRZE-Webteam/FAU-Einrichtungen/issues

Alternativ können Sie auch eine E-Mail senden an: 
 webmaster@rrze.fau.de

## Dokumentation

Eine Dokumentation des Themes und dessen Funktionen findet sich unter der Adresse https://wordpress.rrze.fau.de  



## Entwickler-Hinweise

### Testportal

Testseiten und Hinweise zur Neuentwicklung finden sich auf dem Testportal https://www.beta.wordpress.rrze.fau.de/ Dort finden sich auch weitere Hinweise zur Entwicklung.

### Gulp Skript zur Compilierung

Im Projekt befindet sich eine ```package.json``` und ein Gulpskript ```gulpfile.js```.
Durch Ausführung mit NodeJS können 
- die SASS-Dateien zu den notwendigen CSS compiliert werden. 
- Vendor-Prefixes in den CSS-Dateien ergänzt werden,
- CSS-Dateien minifiziert werden,
- die zentrale Sprachdatei fau.pot aktualisiert werden,
- die Versionsnummer des Projektes (automatisch) erhöht werden,
sowie weitere Tasks ausgeführt weren.

Übliche Startprozesse sind:

```gulp [option]```

mit [option]:

- "dev" (Erstellung der CSS Dateien aus SASS plus Autoprefixer; Zusätzlich wird die POT-File in /languages aktualisiert.)
- "build" (Erstellung der CSS Dateien aus SASS plus Autoprefixer und Minifizierer. Zusätzlich wird die Patch-Version des Projektes um eine Nummer erhöht)
- "pot" (Aktualisierung der POT-File in /languages )
- "clone --theme=[phil|nat|tf|rw|med]" (Erstellung der Theme-Variante für eine Fakultät. Das Theme der Fakultät wird hierzu in ein Unterordner "../build" angelegt.)


### SASS-Compiler

ZUr Compilierung der SASS Dateien befindet sich ein Gulp-Skript im Basisordner. Dieses Skript compiliert die CSS-Dateien aus den SASS, ergänzt notwendige Vendor-Prefixes und minifiziert die Ausgaben.
Es ist jedoch abseits des Gulp-Skriptes möglich, SASS auch mit einer eigenen IDE zu compilieren. Hierzu können die folgenden Parameter verwendet werden:

Im Verzeichnis  ```/src/sass/``` wurden alle notwendigen SASS und SCSS Dateien abgelegt.
Die zentrale CSS-Datei style.css wird bei der SASS-Compilierung im  Hauptverzeichnis des Themes unter den Namen ```style.css``` abgelegt. Die CSS-Dateien für das Backend werden dagegen im Unterverzeichnis ```/css``` abfelegt.


#### SASS-Watcher

Einstellungen für eigene Watcher:

1. Basis Stylesheet
    Eingabequelle:   ```/src/sass/fau-theme-style.scss```   
    Ausgabeort:  ```/style.css```

2. Sonstiges Styles
    Eingabequelle:  ```/src/sass/```  
    Ausgabeort:     ```/css```


## Verwendete Drittquellen

### Font Awesome V4.7

Font Awesome Free is free, open source, and GPL friendly. You can use it for
commercial projects, open source projects, or really almost whatever you want.
Full Font Awesome Free license: https://fontawesome.com/license/free.

- Icons: CC BY 4.0 License (https://creativecommons.org/licenses/by/4.0/)
In the Font Awesome Free download, the CC BY 4.0 license applies to all icons
packaged as SVG and JS file types.

- Fonts: SIL OFL 1.1 License (https://scripts.sil.org/OFL)
In the Font Awesome Free download, the SIL OFL license applies to all icons
packaged as web and desktop font files.

- Code: MIT License (https://opensource.org/licenses/MIT)
In the Font Awesome Free download, the MIT license applies to all non-font and
non-icon files.


### Font Roboto
- Copyright: Copyright 2011 Google Inc. all rights reserved.
- Trademark: Roboto is a trademark of Google.
- Licensed under the Apache License, version 2.0.
- License URL: http://www.apache.org/licenses/LICENSE-2.0
- Designer: Google - Christian Robertson - Google.com

### Font FAU Chimera

This font consists of components of the Roboto and Hind fonts. Please see the following details of the separate font packages

Roboto:
- Copyright: Copyright 2011 Google Inc. all rights reserved.
- Trademark: Roboto is a trademark of Google.
- Licensed under the Apache License, version 2.0.
- License URL: http://www.apache.org/licenses/LICENSE-2.0
- Designer: Google - Christian Robertson - Google.com


Hind:
- Copyright: Copyright (c) 2014 Indian Type Foundry.
- Trademark: Hind is a trademark of Indian Type Foundry.
- Licensed under the SIL Open Font License, version 1.1. This license is available with an FAQ at: http://scripts.sil.org/OFL
- Designer: Manushi Parikh, Satya Rajpurohit - http://www.indiantypefoundry.com
- Producer: Indian Type Foundry - http://www.indiantypefoundry.com


### Bootstrap-Bestandteile

This theme uses several parts of Bootstrap (https://getbootstrap.com/) in version 3.3.7.

Code licensed MIT, docs CC BY 3.0.

