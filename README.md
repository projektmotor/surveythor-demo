surveythor
==========

[![Tests][1]][2] [![Heroku][3]][4]

A Symfony bundle to demonstrate usage and features of the pm surveythor, a tool to create and perform online surveys.

Features:
+ a simple backend to create surveys
+ a simple frontend to render survey forms and handle the result by your own event
+ different types of possible questions:
  - multiple choice
  - single choice
  - free text questions
+ questions / answers can have child / parent relations

Still not features:
+ groupable questions
+ other elements then questions like html elements, text, dividers, etc.

### XDebug nutzen

* XDebug in `docker-compse.yml` aktivieren:
```yaml
  services:
    ...
    web:  
      ...
      environment:
        PHP_XDEBUG_ENABLED: 0                       # load XDebug on container start
        XDEBUG_CONFIG: remote_host=172.17.0.1       # docker HOST-IP
        PHP_IDE_CONFIG: serverName=localhost        # PHPStorm server name - only used in CLI debug
```
* PHPStorm Einstellungen:
    * Setting -> Languages & Frameworks -> PHP -> Servers: neuen Server anlegen
        * Name: beliebig, sollte jedoch dem Eintrag in PHP_IDE_CONFIG entsprechen
        * Port: 80
        * Path-Mapping: es genügt den Pfad zum Projekt-Root zu configurieren 
    * Setting -> Languages & Frameworks -> PHP -> Debug -> DBGp Proxy:
        * `Port`: 9000
* Container starten:
    ```bash
    $ docker-compose up -d
    ```
* sobald in PHPStorm der Button "Start Listening for PHP Debug Connections" geklickt wurde, werden sowohl Web- als 
auch CLI-Breakpoints angesprungen
* um XDebug  zu aktivieren/deaktivieren, genügt es die ENV-Variable `PHP_XDEBUG_ENABLED` in der `docker-compose.yml` 
anzupassen und den Container neuzustarten (`docker-compose down && docker-compose up -d`) 

License
-------

This software is published under the [MIT License](LICENSE.md)

[1]: https://travis-ci.org/projektmotor/surveythor-demo.svg?branch=master
[2]: https://travis-ci.org/projektmotor/surveythor-demo
[3]: http://heroku-badge.herokuapp.com/?app=surveythor-demo-app&style=flat
[4]: http://surveythor-demo-app.herokuapp.com