<?php
/**
 * Application/lib/View/Admin/Image.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Bison
 */


class Pico_View_Admin_Image extends Pico_View_Admin_Base{

    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     * @return unknown
     */
    public function get( Nano_App_Request $request, $config ) {
        @list( , , , $id ) = $request->pathParts();
        $values = array();

        if ( $id && is_numeric( $id ) ) {
            $label = $this->model('Item', $id );
            $pager = $label->pager('images');
        }
        else {
            $where = array('type' => 'image', 'order' => '-inserted', 'limit' => 35 );
            $pager  = $this->model('Item')->pager('search', $where );
        }

        $labels   = $this->model('Item')->search( array('type' => 'label') );
        $template = $this->template();

        $template->id     = $id;
        $template->labels = $labels;
        $template->images = $pager->getPage($request->page);
        $template->pager  = $pager;

        return $template->render( 'image/list');
    }


    /**
     *
     *
     * @param unknown $request
     * @param unknown $config
     * @return unknown
     */
    public function upload( $request, $config ) {
        if ( ! $request->isPost() ) {
            return $this->template()->render( 'image/upload');
        }

        $file = (object) $_FILES['image'];

        if ( $file->error ) {
            $this->response()->redirect('/admin/image?error=' . $file->error );
        }

        $post = $request->post();

        if ( null !== ($info = Nano_Gd::getInfo( $file->tmp_name ))) {
            $item = $this->model('Item', array(
                    'name'     => $file->name,
                    'visible'   => 0,
                    'type'      => 'image',
                    'inserted'  => date('Y-m-d H:i:s'),
                    'slug'      => Nano_Util_String::slugify($file->name)
                ))->store();

            $this->_storeImageData( $item, $file );
        }

        $this->response()->redirect('/admin/image' );
        exit;
    }


    /**
     *
     *
     * @param unknown $request
     * @param unknown $config
     */
    public function postEdit( $request, $config ) {
        @list( , , , $id ) = $request->pathParts();

        $post   = (object) $request->post();
        $item   = $this->model('Item', $id );
        $labels = array_keys((array) $post->labels);


        $this->model('ImageLabel')->delete(array(
                'image_id' => $id
            ));

        foreach ( $labels as $label_id ) {
            $n = $this->model('ImageLabel', array(
                    'image_id' => $id,
                    'label_id' => $label_id,
                    'priority' => 0
                ))->store();
        }

        parent::post( $request, $config );
    }


    /**
     *
     *
     * @param unknown $request
     * @param unknown $config
     * @return unknown
     */
    protected  function getEdit(  $request, $config ) {
        @list( , , , $id ) = $request->pathParts();

        $template = $this->template();

        $image           = new Pico_Model_Item( $id );
        $labels          = $this->model('Item')->search(array('type' => 'label'));
        $labels_selected = $image->labels();

        $labels_selected->setFetchMode( PDO::FETCH_COLUMN, 0 );
        $labels_selected_ids = $labels_selected->fetchAll();

        $form = new Pico_Form_Item( $image );

        $fieldset = array(
            'type'  => 'fieldset',
            'elements'=>array(),
            'label' => 'labels'
            .' <a onclick="$(this.parentNode.parentNode).find(\'input\').attr(\'checked\', true)" href="#all">(select all)</a>'
            .' <a onclick="$(this.parentNode.parentNode).find(\'input\').attr(\'checked\',0)" href="#none">(select none)</a>'
        );

        foreach ( $labels as $label ) {
            $fieldset['elements']['labels[' . $label->id . ']'] = array(
                'type'  => 'checkbox',
                'value' => (bool) in_array( $label->id, $labels_selected_ids),
                'label' => $label->name
            );
        }

        // append fieldset and move the submit button...
        $block = current($form->children(array('id'=> 'item-values')));
        $block->addElement('fieldset-labels', $fieldset );

        $submit = current($form->removeChildren(array('name'=>'save-changes')));
        $block->addChild($submit);

        $template->image = $image;
        $template->form = $form;
        $template->render('image/edit');
        return $template;
    }


    /**
     *
     *
     * @param unknown $request
     * @param unknown $config
     */
    public function postOrder( $request, $config ) {
        @list( , , , $label_id ) = $request->pathParts();
        $post = (object) $request->post();

        $label      = $this->model('Item', $label_id );
        $image_ids  = array_keys( $post->priority );

        $this->model('ImageLabel')->delete(array(
                'image_id' => $image_ids,
                'label_id' => $label_id
            ));

        foreach ( $post->priority as $image_id => $priority ) {
            $this->model('ImageLabel', array(
                    'label_id' => $label_id,
                    'image_id' => $image_id,
                    'priority' => $priority
                ))->store();
        }

        $this->response()
        ->redirect( '/admin/image/list/' . $id );
    }


