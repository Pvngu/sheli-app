<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Models\Enrollment;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Course\IndexRequest;
use App\Http\Requests\Api\Course\StoreRequest;
use App\Http\Requests\Api\Course\DeleteRequest;
use App\Http\Requests\Api\Course\UpdateRequest;

class CourseController extends ApiBaseController
{
    protected $model = Course::class;

    protected $indexRequest = IndexRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;
    protected $storeRequest = StoreRequest::class;

    public function storing(Course $course) {
        $course->status = 'inactive';

        return $course;
    }

    public function stored(Course $course) {
        $request = request();

        if($request->has('user_id') && $request->user_id) {
            $allUsers = $request->user_id;

            foreach ($allUsers as $user) {
                $userId = $this->getIdFromHash($user);
                $enrollment = new Enrollment();
                $enrollment->user_id = $userId;
                $enrollment->course_id = $course->id;
                $enrollment->enrollment_date = now();
                $enrollment->save();
            }
        }
    }

    public function updated(Course $course) {
        $request = request();

        if($request->has('user_id') && $request->user_id) {
            $allUsers = $request->user_id;
            $existingEnrollments = Enrollment::where('course_id', $course->id)->get();

            // Delete users not in the array
            foreach ($existingEnrollments as $enrollment) {
                if (!in_array($this->getHashFromId($enrollment->user_id), $allUsers)) {
                    $enrollment->delete();
                }
            }

            // Add users not already enrolled
            foreach ($allUsers as $user) {
                $userId = $this->getIdFromHash($user);
                $enrollment = Enrollment::where('user_id', $userId)->where('course_id', $course->id)->first();
                if (!$enrollment) {
                    $enrollment = new Enrollment();
                    $enrollment->user_id = $userId;
                    $enrollment->course_id = $course->id;
                    $enrollment->enrollment_date = now();
                    $enrollment->save();
                }
            }
        }
    }
}