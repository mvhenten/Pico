<?php
class Navtree_Model_Navtree extends Pico_Schema_Item {    
    function items(){
        $model = new Pico_Schema_Item();
        
        return $model->search(array(
            'where' => array(
                'type' => 'navigation',
            ),
            'order' => 'parent'//array( 'parent', 'priority' )
        ));        
    }


}