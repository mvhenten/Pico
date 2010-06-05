<?php
class Helper_ImageList{
    function ImageList( $contents = null ){
        return $this->renderList( (array) $contents );
    }

    private function renderList( $items ){
        $elements = array();

        foreach( $items as $item ){
			$img = new Nano_Element( 'img', array(
                'class'     => 'thumbnail',
				'alt'	     => $item->name,
				'width'		 => 96,
				'height' 	 => 96,
				'src'		 => sprintf("/image/thumbnail/%d", $item->id),
                'onclick'    => '$(this).up(\'dl\').toggleClassName(\'active\');',
                'ondblclick' => 'document.location.href = $(this).up(\'dl\').down(\'a\').href;return false;'
			));

            $link = new Nano_Element( 'a', array(
                'href'  => '/admin/image/edit/' . $item->id,
                'title' => 'Edit ' . $item->name,
                'style'  => 'display: none;'
            ), 'Edit ' . $item->name );

            $elements['image-' . $item->id] = array(
                'type'    => 'fieldset',
                'tagname' => 'dl',
                'elements' => array(
                    'title[' . $item->id . ']' => array(
                        'type' => 'text',
                        'value' => $item->name,
                        'prefix' => '<dt>' . $link,
                        'suffix' => '</dt>',
                        'wrapper' => false
                    ),
                    'selection[' . $item->id . ']' => array(
                        'type'  => 'checkbox',
                        'prefix' => '<dd>',
                        'suffix' => '</dd>',
                        'label' => $img,
                        'wrapper' => false
                    )
                )
            );
        }
        /**
<script>
Sortable.create('images',{
    constraint:false,
    overlap:    'horizontal',
    elements:$$('.list-item'),
    //onUpdate: function( el ){
    //    console.log(el);
    //},
    //onChange: function( el ){
    //    console.log(el);
    //}
});
</script>
*/


        return new Nano_Form( 'images', array(
            'class' => 'list-images',
            'elements'  => array(
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
                            'label'		=> 'With selected images do ',
                            'onchange'  => 'this.form.submit()',
                            'options'	=> array(
                                'delete'	=> 'delete images',
                                'labels'	=> 'edit labels'
                            ),
                        )
                    )
                ),
                'viewport' => array(
                    'type'      => 'fieldset',
                    'tagname'   => 'div',
                    'class'     => 'wrap',
                    'elements'  => $elements
                )
            )
        ));
    }
}
