<?php
/**
 * Application/lib/View/Admin/Base.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


error_reporting(E_ALL | E_STRICT);

class Pico_View_Admin_Base extends Nano_App_View{

    /**
     *
     *
     * @param unknown $request
     * @param unknown $config
     */
    public function __construct( $request, $config ) {
        // awful hacks here, for now.
        @session_start();

        if ( !isset( $_SESSION['user'] ) ) {
            $this->response()->redirect( '/admin/user/login' );
        }

        $this->template()->request = $request;
        $this->template()->templatePath = 'Application/Admin/template';

        parent::__construct( $request, $config );
    }


    /**
     *
     *
     * @param object  $request
     * @param unknown $extra
     * @return unknown
     */
    protected function dispatch( Nano_App_Request $request, $extra ) {
        $method = $this->_getDispatchHandler( $request );

        if ( null !== $method ) {
            return $this->$method( $request, $extra );
        }
        else {
            $method = $request->isPost() ? 'post' : 'get';
            return $this->$method( $request, $extra );
        }
    }


    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     */
    public function delete( Nano_App_Request $request, $config ) {
        @list( , $controller, $action, $id ) = $request->pathParts();

        $this->model('Item', $id )->delete();

        $this->response()
        ->redirect( '/' . join('/', array( 'admin', $controller )) );
    }


    /**
     * # todo all posts end here.
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
        $post = (object) array_merge( array('visible' => 1), $request->post);

        $form   = new Pico_Form_Item( $item );
        $errors = $form->validate( $post );
        $error_string = '';

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
     *
     *
     * @param object  $request
     * @return unknown
     */
    protected function _getDispatchHandler( Nano_App_Request $request ) {
        @list( , , $action ) = $request->pathParts();

        $method = $request->isPost() ? 'post' : 'get';

        if ( $action && ($handler=$method . ucfirst($action)) && method_exists( $this, $handler )) {
            return $handler;
        }
        elseif ( method_exists( $this, $action ) ) {
            return $action;
        }
    }


    /**
     *
     *
     * @param unknown $item
     * @param unknown $post
     */
    private function _storeItem( $item, $post ) {
        if ( preg_match( '/^untitled/',  $post->slug ) ) {
            $item->slug = Nano_Util_String::slugify( $post->name );
        }
        else {
            $item->slug = $post->slug;
        }

        $item->name         = $post->name;
        $item->description  = $post->description;
        $item->visible      = (bool) $post->visible;
        $item->priority     = intval( $post->priority );
        $item->store(array( 'id' => $item->id ) );

    }


    /**
     *
     *
     * @param unknown $request
     * @param unknown $config
     */
    public function content( $request, $config ) {
        @list( , $controller, $action, $id ) = $request->pathParts();

        $content = $this->model('ItemContent', array('item_id' => $id ));
        $content->store();

        $this->response()->redirect( '/admin/' . join('/', array($controller, 'edit', $id )) );
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
                'type'          => $controller,
                'slug'          => 'untitled'
            ));

        $item->store();

        $this->response()->redirect( '/admin/' . join('/', array($controller, 'edit', $item->id )) );
    }


    /**
     *
     *
     * @param unknown $type
     * @return unknown
     */
    protected function _items( $type ) {
        return $this->model('Item')->search(array(
                'where' => array('type' => $type ),
                'order' => 'updated'
            ));
    }


}
