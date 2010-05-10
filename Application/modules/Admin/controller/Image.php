<?php
class Controller_Admin_Image extends Nano_Controller{
    public function indexAction(){
        if( ! Nano_Session::session()->auth ){
            $this->_redirect( '/admin/login' );
        }

        $this->getView()->content = "Hello World";
    }

    protected function addAction(){
        $request = $this->getRequest();

        if( $request->isPost() ){
            $data = new Model_ImageData();

            $file = (object) $_FILES['image'];

            $data->imageId = 1;
            $data->mime    = $file->type;
            $data->width   = 100;
            $data->height   = 100;
            $data->data     = file_get_contents( $_FILES['image']['tmp_name']);
            $data->filename  =  $file->name;
            $data->type       = 1;

            $data->save();

            var_dump( $request->getPost() );
            var_dump( $_FILES );

            exit;

        }

        $form = new Nano_Form();

        $form->addElements(array(
            'image' => array(
                'type'  => 'file'
            ),
            'upload'    => array(
                'type'  => 'submit',
                'value' => 'Submit'
            )
        ));

        $this->getView()->form = $form;
    }

    public function viewAction(){
        $request= $this->getRequest();

        $image = new Model_ImageData( array( 'imageId' => 1, 'type' => 1 ) );

        $img = $image->search();
        $img = $img[0];


        header( 'Content-Type: ' . $img->mime );
        echo $img->data;

        //echo $imageData->type;

        exit;


        $project = new Model_Project(array('id'=>$request->id));

        //@todo fix this wiht a real user
        $project->userId = 1;

        if( null == $project->name ){
            $this->_pageNotFound( "Invalid project id" );
        }

        $image = new Model_Image(array('projectId' => $project->id ));

        $this->getView()->images    = $image->search();
        $this->getView()->projects  = $project->search();
        $this->getView()->project   = $project;
    }

    public function postDispatch(){
        $this->getView()->actions = $this->getMenu();
    }

    private function getMenu(){
        return array(
            'add'   => array(
                'target' => '/admin/image/add',
                'value'  => 'Add new image'
            )
        );
    }
}
