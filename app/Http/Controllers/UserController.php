<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Dirape\Token\Token;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\RefreshToken;


class UserController extends Controller
{

        protected function create(array $data)
    {

    }


    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        /// find user by username/email
        /// if user not found return error
        /// check if submitted bycrypt(password) === password in the database
        /// return if password match or not
        ///

//        $user = User::where('email', request('email'))->first();
//        if (!$user)
//            return response()->json(['error' => 'Email not found'], 401);
//
//        if (bcrypt(request('password')) == $user->password) {
//
//            $success['token'] = $user->createToken('MyApp')->accessToken;
//            return response()->json(['success' => $success], $this->successStatus);
//        } else {
//            return response()->json(['error' => 'wrong password'], 401);
//        }
        //$credentials = $request->only('email', 'password');
        //dd($credentials);


        $user = User::where('email', $request->email)->first();
        if ($user) {

            if (Hash::check($request->password, $user->password)) {

                $token = $user->token;
                $response = ['token' => $token];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }

        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }
    }

    public function profile(Request $request)
    {

       // $value = $request->header('Auth');
//        dd($value);
        $user = Auth::user();
      //  $user = User::where('token', $value)->first();

        $response = ['user' => $user];
        return response($response, 200);
   }


    public function profileUpdate(Request $request)
    {

//        $value = $request->header('Auth');
//        dd($value);
//        $user = User::where('token', $value)->first();
        $user=Auth::user();
        $user->user_name = $request['newUser'];
        $user->email = $request['newEmail'];
        if (isset($request['newPassword'])) {
            $user->password =Hash::make($request['newPassword']);
        }

        $user->save();


        $response = ['user' => $user];
        return response($response, 200);


        //return back()->with('message','Profile Updated');
        }


    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => ['required',
                'min:3',
            ],
            //'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }


        $input = $request->all();
        $input['token'] = Str::random(32);
        $input['password'] = $input['password'];

        $user = new User();
        $user->user_name = $input['user_name'];
        $user->email = $input['email'];
        $user->password =Hash::make($input['password']);
        $user->token = Str::random(32);
        $user->save();


         $success['token'] = $user->token;
         $success['user_name'] = $user->user_name;

        return response()->json(['success' => $success], $this->successStatus);

    }

    public function checkEmail()
    {
        $user = User::where('email', request('email'))->exists();
        if ($user)
            return response()->json(['check' => true], 200);
        return response()->json(['check' => false], 200);
    }


/**
 * post api
 *
 */

public function createPost(Request $request)
{
    $input = $request->all();
    //$name = $request->file('image')->getClientOriginalName();
    $path=$request->file('image')->store('public/images');

    if($request->hasFile('image')){
        $image = $request->file('image');
        $image_name = $image->getClientOriginalName();
       $image->move(public_path('/images'),$image_name);

        $image_path = "/images/" . $image_name;
    }

    $post = new Post();
    $post->title = $input['title'];
    $post->body = $input['body'];
    $post->image = $path;
    //$input['image']->file('image')->store('public/images');
   // $post->image = $input['image'];
    $post->user_id =Auth::user()->id;


    $post->save();
    $response = ['post' => $post];
    return response($response, 200);

}

}

