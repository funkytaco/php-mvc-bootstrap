# php-mvc

A modular 'no framework' MVC framework in PHP5.

[![Build Status](https://travis-ci.org/funkytaco/php-mvc.svg)](http://travis-ci.org/funkytaco/php-mvc)

## Installation

```sh
$ composer install
```
## Usage
To run a development server on localhost port 8000:
```sh
$ composer serve
```
The port can be changed in **DevTasks.php**, which is a Composer scripts PHP file.

## Usage ##
### 1) Create your own View ###
- add a template view in **src/Views/** by default, this is a [Mustache]() template. (It is possible to change the rendering engine).
    - add a controller in src/Controllers/ which uses the view.
    - add a route in **src/Routes.php** that uses the controller.

    For further templating information, [mustache.php] has a good primer on how to pass in your data. If you don't like Mustache, then [No Framework Templating], explains how to replace the "Renderer".

### 2) Create your own Controller ###
- add a controller in **src/Controllers/** [(Example Controller)](https://github.com/funkytaco/php-mvc/blob/master/src/Controllers/IndexController.php)
    - For the controller to be used, it must be used by a route in  **src/Routes.php**
    - Reference a view to load in the controller function, if applicable.
    - `$this->data` is how your model data will be accessed by the controller, and shared with the view.


### 3) Create your own Model ###
 - You can put your model in **src/Traits/** or **src/Models** for models which will not be re-used.
    - The **$conn PDO** connection is be passed into the controller.
 - The PDOWrapper class `uses` the namespace of your Trait file, e.g.,
`use \Main\Traits\MyQueryData`. Since this class is now loaded in the class all of its functions are available to the parent class.
- e.g. `getUsers()` in our traits file is accessible as `$conn->getUsers()`.

    ####To use a MySQL/Postgres/Other Database:####
- In `src/Traits/QueryData.php`
    - add your query functions in  (I will explain how to use these functions in your view)
    - uncomment `$conn = $injector->make('\Main\PDO');` . It must stay below the `$injector->define` for PDO.
- In your controller:
    -  **use \Main\PDO;** and comment/remove **use \Main\Mock\PDO;**
- In `Config.php`:
    - `$dbtype` should be set to *mysql* or *postgres*
    - You can add other types supported by PDO, as this is just a PDO instantiation.
- Stub out your database queries:
    - In this demo, we stubbed them out to \Main\Traits\QueryData.php which is included by the PDO class, so `$conn->getUsers()` is treated like a local class function.



Where is all of our model code? In the **PHP Traits** file, `src/Traits/QueryData.php`.

***
*Additional Info*

##Tree##
Optional directory:

    optional
    ├── css
    └── themes
        └── simple-sidebar

Source directory:

    src
    ├── Controllers
    ├── Database
    ├── Mock
    │   └── Traits
    ├── Renderer
    ├── Static
    ├── Traits
    │   └── DB
    └── Views
        └── partials
Test directory:

    test
    └── src
        ├── Controllers
        └── Mock
***

## Components

Components
  - [Bootstrap] for front-end development (in bootstrap branch)
  - [Composer] for dependency management and project setup (i.e. post installation script events)
  - [whoops] for error handling
  - [Klein.php] for routing
  - [mustache.php] for templating
  - [Auryn] for IoC dependency injection

Change out these components for others (i.e. replace [mustache.php] with [handlebars.php]) by reading through [No Framework] for PHP.

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## History
  - v 0.7.5 stripped out Bootstrap from master branch and moved it to a bootstrap branch.
  - v 0.7.4 PHPUnit Travis-CI tests. Callout CSS. PDO Config file added. PDO structure and file name changes. Code cleanup for Routes.php
  - v 0.7.3 Updated license. PDO wrapper changes.
  - v 0.7.2 Initial commit

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
[No Framework Templating]: https://github.com/PatrickLouys/no-framework-tutorial/blob/master/09-templating.md
[@PatrickLouys]:https://github.com/PatrickLuoys/
