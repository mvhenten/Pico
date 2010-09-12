<?php
class Form_EditLink extends Nano_Form{
    public function __construct( Model_Link $link ){
        parent::__construct(null, array('class'=>'link-form'));

        $search = new $link;
        $search = $search->all()->where('group', $link->group );
        $parents = array(); foreach( $search as $i => $p ) $parents[$p->id] = $p->title;

        if( null == $link->id ){
            $this->setAttribute( 'action', '/admin/link/add/' . $link->group );
        }

        $this->addElements(array(
            'viewport' => array(
                'type' => 'fieldset',
                //'legend'    => $link->id ? 'Edit ' . $link->title : 'Add link',
                //'class' => 'viewport',
                'elements' => array(
                    'title' => array(
                        'type'  => 'text',
                        'value'         => $link->title ? $link->title : 'New menu item',
                        'label'         => 'Title',
                        'validators'    => array(
                            array('stringLength', array(1, 64), false )
                        ),
                        'required'      => true
                    ),
                    'url' => array(
                        'type'          => 'text',
                        'value'         => $link->url,
                        'label'         => 'Url',
                        'validators'    => array(
                            array('stringLength', array(1, 255), false )
                        ),
                        'required'      => true
                    ),
                    'priority' => ( $link->id ? array(
                        'type'  => 'text',
                        'value' => $link->priority > 0 ? $link->priority : '0 ',
                        'label' => 'Priority',
                        'required'  => false
                    ):null),
                    'parent_id' => array(
                        'type'		=> 'select',
                        'label'		=> 'Parent item',
                        //'onchange'  => 'this.form.submit()',
                        'value'     => $link->parent_id > 0 ? $link->parent_id : null,
                        'options'	=> $parents
                    ),
                    'save'  => array(
                        'prefix' => '<div class="toolbar">',
                        'suffix'    => ( null == $link->id ? '</div>' : ''),
                        'wrapper' => false,
                        'type'  => 'submit',
                        'value' => $link->id ? 'Save changes' : 'Add link',
                        //'disabled' => 'disabled'
                    ),
                    'delete' => ( null !== $link->id ? array(
                        'wrapper' => false,
                        'type'  => 'submit',
                        'suffix'    => '</div>',
                        'value' => 'Delete ' . $link->title
                    ):null)
                )
            ),
        ));
    }
}
