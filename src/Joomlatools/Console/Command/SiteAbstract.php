<?php
/**
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		Mozilla Public License, version 2.0
 * @link		http://github.com/joomlatools/joomla-console for the canonical source repository
 */

namespace Joomlatools\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class SiteAbstract extends Command
{
    protected $site;
    protected $www;

    protected $target_dir;
    protected $target_db;

    protected $mysql;

    protected function configure()
    {
        $this->addArgument(
            'site',
            InputArgument::REQUIRED,
            'Alphanumeric site name. Also used in the site URL with .dev domain'
        )->addOption(
            'nousers',
            null,
            InputOption::VALUE_OPTIONAL,
            'Build the joomla instance without adding default users',
            true
        )
        ->addOption(
            'www',
            null,
            InputOption::VALUE_REQUIRED,
            "Web server root",
            '/var/www'
        )
        ->addOption(
            'mysql',
            null,
            InputOption::VALUE_REQUIRED,
            "MySQL credentials in the form of user:password@host:port",
            'root:root@localhost:3306'
        )
        ->addOption(
            'title',
            null,
            InputOption::VALUE_OPTIONAL,
            "Title for the site"
        );
        
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->site       = $input->getArgument('site');
        $this->www        = $input->getOption('www');

        $this->target_db  = 'sites_'.$this->site;
        $this->target_dir = $this->www;

        if (strpos($input->getOption('mysql'), '@') !== false) 
        {
            $dbstring = explode('@',$input->getOption('mysql'),2);
            $credentials = explode(':', $dbstring[0], 2);    
            $hostport = explode(':', $dbstring[1],2);
        }
        else $credentials = explode(':', $input->getOption('mysql'), 2);
        $this->mysql = (object) array('user' => $credentials[0], 'password' => $credentials[1], 'host' => $hostport[0]);
        $this->mysql->port = (count($hostport) > 1) ? $hostport[1] : 3306;
    }
}