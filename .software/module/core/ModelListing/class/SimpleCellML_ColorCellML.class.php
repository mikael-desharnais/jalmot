<?php

class ColorCellML extends  SimpleCellML {
    
    public function toHTML($line){
        ob_start();
        include(Resource::getCurrentTemplate()->getURL("html/module/ModelListing/ColorCellML.phtml"));
        return ob_get_clean();
    }
}
