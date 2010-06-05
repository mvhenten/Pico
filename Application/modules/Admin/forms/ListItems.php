<?php
class Form_ListItems extends Nano_Form{
    public function __construct( $items ){
        parent::__construct();

        $viewport = $this->getFieldset( 'viewport', array(
            'tagname' => 'table',
            'class'   => 'viewport'
        ));

        $head = '<th>name</th><th>description</th><th>visible</th><th colspan=3>created</th>';

        $head = new Nano_Element( 'tr', null, $head );
        $class = 'even';
        $viewport->addChild( $head );

        foreach( $items as $item ){
            $class = ($class == 'even') ? 'uneven' : 'even';
            $row = new Nano_Element( 'tr', array('class'=>$class) );
            $id = 'item-' . $item->id;
            $updated = (null == $item->updated) ? $item->inserted : $item->updated;

            $input = $this->getCheckbox( 'item[' . $item->id . ']', array(
                'type'  => 'checkbox',
                'label' => $item->name,
                'id'    => 'item-' . $item->id,
                'wrapper' => new Nano_Element('td')
            ));

            $row->addChild( $input );

            $row->addChild('td', null, sprintf('<label for="%s">%s</label>', $id, $item->description) );
            $row->addChild('td', null, sprintf('<label for="%s">%s</label>', $id, $item->visible) );
            $row->addChild('td', null, sprintf('<label for="%s">%s</label>', $id, $updated) );
            $row->addChild('td', array('width'=>'10%'), '<a href="/admin/page/edit/' . $item->id . '">edit</a>');
            $row->addChild('td', array('width'=>'10%'), '<a href="/admin/page/delete/' . $item->id . '">delete</a>');
            $viewport->addChild( $row );

        }

        $this->addChildren( array(
            'div'  => array(
                'class' => 'foo',
                'content' => 'foo',
                'children' => array(
                    'span'  => array(
                        'class' => 'biz',
                        'content' => 'fiz'
                    )
                )
            )
        ) );

        $this->addElements(array(
            'toolbar'   => array(
                'type'  => 'fieldset',
                'class' => 'toolbar',
                'elements' => array(
                    'select-all'    => array(
                        'type'		=>'button',
                        'wrapper'	=> false,
                        'value'		=> 'select all',
                        'onclick'	=> "$(this.form).select('.input-checkbox').each(function(el){el.up('dl').addClassName('active');el.checked=true});"
                    ),
                    'reset' => array(
                        'type'=>'reset',
                        'wrapper'	=> false,
                        'value'=> 'clear selection',
                        'onclick'	=> '$(this).up(\'form\').select(\'dl\').invoke(\'removeClassName\', \'active\');'
                    ),
                    'action' => array(
                        'name'      => 'action',
                        'type'		=> 'select',
                        'wrapper'	=> false,
                        'label'		=> 'With selected items do ',
                        'onchange'  => 'this.form.submit()',
                        'options'	=> array(
                            'delete'	=> 'delete',
//                            'labels'	=> 'edit labels'
                        ),
                    )
                )
            )
        ));
        $this->addChild( $viewport );

    }

}
