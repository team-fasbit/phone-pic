<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use Auth;
use Log;
use DB;
use Hash;
use Mail;
use App\Mail\ForgotPassword;
use App\Job;
use App\JobImage;
class ApiController extends Controller
{
    /*
    * public function check user registered or not
    */
    
    public function addeditjob(Request $request){
        if($request->has("id") && $request->get("id")!=""){
        $rules = [
                'job_name'      => 'required|string|max:255|unique:jobs,job_name,'.$request->id,
            ];
            $jobobj = Job::find($request->id);
        }else{
            $rules = [
                'job_name'      => 'required|string|max:255|unique:jobs',
            ];
           $jobobj = new Job; 
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['success']  = false;
            $response['message'] = $validator->messages();
            return $response;
        }
        $jobobj->user_id = Auth::id();
        $jobobj->job_name = $request->job_name;
        $jobobj->job_description = $request->job_description;
        $jobobj->address = $request->address;
        $jobobj->status = 1;
        $images = $request->file('images');
        if($jobobj->save()){
            if ($request->has('images') && count($images)>0) {
                if (!file_exists( public_path('/jobimages'))) {
                    mkdir(public_path('/jobimages'), 0777, true);
                }
                $destinationPath = public_path('/jobimages');
                foreach ($images as $photo) {
                    $rand_number = rand(100000, 999999);
                    $filename = $rand_number.time().'.'.$photo->getClientOriginalExtension();
                    $photo->move($destinationPath, $filename);
                    $JobImageobj = new JobImage;
                    $JobImageobj->job_id = $jobobj->id;
                    $JobImageobj->image_name = $filename;
                    $JobImageobj->status = 1;
                    $JobImageobj->save();
                }
            }
            if($request->id != ''){
                $response['success']  = true;
                $response['response'] = 'Newsfeed has been updated successfully.';
                return $response;
            }
            $response['success']  = true;
            $response['response'] = 'Newsfeed has been added successfully.';
            return $response;
        }else{
            $response['success']  = false;
            $response['response'] = 'Something went wrong, please try again.';
            return $response;
        }
    }

    public function getalljobs(Request $request){
        $newsfeeds = Job::orderBy("updated_at","DESC")->with("images","ownerinfo")->paginate(20);
        $response['success'] = true;
        $response['response'] =  $newsfeeds;
        return $response;
    }

  

    public function deleteNewsfeeds(Request $request){
        $rules = [
                    
                    'id'  => 'required',
                ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['success']  = false;
            $response['response'] = $validator->messages();
            return $response;
        }

        $delete = Newsfeed::where('id', $request->id)->delete();
        if($delete){
            $response['success'] = true;
            $response['response'] =  'Record has been deleted successfully.';
            return $response;
        } 
    }

    public function getComments(Request $request){
        $rules = [
                    
                    'newsfeed_id'  => 'required',
                ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['success']  = false;
            $response['response'] = $validator->messages();
            return $response;
        }
        $comments = Comments::where('id', $request->newsfeed_id)->paginate(10);
        $response['success']  = true;
        $response['comments'] = $comments;
        return $response;
    }

    public function addComments(Request $request){
        $rules = [
                    
                    'newsfeed_id'  => 'required',
                    'comment'      => 'required'
                ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['success']  = false;
            $response['response'] = $validator->messages();
            return $response;
        }
        $comment = new Comments;
        $comment->user_id = Auth::id();
        $comment->newsfeed_id = $request->newsfeed_id;
        $comment->comment = $request->comment;

        if($comment->save()){
            $response['success'] = true;
            $response['response'] =  'Comments has been added successfully.';
            return $response;
        }else{
            $response['success'] = false;
            $response['response'] =  'Something went wrong.';
            return $response;
        }
    }

    public function getProfile(){
        $data = Auth::user();
        $response['success'] = true;
        $response['user'] =  $data;
        return $response;
    }
}
