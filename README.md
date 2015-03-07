# php-mvc-bootstrap

A 'no framework' MVC PHP 5.4 scaffold to seed a Bootstrap-enabled application utilizing Composer and namespaces.

[![Build Status](https://travis-ci.org/funkytaco/php-mvc-bootstrap.svg)](http://travis-ci.org/funkytaco/php-mvc-bootstrap)

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

#Web Sequence Diagram#

![php-mvc-bootstrap web sequence](http://funkytaco.github.io/php-mvc-bootstrap/images/diagram.svg "php-mvc-bootstrap web sequence")

## Documentation ##
To create your own view:
- add a route in src/Routes.php
- add a template view in src/Views/
- add a controller in src/Controllers/

###Database PDO Setup###
By default, the project is using a Mock Database wrapper.

To use a MySQL/Postgres/Other Database:
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


##Using Database results in your view##

We use **PHP Traits** includes to keep the code out of our PDOWrapper class. Take a look at `src/PDO.php` to see how we `include` it.

Where is all of our model code? In the **PHP Traits** file, `src/Traits/QueryData.php`.

If you are new to **PHP Traits** it works like this:
- The PDOWrapper class `uses` the namespace of your Trait file, e.g., 
`use \Main\Traits\MyQueryData`. Since this class is now loaded in the class all of its functions are available to the parent class. 
- e.g. `getUsers()` in our traits file is accessible as `$conn->getUsers()`.

###Rendering database results in your template###


In `src/Controllers/DemoController.php`:
 - Add your function. For this example, we're gonna skip this step.

NOTE: The `$conn` variable is a reference to the PDO class injected into the controller.
1. Change this:

        //Database Layer example
        $mock_database_users = $conn->getUsers();
To this:

        //Database Layer example
        $mock_database_users = $conn->getUsers();
        $get_my_data = 'Hello world!'; //or $conn->yourFunction();
2. Change this:

            $this->data = [
                    'appName' => self::appName(), //from DemoData.php
                    'month' =>          date('M'),
                    'day' =>            date('d'),
                    'year' =>           date('Y'),
                    'today'=>           date('l'),
                    'time'=>            date( "F, d"),
                    'bootstrapLint'=>  $trait_lint,
                    'users' =>          $mock_database_users,
                    'appTree' => self::appTree()
                ];
To this:

            $this->data = [
                    'appName' => self::appName(), //from DemoData.php
                    'month' =>          date('M'),
                    'day' =>            date('d'),
                    'year' =>           date('Y'),
                    'today'=>           date('l'),
                    'time'=>            date( "F, d"),
                    'bootstrapLint'=>  $trait_lint,
                    'users' =>          $mock_database_users,
                    'get_my_data' =>         $get_my_data, //this line!
                    'appTree' => self::appTree()
                ];
`$this->data` is data that will be used by the template engine.

**We still need to modify the template**


In **src/Views/Home-demo.html**:

Change this:

`<h1>PHP Template Seed Project</h1>`

To this:

`<h1>PHP Template Seed Project</h1><div>Get One User: {{get_my_data}}</div>`

Save the template file and refresh the page. You should see your data now!

If you are not seeing a response, it means the templating engine, Mustache, got an empty variable. Either your database is not returning a row, or you might have mistyped the variable name.

For further templating information, [mustache.php] has a good primer on how to pass in your data. If you don't like Mustache, then [No Framework] tutorial, which this project is based on, explains how to replace the "Renderer".

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

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## History
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
[@PatrickLouys]:https://github.com/PatrickLuoys/