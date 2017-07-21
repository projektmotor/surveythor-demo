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

How to run EndToEnd-Tests
==========================

Start local selenium instance:
```
./vendor/bin/selenium-server-standalone
```

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

License
-------

This software is published under the [MIT License](LICENSE.md)

[1]: https://travis-ci.org/projektmotor/surveythor-demo.svg?branch=master
[2]: https://travis-ci.org/projektmotor/surveythor-demo
[3]: http://heroku-badge.herokuapp.com/?app=surveythor-demo-app&style=flat
[4]: http://surveythor-demo-app.herokuapp.com
