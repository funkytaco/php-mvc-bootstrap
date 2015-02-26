# php-seed-bootstrap

A 'no framework' MVC PHP 5.4 scaffold to seed a Bootstrap-enabled application utilizing Composer and namespaces.

[![Build Status](https://travis-ci.org/funkytaco/php-seed-bootstrap.svg)](http://travis-ci.org/funkytaco/php-seed-bootstrap)

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

![php-seed-bootstrap web sequence](http://funkytaco.github.io/php-seed-bootstrap/images/diagram.svg "php-seed-bootstrap web sequence")

## Documentation ##
To create your own view:
- add a route in src/Routes.php
- add a template view in src/Views/
- add a controller in src/Controllers/

###Database PDO Setup###
By default, the project is using a Mock Database wrapper.

To use a MySQL/Postgres Database:
- add your query functions in `src/Traits/QueryData.php` (I will explain how to use these functions in your view)
- In *src/Bootstrap.php*:
    - uncomment `include('Database/PDOWrapper.php');`
- In your controller:
    - uncomment `use \Main\Database\PDOWrapper;`
- In *src/Database/PDOWrapper.php*:
    - For MySQL: uncomment `use \Main\Traits\DB\MySQL;`
    - For PostGreSQL: uncomment `use \Main\Traits\DB\PostgreSQL;`
    - Make sure you comment out the one you do not use, including the mock.
If you run into problems, the error handler should tell you what you've missed.

##Using Database results in your view##
###But First... Understanding PHP Traits###
Lets look at our PDO Wrapper class:

    <?php
    namespace Main\Database;

    class PDOWrapper {
        use \Main\Traits\DB\MySQL;
        //use \Main\Traits\DB\PostgreSQL;
        use \Main\Traits\QueryData;
    }
Where is all of our code?? In the **PHP Traits** file, `src/Main/Traits/QueryData.php`. **PHP Traits** are like interface files, but they allow implementation but you don't `extend` the class, you just `use` the Traits file, which includes them as if they were an include file. Note also, how we can swap between MySQL/PostgreSQL without much work, either! 

**Adding some code to get one database user**

In the **src/Traits/MyQueryData.php** Traits file - which we `use` in the PDOWrapper class - we are going to add a function to select one user from the database. Once this function is added, the PDOWrapper class will be able to use `$this->getFirstUser()`, as if the function was a part of the class.

If you are new to **PHP Traits** it works like this:
- The PDOWrapper class `uses` the namespace of your Trait file, e.g., 
`use \Main\Traits\MyQueryData`. Since this class is now loaded in the class all of its functions are available to the parent class. Cool, huh?

We use PHP Traits file to keep the code out of our PDOWrapper class. In this way, we can re-use the query functions in MySQL/PostgreSQL by just commenting out a line or two. 

**Let's get to it!**

Open `src/Traits/MyQueryData.php` and add this function:


        public function getFirstUser() {
            $users = [];
            function prepUserForMustache($user) { return array('name' => $user); }

            $query = $this->query("SELECT * FROM users LIMIT 1");
            $rows = $query->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $user[]  = prepUserForMustache($row['name']);
            }

            return $user;
        }
NOTE: If you don't feel like making a `users` table right now, just put some pseudo code into `getFirstUser()`; for example, `return 'Waldo';`

In the controller, we are going to add `$db->getFirstUser()` as a variable that the templating engine, Mustache, can render in the template.

Open `src/Controllers/DemoController.php`


1. Change this:

        //Database Layer example
        $mock_database_users = $db->getUsers();
To this:

        //Database Layer example
        $mock_database_users = $db->getUsers();
        $get_first_user = $db->getFirstUser();
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
                    'get_first_user' =>          $get_first_user,
                    'appTree' => self::appTree()
                ];

**We still need to modify the template**

So far, we've created our new `Trait::getFirstUser()` function, which is used by our PDOWrapper class as `$this->getFirstUser()`, included it in our controller class as `$db->getFirstUser()` and then assigned to `$this->data['get_first_user']`. 

*What is `$this->data` used by*, you're probably wondering. The template rendering engine, Mustache.

Open `src/Views/Demo-home.html`. See if you can spot the template variables. For example, {{appName}} was passed to the template as `$this->data['appName']`.

In **src/Views/Home-demo.html**:

Change this:

`<h1>PHP Template Seed Project</h1>`

To this:

`<h1>PHP Template Seed Project</h1><div>Get One User: {{get_first_user}}</div>`

Save the template file and refresh the page. You should see your data now!

If you are not seeing a response, it means Mustache got an empty variable. Either your database is not returning a row, or you might have mistyped the variable name.

For further templating information, [mustache.php] has a good primer on how to pass in your data. If you don't like Mustache [No Framework] tutorial, which this project is based on, explains how to replace the "Renderer".

***
*Additional Info*

##Tree##

    php-seed-bootstrap/
    ├── optional
    │   └── themes (3rd party themes I did not install into the app structure)
    ├── public/ (your public web folder)
    │   ├── assets/ (css, js, et cetera)
    │   └── index.php (app entry point)
    └── src
        ├── Controllers/
        ├── Database/PDOWrapper.php (wrap Postgres/MySQL et cetera)
        ├── Mock/ 
        │   ├── Database/PDOWrapper.php (mock implementation of the PDO wrapper)
        │   └── Traits/
        │       └── DB/QueryData.php (mock your database query functions)
        ├── Renderer/ (For templating - i.e. mustache, handlebars)
        ├── Static/ (your static error page is in this directory)
        ├── Bootstrap.php (bootstrap your project)
        ├── Dependencies.php (for dependency injection)
        ├── Routes.php (setup your URI endpoints/routes)
        ├── Traits/
        │   └── DB
        └── Views
            └── partials (templating include files)
            

***

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