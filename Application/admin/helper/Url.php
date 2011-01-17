<?php
class Helper_Url extends Nano_View_Helper{
    function Url( $url = array() ){
        $request = $this->getView()->getRequest();

        $base = (array) $request->getRouter();
        $route = array_merge( $base, $url );

        $base = array_intersect_key( $route, $base );//holds controller,etc
        $second = array_diff_key( $route, $base );//key value pairs url

        $module = isset( $base['module'] ) ? '/' . $base['module'] : '';
        unset( $base['module'] );

        foreach( $second as $key => $value ){
            $base[] = $key;
            $base[] = $value;
        }

        $url = $module . '/' . join("/", $base );

        return rtrim( $url, '/');
    }
}
