<?php

include("class/Classe.class.php");
include("misc/functions.php");


 Classe::IncludeAll();
 
 Log::LogData("After Class include",Log::$LOG_LEVEL_INFO);
 
 Ressource::setConfiguration(Configuration::getCurrentConfiguration());
 Ressource::setParameters(Parameters::getCurrentParameters());
 Ressource::setCurrentTemplate(Template::getCurrentTemplate());
 Ressource::setCurrentLanguage(Language::getCurrentLanguage());
 Ressource::setCurrentPage(Page::getCurrentPage());
 Ressource::setSessionManager(SessionManager::getCurrentSessionManager());
 Ressource::setUserSpace(UserSpace::getCurrentUserSpace());
 
 Log::LogData("Before Module",Log::$LOG_LEVEL_INFO);
 Module::loadAll();
 Log::LogData("Before HTML",Log::$LOG_LEVEL_INFO);
 

 Ressource::getCurrentPage()->toHTML();

 Log::LogData("End HTML",Log::$LOG_LEVEL_INFO);

 ?>
