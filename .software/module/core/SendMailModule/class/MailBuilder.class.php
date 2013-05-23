<?php

class MailBuilder{
	protected $mailSender;
	
	protected $name;
	protected $title;
	protected $receiver;
	protected $sender;
	protected $replyTo;
	protected $subject;
	

	function __construct(){
		
	}
	
	public function getSubject() {
		return $this->subject;
	}
	public function setSubject($subject) {
		$this->subject = $subject;
	}
	public function getMailSender() {
		return $this->mailSender;
	}
	public function getName() {
		return $this->name;
	}
	public function getReceiver() {
		return $this->receiver;
	}
	public function getSender() {
		return $this->sender;
	}
	public function getReplyTo() {
		return $this->replyTo;
	}
	public function setMailSender($mailSender) {
		$this->mailSender = $mailSender;
	}
	public function setName($name) {
		$this->name = $name;
	}
	public function setReceiver($receiver) {
		$this->receiver = $receiver;
	}
	public function setSender($sender) {
		$this->sender = $sender;
	}
	public function setReplyTo($replyTo) {
		$this->replyTo = $replyTo;
	}
	public function send(){
		$this->mailSender->sendMail($this);
	}

}