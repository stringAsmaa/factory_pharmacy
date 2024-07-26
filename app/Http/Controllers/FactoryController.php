<?php

namespace App\Http\Controllers;

use App\Models\categorie;
use App\Models\Factory;
use App\Models\factory_order;
use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use App\Notifications\newOrder;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use PhpParser\Node\Stmt\Return_;

use function Laravel\Prompts\table;
use function PHPUnit\Framework\countOf;

class FactoryController extends Controller
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
    'status' => 'factory'
]);


$token=$user->CreateToken("API_TOKEN")->plainTextToken;

$data =[];
$data ['User']=$user ;
$data ['token']=$token ;



return response( )-> json ([
'status'=>1,
'data'=>$data,
'message'=>'owner created successfully '
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
         'message'=>'owner logged in  successfully '
         ]);
 }





public function logout():JsonResponse
{
 
  Auth ::user()->CurrentAccessToken()-> delete();

  


return response( )-> json ([
    'status'=>1,
    'data'=>[],
    'message'=>'owner logged out  successfully '
    ]);



}

public function insert(Request $request){


    $request-> validate
    ([ 
        'scientific_name'=> ['required','unique:factories,scientific_name' ],
        'trade_name'=> ['required'],
        'categorie'=> ['required'],
        'company'=> ['required'],
        'amount'=> ['required', 'numeric','min:1'],
        'exspiry_date_id'=> ['required','after:today'],
        'price'=> ['required'],
       
    ]);


    $factory =Factory::query ( )-> create ([ 
        'scientific_name' => $request['scientific_name'] ,
        'trade_name' => $request['trade_name'] ,
         'categorie' => $request['categorie'] ,
         'company' => $request['company'] ,
         'amount' => $request['amount'] ,
         'price' => $request['price'] ,
        
       
     ]);
     
     $id= DB::table('factories')->where('scientific_name',$request['scientific_name'])->first();
   
    
$status=Status::query()->create([
    'factory_id'=>$id->id,
'exspiry_date_id'=>$request['exspiry_date_id'],
'amount_id'=>$request['amount'],

]);






$categorie=categorie::query()->create([

    'trade_name' => $request['trade_name'] ,
    'categorie' => $request['categorie'] ,


]);


 if($factory && $categorie){

       return response( )-> json ([
          'status'=>1,
          'data'=>$factory,
          'message'=>'inserted  successfully '
         ]);

   }


}


public function add_amount(Request $request){

$request->validate([
    'factory_id'=>['required'],
'amount_id'=>['required','numeric','min:1'],
'exspiry_date_id'=>['required','after:today'],

]);

$status=DB::table('statuses')->where('factory_id',$request['factory_id'])->insert([
'factory_id'=>$request['factory_id'],
    'amount_id'=>$request['amount_id'],
    'exspiry_date_id'=>$request['exspiry_date_id'],
    
]);



if($status){
    $amount_factory= DB::table('factories')->where('id',$request['factory_id'])->first();//كمية الدوا من المستودع

     $amount_status=$request['amount_id'];

    $new_amount=$amount_factory->amount + $amount_status ;
   
 DB::table('factories')->where('id',$request['factory_id'])->update([
 'amount'=> $new_amount
 
 ]);

$expiry=DB::table('statuses')->whereDate('exspiry_date_id','<=',now())->select('amount_id')->first();



if($expiry){
   $amount= DB::table('factories')->where('id',$request['factory_id'])->select('amount')->first();


$new_amount=$amount->amount -$expiry->amount_id ; 
     DB::table('factories')->where('id',$request['factory_id'])->update([
        'amount'=>$new_amount
     ]);
     DB::table('statuses')->whereDate('exspiry_date_id','<=',now())->delete();
}

$insert=DB::table('factories')->get();

return response()->json([
'status'=>1,
'data'=>$insert

]);

}}





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

        $select_factory=DB::table('factories')->where('trade_name',$request['trade_name'])
        ->select('scientific_name','categorie','company','amount','price')->first();
        
        if($select_factory){
            return response( )-> json ([
                'status'=>1,
                'data'=>$select_factory,
                
                ]);}
        
                return  response()->json([
                'status'=>0,
                'data'=>[],
            'message'=>'not found'
                ]
            );
        
    

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






            
public function edit_orders(Request $request){

$request->validate([

   
   'order_id'=>['required'],
    'status_order'=>['required'],
    'status_paid'=>['required']
]);




$ordersToUpdate = DB::table('factory_orders')
                ->where('order_id', $request['order_id'])
                ->pluck('id'); // Use pluck to get an array of ids



$status='تم الارسال';

$already_sent=DB::table('orders')->whereIn('id',$ordersToUpdate)->where('status_order', "=",$status)->get();


if(   ($request['status_order']!='تم الارسال') ||   ($already_sent->isEmpty()) &&( $request['status_order']=='تم الارسال')){
 
$updatedOrders = DB::table('orders')
                ->whereIn('id',  $ordersToUpdate)
                ->update([
                    'status_order' => $request['status_order'],
                    'status_paid' => $request['status_paid']
                ]);
            }


               else{return 'already sent the order';}


if($request['status_order']=='تم الارسال'){
    $amount_orders = DB::table('orders')->whereIn('id',  $ordersToUpdate)->where('status_order','تم الارسال')->select('amount')->get();
   

    

    $amount_factories = DB::table('factory_orders')->whereIn('factory_orders.id',  $ordersToUpdate)->join('factories','factory_orders.factory_id','=','factories.id')->select('factories.id','factories.amount')->get();
    


    $new_amount= [];
    for($i=0; $i<count($amount_factories); $i++){



        $new_amount[$i] = $amount_factories[$i]->amount - $amount_orders[$i]->amount;
        DB::table('factories')->where('id',  $amount_factories[$i]->id)->update(['amount' => $new_amount[$i]]);
        $new_amount[] = $new_amount;
      
    }
   
}
$message='تم تغيير حالة الطلب';
$user=User::where('status','pharmacy')->get();

$not=Notification::send($user,new newOrder($message));

return 'تم تغيير حالة الطلب و ارسال الاشعار ';


}

public function notify(){

    $show=DB::table('notifications')->where('notifiable_id',2)->get();
    return $show;

}


















}


    














