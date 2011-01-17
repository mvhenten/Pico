<?php
class Helper_Modules extends Nano_View_Helper{
    private $modules;
    
    public function Modules(){
        $config = Nano_Registry::get('config');
        $modules = (array) $config->modules;
        $links = array();
        
        foreach( $modules as $mod => $controllers ){
            foreach( $controllers as $key => $value ){
                $links[] = array(
                    'target'    => array(
                        'module'     => $mod,
                        'controller' => $key,
                        'action'     => null,
                        'id'         => null
                    ),
                    'value'     => $value
                );                
            }
        }
                
        return $this->getView()->LinkList($links);        
    }

    //public function modules( $render = true ){
    //    if( ! $render ){
    //        return $this->modules;
    //    }
    //
    //    return $this->renderModules();
    //}
    //
    //private function renderModules(){
    //    $modules = '';
    //
    //    foreach( $this->modules as $module => $values ){
    //        $ul = new Nano_Element('ul');
    //        foreach( $values as $controller => $title ){
    //            $a = new Nano_Element( 'a',
    //                array( 'href' => sprintf('/%s/%s', $module, $controller)), $title);
    //
    //            $li = new Nano_Element('li');
    //            $li->addChild($a);
    //            $ul->addChild($li);
    //        }
    //
    //        $modules .= (string) $ul;
    //    }
    //
    //    return $modules;
    //}

}
