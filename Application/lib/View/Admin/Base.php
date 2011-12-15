<?php
error_reporting(E_ALL | E_STRICT);

class Pico_View_Admin_Base extends Nano_App_View{
    public function __construct( $request, $config ){
        // awful hacks here, for now.
        @session_start();

        if( !isset( $_SESSION['user'] ) ){
            $this->response()->redirect( '/admin/user/login' );
        }

        $this->template()->request = $request;
        $this->template()->templatePath = 'Application/Admin/template';

        parent::__construct( $request, $config );
    }

	protected function dispatch( Nano_App_Request $request, $extra ) {
        @list( , , $action, $id ) = $request->pathParts();

        $method = $request->isPost() ? 'post' : 'get';

        if( $action && ($handler=$method . ucfirst($action)) && method_exists( $this, $handler )){
            return $this->$handler( $request, $extra );
        }
        elseif( method_exists( $this, $action ) ){
            return $this->$action( $request, $extra );
        }

		return $this->$method( $request, $extra );
	}


    public function delete( Nano_App_Request $request, $config ){
        @list( , $controller, $action, $id ) = $request->pathParts();



        $this->model('Item', $id )->delete();

        $this->response()
            ->redirect( '/' . join('/', array( 'admin', $controller )) );
    }

    public function post( Nano_App_Request $request, $config ){
        @list( , $controller, $action, $id ) = $request->pathParts();

        if( null == $id ){
            throw new Exception('invalid post');
        }

        $item = $this->model('Item', $id );
        $post = (object) array_merge( array('visible' => 1), $request->post);

        if( preg_match( '/^untitled/',  $post->slug ) ){
            $item->slug = Nano_Util_String::slugify( $post->name );
        }
        else{
            $item->slug = $post->slug;
        }

        $item->name         = $post->name;
        $item->description  = $post->description;
        $item->visible      = (bool) $post->visible;
        $item->store(array( 'id' => $id ) );

        $this->response()
            ->redirect( '/' . join('/', array( 'admin', $controller, $action, $id )) );
    }


    public function content( $request, $config ){
        @list( , $controller, $action, $id ) = $request->pathParts();

        $content = $this->model('ItemContent', array('item_id' => $id ));
        $content->store();

        $this->response()->redirect( '/admin/' . join('/', array($controller, 'edit', $id )) );
    }

    public function create(Nano_App_Request $request, $config ){
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

    protected function _items( $type ) {
        return $this->model('Item')->search(array(
            'where' => array('type' => $type ),
            'order' => 'updated'
        ));
    }
}