    /**
     *
     *
     * @param object  $request
     * @param unknown $extra
     * @return unknown
     */
    public function bulk( Nano_App_Request $request, $extra ) {
        @list( , , , $label_id ) = $request->pathParts();
        $post = $request->post;

        if ( isset( $post['action-labels'] ) ) {
            return $this->labels( $request, $extra );
        }
        else if ( isset( $post['apply'] ) ) {
                $image_ids  = (array) json_decode($post['images']);
                $labels     = isset($post['labels']) ?array_keys($post['labels']) : array();

                $this->_updateLabels( $image_ids, $labels );
            }

        $this->response()
        ->redirect( '/admin/image/list/' . $label_id );
    }


    /**
     *
     *
     * @param unknown $request
     * @param unknown $config
     * @return unknown
     */
    public function labels( $request, $config ) {
        @list( , , , $label_id ) = $request->pathParts();
        $post   = (object) $request->post();

        $selected_query = $this->model('ImageLabel')->search(array(
                'where' => array( 'image_id' => $post->image ),
                'group' => 'label_id'
            ));

        $selected_query->setFetchMode( PDO::FETCH_COLUMN, 1 );
        $selected_labels = $selected_query->fetchAll();

        $labels = array();

        foreach ( $this->_items('label')  as $label ) {
            $labels[$label->id] = (object) array(
                'selected'  => in_array( $label->id, $selected_labels ),
                'name'      => $label->name,
                'id'        => $label->id
            );
        }

        $template = $this->template();

        $template->labels   = $labels;
        $template->images   = json_encode($post->image);
        $template->label_id = $label_id;

        return $this->template()->render( 'image/bulk');
    }


    /**
     *
     *
     * @param unknown $request
     * @param unknown $config
     * @return unknown
     */
    public function settings( $request, $config ) {

        $this->template()->settings = array(
            'size'  => array(
                'portfolio' => array( 'width' => 900, 'height' => 1 ),
                'xtra-large' => array( 'width' => 900, 'height' => 1 )
            )
        );

        return $this->template()->render( 'image/settings');
    }


    /**
     *
     *
     * @param unknown $image_ids
     * @param unknown $label_ids
     */
    private function _updateLabels( $image_ids, $label_ids ) {
        $this->model('ImageLabel')->delete(array('image_id' => $image_ids));

        foreach ( $label_ids as $label_id ) {
            foreach ( $image_ids as $image_id ) {
                $this->model('ImageLabel', array(
                        'image_id'  => $image_id,
                        'label_id'  => $label_id
                    ))->store();
            }
        }
    }


    /**
     *
     *
     * @param unknown $item
     * @param unknown $file
     */
    private function _storeImageData( $item, $file ) {
        $gd  = new Nano_Gd( $file->tmp_name );

        list( $width, $height ) = array_values( $gd->getDimensions() );
        $exif = new Nano_Exif( $file->tmp_name );

        $gd   = $this->_rotateImageData( $exif, $gd );
        $src  = ($info[2] == IMAGETYPE_PNG) ?  $gd->getImagePNG() : $gd->getImageJPEG(100);
        $type = ($info[2] != IMAGETYPE_PNG) ? 'image/jpeg' : $file->type;

        $this->model('ImageData', array(
                'image_id'  => $item->id,
                'size'      => $file->size,
                'mime'      => $type,
                'width'     => $width,
                'height'    => $height,
                'data'      => $src,
                'filename'  => $file->name,
                'type'      => 'original'
            ))->store();
    }


    /**
     *
     *
     * @param object  $exif
     * @param object  $gd
     * @return unknown
     */
    private function _rotateImageData( Nano_Exif $exif, Nano_Gd $gd ) {
        switch ( $exif->orientation() ) {
        case 2:
            return $gd->flipHorizontal();
        case 3:
            return $gd->rotate( 180 );
        case 4:
            return $gd->flipVertical();
        case 5:
            return $gd->flipVertical()->rotate(90);
        case 6:
            return $gd->rotate( -90 );
        case 7:
            return $gd->flipHorizontal()->rotate( -90 );
        case 8:
            return $gd->rotate( 90 );
        }

        return $gd;
    }


}
