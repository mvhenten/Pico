<?php
class View_Index extends Nano_View{
    public function get( $request, $config ){
        if( $request->primary ){
            if( is_numeric($request->primary) ){
                $item = new Model_Item( $request->primary );   
            }
            else{
                $item = Nano_Db_Query::get('Item')->where('slug', $request->primary)->current();
            }
        }
        $this->template()->item = $item;
        
        //var_dump($item);
        
        if( $request->primary == 'home' || $item->type == 'page' ){
            $images = Nano_Db_Query::get('Item')
                ->where('type', 'image')
                ->limit(9)
                ->order('RAND');
            
            $this->template()->images = $images;
            return $this->template()->render('template/home');
        }
        //else if( $item->type == 'image' ){
        //    
        //}
        else if( $item->type == 'label' ){
            $images = Nano_Db_Query::get('ImageLabel')
                    ->leftJoin( 'item', 'id', 'image_id')
                    ->where( 'label_id', $item->id)
                    ->where( 'item.id !=', 'NULL' )
                    ->order('priority')
                    ->setModel( new Model_Image() );
                                
            $this->template()->images = $images;
            $this->template()->labels = Nano_Db_Query::get('Item')->where('type', 'label');
        }

        return $this->template()->render('template/' . $item->type);            

    }    
}
