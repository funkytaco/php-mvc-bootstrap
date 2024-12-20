<?php

namespace Main\Renderer;

interface Renderer {
    public function render(string $template, array $data = []): string;
    public function renderString(string $template, array $data = []): string;
}
