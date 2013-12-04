<?php

class BasicMailBuilder{
	protected $mailSender;
	
	protected $content;
	protected $title;
	protected $receiver;
	protected $sender;
	protected $replyTo;
	protected $subject;
	protected $confParams;
	

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
	public function getContent() {
		return $this->content;
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
	public function addContent($content){
		$this->content.=$content;
	}
	public function setContent($content){
		$this->content=$content;
	}
	
	public function setConfParam($key,$value){
		$this->confParams[$key]=$value;
	}
	
	public function getConfParam($key){
	    return $this->confParams[$key];
	}

}