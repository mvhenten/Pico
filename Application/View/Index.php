<?php
class View_Index extends Nano_View{
    public function post( $request, $config ){
        $errors = array();
        $post = $request->getPost();
        if( $post->email ){
            if( strlen($post->email) == 0 ){
                $errors['email'] = 'E-mail address is missing!';
            }
            else if( !preg_match('/^.+@.{2,255}\.\w{2,4}/', $post->email)){
                $errors['email'] = 'It seems that e-mail is not correct';
            }
            if( strlen( $post->name ) <= 2 ){
                $errors['name'] = 'That is very short!';
            }

            if( empty($errors) ){
                $message = '';

                foreach( $post as $key => $value ){
                    $message .= "$key:\n$value\n";
                }

                @mail(
                    'wedding@ischen.nl',
                    'Mail from the wedding site: RSVP',
                    $message
                );
            }

        }

        $this->template()->formErrors = $errors;
        return $this->get( $request, $config);

    }
    public function get( $request, $config ){
        if( $request->primary ){
            if( is_numeric($request->primary) ){
                $item = new Model_Item( $request->primary );
            }
            else{
                $item = Nano_Db_Query::get('Item')->where('slug', $request->primary)->current();
            }
        }

        if( $item ){
            $this->template()->item = $item;
            var_dump( $item );
        }

        $this->template()->item = $item;


        if( $request->primary == 'home' || ( $item && $item->type == 'page' ) ){
            $images = Nano_Db_Query::get('Item')
                ->where('type', 'image')
                ->limit(9)
                ->order('RAND');

            $this->template()->images = $images;
//            return $this->template()->render('template/home');
        }
        else if( $item && $item->type == 'label' ){
            $images = Nano_Db_Query::get('ImageLabel')
                    ->leftJoin( 'item', 'id', 'image_id')
                    ->where( 'label_id', $item->id)
                    ->where( 'item.id !=', 'NULL' )
                    ->order('priority')
                    ->setModel( new Model_Image() );

            $this->template()->images = $images;
            $this->template()->labels = Nano_Db_Query::get('Item')->where('type', 'label');
        }

        $template_path = $config->settings->template_path . '/';
        $template      = $this->template();

        $tpl = $template_path . 'home';

        if( $item ){
            $tpl = $template_path . $item->slug;

            if( ! $template->templateExists( $tpl ) ){
                $tpl = $template_path . $item->type;
            }

        }

        return $this->template()->render( $tpl );
    }

}
