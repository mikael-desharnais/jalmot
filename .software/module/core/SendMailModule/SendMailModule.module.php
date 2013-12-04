<?php

class SendMailModule extends Module{
	protected $configuration=array();
	protected $log;
	public function init(){
		parent::init();
		$xml = XMLDocument::parseFromFile(Ressource::getCurrentTemplate()->getFile(new File("xml/module/SendMail","configuration.xml",false)));
		$this->setConfParams(XMLParamsReader::read($xml));
		$this->importClasses();
		$this->log = new Log();
		$this->log->setLogLevel(Log::$LOG_LEVEL_INFO);
		$this->log->setLogMode(Log::$LOG_MODE_STD_OUTPUT_AND_FILE);
		$this->log->setLogFile(new File('tmp/log/','SendMail.log',false));
	}
	public function getPageMailBuilder(){
		$toReturn = new PageMailBuilder();
		$toReturn->setMailSender($this);
		return $toReturn;
	}
	public function getBasicMailBuilder(){
		$toReturn = new BasicMailBuilder();
		$toReturn->setMailSender($this);
		return $toReturn;
	}
	public function sendMail($mailBuilder){
		require_once('ext/SwiftMailer/swift_required.php');

		$transport = Swift_SmtpTransport::newInstance($this->getConfParam("SMTP_SERVER"), $this->getConfParam("SMTP_PORT"))
											->setUsername($this->getConfParam("SMTP_USER"))
											->setPassword($this->getConfParam("SMTP_PASSWORD"));
		
		$mailer = Swift_Mailer::newInstance($transport);
		
		$message = Swift_Message::newInstance()
						->setSubject($mailBuilder->getSubject())
						->setFrom($this->getConfParam("EMAIL_SENDER_ADDRESS"))
						->setReplyTo($mailBuilder->getReplyTo())
						->setTo($mailBuilder->getReceiver())
						->setBody($mailBuilder->getContent());
				
		$type = $message->getHeaders()->get('Content-Type');
		$type->setValue($mailBuilder->getConfParam("MAIL_CONTENT_TYPE"));
		$type->setParameter('charset', $mailBuilder->getConfParam("MAIL_CONTENT_CHARSET"));
		$this->log->logData("Sending email ".$mailBuilder->getSubject()." to ".print_r($mailBuilder->getReceiver(),true));
		try {
			$result = $mailer->send($message);
			$this->log->logData("Successfull Sending ".$mailBuilder->getSubject()." to ".print_r($mailBuilder->getReceiver(),true));
		}catch (Exception $ex){
			$this->log->logData("Failure Sending ".$mailBuilder->getSubject()." to ".print_r($mailBuilder->getReceiver(),true));
		}
		
	}
}
