<?php

class Config {

    public static $ganglia_base_url  = 'http://ganglia.example.com';
    public static $graphite_base_url = 'http://graphite.example.com';
    public static $cacti_base_url    = 'http://cacti.example.com/cacti';

    public static $graphite_deploys = array(
        'config' => array('color' => '#0000ff', 'title' => 'Config',    'target' => 'deploy.config.prod'),
        'web'    => array('color' => '#ff0000', 'title' => 'Web',       'target' => 'deploy.web.prod'),
        'search' => array('color' => '#006633', 'title' => 'Search',    'target' => 'deploy.search.prod'),
        'photos' => array('color' => '#cc6600', 'title' => 'Photos',    'target' => 'deploy.photos.prod'),
    );

}
