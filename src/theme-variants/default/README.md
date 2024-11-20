# FAU-Einrichtungen

[![Aktuelle Version](https://img.shields.io/github/package-json/v/rrze-webteam/fau-einrichtungen/master?label=Version)](https://github.com/RRZE-Webteam/FAU-Einrichtungen) [![Release Version](https://img.shields.io/github/v/release/rrze-webteam/FAU-Einrichtungen?label=Release+Version)](https://github.com/rrze-webteam/fau-einrichtungen/releases/) [![GitHub License](https://img.shields.io/github/license/rrze-webteam/fau-einrichtungen?label=Lizenz)](https://github.com/RRZE-Webteam/FAU-Einrichtungen/blob/master/LICENSE) [![GitHub issues](https://img.shields.io/github/issues/rrze-webteam/fau-einrichtungen)](https://github.com/RRZE-Webteam/FAU-Einrichtungen/issues)

WordPress-Theme für zentrale Einrichtungen der Friedrich-Alexander-Universität Erlangen-Nürnberg (FAU), https://www.fau.de

## Version

Version: 2.6.2

## Screenshot

![Beispiel Theme Screenshot](screenshot.png)

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
Durch Ausführung der Gulpfile werdenn 

- die SASS-Dateien zu den notwendigen CSS compiliert,
- die JavaScript-Datein zusammengebaut,
- Vendor-Prefixes in den CSS-Dateien ergänzt,
- CSS-Dateien minifiziert,
- die zentrale Sprachdatei fau.pot aktualisiert,
- die Versionsnummer des Projektes (automatisch) erhöht

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

2. Printer Style
    Eingabequelle:  ```/src/sass/fau-theme-print.scss```  
    Ausgabeort:     ```/print.css```

3. Backend Style
    Eingabequelle:  ```/src/sass/fau-theme-admin.scss```  
    Ausgabeort:     ```/css/fau-theme-admin.css```


## Verwendete Drittquellen

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
- Licensed under the SIL Open Font License, Version 1.1. This license is available with an FAQ at: http://scripts.sil.org/OFL
- Designer: Manushi Parikh, Satya Rajpurohit - http://www.indiantypefoundry.com
- Producer: Indian Type Foundry - http://www.indiantypefoundry.com


### Bootstrap-Bestandteile

This theme uses several parts of Bootstrap (https://getbootstrap.com/) in version 3.3.7.

Code licensed MIT, docs CC BY 3.0.

