<?php

	class EventListener{
		private $listeningObject;
		public function __construct($listeningObject){
			$this->listeningObject=$listeningObject;
		}
		public function getListeningObject(){
			return $this->listeningObject;
		}
	}
