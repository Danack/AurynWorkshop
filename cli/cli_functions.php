<?php

use Danack\Console\Application;
use Danack\Console\Command\Command;
use Danack\Console\Input\InputArgument;

/**
 * @param Application $console
 */
function add_console_commands(Application $console)
{
    addDebugCommand($console);
    addUserAccountCommands($console);
}

/**
 * @param Application $console
 */
function addDebugCommand(Application $console)
{
    $command = new Command('debug', 'AurynWorkshop\CliController\Debug::test');
    $command->setDescription("Debugging the app is nice");
    $console->add($command);
}

function addUserAccountCommands(Application $console)
{
    $command = new Command('user:create', 'AurynWorkshop\CliController\UserAccount::createUserWithLogin');
    $command->setDescription("Create a user");
    $command->addArgument('username', InputArgument::REQUIRED, "The username for the account.");
    $command->addArgument('password', InputArgument::REQUIRED, "The password for the account.");
    $console->add($command);

//    $command = new Command('admin:change_password', 'AurynWorkshop\CliController\UserAccount::changePassword');
//    $command->setDescription("Change an user's password");
//    $command->addArgument('username', InputArgument::REQUIRED, "The username of the account to change");
//    $command->addArgument('password', InputArgument::REQUIRED, "The password for the account.");
//    $console->add($command);

}


