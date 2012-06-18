<?php

include("class/Classe.class.php");
include("misc/functions.php");


 Classe::IncludeAll();


 Ressource::setConfiguration(Configuration::getCurrentConfiguration());
 Ressource::setParameters(Parameters::getCurrentParameters());
 Ressource::setCurrentTemplate(Template::getCurrentTemplate());
 Ressource::setCurrentLanguage(Language::getCurrentLanguage());
 Ressource::setCurrentPage(Page::getCurrentPage());
 Ressource::setDataSource(DataSource::getCurrentDataSource());
 Ressource::setSessionManager(SessionManager::getCurrentSessionManager());
 Ressource::setUserSpace(UserSpace::getCurrentUserSpace());

 Module::loadAll();
 
 Ressource::getCurrentPage()->toHTML();
 
 //HTAccess::generate();
 
 ?>
