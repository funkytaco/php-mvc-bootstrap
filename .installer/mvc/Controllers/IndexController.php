<?php
require_once('ControllerInterface.php');

use \Klein\Request;
use \Klein\Response;
use \Main\Renderer\Renderer;
use \Main\Mock\PDO;
use Main\Modules\Date_Module;

    /**
    *   NOTE that the following are injected into your controller
    *   Renderer $renderer - Template Engine
    *   PDO $conn - PDO
    *   Dependency Injecting makes testing easier!
    ***/
    //#[\AllowDynamicProperties]
    class IndexController implements \App\ControllerInterface {

        private $data;
        private Renderer $renderer;
        private PDO $conn;

        public function __construct(
            Renderer $renderer,
            PDO $conn, Date_Module $mod_date
        ) {

            $this->renderer = $renderer;
            $this->conn = $conn;

            $this->data = [
                // Basic Info
                'title' => 'Icarus Web Panel',
                'brandName' => 'Icarus',
                'currentYear' => date('Y'),

                // Hero Section
                'heroTitle' => 'Icarus Web Panel',
                'heroSubtitle' => 'Soar to new heights with modern web development',
                'ctaPrimary' => 'Get Started',
                'ctaSecondary' => 'View Documentation',

                // Features Section
                'featuresTitle' => 'Why Choose Icarus?',
                
                // Feature 1
                'feature1Title' => '⚡ Custom Templating Freedom',
                'feature1Description' => 'Build your templates your way. Icarus\'s flexible templating system lets you craft pixel-perfect designs without fighting your tools.',
                
                // Feature 2
                'feature2Title' => '🎨 Modern Admin Interface',
                'feature2Description' => 'Manage your web projects through a clean, intuitive admin panel. No clutter, no confusion – just efficient project management.',
                
                // Feature 3
                'feature3Title' => '🛠️ Developer-Friendly',
                'feature3Description' => 'Built on PHP 8 with developers in mind. Create custom modules, extend functionality, and maintain clean, efficient codebases.',
                
                // Feature 4
                'feature4Title' => '☁️ Lightweight Core',
                'feature4Description' => 'A lean, powerful foundation that stays out of your way. No unnecessary features – just the tools you need to build exceptional web projects.',

                // Target Audience Section
                'targetTitle' => 'Perfect For',
                'targetAudience' => [
                    [
                        'title' => 'Web Developers',
                        'description' => 'who need precise control over their projects'
                    ],
                    [
                        'title' => 'Agencies',
                        'description' => 'building custom client solutions'
                    ],
                    [
                        'title' => 'System Administrators',
                        'description' => 'managing multiple web properties'
                    ],
                    [
                        'title' => 'Backend Developers',
                        'description' => 'creating scalable web applications'
                    ]
                ],

                // Features List Section
                'featureListTitle' => 'Features That Matter',
                'featuresList' => [
                    'Custom templating engine for maximum flexibility',
                    'Clean, modern admin interface',
                    'API routing for headless implementations',
                    'Module system for extending functionality',
                    'Built on PHP 8 for optimal performance'
                ],

                // CTA Section
                'ctaTitle' => 'Ready to Elevate Your Web Development?',
                'ctaDescription' => 'Download Icarus and experience the freedom of true custom development',
                'ctaButtonText' => 'Download Icarus',

                // Footer
                'footerDescription' => 'Beyond Traditional Limits. Icarus is a powerful web panel that gives you the freedom to build exactly what you need.',

                // Legacy data (keeping for compatibility)
                'myDateModule' => $mod_date->getDate(),
                'projectList' => self::getLegacyProjects()
            ];
        }

        public function getLegacyProjects() {
            $projPaths = array();
            if (is_dir('Legacy')) {
                $paths = scandir('Legacy');
                foreach ($paths as $path) {
                    if (is_dir('Legacy' . $path) && $path != '.' && $path != '..') {
                        $projPaths[] = $path;
                    }
                }
            }
            return $projPaths;
        }

        public function get(Request $request, Response $response) {
            $this->data['getVar'] = $request->__get('get');
            $html = $this->renderer->render('index', $this->data);
            $response->body($html);
            return $response;
        }

        public function getAbout(Request $request, Response $response) {
            return self::get($request, $response);
        }
        public function getContact(Request $request, Response $response) {
            return self::get($request, $response);
        }
    }
