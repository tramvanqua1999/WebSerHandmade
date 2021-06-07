<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\HomeCollection;
use App\Models\User;
use DB;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class HomeController extends Controller 
{
    public function index()
    {
      $data = DB::select('SELECT `id`, `username`, `password`, `type` FROM `user` WHERE `check` != ? ',[100]);
      return response()->json($data);
      //  return new HomeCollection(User::all())
    }
    public function delete($id)
    {
      $user = User::find($id);

      $user->delete();

      return response()->json('successfully deleted');
    }


    // --------------------------------
    public function block($id)
    {
      User::where('id', $id)
      ->update(['check' => 1]);

      return response()->json('successfully block',200);
    }
    public function getinfoShop($id)
    {
      $data = DB::select('SELECT `credit`.`shopCode`, `credit`.`cardNumber`, `credit`.`expiryDate`, `credit`.`cardHolderName`, `credit`.`cvvCode`,`shop`.`id`, `shopID`, `nameShop`, `img`, `imgBg`, `ratingShop`, `follow`, `description`, `address`, `phone`, `email`, `fee`, `price`, `createshop`,`user`.`check` FROM `shop`,`user`,`credit` WHERE `shop`.`shopID` = `user`.`username` and `shop`.`id` = `credit`.`shopCode` and `user`.`id` =  ?',[$id]);
      return response()->json($data);
    }
    public function getinfoCustomer($id)
    {
      $data = DB::select('SELECT `customer`.`id`, `user`.`check`,`userID`, `name`, `email`, `address`, `create` FROM `customer`,`user` WHERE `customer`.`userID` = `user`.`username` and `user`.`id` = ?',[$id]);
      return response()->json($data);
    }
    public function confirmation($id)
    {
      User::where('id', $id)
      ->update(['check' => 2]);
      return response()->json('successfully confirmation',200);
    }
    public function unblock($id)
    {
      User::where('id', $id)
      ->update(['check' => 2]);

      return response()->json('successfully unblock',200);
    }
    public function resultConfirmation()
    {
      $data = DB::select('SELECT `id`, `username`, `password`, `type` FROM `user` WHERE `check` = ? AND `type` != ?',[0,0]);
      return response()->json($data);
      //  return new HomeCollection(User::all())
    }
    
    public function resultBlockaccount()
    {
      $data = DB::select('SELECT `id`, `username`, `password`, `type`, `check` FROM `user` WHERE `check` = ? OR `check` = ? AND  `type`= ? ',[2,0,0]);
      return response()->json($data);
    }

    public function resultUnblockaccount()
    {
      $data = DB::select('SELECT `id`, `username`, `password`, `type`, `check` FROM `user` WHERE `check` = ? ',[1]);
      return response()->json($data);
    }

    

     //  -----------------------------

    public function store(Request $request)
    {
      $user = new User([
        'username' => $request->username,
        'password' => bcrypt($request->password),
        'type'     => $request->type,
      ]);

      $user->save();

      return response()->json('successfully added');
    }
    
    public function addgroup(Request $request)
    {
      DB::select('INSERT INTO `group`( `name`) VALUES (?)',[$request->namegroup]);

      return response()->json('successfully add group product');
    }
    
    public function login(Request $request){
      $arr = $request->only('username', 'password');
      // return response()->json(!$token = JWTAuth::attempt($arr));

      try {
        if (!$token = JWTAuth::attempt($arr)) {
            return response()->json([
                'status' => false,
                'msg' => 'Invalid Username or Password',
            ], 202);
        }else{
            $user = Auth::user();
            if($user->type != '0'){
              if($user->check != '1'){
                return $this->respondWithToken($token,$user->type, $user->id);
              }else{
                return response()->json([ 
                  'status' => false,
                  'msg'=>'Your Account Blocked '],201);
              }
            }else{
              return response()->json([ 
                'status' => false,
                'msg'=>'Incorrect login detail '],201);
            }
            
        }
      }catch (JWTException $e) {
          return response()->json(['msg' => 'could_not_create_token'], 203);
      }

  }
  public function checklogin(Request $request)
  {
      return view('admin/index');
  }

  public function logout()
  {
    if(auth('api')->check()){
      auth('api')->logout();

    }
  
  }
  protected function respondWithToken($token,$type,$id)
  {
      return response()->json([
          'access_token' => $token,
          'type' => $type,
          'id' => $id,
          'msg' => 'Login Success',
          'user' => $this->guard(),
          'token_type' => 'bearer',
          'expires_in' => auth('api')->factory()->getTTL() * 60
      ]);
  }
  public function guard()
  {
      return Auth::Guard('api')->user();
  }

  public function order(Type $var = null)
  {
    $data = DB::select('SELECT `order`.`id`,`product`.`name`,(SELECT GROUP_CONCAT(`imageproduct`.`imgpath`) as images FROM `imageproduct` WHERE `imageproduct`.`productCode` = `product`.`id`) as listpath, `order`.`username`, `order`.`productCode`, `order`.`amount`, `order`.`price`, `order`.`priceship`, `order`.`type`, `order`.`check`, `order`.`create` FROM `order`,`shop`,`product` WHERE `order`.`productCode` = `product`.`id` AND `product`.`shopCode` = `shop`.`id` AND `order`.`check` = 1');
    return response()->json($data);
  }

  public function cofirm($id)
  {
     DB::select('UPDATE `order` SET `check`= 3 WHERE `order`.`id` = ?',[$id]);
     return response()->json("success",200);
  }
  public function cancel($id)
  {
     DB::select('UPDATE `order` SET `check`= 4 WHERE `order`.`id` = ?',[$id]);
     return response()->json("success",200);
  }
  public function detail($id)
  {
    $data = DB::select('SELECT `order`.`id`,`order`.`username`,`customer`.`name`as namecustomer,`customer`.`email`,`customer`.`address`as addresscustomer,`shop`.`nameShop`,`product`.`name`,`product`.`price`,`product`.`description`,`product`.`description`, `product`.`rating`, (SELECT GROUP_CONCAT(`imageproduct`.`imgpath`) as images FROM `imageproduct` WHERE `imageproduct`.`productCode` = `product`.`id`) as listpath,`order`.`username`, `order`.`productCode`, `order`.`amount`, `order`.`price`, `order`.`priceship`, `order`.`type`, `order`.`check`, `order`.`create` FROM `order`,`product`,`shop`,`user`,`customer` WHERE `order`.`id` = ? AND `order`.`productCode` = `product`.`id` AND `product`.`shopCode` = `shop`.`id` AND `order`.`username` = `user`.`username` AND `user`.`username` = `customer`.`userID`',[$id]);
    return response()->json($data);
  }
  public function approved()
  {
    $data = DB::select('SELECT `order`.`id`,`product`.`name`,(SELECT GROUP_CONCAT(`imageproduct`.`imgpath`) as images FROM `imageproduct` WHERE `imageproduct`.`productCode` = `product`.`id`) as listpath, `order`.`username`, `order`.`productCode`, `order`.`amount`, `order`.`price`, `order`.`priceship`, `order`.`type`, `order`.`check`, `order`.`create` FROM `order`,`shop`,`product` WHERE `order`.`productCode` = `product`.`id` AND `product`.`shopCode` = `shop`.`id` AND `order`.`check` = 3');
    return response()->json($data);
  }
  public function canceled()
  {
    $data = DB::select('SELECT `order`.`id`,`product`.`name`,(SELECT GROUP_CONCAT(`imageproduct`.`imgpath`) as images FROM `imageproduct` WHERE `imageproduct`.`productCode` = `product`.`id`) as listpath, `order`.`username`, `order`.`productCode`, `order`.`amount`, `order`.`price`, `order`.`priceship`, `order`.`type`, `order`.`check`, `order`.`create` FROM `order`,`shop`,`product` WHERE `order`.`productCode` = `product`.`id` AND `product`.`shopCode` = `shop`.`id` AND `order`.`check` = 4');
    return response()->json($data);
  }
  public function product()
  {
    $data = DB::select('SELECT `product`.`id`,GROUP_CONCAT(`imageproduct`.`imgpath`) as listpath, `groupProduct`, `shopCode`, `name`, `product`.`price`, `product`.`description`, `rating`, `amount`, `countSale`, `discount`, `lastday`, `create` FROM `product`,`imageproduct` WHERE `imageproduct`.`productCode`= `product`.`id` AND `amount` > 0 GROUP BY `imageproduct`.`productCode`');
    return response()->json($data);
  }
  public function comment()
  {
    $data = DB::select('SELECT `commentshop`.`id`, `shop`.`nameShop`, `commentshop`.`content`, `customer`.`name`, `commentshop`.`rating`, `commentshop`.`create` FROM `commentshop`, `shop`,`customer` WHERE `commentshop`.`shopCode` = `shop`.`id` AND `commentshop`.`user` = `customer`.`userID` order BY `commentshop`.`create`DESC');
    return response()->json($data);
  }
  public function deletecmt($id)
  {
    DB::select('DELETE FROM `commentshop` WHERE `commentshop`.`id` = ?',[$id]);
    return response()->json("success",200);
  }
  public function detailproduct($id)
  {
    $data = DB::select('SELECT `product`.`id`, `groupProduct`,`groupProduct`,(SELECT GROUP_CONCAT(`imageproduct`.`imgpath`) as images FROM `imageproduct` WHERE `imageproduct`.`productCode` = `product`.`id`) as listpath, `shopCode`, `name`, `price`, `description`, `rating`, `amount`, `countSale`, `discount`, `lastday`, `create` FROM `product`,`imageproduct` WHERE `imageproduct`.`productCode` = `product`.`id` AND `product`.`id` = ? GROUP BY `imageproduct`.`productCode`',[$id]);
    return response()->json($data);
  }
  public function groupbar($year)
  {
    $data = DB::select('SELECT `group`.`name`, COUNT(`group`.`name`) as amount FROM `group`,`order`,`product` WHERE DATE_FORMAT(`order`.`create`, "%Y") - ? = 0 AND`group`.`id` = `product`.`groupProduct` and `order`.`check` = 3 AND `product`.`id` = `order`.`productCode` GROUP BY `group`.`name` ORDER BY COUNT(`group`.`name`) DESC LIMIT 5',[$year]);
    return response()->json($data);
  }
  public function totalbar($year)
  {
    $data = DB::select('SELECT `shop`.`nameShop`, SUM(`order`.`price` * `order`.`amount` + `order`.`priceship`) as total FROM `order`,`product`,`shop` WHERE DATE_FORMAT(`order`.`create`, "%Y") - ? = 0 and `order`.`check` = 3 and `product`.`id` = `order`.`productCode` AND `shop`.`id` = `product`.`shopCode` GROUP BY `shop`.`nameShop` ORDER BY SUM(`order`.`price` * `order`.`amount` + `order`.`priceship`) DESC LIMIT 5',[$year]);
    return response()->json($data);
  }
  public function totalline($year)
  {
    $data = DB::select('SELECT DATE_FORMAT(`order`.`create`, "%m") as month ,SUM(`order`.`price` * `order`.`amount` + `order`.`priceship`) as total FROM `order` WHERE DATE_FORMAT(`order`.`create`, "%Y") - ? = 0 and `order`.`check` = 3 GROUP BY DATE_FORMAT(`order`.`create`, "%m") ORDER BY DATE_FORMAT(`order`.`create`, "%m")',[$year]);
    return response()->json($data);
  }
}
