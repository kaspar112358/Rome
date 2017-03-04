<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of main_view
 *
 * @author Kaspar
 */
class main_view {
    //put your code here
    
    private $controller;
    private $model;
    
    public function __construct($controller, $model) {
        $this->controller = $controller;
        $this->model = $model;
    }
    
    public function get_content(){
        return $this->model->response;
    }
    
    public function get_content_two(){
        return "test bæla";
    }
}
