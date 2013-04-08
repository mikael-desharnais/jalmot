<?php

class SendMailModule extends Module{
	protected $configuration=array();
	public function init(){
		parent::init();
		$xml = XMLDocument::parseFromFile(Ressource::getCurrentTemplate()->getFile(new File("xml/module/SendMail","configuration.xml",false)));
		$this->setConfParams(XMLParamsReader::read($xml));
	}
	public function sendMail($mailName,$from,$to,$subject){
		require_once('ext/SwiftMailer/swift_required.php');
		$formerCurrentPage = Ressource::getCurrentPage();
		$mailPage = new CoreMailPage($mailName);
		Ressource::setCurrentPage($mailPage);
		$body = $mailPage->toHTML();

		$transport = Swift_SmtpTransport::newInstance($this->getConfParam("SMTP_SERVER"), $this->getConfParam("SMTP_PORT"))
											->setUsername($this->getConfParam("SMTP_USER"))
											->setPassword($this->getConfParam("SMTP_PASSWORD"));
		
		$mailer = Swift_Mailer::newInstance($transport);
		
		$message = Swift_Message::newInstance()
						->setSubject($subject)
						->setFrom($from)
						->setTo($to)
						->setBody($body);
				
		$type = $message->getHeaders()->get('Content-Type');
		$type->setValue($mailPage->getConfParam("MAIL_CONTENT_TYPE"));
		$type->setParameter('charset', $mailPage->getConfParam("MAIL_CONTENT_CHARSET"));
		
		$result = $mailer->send($message);
		Ressource::setCurrentPage($formerCurrentPage);
		
	}
}
