<?php
class Form_EditLink extends Nano_Form{
    public function __construct( Model_Link $link ){
        parent::__construct();

        //$item = ( isset($item) ? $item : Model_Link::get() );
        //
        //$title = ( $item->id == null ) ? 'Create new' . $item->type : 'Editing ' . $item->name;
        $search = new $link;
        $search = $search->all();
                //->where( 'group', $link->group )
                //->where( 'id !=', $link->id );

        $parents = array(); foreach( $search as $i => $p ) $parents[$p->id] = $p->title;

        //var_dump( $parents );

        $this->addElements(array(
            'viewport' => array(
                'type' => 'fieldset',
                //'class' => 'viewport',
                'elements' => array(
                    'title' => array(
                        'type'  => 'text',
                        'title'          => 'text',
                        'value'         => $link->title,
                        'label'         => 'Title',
                        'validators'    => array(
                            array('stringLength', array(1, 64), false )
                        ),
                        'required'      => true
                    ),
                    'description'   => array(
                        'type'  => 'textarea',
                        'value' => $link->description,
                        'label' => 'Description (optional)',
                        'validators'    => array(
                            array('stringLength', array(0, 254), false )
                        ),
                    ),
                    'priority' => array(
                        'type'  => 'text',
                        'value' => $link->priority > 0 ? $link->priority : '0 ',
                        'label' => 'Priority',
                        //'validators' => array(
                        //    array( 'is_numeric' )
                        //),
                        'required'  => false
                    ),
                    'parent_id' => array(
                        //'name'      => 'parent_id',
                        'type'		=> 'select',
                        //'label'		=> 'Parent item',
                        //'onchange'  => 'this.form.submit()',
                        'value'     => $link->parent_id,
                        'options'	=> $parents
                    ),
                    'actions' => array(
                        'type'  => 'fieldset',
                        'elements' => array(
                            'save'  => array(
                                'wrapper' => false,
                                'type'  => 'submit',
                                'value' => 'Save changes',
                                //'disabled' => 'disabled'
                            ),
                            'delete' => ( null !== $link->id ? array(
                                'wrapper' => false,
                                'type'  => 'submit',
                                'value' => 'Delete ' . $link->title
                            ):null)
                        )
                    )
                )
            ),
        ));
    }
}
