<?php

class PageMailBuilder extends BasicMailBuilder{
	
	protected $name;

	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
	}
	public function send(){
		$formerCurrentPage = Ressource::getCurrentPage();
		$mailPage = new MailPage($this->getName());
		Ressource::setCurrentPage($mailPage);
		$body = $mailPage->toHTML();
		Ressource::setCurrentPage($formerCurrentPage);
		$this->setConfParam("MAIL_CONTENT_TYPE",$mailPage->getConfParam("MAIL_CONTENT_TYPE"));
		$this->setConfParam("MAIL_CONTENT_CHARSET",$mailPage->getConfParam("MAIL_CONTENT_CHARSET"));
		$this->setContent($body);
		parent::send();
	}

}