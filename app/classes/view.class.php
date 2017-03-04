<?php

class View {
    private static function require_files($name){
        $spaces = [
            ["location" => "controllers", "extension" => "_controller.php"],
            ["location" => "models", "extension" => "_model.php"],
            ["location" => "views", "extension" => "_view.php"],
        ];
        
        foreach($spaces as $space){
            $path = CODE_FOLDER."/".$space["location"]."/".$name.$space['extension'];
            if(!file_exists($path)){
                return "No file named ".$name.$space['extension']." in ".$space['location'];
            } else {
                require_once $path;
            }
        }
    }
    
    private static function get_template($name){
        $file = CODE_FOLDER."/views/templates/".$name."_template.html";
        if(file_exists($file)){
            return file_get_contents($file);
        }
        
        return false;
    }
    
    private static function generate_template_from($view, $template){
        $output = preg_replace_callback('/{{(.*?)[\|\|.*?]?}}/', function($m) use($view) {
            if(method_exists($view, $m[1])){
                $method = $m[1];
                $value = $view->{$method}();
                return $value;
            }else{
                return $m[0]; 
            }
        }, $template);
        
        return $output;
    }
    
    private static function initialize($name){
        $model = $name."_model";
        $model = new $model();
        
        $controller = $name."_controller";
        $controller = new $controller($model);
        
        $view = $name."_view";
        $view = new $view($controller, $model);
        
        return ["model" => $model, "controller" => $controller, "view" => $view];
    } 
    
    public static function call_method_in($name, $method){
        View::require_files($name);
        
        $mvc = View::initialize($name);
        
        if(method_exists($mvc['controller'], $method)){
            $mvc['controller']->{$method}();
        } else {
            Route::pop_url();
        }
        
        echo View::generate_template_from($mvc["view"], View::get_template($name));
    }


    public static function create($name){
        View::require_files($name);
        
        $mvc = View::initialize($name);
        
        echo View::generate_template_from($mvc["view"], View::get_template($name));
    }
}
