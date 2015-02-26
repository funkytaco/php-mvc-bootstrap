<?php

namespace Main\Traits;

    trait DemoData {

        public function appName() {
            return 'PHP Template Seed Project';
        }

        public function appTree() {
            return '
php-seed-bootstrap/
    ├── optional
    │   └── themes (3rd party themes I didn\'t install into the app structure)
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
            └── partials (templating include files)';
        }

        public function getLintHtmlFromTrait() {
            return '<script type="text/javascript">
                javascript:(function(){var s=document.createElement("script");s.onload=function(){bootlint.showLintReportForCurrentDocument([]);};s.src="https://maxcdn.bootstrapcdn.com/bootlint/latest/bootlint.min.js";document.body.appendChild(s)})();
                </script>';
        }
    }