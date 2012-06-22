<?php
/**
* The simple event listener class
* Should be used in any event
* The method called on the event depends on the event
*/
	class EventListener{
		/**
		* The Object that created this listener and is thus listening ...
		*/
		private $listeningObject;
		/**
		* initialises the object that create this listener
		* @param mixed $listeningObject the object that create this listener
		*/
		public function __construct($listeningObject){
			$this->listeningObject=$listeningObject;
		}
		/**
		* Returns the object that create this listener
		* @return mixed  the object that create this listener
		*/
		public function getListeningObject(){
			return $this->listeningObject;
		}
	}


