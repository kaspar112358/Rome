<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of routes
 *
 * @author Kaspar
 */
class Route {
    
    /*
     * 
     * Getting the current URL PATH
     * 
     */
    private static function url(){
        return Route::fix_path("http://".filter_input(INPUT_SERVER, "HTTP_HOST").filter_input(INPUT_SERVER, "REQUEST_URI"));
    }
    
    /*
     * 
     * Checking if current URL is Root Url
     * 
     */
    
        
    private static function is_root_url(){
        return Route::url() == Route::fix_path(ROOT_URL);
    }

    /*
     * 
     * Fixing all paths to have "/" at the end, if not present
     * 
     */

    private static function fix_path($path){
        $p = str_replace('\\','/',trim($path));
        
        return (substr($p,-1) !='/' ) ? $p.'/' : $p;
    }
    
    private static function match($path){
        return Route::fix_path(ROOT_URL.$path) == Route::url();
    }
    
    private static function sanatize_url(){
        $root_parts = count(array_filter(explode("/", ROOT_URL)));
        $current_parts = array_filter(explode("/", Route::url()));
        
        return array_slice($current_parts, $root_parts);
    }
    
    private static function extract_parameters($path){
        $url = Route::sanatize_url();
        $parts = array_values(array_filter(explode("/", $path)));
        $parameters = [];
        
        foreach($parts as $key => $part){
            if (mb_strpos($part, "{") !== false) {
                $parameters[] = $url[$key];
            }
        }
        
        return $parameters;
    }
    
    public static function pop_url($level = 1){
        $urlBits = explode("/", Route::url());
        unset($urlBits[count($urlBits)-1]);
        
        $i = 0;
        
        do {
            array_pop($urlBits);
            
            $i++;
        } while($i != $level);

        header("Location:".implode("/", $urlBits));
    }
    
    public static function get($path, $function){
        if (!Route::is_root_url() && mb_strpos($path, "{") !== false) {
            $parameters = Route::extract_parameters($path);
            
            return call_user_func_array($function, $parameters);
        } else if(!Route::match($path)) {
            return;
        }

        return $function();
    }
    
    public static function ressource($path, $ressource){
        $url = array_values(array_filter(explode("/",str_replace(ROOT_URL.$path, "", Route::url()))));
        if(Route::match($path)){
            return View::create($ressource);
        } else if(count($url) > 1){
            return;
            
        } else if(isset($url[0])){
            
            View::call_method_in($ressource, $url[0]);
        }
        
        return;
    }
}
