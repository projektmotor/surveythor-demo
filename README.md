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
+ event controlled result handling, which means:
    - the result is processed by an EventSubscriber
    - you can override it by using a compilerpass (see the example in the AppBundle)

Still not features:
+ groupable questions
+ other elements then questions like html elements, text, dividers, etc.

# Docker

## Prepare host (initial setup)

* extend `/etc/hosts`
    ```bash
    $ sudo vi /etc/hosts 
  
    ...
    127.0.0.1 surveythor-demo
    127.0.0.1 surveythor-frontend
    ```

## Development with docker containers

* All commands have to be executed in workspace (symfony project root)
* Start all containers in background
    ```bash
    $ docker-compose up -d web
    ```
* Install vendors, execute migrations and load fixtures (only at first time) 
    * Install vendors
        ```bash
        $ docker-compose exec web php composer.phar install
        ```
    * Execute migrations
        ```bash
        $ docker-compose exec web php bin/console doctrine:migrations:migrate -n
        ```
    * Load fixtures
        ```bash
        $ docker-compose exec web php bin/console hautelook_alice:doctrine:fixtures:load -n
        ```
### Use XDebug

* Activate XDebug in `docker-compse.yml`:
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
* PHPStorm setup:
    * Settings... -> Languages & Frameworks -> PHP -> Servers: Add
        * name: has to be same as PHP_IDE_CONFIG value
        * port: 80
        * path-mapping: path of project root in host system 
    * Setting -> Languages & Frameworks -> PHP -> Debug -> DBGp Proxy:
        * `Port`: 9000
* Start containers:
    ```bash
    $ docker-compose up -d
    ```
* After clicking "Start Listening for PHP Debug Connections" in PHPStorm you can jump to web and cli breakpoints.
* To activate/deactivate XDebug simply adjust ENV-Variable `PHP_XDEBUG_ENABLED` in `docker-compose.yml`
and restart containers (`docker-compose down && docker-compose up -d`) 

### Cheat Sheet

* Execute symfony command
    ```bash
    $ docker-compose exec web php bin/console [SF-CONSOLE-COMMAND]
    ```
* Start webpack encore
    ```bash
    $ docker-compose exec web node_modules/.bin/encore dev-server
    ```
* Show containers and their status
    ```bash
    $ docker-compose ps
    ```
* Container shell access
    ```bash
    $ docker-compose exec web bash
    ```
* CLI connection to MySQL:
    ```bash
    $ mysql -u surveythor_demo -p
    ```
* Stop services/container
    ```bash
    $ docker-compose stop
    ```
* Stop and delete container (incl. volumes, images und networks except data volumes)
    ```bash
    $ docker-compose down
    ```

How to run EndToEnd-Tests
==========================

After booting container you can start EndToEnd-Tests with:
```BASH
$ docker-compose exec web vendor/bin/phpunit --filter EndToEnd
```
If you want to watch EndToEnd-Tests then you have to start a vnc viewer and connect to 0.0.0.0:5900 for firefox 
and 0.0.0.0:5901 for chrome. Maybe you have to set color depth to at least 15 bit.

Use Sauce Labs locally
----------------------

Install:
https://wiki.saucelabs.com/display/DOCS/Basic+Sauce+Connect+Proxy+Setup

After download and extraction start sauce labs from outside of docker (currently there is no docker container support):
```BASH
$ sc-4.4.8-linux/bin/sc -u username -k api_key -i my-tun2 --se-port 4446
```

Start tests:
```BASH
$ docker-compose exec web vendor/bin/phpunit
```
Embedded View
-------------
You can see how the app embeds into a frame site on:
domain.tld/embed/edit/1
domain.tld/embed/result/1
domain.tld/embed/evaluate/1

License
-------

This software is published under the [MIT License](LICENSE.md)

[1]: https://travis-ci.org/projektmotor/surveythor-demo.svg?branch=master
[2]: https://travis-ci.org/projektmotor/surveythor-demo
[3]: http://heroku-badge.herokuapp.com/?app=surveythor-demo-app&style=flat
[4]: http://surveythor-demo-app.herokuapp.com
