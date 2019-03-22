<?php

namespace App\Controllers;

use App\Models\Exercise;
use App\Core\Controller;
use Rakit\Validation\Validator;

/**
 * Exercise controller
 */
class ExerciseController extends Controller {

    public function index() {
        $exercises = Exercise::all();
        
        $this->view("exercise/index", ["exercises" => $exercises]);
    }

    public function getall() {
        $exercises = Exercise::select('id', 'name', 'created_at')->get()->toArray();
        
        $return = [
            'draw' => 1,
            'recordsTotal' => count($exercises),
            'recordsFiltered' => count($exercises),
            'data' => $exercises
        ];

        echo json_encode($return);
        die();
    }

    public function create() {

        $validator = new Validator;

        $validation = $validator->validate($_POST, [
            'name' => 'required|min:2|max:255'
        ]);

        $this->formData = $validation->getValidatedData();

        $response = [];

        if ($validation->fails()) {
            // handling errors
            $response['status'] = 'error';
            $response['errors'] = $validation->errors()->toArray();
        } else {
            $exercise = $this->model('Exercise');

            $exercise->name = $this->formData['name'];
            $exercise->save();

            $response['status'] = 'ok';
            $response['data'] = $exercise->toArray();
        }
        
        echo json_encode($response);
        die();
    }

    public function edit($id) {

        $exercise = Exercise::findOrFail($id);

        $response = [
            'status'    => 'ok',
            'data'      => $exercise->toArray()
        ];

        if (isset($_POST['name'])) {

            $validator = new Validator;

            $validation = $validator->validate($_POST, [
                'name' => 'required|min:2|max:255'
            ]);
            
            $this->formData = $validation->getValidatedData();

            if ($validation->fails()) {
                // handling errors
                $response['status'] = 'error';
                $response['errors'] = $validation->errors()->toArray();
            } else {
                $exercise->name = $this->formData['name'];
                $exercise->save();

                $response['status'] = 'ok';
                $response['data'] = $exercise->toArray();
            }
        }

        echo json_encode($response);
        die();
    }
    
    public function delete($id) {

        if (!isset($_POST['id'])) {
            return false;
        }
        
        $exercise = Exercise::findOrFail($id);
        
        try {
            // We have to use transactions here because won't to have any lost data if something went wrong
            Capsule::beginTransaction();
            
            // Delete all workout day exercises
            $exercise->workoutDays()->detach();
            
            // And finally... delete exercise!
            if ($exercise->delete()) {
                $response = [
                    'status' => 'ok',
                    'message' => 'Exercise with id #' . $id . ' has been deleted successfully!'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Error occurred when try to delete exercise with id #' . $id . '!'
                ];
            }

            Capsule::commit();

        } catch (\Exception $e){
            // Something went wrong, rollback all changes
            Capsule::rollback();
            
            $response = [
                'status' => 'error',
                'message' => 'Error occurred when try to delete exercise with id #' . $id . '!'
            ];

        }
        
        echo json_encode($response);
        die();
    }

}
