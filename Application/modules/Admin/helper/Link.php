<?php
/**
 * @class Helper_Link
 * Creates a link, adding a class 'active' for active links.
 */
class Helper_Link extends Nano_View_Helper{
    /**
     * Creates html for a link. If current URL is the same as target,
     * a class 'active' is added.
     *
     * @param string $name Contents for the link
     * @param mixed $target An array for Helper_Url or string for link
     * @param array $attributes Optional extra attributes for the html element
     */
    function Link( $name, $target, $attributes = null ){
        $request = $this->getView()->getRequest();
        $attr = is_array( $attributes ) ? $attributes : array();
        $url  = $target;

        if( is_array( $target ) ){
            $url = $this->getView()->url( $target );
        }

        if( $url == $request->getRequestUri() ){
            $class = array();

            if( isset( $attr['class'] ) ){
                $class[] = $attr['class'];
            }
            $class[] = 'active';

            $attr['class'] = join( ' ', $class );
        }

        $attr['href'] = $url;

        $element = new Nano_Element( 'a', $attr, $name );

        return $element;
    }
}
