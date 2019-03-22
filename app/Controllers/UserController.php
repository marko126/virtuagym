<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Controller;
use Rakit\Validation\Validator;

/**
 * User controller
 */
class UserController extends Controller {

    public function index() {
        $users = User::all();

        $this->view("user/index", ["users" => $users]);
    }

    public function getall() {
        $users = User::select('id', 'first_name', 'last_name', 'email', 'status')->get()->toArray();

        $return = [
            'draw' => 1,
            'recordsTotal' => count($users),
            'recordsFiltered' => count($users),
            'data' => $users
        ];

        echo json_encode($return);
        die();
    }

    /* public function create() {

      if (isset($_POST['submit'])) {

      $validator = new Validator;

      $validation = $validator->validate($_POST, [
      'first_name'        => 'required|min:2|max:50',
      'last_name'         => 'required|min:2|max:50',
      'email'             => 'required|email',
      'password'          => 'required|min:6|max:20',
      'confirm_password'  => 'required|same:password',
      'status'            => 'required|integer'
      ]);

      $this->formData = $validation->getValidatedData();

      if ($validation->fails()) {
      // handling errors
      $this->formErrors = $validation->errors()->toArray();
      } else {
      $user = $this->model('User');

      $user->fill($validation->getValidData());
      $user->save();

      $this->redirect('/public/user/index');
      }
      }

      $this->view("user/create");
      }

      public function edit($id) {

      $user = User::findOrFail($id);

      $this->formData = $user;

      if (isset($_POST['submit'])) {

      $validator = new Validator;

      $validation = $validator->validate($_POST, [
      'first_name'    => 'required|min:2|max:50',
      'last_name'     => 'required|min:2|max:50',
      'email'         => 'required|email',
      'status'        => 'required|integer'
      ]);

      $this->formData = $validation->getValidatedData();
      $this->formData['id'] = $user['id'];

      if ($validation->fails()) {
      // handling errors
      $this->formErrors = $validation->errors()->toArray();
      } else {
      $user->fill($validation->getValidData());
      $user->save();

      $this->redirect('/public/user/index');
      }
      }

      $this->view("user/edit");
      } */

    public function create() {

        $validator = new Validator;

        $validation = $validator->validate($_POST, [
            'first_name'        => 'required|min:2|max:50',
            'last_name'         => 'required|min:2|max:50',
            'email'             => 'required|email',
            'password'          => 'required|min:6|max:20',
            'confirm_password'  => 'required|same:password',
            'status'            => 'required|integer'
        ]);

        $this->formData = $validation->getValidatedData();

        $response = [];

        if ($validation->fails()) {
            // handling errors
            $response['status'] = 'error';
            $response['errors'] = $validation->errors()->toArray();
        } else {
            $user = $this->model('User');

            $user->first_name   = $this->formData['first_name'];
            $user->last_name    = $this->formData['last_name'];
            $user->email        = $this->formData['email'];
            $user->password     = $this->formData['password'];
            $user->status       = $this->formData['status'];
            $user->save();

            $response['status'] = 'ok';
            $response['data'] = $user->toArray();
        }

        echo json_encode($response);
    }

    public function edit($id) {

        $user = User::findOrFail($id);

        $response = [
            'status' => 'ok',
            'data' => $user->toArray()
        ];

        if (isset($_POST['id'])) {

            $validator = new Validator;

            $validation = $validator->validate($_POST, [
                'first_name'    => 'required|min:2|max:50',
                'last_name'     => 'required|min:2|max:50',
                'email'         => 'required|email',
                'status'        => 'required|integer'
            ]);

            $this->formData = $validation->getValidatedData();

            if ($validation->fails()) {
                // handling errors
                $response['status'] = 'error';
                $response['errors'] = $validation->errors()->toArray();
            } else {
                $user->fill($this->formData);
                $user->save();

                $response['status'] = 'ok';
                $response['data'] = $user->toArray();
            }
        }

        echo json_encode($response);
    }

    public function delete($id) {

        if (!isset($_POST['id'])) {
            return false;
        }

        $user = User::findOrFail($id);

        if ($user->delete()) {
            $response = [
                'status' => 'ok',
                'message' => 'User with id #' . $id . ' has been deleted successfully!'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Error occurred when try to delete user with id #' . $id . '!'
            ];
        }

        echo json_encode($response);
    }

}
