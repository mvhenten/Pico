<?php
class Helper_Modules{
    private $modules;

    public function __construct(){
        $config = Nano_Registry::get('config');

        $this->modules = $config->modules;
    }

    public function modules( $render = true ){
        if( ! $render ){
            return $this->modules;
        }

        return $this->renderModules();
    }

    private function renderModules(){
        $modules = '';

        foreach( $this->modules as $module => $values ){
            $ul = new Nano_Element('ul');
            foreach( $values as $controller => $title ){
                $a = new Nano_Element( 'a',
                    array( 'href' => sprintf('/%s/%s', $module, $controller)), $title);

                $li = new Nano_Element('li');
                $li->addChild($a);
                $ul->addChild($li);
            }

            $modules .= (string) $ul;
        }

        return $modules;
    }

}
