<?php

include("class/Classe.class.php");

 Classe::IncludeAll();
 

 Resource::setSessionManager(SessionManager::getCurrentSessionManager());
 Resource::setConfiguration(Configuration::getCurrentConfiguration());
 Resource::setParameters(Parameters::getCurrentParameters());
 Resource::setCurrentTemplate(Template::getCurrentTemplate());
 Resource::setCurrentLanguage(Language::getCurrentLanguage());
 Resource::setCurrentPage(Page::getCurrentPage());
 Resource::setUserSpace(UserSpace::getCurrentUserSpace());

 Log::GlobalLogData("Before Module",Log::$LOG_LEVEL_INFO);
 Module::loadAll();
 Log::GlobalLogData("Before HTML",Log::$LOG_LEVEL_INFO);
 
 Resource::getCurrentPage()->toHTML();

 Log::GlobalLogData("End HTML",Log::$LOG_LEVEL_INFO);
 
 ?>
