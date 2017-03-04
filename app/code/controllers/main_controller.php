<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of main_controller
 *
 * @author Kaspar
 */
class main_controller {
    //put your code here
    
    private $model;
    
    public function __construct($model) {
        $this->model = $model;
    }
    
    public function test(){
        $this->model->response = "test response";
    }
}
