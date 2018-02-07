<?php

declare(strict_types=1);

namespace AurynWorkshop;

interface SessionRender
{
    public function render($name, array $context = array());
}
