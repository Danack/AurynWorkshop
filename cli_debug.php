<?php


$container['request'] = function ($container)  {
    $request = new AurynWorkshop\CLIRequest('/images', '127.0.0.1', 'GET', true, null);

    $request = new AurynWorkshop\CLIRequest('/block_edit?content_block_id=1&language_id=38', '127.0.0.1', 'GET', true, null);


    return $request;
};







