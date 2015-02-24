# php-seed-bootstrap

A 'no framework' MVC PHP 5.4 scaffold to seed a Bootstrap-enabled application utilizing Composer and namespaces.

[![Build Status](https://secure.travis-ci.org/funkytaco/php-seed-bootstrap.png?branch=master)](http://travis-ci.org/funkytaco/php-seed-bootstrap)

## Components

Components
  - [Bootstrap] for front-end development
  - [Composer] for dependency management and project setup (i.e. post installation script events)
  - [whoops] for error handling
  - [Klein.php] for routing
  - [mustache.php] for templating
  - [Auryn] for IoC dependency injection

If you read through [No Framework] for PHP, you can see how to change out these components for others (i.e. replace [mustache.php] with [handlebars.php])

## Installation

```sh
$ composer install
```

This will install the necessary vendor components and run some post-update scripts to copy [Bootstrap] assets to the public folder.


## Usage

To run a development server on localhost port 8000:

```sh
$ composer serve
```
The port can be changed in DevTasks.php, which is a Composer scripts PHP file.


## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## History

v 0.7.2 Initial commit

## Credits
Created by [@funkytaco] based on [No Framework] by [@PatrickLouys].


## License

# DON'T BE A DICK PUBLIC LICENSE

> Version 1, December 2009

> Copyright (C) 2009 Philip Sturgeon <email@philsturgeon.co.uk>
 
 Everyone is permitted to copy and distribute verbatim or modified
 copies of this license document, and changing it is allowed as long
 as the name is changed.

> DON'T BE A DICK PUBLIC LICENSE
> TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

 1. Do whatever you like with the original work, just don't be a dick.

     Being a dick includes - but is not limited to - the following instances:

     1a. Outright copyright infringement - Don't just copy this and change the name.  
     1b. Selling the unmodified original with no work done what-so-ever, that's REALLY being a dick.  
     1c. Modifying the original work to contain hidden harmful content. That would make you a PROPER dick.  

 2. If you become rich through modifications, related works/services, or supporting the original work,
 share the love. Only a dick would make loads off this work and not buy the original work's 
 creator(s) a pint.
 
 3. Code is provided with no warranty. Using somebody else's code and bitching when it goes wrong makes 
 you a DONKEY dick. Fix the problem yourself. A non-dick would submit the fix back.


[Bootstrap]:http://www.getbootstrap.com/
[Composer]:https://getcomposer.org/
[whoops]:https://github.com/filp/whoops/
[Klein.php]:https://github.com/chriso/klein.php/
[mustache.php]:https://github.com/bobthecow/mustache.php
[handlebars.php]:https://github.com/XaminProject/handlebars.php/
[Auryn]:https://github.com/rdlowrey/Auryn/
[@funkytaco]:https://github.com/funkytaco/
[No Framework]:https://github.com/PatrickLouys/no-framework-tutorial/
[@PatrickLouys]:https://github.com/PatrickLuoys/
