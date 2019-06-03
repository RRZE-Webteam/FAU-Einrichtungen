# FAU-Einrichtungen

Wordpress-Theme für zentrale Einrichtungen der Friedrich-Alexander-Universität Erlangen-Nürnberg (FAU)


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
* Slick Slider v1.9
* Bootstrap 3.3.7, http://getbootstrap.com/



## Feedback

Please use github for submitting new features or bugs:
 https://github.com/RRZE-Webteam/FAU-Einrichtungen/issues

or send an email to 
 webmaster@rrze.fau.de

## Dokumentation

Eine Dokumentation des THemes und dessen Funktionen findet sich unter der Adresse
https://wordpress.rrze.fau.de  


## Entwickler-Hinweise

## Testportal

Testseiten und Hinweise zur Neuentwicklung finden sich auf dem Testportal
https://www.beta.wordpress.rrze.fau.de/ .
Dort finden sich auch weitere Hinweise zur Entwicklung.


### SASS-Compiler

Die CSS Anweisungen werden mittels SASS erzeugt. Hierzu werden im Verzeichnis 
```/css/sass/``` alle notwendigen SASS und SCSS Dateien abgelegt.
Die zentrale CSS-Datei style.css wird bei der SASS-Compilierung im  
Hauptverzeichnis des Themes abgelegt. Die CSS-Datei für das Backend wird 
dagegen im Unterverzeichnis ```/css``` abfelegt.

#### SASS-Watcher:

1. Basis Stylesheet
    Eingabequelle:   ```/css/sass/base.scss```   
    Ausgabeort:  ```/style.css```

2. Sonstiges Styles
    Eingabequelle:  ```/css/sass/```  
    Ausgabeort:     ```/css```

Mit Compiler-Option soll im prdokutiven Betrieb die erzeigte CSS-Datei kompimiert 
sein. Außerdem sind Source-Map Dateien nicht benötigt. Die dafür notwendige 
Compiler-Argumente sind daher ```--style compressed  --sourcemap=none```


## Hinweis zu Vendor-Prefixes

In den SASS-Dateien befinden sich teilweise noch Vendor-Prefixes. Diese wurde
in älteren Versionen in das Theme eingestellt, als es noch keinen Autoprefixer
gab.
Diese Vendor-Prefixes sollen in den nächsten Versionen des Themes entfernt werden.
Vendor-Prefixes sollen stattdessen -sofern sie noch benötigt werden- durch einen
Autoprefixer auf das style.css ergänzt werden. Diese Funktion und die dazugehörigen
Konfigurationsdateien sind jedoch (aktuell) nicht Teil des GitHub-Projektes.

