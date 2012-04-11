<?php
class Builder_Editor_Decorator_Block extends Builder_Abc {
    protected $_style;
    protected $_content;
    protected $_id_title;

    public function as_string(){
        $style   = $this->style;
        $content = $this->content;

        ob_start();

        ?>
        <div class="element-block" style="<?php echo $style->width ?>">
            <div class="margin" style="<?php echo $style->margin ?>">
            </div>
            <div class="inner">
                <div class="handle">
                    <?php echo $this->id_title ?>
                </div>
                <div class="height-spacer" style="<?php echo $style->height ?>">
                    <div class="padder" style="<?php echo $style->padding ?>">
                        <div class="body">
                            <?php echo $this->content ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php

        return ob_get_clean();
    }

    protected function _build__id_title(){
        return '#Element ' . $this->arg('id');
    }

    protected function _build__style(){
        return new Builder_Editor_Style( $this->arg('style') );
    }

    protected function _build__content(){
        $children = $this->arg('children');
        error_log( '$children: ' .  print_r($children, true ) );

        $html = '';

        foreach( $children as $child ){
            $block = new Builder_Editor_Decorator_Block( $child );
            $html .= $block;
        }

        error_log( 'child: ' .  print_r($html, true ) );


        return $html;
    }


}
