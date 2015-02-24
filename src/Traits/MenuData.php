<?php

namespace Main\Traits;

    trait MenuData {

        public function getDemoMenu($select) {
            $selected = 'class="active"';
            $selectedHome = null;
            $selectedDashboard = null;
            $selectedJumboTron = null;
            $selectedCover = null;
            $selectedVanilla = null;

            switch($select) {
                case 'dashboard':
                $selectedDashboard = $selected;
                break;
                case 'jumbotron':
                $selectedJumboTron = $selected;
                break;
                case 'vanilla':
                $selectedVanilla = $selected;
                break;

                case 'home':
                default:
                $selectedHome = $selected;
                break;
            }
            return '<nav>
                <ul class="nav nav-sidebar">
                        <li '. $selectedHome .'><a href="/">Home</a></li>
                        <li '. $selectedDashboard .'><a href="/demos/dashboard">Dashboard Demo</a></li>
                        <li '. $selectedJumboTron .'><a href="/demos/jumbotron">Jumbotron Demo</a></li>
                        <li '. $selectedVanilla .'><a href="/demos/vanilla">Vanilla Demo</a></li>
                        <li><a href="/demos/cover">Cover Demo</a></li>
                    </ul>
                </nav>';
        }
    }