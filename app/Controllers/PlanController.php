<?php

namespace App\Controllers;

use App\Models\Plan;
use App\Models\User;
use App\Core\Controller;
use Rakit\Validation\Validator;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Helpers\Mailer;

/**
 * Plan controller
 */
class PlanController extends Controller {

    public function index() {
        $plan = Plan::all();
        
        $this->view("plan/index", ["plan" => $plan]);
    }

    public function getall() {
        $plan = Plan::select('id', 'name', 'created_at')->get()->toArray();
        
        $return = [
            'draw' => 1,
            'recordsTotal' => count($plan),
            'recordsFiltered' => count($plan),
            'data' => $plan
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
            $plan = $this->model('Plan');

            $plan->name = $this->formData['name'];
            $plan->save();

            $response['status'] = 'ok';
            $response['data'] = $plan->toArray();
        }
        
        echo json_encode($response);
    }
    
    public function show($id) {

        $plan = Plan::findOrFail($id);
        
        $mailer = new Mailer();
        
        $query = <<<'SQL'
                SELECT 
                    CONCAT(users.first_name, " ", users.last_name) as name, 
                    users.id
                FROM
                    users
                LEFT JOIN
                    users_plans ON users_plans.user_id = users.id AND users_plans.plan_id = ?
                WHERE
                    users_plans.user_id IS NULL
SQL;
        
        $unassignedUsers = Capsule::select($query, [$id]);

        $this->view("plan/show", [
            'plan' => $plan,
            'unassignedUsers' => $unassignedUsers
        ]);
    }
    
    public function edit($id) {

        $plan = Plan::findOrFail($id);

        $response = [
            'status'    => 'ok',
            'data'      => $plan->toArray()
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
                $plan->name = $this->formData['name'];
                $plan->save();

                $response['status'] = 'ok';
                $response['data'] = $plan->toArray();
                
                foreach ($plan->users as $user) {
                    $mailer = new Mailer();
                    $mailer->setAddress($user->email, $user->first_name . ' ' . $user->last_name)
                            ->setSubject('The plan assigned to you has been modified!')
                            ->setBody(" 
                                <p>Dear {$user->first_name},</p><br>
                                <p>The plan {$plan->name} assigned to you has been modified.</p>
                                <p>We hope you will continue with health life!</p>
                                <br>
                                <p>Best regards,</p>
                                <p>Your Virtuagym Team</p>")
                            ->send();
                }
                
                $subject = 'The plan assigned to you has been modified!';
            
                foreach ($plan->users as $user) {
                    $body = "<p>Dear {$user->first_name},</p><br>
                            <p>The plan {$plan->name} assigned to you has been modified.</p>
                            <p>We hope you will continue with health life!</p>
                            <br>
                            <p>Best regards,</p>
                            <p>Your Virtuagym Team</p>";
                    Mailer::queueEmail($user->email, $subject, $body);
                }
                Mailer::curlProcessEmailQueue();
            }
        }

        echo json_encode($response);
        die();
    }
    
