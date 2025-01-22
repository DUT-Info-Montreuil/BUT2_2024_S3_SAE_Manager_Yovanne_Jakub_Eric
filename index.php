<?php

include_once 'modules/mod_connexion/mod_connexion.php';
include_once 'module_name.php';
include_once 'Connexion.php';
include_once 'composants/menu/CompMenu.php';

session_start();

$connexion = new Connexion();
$connexion->initConnexion();
$menu = new ComposantMenu();

$module = new ModuleName();
$module->exec_module();

$module_html = $module->get_module()->getAffichage();
include_once 'site.php';

//test