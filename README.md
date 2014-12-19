# Phprest Sample Heroku App

[![Author](http://img.shields.io/badge/author-@adammbalogh-blue.svg?style=flat-square)](https://twitter.com/adammbalogh)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)

[![Deploy](https://www.herokucdn.com/deploy/button.png)](https://heroku.com/deploy)

# Demo

[phprest.herokuapp.com/docs](http://phprest.herokuapp.com/docs)

# Api docs screenshot

![Api docs](http://i.imgur.com/OZVO8eF.png)

Of course you can send curl requests too:

```cli
curl -X GET --header "Accept: */*" phprest.herokuapp.com/camera
```

# Installation

* Install [Heroku Toolbelt](https://toolbelt.heroku.com)
* `heroku login`
* `git clone https://github.com/phprest/phprest-sample-heroku-app.git`
* `cd phprest-sample-heroku-app`
* `heroku create yourappname`
* `heroku addons:add cleardb:ignite`
* `git push heroku master`
* `heroku run composer init-phprest-sample`

