<?php

namespace App\Controllers;

use App\Models\WorkoutDay;
use App\Models\Exercise;
use App\Models\Plan;
use App\Core\Controller;
use Rakit\Validation\Validator;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Helpers\Mailer;

/**
 * Workoutday controller
 */
class WorkoutdayController extends Controller {

    public function getExercises(int $id, int $isUsed = 0) {
        
        $day = WorkoutDay::findOrFail($id);
        
        $usedExerciseIds = $day->exercises()->pluck('exercise_id')->toArray();
        
        $unusedExerciseIds = Exercise::whereNotIn('id', $usedExerciseIds)->pluck('id')->toArray();
        
        $exerciseIds = (!empty($isUsed) ? $usedExerciseIds : $unusedExerciseIds);
        
        $exercises = Exercise::whereIn('id', $exerciseIds)->pluck('name', 'id');
        
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
            'name'      => 'required|min:2|max:255',
            'plan_id'   => [
                'required',
                function ($value) {
                    $planIds = Plan::all()->pluck('id')->toArray();
                    return in_array($value, $planIds);
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
            $day = $this->model('WorkoutDay');

            $day->name = $this->formData['name'];
            $day->plan_id = $this->formData['plan_id'];
            $day->save();

            $response['status'] = 'ok';
            $response['data'] = $day->toArray();
            
            $subject = 'The plan assigned to you has been modified!';
            
            foreach ($day->plan->users as $user) {
                $body = "<p>Dear {$user->first_name},</p><br>
                        <p>The plan {$day->plan->name} assigned to you has been modified.</p>
                        <p>The new workout day {$day->name} has been added.</p>
                        <p>We hope you will continue with health life!</p>
                        <br>
                        <p>Best regards,</p>
                        <p>Your Virtuagym Team</p>";
                Mailer::queueEmail($user->email, $subject, $body);
            }
            Mailer::curlProcessEmailQueue();
        }
        
        echo json_encode($response);
    }

    public function edit($id) {

        $day = WorkoutDay::findOrFail($id);

        $response = [
            'status'    => 'ok',
            'data'      => $day->toArray()
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
                $day->name = $this->formData['name'];
                $day->save();

                $response['status'] = 'ok';
                $response['data'] = $day->toArray();
            }
        }

        echo json_encode($response);
    }
    
    public function delete() {

        if (!isset($_POST['id'])) {
            return false;
        }
        
        $workoutDay = WorkoutDay::findOrFail($_POST['id']);
        
        try {
            // We have to use transactions here because won't to have any lost data if something went wrong
            Capsule::beginTransaction();
            
            // Delete all workout day exercises
            $workoutDay->exercises()->detach();
            
            // And finally... delete workout day!
            if ($workoutDay->delete()) {
                $response = [
                    'status' => 'ok',
                    'message' => 'Workout day with id #' . $_POST['id'] . ' has been deleted successfully!'
                ];
                
                $subject = 'The plan assigned to you has been modified!';
            
                foreach ($workoutDay->plan->users as $user) {
                    $body = "<p>Dear {$user->first_name},</p><br>
                            <p>The plan {$workoutDay->plan->name} assigned to you has been modified.</p>
                            <p>The workout day {$workoutDay->name} has been deleted.</p>
                            <p>We hope you will continue with health life!</p>
                            <br>
                            <p>Best regards,</p>
                            <p>Your Virtuagym Team</p>";
                    Mailer::queueEmail($user->email, $subject, $body);
                }
                Mailer::curlProcessEmailQueue();
                
                Capsule::commit();
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Workout day occurred when try to delete exercise with id #' . $_POST['id'] . '!'
                ];
                
                Capsule::rollback();
            }

        } catch (\Exception $e){
            // Something went wrong, rollback all changes
            Capsule::rollback();
            
            $response = [
                'status' => 'error',
                'message' => 'Workout day occurred when try to delete exercise with id #' . $_POST['id'] . '!'
            ];

        }
        
        echo json_encode($response);
    }
    
    public function addExercise() {
        
        $validator = new Validator;

        $validation = $validator->validate($_POST, [
            'workout_day_id'   => [
                'required',
                function ($value) {
                    $workoutDayIds = WorkoutDay::all()->pluck('id')->toArray();
                    return in_array($value, $workoutDayIds);
                }
            ],
            'exercise_id'   => [
                'required',
                function ($value) {
                    $exerciseIds = Exercise::all()->pluck('id')->toArray();
                    return in_array($value, $exerciseIds);
                }
            ]
        ]);
            
        $this->formData = $validation->getValidatedData();

        $response = [];
        
        $post_processing = [];

        if ($validation->fails()) {
            // handling errors
            $response['status'] = 'error';
            $response['errors'] = $validation->errors()->toArray();
        } else {
            
            Capsule::insert('INSERT INTO `workout_days_exercises` (`workout_day_id`, `exercise_id`) VALUES (?, ?)', [$this->formData['workout_day_id'], $this->formData['exercise_id']]);

            $day = WorkoutDay::find($this->formData['workout_day_id']);
            $exercise = Exercise::find($this->formData['exercise_id']);
            
            $response['status'] = 'ok';
            $response['data'] = $exercise->toArray();
            
            $subject = 'The plan assigned to you has been modified!';
            
            foreach ($day->plan->users as $user) {
                $body = "<p>Dear {$user->first_name},</p><br>
                        <p>The plan {$day->plan->name} assigned to you has been modified.</p>
                        <p>The new exercise '{$exercise->name}' has been added to workout day '{$day->name}'.</p>
                        <p>We hope you will continue with health life!</p>
                        <br>
                        <p>Best regards,</p>
                        <p>Your Virtuagym Team</p>";
                Mailer::queueEmail($user->email, $subject, $body);
            }
            Mailer::curlProcessEmailQueue();
        }
        
        echo json_encode($response);    
    }
    
    public function removeExercise() {

        if (!isset($_POST['workout_day_id']) || !isset($_POST['exercise_id'])) {
            return false;
        }
        
        $delete = Capsule::table('workout_days_exercises')
                ->where('workout_day_id', $_POST['workout_day_id'])
                ->where('exercise_id', $_POST['exercise_id'])
                ->delete();
        
        if ($delete) {
            $response = [
                'status' => 'ok',
                'message' => 'Exercise with id #' . $_POST['exercise_id'] . ' has been deleted successfully!'
            ];
            
            $day = WorkoutDay::find($_POST['workout_day_id']);
            $exercise = Exercise::find($_POST['exercise_id']);
            
            $subject = 'The plan assigned to you has been modified!';
            
            foreach ($day->plan->users as $user) {
                $body = "<p>Dear {$user->first_name},</p><br>
                        <p>The plan {$day->plan->name} assigned to you has been modified.</p>
                        <p>The exercise '{$exercise->name}' has been removed from workout day '{$day->name}'.</p>
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
                'message' => 'Error occurred when try to delete exercise with id #' . $_POST['exercise_id'] . '!'
            ];
        }
        
        echo json_encode($response);
    }

}
