<?php

namespace Main\Traits;

    trait DemoData {

        public function appName() {
            return 'PHP Template Seed Project';
        }

        public function appTree() {
            return '
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
        └── Mock';
        }

        public function getLintHtmlFromTrait() {
            return '<script type="text/javascript">
                javascript:(function(){var s=document.createElement("script");s.onload=function(){bootlint.showLintReportForCurrentDocument([]);};s.src="https://maxcdn.bootstrapcdn.com/bootlint/latest/bootlint.min.js";document.body.appendChild(s)})();
                </script>';
        }
    }