<?php
class Controller_Admin_Label extends Pico_AdminController{
    protected function indexAction(){
        $this->_forward('list');
    }

    protected  function listAction(){
        $items = new Model_Item( array('type'=>self::ITEM_TYPE_LABEL));
        $items = $items->search();

        if( count( $items ) == 0 ){
            $this->_forward( 'edit' );
        }

        foreach( $items as $i => $item ){
            $item = $item->toArray();
            $item['title'] = sprintf('<a href="/admin/label/edit/%d">%s</a>', $item['id'], $item['name']);

            $items[$i] = $item;
        }

        $this->getView()->items = $items;
    }

    protected  function editAction(){
        $request = $this->getRequest();
        $label = new Model_Label();
        $label->type = self::ITEM_TYPE_LABEL;
        $label->name = 'New label';

        if( null !== $request->id ){
            $label->id = $request->id;
            $images = $label->fetchImages();

            var_dump( $images );exit;
        }

        $form = $this->_helper( 'ItemForm', $label, array(
            'data[color]' => array(
                'label' => 'Color for this label',
                'class' => 'colorpicker',
                'type'  => 'input',
                'value' => ($label->data&&isset($label->data->color)?$label->data->color:null)
            )
        ) );

        if( $request->isPost() ){
            $post = $request->getPost();
            $form->validate( $post );

            if( ! $form->hasErrors() ){
                if( $post->delete ){
                    $this->_redirect('/admin/label/delete/' . $label->id );
                }
                $label->name = $post->name;
                $label->description = $post->description;
                $label->visible     = (bool) $post->visible;

                $label->data = $post->data;

                $label->save();
                $this->_redirect( '/admin/label/edit/' . $label->id );
            }
        }

        $this->getView()->form = $form;
    }

    protected  function addAction(){
        $this->_forward( 'edit' );
    }

    protected function getMenu(){
        return array(
            'add'   => array(
                'target' => '/admin/label/add',
                'value'  => 'Add new label'
            )
        );
    }

}
