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
        @list( , , $action, $id ) = $request->pathParts();

        $method = $request->isPost() ? 'post' : 'get';

        if ( $action && ($handler=$method . ucfirst($action)) && method_exists( $this, $handler )) {
            return $this->$handler( $request, $extra );
        }
        elseif ( method_exists( $this, $action ) ) {
            return $this->$action( $request, $extra );
        }

        return $this->$method( $request, $extra );
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
     *
     *
     * @param object  $request
     * @param unknown $config
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
        }
        else {
            $error_string = '?error=' . urlencode( json_encode( $errors ) );
        }

        $this->response()
        ->redirect( '/' . join('/', array( 'admin', $controller, $action, $id, $error_string )) );
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
