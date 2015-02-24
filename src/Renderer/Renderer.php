<?php

namespace Main\Renderer;

interface Renderer {
    public function render($template, $data = []);
}