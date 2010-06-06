<?php
class Controller_Admin_Label extends Pico_AdminController{
    protected function indexAction(){
        $this->_forward('list');
    }

    protected  function listAction(){
        $items = ( $item = new Model_Label() ) ? $item->search() : null;
//        $items = $items->search();

        $form = new Form_ListItems( $items );

        $this->getView()->mainLeft = $form;
    }

    protected  function editAction(){
        //var_dump( $this->getView()->disableTemplate() );
        $request = $this->getRequest();

        $label = new Model_Label();
        $label->type = self::ITEM_TYPE_LABEL;
        $label->name = 'New label';

        if( null !== $request->id ){
            $label->id = $request->id;
        }

        $form = $this->_helper( 'ItemForm', $label, array(
            'data[color]' => array(
                'id'    => 'colorpicker',
                'label' => 'Color for this label',
                'class' => 'colorpicker',
                'type'  => 'input',
                'value' => ($label->data&&isset($label->data->color)?$label->data->color:null)
            )
        ) );

        $form->setSuffix( '
            <script src="/js/colourPicker.js"></script>
            <script>
                $(document).observe("dom:loaded", function(){new ColourPicker("colorpicker")});
            </script>'
            );

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
            else{
//                var_dump( $form->getErrors() );
            }
        }

        $this->getView()->mainLeft = $form;
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
