<?php

class CoreModelDataCollection implements Iterator {
    private $content = array();
    private $position =0;
    public function current (){
        return $this->content[$this->position];
    }
    public function key (){
        return $this->position;
    }
    public function next (){
        ++$this->position;
    }
    public function rewind (){
        $this->position = 0;
    }
    public function valid (){
        return isset($this->content[$this->position]);
    }
    public function addModelData($dataModel){
    	   $this->content[]=$dataModel;
    }
    public function merge($ModelDataCollection){
        $this->content = array_merge($this->content,$ModelDataCollection->getContent());
        return $this;
    }
    public function getContent(){
        return $this->content;
    }
}

