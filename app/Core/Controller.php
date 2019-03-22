<?php
namespace App\Core;


/** Access views, controllers and models and its methods to render the page. */
class Controller {

    /**
     * @var array 
     */
    protected $formErrors = [];
    
    /**
     * @var array 
     */
    protected $formData = [];
    
    /**
     * @var array
     */
    protected $customJs = [];
    
    /**
     * @var array 
     */
    protected $customCss = [];
    
    /**
     * Load new Model.
     * @param string $model The Name of the model.
     * @return $model Returns an instance of that model object.
     */
    public function model($model) {
        require_once "../app/Models/" . $model . ".php";
        $fullModelName = "App\Models\\" . $model;
        return new $fullModelName();
    }

    /**
     * Load a View
     * @param string $view The filename of the view.
     * @param mixed $data The data the controller wants to push to the View.
     * @return void
     */
    public function view($view, $data = []) {
        require_once "../app/Views/layout/main.php";
        //require_once "../app/views/" . $view . ".php";
    }

    /**
     * Redirect to another page
     * @param string $url
     * @param type $data
     */
    public function redirect($url = '', $data = []) {
        foreach ($data as $key => $value) {
            $url .= '/' . $value;
        }
        
        header('Location: ' . $url);
    }
}
