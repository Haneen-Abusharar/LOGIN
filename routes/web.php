<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Models\Post;
use App\Models\User;
use App\Models\Tag;
use App\Models\Photo;
use App\Models\Video;
use App\Models\Country;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
    // return "hi";
});
Route::get('/read', function (){
   $results = DB::select("select * from posts where id=?",[1]);
   foreach ($results as $post){
       return $post->title;
   }
});

Route::get('/insert',function (){
    DB::insert('insert into posts(title,body) values(?,?)',['laravel is awesome', 'php is the best thing']);
});
//Route::get('/update', function (){
//  $updated =DB::update('update posts set title= "update title "where id=?', [1]);
//  return $updated;
//});

//Route::get('/delete', function (){
//   $deleted= DB:: delete('delete from posts where id =?',[1]);
//   return $deleted;
//});
// Route::get('/about', function () {

//     return "hi abot page";
// });



// Route::get('/post/{id}/{name}', function($id,$name){
//     return "this is no ".$id." ".$name;

// });


//Route::get('/post/{id}/{name}/{password}', [PostController::class, 'show_post']);
//Route::get('/p', function (){
//    return view('greeting',['name'=>'james']);
//});
//Route::resource('posts', PostController::class);
//
//Route::get('/contact', [PostController::class,'contact']);

/*
 * ELOQUENT
 */


Route::get('/find', function (){
    $posts = Post::all();

    foreach ($posts as $post){
        return $post->title;
    }
});
Route::get('/findid', function (){
   $post = Post::find(2);
   return $post->id;
});

Route::get('/findwhere', function (){
   $posts = Post::where('id', 5)->orderBy('id', 'desc')->take(1)->get();

   return $posts;
});

Route::get('/findmore', function (){
//   $posts = Post::findOrFail(1);
//   return $posts;
    $posts = Post::where('users_count', '<', 50)->firstOrFail();
});

Route::get('/basicinsert', function (){
   $post = new Post;

   $post->title= " new eloquent title insert";
   $post->body="wow eloquent is cool";
   $post->save();

});
Route::get('/basicinsert2', function (){
    $post = Post::find(2);

    $post->title= " new eloquent title insert";
    $post->body="wow eloquent is cool";
    $post->save();

});

Route::get('/create', function (){
    Post::create(['title'=>'the create method', 'body'=>'I \'m learning ']);
});

Route::get('/update', function (){
    Post::where('id',6)->where('is_admin',0)->update(['title'=>'new', 'body'=>'method']);
});

Route::get('/delete', function (){
   $post =Post::find(11);
   $post->delete();
});

Route::get('/delete2', function (){
   Post::destroy([3,4]);
//   Post::where('is_admin',0)->delete();
});

Route::get('/softdelete', function (){
Post::find(7)->delete();
});

Route::get('/readsoftdelete', function (){
//   $post= Post::find(7);
//   return $post;
    $post = Post::withTrashed()->where('id',7)->get();
    return $post;
});
Route::get('/restore' , function (){
Post::withTrashed()->where('is_admin', 0)->restore();

});

 Route::get( '/forcedelete' , function (){
 Post::onlyTrashed()->where('is_admin',0)->forceDelete();

});

 /*
  * Eloquent relationships
  */
// one to one relationship

Route::get('/user/{id}/post', function ($id){
  return  User::find($id)->post->title;
});

Route::get('/post/{id}/user', function ($id){
    return Post::find($id)->user->name;
});

// one to many relationship

Route::get('/posts', function (){
    $user = User::find(1);
    foreach ($user->posts as $post){
       echo $post->title."<br>";
    }
});
// many to many relationship
Route::get('/user/{id}/role', function ($id){
    //$user= User::find($id)->roles()->orederBy('id','desc')->get();
   $user = User::find($id);
   foreach ($user->roles as $role){
       return $role->name;
   }
});

// accessing the intermediate table / pivot

Route::get('user/pivot' ,function (){
    $user = User::find(1);
    foreach ($user->roles as $role){
        echo $role->pivot->created_at;
    }
});

Route::get('/user/country', function (){
    $country = Country::find(4);

    foreach ($country->posts as $post){
       return $post->title;
    }
});

//ploymprphic relations

Route::get('user/photo', function (){
    $user = User::find(1);

    foreach ($user ->photos as $photo){
        return $photo->path;
    }
});
Route::get('post/photo', function (){
    $post = Post::find(1);

    foreach ($post ->photos as $photo){
        return $photo->path;
    }
});
Route::get('/photo/{id}/post', function ($id){
   $photo = Photo::findOrFail($id);
       return $photo->imageable;

});

// polymprphic many to many relationship

Route::get('/post/tag', function (){
    $post= Post::find(1);
    foreach ($post->tags as $tag){
        echo $tag->name;
    }
});

Route::get('/tag/post', function (){
    $tag= Tag::find(1);
    foreach ($tag->posts as $post){
        echo $post->title;
    }
});

Route::get('/video/tag', function (){
    $video= Video::find(1);
    foreach ($video->tags as $tag){
        return $tag->name;
    }
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
