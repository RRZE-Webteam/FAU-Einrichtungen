# FAU-Einrichtungen

Wordpress-Theme für zentrale Einrichtungen der Friedrich-Alexander-Universität Erlangen-Nürnberg (FAU)


## Download 

GITHub-Repo: https://github.com/RRZE-Webteam/FAU-Einrichtungen


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

Im Projekt befindet sich eine ```package.json``` und ein Gulpskript ```gulpfile.babel.js```.
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
- "maps" (Erstellung der CSS Dateien aus SASS plus SourceMap, ohne Autoprefixer)
- "build" (Erstellung der CSS Dateien aus SASS plus Autoprefixer und Minifizierer. Zusätzlich wird die Patch-Version des Projektes um eine Nummer erhöht)
- "pot" (Aktualisierung der POT-File in /languages )
- "clone --theme=[phil|nat|tf|rw|med]" (Erstellung der Theme-Variante für eine Fakultät. Das Theme der Fakultät wird hierzu in ein Unterordner "../build" angelegt.)


### SASS-Compiler

ZUr Compilierung der SASS Dateien befindet sich ein Gulp-Skript im Basisordner. Dieses Skript compiliert die CSS-Dateien aus den SASS, ergänzt notwendige Vendor-Prefixes und minifiziert die Ausgaben.
Es ist jedoch abseits des Gulp-Skriptes möglich, SASS auch mit einer eigenen IDE zu compilieren. Hierzu können die folgenden Parameter verwendet werden:

Im Verzeichnis  ```/css/sass/``` wurden alle notwendigen SASS und SCSS Dateien abgelegt.
Die zentrale CSS-Datei style.css wird bei der SASS-Compilierung im  Hauptverzeichnis des Themes unter den Namen ```style.css``` abgelegt. Die CSS-Dateien für das Backend werden dagegen im Unterverzeichnis ```/css``` abfelegt.

#### SASS-Watcher

Einstellungen für eigene Watcher:

1. Basis Stylesheet
    Eingabequelle:   ```/css/sass/base.scss```   
    Ausgabeort:  ```/style.css```

2. Sonstiges Styles
    Eingabequelle:  ```/css/sass/```  
    Ausgabeort:     ```/css```

Mit der Compiler-Option ```--style compressed``` sollen die im produktiven Betrieb erzeugten CSS-Dateien komprimiert sein. 


