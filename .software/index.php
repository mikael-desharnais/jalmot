<?php

include("class/Classe.class.php");

 Classe::IncludeAll();
 

 Ressource::setSessionManager(SessionManager::getCurrentSessionManager());
 Ressource::setConfiguration(Configuration::getCurrentConfiguration());
 Ressource::setParameters(Parameters::getCurrentParameters());
 Ressource::setCurrentTemplate(Template::getCurrentTemplate());
 Ressource::setCurrentLanguage(Language::getCurrentLanguage());
 Ressource::setCurrentPage(Page::getCurrentPage());
 Ressource::setUserSpace(UserSpace::getCurrentUserSpace());

 Log::GlobalLogData("Before Module",Log::$LOG_LEVEL_INFO);
 Module::loadAll();
 Log::GlobalLogData("Before HTML",Log::$LOG_LEVEL_INFO);
 
 Ressource::getCurrentPage()->toHTML();

 Log::GlobalLogData("End HTML",Log::$LOG_LEVEL_INFO);
 
 ?>
