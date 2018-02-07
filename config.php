<?php

declare(strict_types=1);

$options = [];


$options['auryn_workshop']['database']['schema'] = 'workshop';
//$options['auryn_workshop']['database']['host'] = 'workshop';
$options['auryn_workshop']['database']['host'] = '127.0.0.1';
$options['auryn_workshop']['database']['username'] = 'example_user';
$options['auryn_workshop']['database']['password'] = 'example_pass';
$options['auryn_workshop']['database']['root_password'] = 'dev_root';

if (file_exists('/var/i_am_docker') === true) {
    $options['auryn_workshop']['database']['host'] = 'db';
}


$options['auryn_workshop']['redis']['host'] = 'redis';
$options['auryn_workshop']['redis']['password'] = "~CC9:t[e^w<kt{t83[Mzw*@s4LfJ)f";
$options['auryn_workshop']['redis']['port'] = 6379;

$options['auryn_workshop']['file_database']['path'] = __DIR__ . '/var/phpsqlite.db';;

$options['auryn_workshop']['logger']['path'] = __DIR__ . '/var/logger.txt';