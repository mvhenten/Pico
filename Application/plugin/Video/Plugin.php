<?php
/**
 * Application/plugin/ItemThumbnail/Plugin.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package default
 */


class Video_Plugin extends Pico_View_Admin_Base {

    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     */
    public function postEdit( Nano_App_Request $request, $config ) {
        @list( , , , $id ) = $request->pathParts();

        $post   = (object) $request->post();
        $item   = $this->model('Item', $id );
        $form   = new Pico_Form_Item( $item );

        $errors = $form->validate( $post );
        $error_string = '';

        if ( count($errors) == 0 && $post->video ) {
            $item->appendix->video = $post->video;
            $item->store(array( 'id' => $item->id ) );
        }

        parent::post( $request, $config );
    }


    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     * @return unknown
     */
    public function get( Nano_App_Request $request, $config ) {
        $this->template()->addTemplatePath( $this->_templatePath() );

        return $this->template()->render('list');
    }


    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     * @return unknown
     */
    public function getEdit( Nano_App_Request $request, $config ) {
        @list( , , , $id ) = $request->pathParts();

        $this->template()->addTemplatePath( $this->_templatePath() );

        $item = $this->model('Item', $id );

        $form = new Pico_Form_Item( $item );
        $this->template()->item = $item;
        $this->template()->form = $form;


        // append fieldset and move the submit button...
        $block = current($form->children(array('id'=> 'item-values')));
        $block->addElement('fieldset-video', $this->_videoFieldset( $item ) );

        $submit = current($form->removeChildren(array('name'=>'save-changes')));
        $block->addChild($submit);

        return $this->template()->render('edit');
    }


    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     */
    public function create(Nano_App_Request $request, $config ) {
        @list( , $controller ) = $request->pathParts();

        $item = $this->model('Item', array(
                'name'          => 'Untitled new ' . $controller,
                'description'   => 'Description for untitled new ' . $controller,
                'type'          => 'video',
                'slug'          => 'untitled',
            ));

        $item->store();

        $this->response()->redirect( '/admin/' . join('/', array($controller, 'edit', $item->id )) );
    }


    /**
     *
     *
     * @param unknown $item
     * @return unknown
     */
    private function _videoFieldset( $item ) {
        $video =  (array) $item->appendix->video;

        $fieldset = array(
            'type'  => 'fieldset',
            'label' => 'Video',
            'elements' => array(
                'video[url]' => array(
                    'type'  => 'text',
                    'value' => isset( $video['url'] ) ? $video['url'] : '',
                    'label' => 'Video url (youtube, vimeo)'
                ),
                'video[width]' => array(
                    'type'  => 'text',
                    'value' => isset( $video['width'] ) ? $video['width'] : 710,
                    'label' => 'Width',
                    'validators' => array(
                        array('is_numeric', array(), array('priority must be a number')),
                    ),
                ),
                'video[height]' => array(
                    'type'  => 'text',
                    'value' => isset( $video['height'] ) ? $video['height'] : 385,
                    'label' => 'Height',
                    'validators' => array(
                        array('is_numeric', array(), array('priority must be a number')),
                    ),
                )
            )
        );

        return $fieldset;
    }



    /**
     *
     *
     * @return unknown
     */
    private function _templatePath() {
        return  'Application/plugin/Video/template';
    }


}
