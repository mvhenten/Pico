<?php
class Controller_Admin_Setting extends Pico_AdminController{
    protected function indexAction(){
        //        $this->_forward('list');
    }
    
    public function getMenu(){
        $template = (array) $this->getConfig()->settings;
        
        foreach( $template as $key => $value ){
            $template[$key] = array(
                'value' =>  $value['title'],
                'target' => $this->getView()->Url( array('action'=>'edit', 'id'=>$key))
            );
        }
        
        return $this->getView()->LinkList( $template );
        
        
    }
    
    protected function listAction(){
        $this->getView()->actions = '<h2>Setings</h2>';
        
        $html = array();
        
        foreach( $this->getConfig()->settings as $name => $values ){
            $html[] = $this->getView()->Link( $values['title'], array('action'=>'edit', 'id' => $name ) );
            $html[] = new Nano_Element( 'p', null, $values['description']);
        }
        
        $this->getView()->content = join( "\n", $html );
    }
    
    protected function editAction(){
        $request = $this->getRequest();
        $template = (array) $this->getConfig()->settings[$request->id]['values'];

        // merge template settings with real ones....        
        $search = Model_Setting::get()->all()->where('group', $request->id );
        $items = array(); foreach( $search as $item ) $items[$item->name] = $item;
        $items = array_merge( $template, $items );
        
        $form = new Nano_Form();
        $fieldset = new Nano_Form_Element_Fieldset('settings');
        
        foreach( $items as $name => $item ){
            if( ! $item instanceof Model_Setting ){
                $item = new Model_Setting( $item );
                $item->name = $name;
                $item->group = $request->id;
                $items[$name] = $item;
            }
            
            $fieldset->addElement( sprintf('settings[%s]', $name ), array(
                'label' => $item->title,
                'type'  => $item->type,
                'value' => $item->value,
            ));
            
        }
        
        $form->addChild( $fieldset );
        
        $form->addElement('submit', array(
            'value'=>'Save',
            'type'=>'submit',
            'wrapper' => new Nano_Element('div', array('class'=>'toolbar') )
        ));
        
        if( $request->isPost() && $post = $request->getPost() ){
            $form->validate( $post );
            
            if( $form->isValid() ){
                foreach( $post->settings as $name => $value ){
                    $item = $items[$name];                    
                    $item->value = $item->type == 'checkbox' ? '1' : $value;
                    $item->put();
                }
                
                // these were not sent. 
                $check = array_diff_key( $items,  $post->settings );
                foreach( $check as $name => $item ){
                    if( $item->type == 'checkbox')
                        $item->value = 0;
                        
                    $item->put();
                }
                
                $this->_redirect( $this->getView()->Url() );                
            }
            else{
                $this->getView()->setErrors( $form->getErrors() );
            }
            
        }

        $this->getView()->content = $form;
        $this->getView()->actions = new Nano_Element('h2', null,
            sprintf('Editing %s settings', $request->id ));        
    }
}