    public function delete($id) {

        if (!isset($_POST['id'])) {
            return false;
        }
        
        $plan = Plan::findOrFail($id);
        
        try {
            // We have to use transactions here because won't to have any lost data if something went wrong
            Capsule::beginTransaction();
            
            // Delete all workoutdays related to this plan
            foreach ($plan->workoutdays as $workday) {
                // Delete all workout day exercises
                $workday->exercises()->detach();
                // Delete workout day
                $workday->delete();
            }
            
            // Unassign all users from this plan
            $users = $plan->users;
            $plan->users()->detach();
            
            // And finally... delete plan!
            if ($plan->delete()) {
                $response = [
                    'status' => 'ok',
                    'message' => 'Exercise with id #' . $id . ' has been deleted successfully!'
                ];
                
                Capsule::commit();
                
                $subject = 'The plan assigned to you has been modified!';
            
                foreach ($users as $user) {
                    $body = "<p>Dear {$user->first_name},</p><br>
                            <p>The plan {$plan->name} assigned to you has been deleted.</p>
                            <p>We hope you will continue with health life!</p>
                            <br>
                            <p>Best regards,</p>
                            <p>Your Virtuagym Team</p>";
                    Mailer::queueEmail($user->email, $subject, $body);
                }
                Mailer::curlProcessEmailQueue();
                
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Error occurred when try to delete exercise with id #' . $id . '!'
                ];
                
                Capsule::rollback();
            }

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

    public function getAssignedUsers() {
        
        $planId = $_POST['plan_id'] ?? 0;
        
        $planUserIds = Capsule::table('users_plans')
                ->select('user_id')
                ->where('plan_id', (int)$planId)
                ->pluck('user_id')
                ->toArray();
 
        $users = User::select('id', 'first_name', 'last_name', 'email')
                ->whereIn('id', $planUserIds)
                ->get()
                ->toArray();

        $return = [
            'draw' => 1,
            'recordsTotal' => count($users),
            'recordsFiltered' => count($users),
            'data' => $users
        ];

        echo json_encode($return);
        die();
    }
    
    public function addUser() {
        
        $validator = new Validator;

        $validation = $validator->validate($_POST, [
            'plan_id'   => [
                'required',
                function ($value) {
                    $planIds = Plan::all()->pluck('id')->toArray();
                    return in_array($value, $planIds);
                }
            ],
            'user_id'   => [
                'required',
                function ($value) {
                    $userIds = User::all()->pluck('id')->toArray();
                    return in_array($value, $userIds);
                }
            ]
        ]);
            
        $this->formData = $validation->getValidatedData();

        $response = [];

        if ($validation->fails()) {
            // handling errors
            $response['status'] = 'error';
            $response['errors'] = $validation->errors()->toArray();
        } else {
            Capsule::insert('INSERT INTO `users_plans` (`plan_id`, `user_id`) VALUES (?, ?)', [$this->formData['plan_id'], $this->formData['user_id']]);
            
            $response['status'] = 'ok';
            $response['data'] = [];
            
            $user = User::find($this->formData['user_id']);
            
            $plan = Plan::find($this->formData['plan_id']);
                        
            $subject = 'New plan has been assigned to you!';
            $body = "<p>Dear {$user->first_name},</p><br>
                    <p>Great news! The new plan named {$plan->name} has been assigned to you.</p>
                    <p>We hope you will continue with health life!</p>
                    <br>
                    <p>Best regards,</p>
                    <p>Your Virtuagym Team</p>";
                    
            Mailer::queueEmail($user->email, $subject, $body);
            Mailer::curlProcessEmailQueue();
        }
        
        echo json_encode($response);  
        die();
    }
    
    public function removeUser() {
        
        if (!isset($_POST['user_id']) || !isset($_POST['plan_id'])) {
            return false;
        }
        
        $delete = Capsule::table('users_plans')
                ->where('user_id', $_POST['user_id'])
                ->where('plan_id', $_POST['plan_id'])
                ->delete();
        
        if ($delete) {
            
            $response = [
                'status' => 'ok',
                'message' => 'User with id #' . $_POST['user_id'] . ' has been unassigned successfully!'
            ];

            $user = User::find($_POST['user_id']);
            
            $plan = Plan::find($_POST['plan_id']);   
                        
            $subject = 'The plan has been unassigned from you!';
            $body = "<p>Dear {$user->first_name},</p><br>
                    <p>The plan named {$plan->name} has been unassigned from you.</p>
                    <p>We hope you will continue with health life!</p>
                    <br>
                    <p>Best regards,</p>
                    <p>Your Virtuagym Team</p>";
                    
            Mailer::queueEmail($user->email, $subject, $body);
            Mailer::curlProcessEmailQueue();
                        
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Error occurred when try to unassign user with id #' . $_POST['user_id'] . '!'
            ];
        }
        
        echo json_encode($response);
        die();
    }
}
