<?php
/**
 * Application/plugin/News/Plugin.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package default
 *
 * This plugin represents a simple "news" feature - the parent page
 * is an item of type 'news'. it's children are set trough the 'parent' column.
 *
 * News pages don't have content items themselves. instead, the news-item children
 * are their content.
 */


class News_Plugin extends Pico_View_Admin_Base {


    /**
     * Delete news item or news-items
     *
     * @param object  $request
     * @param unknown $config
     */
    public function delete( Nano_App_Request $request, $config ) {
        @list( , $controller, $action, $id ) = $request->pathParts();

        $item = $this->model('Item', $id );
        $parent = $item->parent;
        $item->delete();

        if ( $parent ) {
            $this->response()
            ->redirect( '/' . join('/', array( 'admin', 'news', 'edit', $parent )) );
        }

        $this->model('Item')->delete( array( 'parent' => $item->id ) );
        $this->response()
        ->redirect( '/' . join('/', array( 'admin', 'news' )) );

    }


    /**
     * primary list of news pages
     *
     * @param object  $request
     * @param unknown $config
     * @return unknown
     */
    public function get( Nano_App_Request $request, $config ) {
        $items = $this->model('Item')->search(array(
                'where' => array('type' =>  'news' ),
                'order' => 'priority'
            ));

        $this->template()->items = $items;
        $this->template()->addTemplatePath( $this->_templatePath() );

        return $this->template()->render('list');
    }


    /**
     * save news-page info. N.B. the priority field has no relevance here,
     * therefore it is set to 0.
     *
     * @param object  $request
     * @param unknown $config
     * @return unknown
     */
    public function post( Nano_App_Request $request, $config ) {
        @list( , $controller, $action, $id ) = $request->pathParts();

        if ( null == $id ) {
            throw new Exception('invalid post');
        }

        $item = $this->model('Item', $id );
        $post = (object) array_merge( $request->post, array( 'priority' => 0, 'description' => '' ) );

        $form   = $this->_getForm( $item );
        $errors = $form->validate( $post );

        if ( count($errors) == 0 ) {
            $this->_storeItem( $item, $post );

            if ( $request->isAjax() ) {
                return;
            }

            $this->response()
            ->redirect( '/' . join('/', array( 'admin', $controller, $action, $id )) );
        }

        $this->template()->form   = $form;
        $this->template()->item   = $item;
        $this->template()->errors = $errors;

        return $this->getEdit( $request, $config );
    }


    /**
     * Edit a news page - and display it's children.
     *
     * @param unknown $request
     * @param unknown $config
     * @return unknown
     */
    public function getEdit( $request, $config ) {
        @list( , , , $id ) = $request->pathParts();


        if ( !$request->isPost() ) {
            $page = $this->model('Item', $id );
            $this->template()->item = $page;
            $this->template()->form = $this->_getForm( $page );
        }
        else {
            $page = $this->template()->item;
        }

        $items = $this->model('Item')->search(array(
                'where' => array('type' =>  'news-item', 'parent' => $page->id ),
                'order' => 'priority'
            ));

        $page = $this->template()->items = $items;
        $this->template()->addTemplatePath( $this->_templatePath() );

        return $this->template()->render('edit');
    }





    /**
     * Create a 'news' page
     *
     * @param object  $request
     * @param unknown $config
     */
    public function create(Nano_App_Request $request, $config ) {
        @list( , $controller ) = $request->pathParts();

        $item = $this->_createItemOfType( 'news', null );

        $this->response()->redirect( '/admin/' . join('/', array('news', 'edit', $item->id )) );
    }



    /**
     * Create a 'news-item' page
     *
     * @param object  $request
     * @param unknown $config
     */
    public function getItem(Nano_App_Request $request, $config ) {
        @list( , , , $id ) = $request->pathParts();

        $page = $this->model('Item', $id );

        if ( $page === 'null' && $page->type !== 'news' ) {
            throw new Exception( 'Cannot create a child for ' . $id );
        }

        $this->_createItemOfType( 'news-item', $page );
        $this->response()->redirect( '/admin/' . join('/', array('news', 'edit', $page->id )) );
    }



    /**
     * Store a news-item. News items don't have content, instead use the 'descriptoin' field.
     *
     * @param object  $request
     * @param unknown $config
     */
    public function postItem(Nano_App_Request $request, $config ) {
        @list( , , , $id ) = $request->pathParts();


        $item = $this->model('Item', $id );

        if ( $item === 'null' && $item->type !== 'news' ) {
            throw new Exception( 'Cannot create a child for ' . $id );
        }

        $post = (object) $request->post;
        $this->_storeItem( $item, $post );

        $this->response()->redirect( '/admin/' . join('/', array('news', 'edit', $item->parent )) );
    }


    /**
     * Generic create for both 'news' and 'news-items'
     *
     * @param unknown $type
     * @param unknown $parent
     * @return unknown
     */
    private function _createItemOfType( $type, $parent ) {
        $item = $this->model('Item', array(
                'name'          => "New $type",
                'description'   => 'Description for untitled new "' . $type . ' page"',
                'type'          => $type,
                'slug'          => 'untitled',
                'parent'        => isset( $parent ) ? $parent->id : null
            ));

        $item->store();

        return $item;
    }





    /**
     * Return a 'pico-form-item' object.
     *
     * @param unknown $page
     * @return unknown
     */
    private function _getForm( $page ) {
        $form = new Pico_Form_Item( $page );

        $form->removeChildren( array('description') );
        $form->removeChildren( array('priority') );

        return $form;
    }



    /**
     * Template path of this module.
     *
     * @return unknown
     */
    private function _templatePath() {
        return  'Application/plugin/News/template';
    }


}
