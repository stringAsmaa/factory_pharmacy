<?php

namespace App\Http\Controllers;

use App\Models\categorie;
use App\Models\Factory;
use App\Models\factory_order;
use App\Models\Order;
use App\Models\Pharmacist;
use App\Models\User;
use App\Notifications\newOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

use function PHPUnit\Framework\returnValue;

class PharmacyController extends Controller
{
    public function regester( Request $request):JsonResponse
    { 

$request-> validate
([ 
    'name'=> ['required' ],
    'phone_number'=> ['required','unique:Users,phone_number','digits:10'],
    'password'=> ['required']
   
]);

$user =User::query ( )-> create ([ 
   'name' => $request['name'] ,
   'phone_number' => $request['phone_number'] ,
    'password' => $request['password'] ,
    'status' => 'pharmacy'
]);


$token=$user->CreateToken("API_TOKEN")->plainTextToken;

$data =[];
$data ['User']=$user ;
$data ['token']=$token ;



return response( )-> json ([
'status'=>1,
'data'=>$data,
'message'=>'pharmacist created successfully '
]);
return $request;
    }
public function login ( Request $request){

$request-> validate
([ 

    'phone_number'=> ['required','digits :10','exists:users,phone_number'],
    'password'=> ['required']
]);
if(!Auth ::attempt ( $request ->only ( [ 'phone_number', 'password']))){

    $message =' the phone number and password dose not match with our record';
    
 
    return response( )-> json ([
        'status'=>0,
        'data'=>[],
        'message'=> $message
      ],500);
   
}




$user =User::query()->where('phone_number',$request['phone_number'])->first();


 $token=$user->createToken('API_TOKEN')->plainTextToken;
 $data =[];
 $data ['User']=$user ;
 $data ['token']=$token ;


     return response( )-> json ([
         'status'=>1,
         'data'=>$data,
         'message'=>'pharmacist logged in  successfully '
         ]);
 }





public function logout():JsonResponse
{
 
  Auth ::user()->CurrentAccessToken()-> delete();

  


return response( )-> json ([
    'status'=>1,
    'data'=>[],
    'message'=>'pharmacist logged out  successfully '
    ]);

}

public function show(){

    $show=DB::table('factories')->get();

    return response( )-> json ([
        'status'=>1,
        'data'=>$show,
        'message'=>'your welcome '
        ]);
}

public function show_by_id(Request $request){
    $request->validate([
        'id'=>['required']
    ]);
    $id=$request['id'];
$show= DB::table('factories')->where('id',$id)->first();
return response( )-> json ([
    'status'=>1,
    'data'=>$show,
    'message'=>'your welcome '
    ]);


}


public function search(Request $request){
     $search_categorie=DB::table('categories')->where('trade_name',$request['trade_name'])
     ->select('categorie')->first();//يعرض التصنيف
     $search_name=DB::table('categories')->where('categorie',$request['categorie'])->select('trade_name')
     ->get();//يعرض اسم الدوا

    

       if($search_categorie){
        return response( )-> json ([
            'status'=>1,
            'data'=>$search_categorie,
            
            ]);}

            if($search_name){
                return response( )-> json ([
                    'status'=>1,
                    'data'=>$search_name,
                    
                ]);}
     
    

    }

 



     




public function select(Request $request){

$select_pharmacist=DB::table('factories')->where('trade_name',$request['trade_name'])
->select('scientific_name','categorie','company','amount','price')->first();

if($select_pharmacist){
    return response( )-> json ([
        'status'=>1,
        'data'=>$select_pharmacist,
        
        ]);}

        return  response()->json([
        'status'=>0,
        'data'=>[],
    'message'=>'not found'
        ]
    );


}



public function order(Request $request){


    $request->validate([
        'status_user'=>['required'],
        'trade_name'=>['required'],
        'amount'=>['required']
    ]);

    $factory=DB::table('factories')->where('trade_name',$request['trade_name'])->first();
$amount=DB::table('factories')->where('trade_name',$request['trade_name'])->where('amount','>=',$request['amount'])->first();



if (!$factory) return response()->json(['message'=>'trade_name not found']);
if (!$amount) return response()->json(['message'=>' amount not enough']);


if($factory && $amount){
    
//نضيف الطلبات لجدول الطلبات 
$orders=DB::table('orders')->insert([

  'status_user'=>$request['status_user'],
  'trade_name'=>$request['trade_name'],
  'amount'=>$request['amount']
   ]);



   

   return response()->json(['message'=>'success']);
}


}





public function factory_orders(Request $request){

   
   $request->validate([
    'factory_id'=>['required'],
    'order_id'=>['required'],
    'amount'=>['required']
]);
$id=DB::table('factories')->where('id',$request['factory_id'])->first();

//نضيف العلاقات للجدول الوسيط

if($id){
 $factory_orders=DB::table('factory_orders')->insert([

 'factory_id'=> $request['factory_id'],
 'order_id'=> $request['order_id'],
 'amount'=>$request['amount']
]);

}
$message='تم اضافة طلب';
 $user=User::where('status','factory')->get();

Notification::send($user,new newOrder($message));

return 'تم اضافة الطلب و ارسال االاشعار بنجاح';
}



public function show_orders(Request $request){

$request->validate([
    'order_id'=>['required']
]);



$orders=DB::table('orders')->where('order_id',$request['order_id'])
->join('factory_orders', 'orders.id', '=', 'factory_orders.factory_id')->get();
if( $orders){
 
    return response()->json([
        'status'=>1,
        'orders'=>$orders
        
        ]);
}

}

public function show_all_orders(){

    $orders=DB::table('orders')->get();
return response()->json([
    'status'=>1,
    'orders'=>$orders
    
    ]);
}






public function notify(){

    $show=DB::table('notifications')->where('notifiable_id',1)->get();
    return $show;

}





}









































