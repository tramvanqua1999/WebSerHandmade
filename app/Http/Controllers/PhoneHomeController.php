<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use DB;
use Auth;
use Storage;
use File;
class PhoneHomeController extends Controller
{
    public function login(Request $request)
    {
        $arr = [
            'username' => $request->username,
            'password' =>$request->password,
        ];
         //dd($arr);
         //dd(Auth::attempt($arr));
        if (Auth::attempt($arr)) {
            $id = $request->username;
            $puttype = DB::table('user')->where('username','=',$id)->select('type','check')->first();
            $type = $puttype->type;
            $check = $puttype->check;
            if($type == '0' && $check == 0){
                return response()->json(0, 201);
            }if($type == '0' && $check == 1){
                return response()->json(1, 201);
            }else if($type == '1' || $type == '2'){
                return response()->json(2, 201);
            }
            //  
        }else{
            return response()->json(999, 201);
            // dd('thất bại');   
        }
    }

    public function product()
    {
        try {
            $data =  DB::select('SELECT GROUP_CONCAT(`imageproduct`.`imgpath`) as listpath, `discount`, `lastday`,`product`.`id` ,`imageproduct`.`productCode`, `groupProduct`, `shopCode`, `name`, `price`, `description`, `rating`, `amount`, `create` FROM `product`, `imageproduct` WHERE `product`.`amount` > 0 AND`imageproduct`.`productCode`= `product`.`id` GROUP BY `imageproduct`.`productCode`  order BY `product`.`create` DESC');
            return response()->json($data,200);
        } catch (ModelNotFoundException $exception) {
            return response()->json("error",300);
        }
        
    }
    public function highestdiscount()
    {
        $data =  DB::select('SELECT GROUP_CONCAT(`imageproduct`.`imgpath`) as listpath, `discount`, `lastday`,`product`.`id` ,`imageproduct`.`productCode`, `groupProduct`, `shopCode`, `name`, `price`, `description`, `rating`, `amount`, `create` FROM `product`, `imageproduct` WHERE `imageproduct`.`productCode`= `product`.`id` AND `product`.`discount` != 0 AND `product`.`lastday` >= NOW() GROUP BY `imageproduct`.`productCode` ORDER BY `discount` DESC LIMIT 5');
        return response()->json($data,200);
    }
    
    public function hasdiscount()
    {
        $data =  DB::select('SELECT * FROM `product` WHERE `product`.`discount` > 0 and `product`.`lastday` >= CURRENT_TIMESTAMP');
        return response()->json($data,200);
    }
    public function limited()
    {
        $data =  DB::select('SELECT GROUP_CONCAT(`imageproduct`.`imgpath`) as listpath, `discount`, `lastday`,`product`.`id` ,`imageproduct`.`productCode`, `groupProduct`, `shopCode`, `name`, `price`, `description`, `rating`, `amount`, `create` FROM `product`, `imageproduct` WHERE `imageproduct`.`productCode`= `product`.`id` GROUP BY `imageproduct`.`productCode` ORDER BY `product`.`amount` ASC LIMIT 5');
        return response()->json($data,200);
    }

    public function shop(Request $request)
    {
        $shopcode = json_decode($request->shopCode);
        $data = DB::select('SELECT `shop`.`id`, `shop`.`shopID`, `shop`.`nameShop`, `shop`.`img`, `shop`.`imgBg`, `shop`.`ratingShop`, `shop`.`follow`, `shop`.`description`, `shop`.`address`, `shop`.`phone`, `shop`.`email`, `shop`.`fee`, `shop`.`price`, `shop`.`createshop`, (SELECT COUNT(`shop`.`id`) FROM `product`,`shop` WHERE `product`.`shopCode` = `shop`.`id` AND `shop`.`id` = ? group BY `shop`.`id`) as sumproduct FROM `shop` WHERE `shop`.`id`= ?', [$shopcode,$shopcode]);
        return response()->json($data,200);
    }
    
    public function getprofile(Request $request)
    {
        $username = json_decode($request->username);
        $data = DB::select('SELECT * FROM `customer` WHERE `customer`.`userID` = ?',[ $username]);
       return response()->json($data,200);
    }

    public function favorite(Request $request)
    {
       if($data = DB::select('SELECT * FROM `favorite` WHERE `favorite`.`productCode` = ?  AND `favorite`.`username` = ?',[json_decode($request->productCode),$request->username])){
            return response()->json(true,200);
       }
       else{
           return response()->json(false,200);
       }
    }

    public function follow(Request $request)
    {
       if($data = DB::select('SELECT * FROM `follow` WHERE `follow`.`shopCode` = ?  AND `follow`.`username` = ?',[json_decode($request->shopCode),$request->username])){
            return response()->json(true,200);
       }
       else{
           return response()->json(false,200);
       }
    }
    
    public function checkFollow(Request $request)
    {
        $username =  $request->username;
        $shopCode = $request->shopCode;
            if($check =  DB::select( 'SELECT `id`, `shopCode`, `username` FROM `follow` WHERE `username` = ? AND `shopCode` = ? ', [$username,json_decode($shopCode)])){
                DB::select('DELETE FROM `follow` WHERE `follow`.`shopCode` = ? and `follow`.`username` = ?', [json_decode($shopCode),$username]);
           }else{
                DB::select('INSERT INTO `follow`(`shopCode`, `username`) VALUES (?,?)',[$shopCode,$username]);
           }
           return response()->json(1,200);
    }
    
    public function countFollow(Request $request)
    {
        $shopCode = json_decode($request->shopCode);
        $count  =  count(DB::select('SELECT * FROM `follow` WHERE `shopCode` = ?',[$shopCode]));
        return response()->json($count,200);
    }

    public function checkFavorite(Request $request)
    {
        $username =  $request->username;
        $productCode = $request->productCode;
        $check = json_decode($request->check); 
      
            if($check =  DB::select( 'SELECT `id`, `productCode`, `username` FROM `favorite` WHERE `username` = ? AND `productCode` = ? ', [$username,json_decode($productCode)])){
                DB::select('DELETE FROM `favorite` WHERE `favorite`.`productCode` = ? and `favorite`.`username` = ?', [json_decode($productCode),$username]);
           }else{
                DB::select('INSERT INTO `favorite`(`productCode`, `username`) VALUES (?,?)',[$productCode,$username]);
           }
           return response()->json(1,200);
      
    }
    

    public function checkdiscount(Request $request)
    {
        $data =  DB::select('SELECT `id`, `productCode`, `shopCode`, `discount`, `lastday` FROM `discounts`');
        return response()->json($data,200);
    }


    public function phone(Request $request)
    {
        $phone = $request->phone;
        if(!DB::table('user')
        ->select('id')
        ->where('username','=', $phone)
        ->first()){
            $pin = rand(1000, 9999);
            
            return response()->json($pin,
             201);
        }
        return response()->json('error',300);
    }

    public function register(Request $request)
    {
        $phone = $request->phone;
        $check = json_decode($request->check);
        $name = $request->name;
        $mail = $request->mail;
        $address = $request->address;
        // $pass = $request->pass;
        $pass = bcrypt($request->pass);
    
        if( $check == 1){
            DB::select('INSERT INTO `customer`( `userID`, `name`, `email`, `address`) VALUES (?,?,?,?)', [$phone,$name,$mail,$address]);
            DB::select('INSERT INTO `user`(`username`, `password`, `type`) VALUES (?,?,0)', [$phone,$pass]);
            return response()->json(1,201);
        }else if($check == 2){
            DB::select('INSERT INTO `user`(`username`, `password`, `type`) VALUES (?,?,1)', [$phone,$pass]);
            DB::select('INSERT INTO `shop`(`shopID`, `nameShop`,  `email`, `address`)  VALUES (?,?,?,?)', [$phone,$name,$mail,$address]);
            $lastid = intval(DB::getPdo()->lastInsertId());
            DB::select('INSERT INTO `credit`(`shopCode`) VALUES (?)',[$lastid]);
            return response()->json(2,201);
        }
        return response()->json('error',300);
    }

    
    public function saveprofile(Request $request)
    {
        $name = $request->name;
        $mail = $request->mail;
        $address = $request->address;
        $pass = bcrypt($request->pass);
        $username =  json_decode($request->username);
        try{
            DB::select('UPDATE `customer` SET `name`=?,`email`=?,`address`=? WHERE `customer`.`userID` = ?',[$name,$mail,$address,$username]);
            DB::select('UPDATE `user` SET `password`= ? WHERE `user`.`username` = ?',[$pass,$username]);
            return response()->json(1,200);
        }catch(ModelNotFoundException $exception){
            return response()->json(0,200);
        }
    }
    public function shopfollow(Request $request)
    {
        $username = json_decode($request->username);
        try {
            $data =  DB::select('SELECT `shop`.`id`, `shopID`, `nameShop`, `img`, `imgBg`, `ratingShop`, `follow`, `description`, `address`, `phone`, `email`, `fee`, `price`, `createshop`,(SELECT COUNT(`product`.`shopCode`) FROM `product`,`shop` WHERE `product`.`shopCode` = `shop`.`id` ) as sumproduct FROM `shop`,`follow` WHERE `follow`.`shopCode` = `shop`.`id` AND `follow`.`username`=? order BY `follow`.`create`',[$username]);
            return response()->json($data,200);
        } catch (ModelNotFoundException $exception) {
            return response()->json("error",300);
        }
    }
    
    public function commentshop(Request $request)
    {
        $username = $request->username;
        $comment = $request->comment;
        $id = json_decode($request->id);
        $rating = json_decode($request->rating);
        try {
            $data =  DB::select('INSERT INTO `commentshop`( `shopCode`, `content`, `user`, `rating`) VALUES (?,?,?,?)',[$id,$comment,$username,$rating]);
            return response()->json(1,200);
        } catch (ModelNotFoundException $exception) {
            return response()->json("error",300);
        }
    }
    public function ratingproduct(Request $request)
    {
        $username = $request->username;
        $id = json_decode($request->id);
        $rating = json_decode($request->rating);
        try {
            if(DB::select('SELECT * FROM `ratingproduct` WHERE `ratingproduct`.`productCode` = ? AND `ratingproduct`.`user` = ?',[$id,$username])){
                return response()->json(1,201);
            }
            else{ 
                $data =  DB::select('INSERT INTO `ratingproduct`( `productCode`, `user`, `rating`) VALUES (?,?,?)',[$id,$username,$rating]);
                return response()->json(1,200);
            }
           
        } catch (ModelNotFoundException $exception) {
            return response()->json("error",300);
        }
    }
    public function productfavorite(Request $request)
    {
        $username = json_decode($request->username);
        try {
            $data =  DB::select('SELECT GROUP_CONCAT(`imageproduct`.`imgpath`) as listpath,`product`.`id`, `groupProduct`, `shopCode`, `name`, `price`, `description`, `rating`, `amount`, `countSale`, `discount`, `lastday`, `create` FROM `product`,`favorite`,`imageproduct` WHERE `favorite`.`username` = ? AND `product`.`id` = `favorite`.`productCode` AND `product`.`amount` > 0 AND`imageproduct`.`productCode`= `product`.`id` GROUP BY `imageproduct`.`productCode`',[$username]);
            return response()->json($data,200);
        } catch (ModelNotFoundException $exception) {
            return response()->json("error",300);
        }
    }
    public function ProductShopSale(Request $request)
    {
        $shopcode = json_decode($request->shopCode);
        $data =  DB::select('SELECT GROUP_CONCAT(`imageproduct`.`imgpath`) as listpath, `product`.`id`, `groupProduct`, `shopCode`, `name`, `price`, `description`, `rating`, `amount`, `discount`, `lastday`, `create` FROM `product`, `imageproduct` WHERE `imageproduct`.`productCode`= `product`.`id`AND `product`.`shopCode` = ? AND `product`.`lastday` >= CURDATE() GROUP BY `imageproduct`.`productCode`',[$shopcode]);
        return response()->json($data,200);
    }
    
    public function ProductShop(Request $request)
    {
        $shopcode = json_decode($request->shopCode);
        $data =  DB::select('SELECT GROUP_CONCAT(`imageproduct`.`imgpath`) as listpath, `product`.`id`, `groupProduct`, `shopCode`, `name`, `price`, `description`, `rating`, `amount`, `discount`, `lastday`, `create` FROM `product`, `imageproduct` WHERE `imageproduct`.`productCode`= `product`.`id`AND `product`.`shopCode` = ? GROUP BY `imageproduct`.`productCode`',[$shopcode]);
        return response()->json($data,200);
    }
    public function order(Request $request)
    {
       $username = $request->username;
       $arrproductcode =  json_decode($request->arrproductcode);
       $arramount =  json_decode($request->arramount);
       $price = json_decode($request->price);
       $priceship = json_decode($request->priceship);
       for ($i=0; $i < count($arrproductcode); $i++) { 
        $a = 
          DB::select('INSERT INTO `order`(`username`, `productCode`, `amount`, `price`, `priceship`) VALUES (?,?,?,?,?)',[$username,$arrproductcode[$i],$arramount[$i],$price[$i]*1000,$priceship[$i]]);
       } 
       return response()->json("success",200);
    }
    public function ordercard(Request $request)
    {
       $username = $request->username;
       $type = json_decode($request->type);
       $arrproductcode =  json_decode($request->arrproductcode);
       $arramount =  json_decode($request->arramount);
       $price = json_decode($request->price);
       $priceship = json_decode($request->priceship);
       for ($i=0; $i < count($arrproductcode); $i++) { 
        $a = 
          DB::select('INSERT INTO `order`(`username`, `productCode`, `amount`, `price`, `priceship`,`type`) VALUES (?,?,?,?,?,?)',[$username,$arrproductcode[$i],$arramount[$i],$price[$i]*1000,$priceship[$i],$type]);
       } 
       return response()->json("success",200);
    }
    
    public function listcomment(Request $request)
    {
        $shopcode = json_decode($request->shopcode);
        try {
            $data = DB::select('SELECT `customer`.`id`, `shopCode`, `content`, `customer`.`name`, `rating`, `commentshop`.`create` FROM `commentshop`, `customer` WHERE `commentshop`.`shopCode` = ? AND `customer`.`userID`= `commentshop`.`user` order BY `commentshop`.`create` DESC',[$shopcode]);
            return response()->json($data,200);
        } catch (ModelNotFoundException $exception) {
            return response()->json("error",300);
        }
    }

  
// --------------Ace.Lyon.thon-------------------
    // public function updateImage(Request $request)
    // {
    //     $username = $request->username;
    //     $image = $request->file('image');
        
    //     $googleDriveStorage=Storage::cloud();
    //     if(isset($image))
    //     {
    //         $path = public_path('File/File_img');
    //         $name = Str::Random(5).'_'.$image->getClientOriginalName(); 
    //         $fileData = File::get($image);

    //         $googleDriveStorage->put($name,  $fileData);
    //         $recursive = false;
    //         $dir = '/';
    //         $fileinfo = collect($googleDriveStorage->listContents($dir, $recursive))
    //         ->where('type', 'file')
    //         ->where('name', $name)
    //         ->first();
    //         $contents = $fileinfo['path'];
    //         $url = "https://drive.google.com/uc?export=view&id=".$contents;

    //     }else{
    //         $name = "";
    //     };
    //     if(DB::update('UPDATE `customer` , `user` SET `customer`.`avatar` = ? WHERE `customer`.`usernameId` = `user`.`id` AND `user`.`username` = ?',[$url,$username])){
    //         return response()->json(0, 200);
    //     }else{
    //         return response()->json(1, 200);
    //     }
    // }
    // public function updateBackground(Request $request)
    // {
    //     $username = $request->username;
    //     $image = $request->file('image');
        
    //     $googleDriveStorage=Storage::cloud();
    //     if(isset($image))
    //     {
    //         $path = public_path('File/File_img');
    //         $name = Str::Random(5).'_'.$image->getClientOriginalName(); 
    //         $fileData = File::get($image);

    //         $googleDriveStorage->put($name,  $fileData);
    //         $recursive = false;
    //         $dir = '/';
    //         $fileinfo = collect($googleDriveStorage->listContents($dir, $recursive))
    //         ->where('type', 'file')
    //         ->where('name', $name)
    //         ->first();
    //         $contents = $fileinfo['path'];
    //         $url = "https://drive.google.com/uc?export=view&id=".$contents;

    //     }else{
    //         $name = "";
    //     };
    //     if(DB::update('UPDATE `customer` , `user` SET `customer`.`background` = ? WHERE `customer`.`usernameId` = `user`.`id` AND `user`.`username` = ?',[$url,$username])){
    //         return response()->json(0, 200);
    //     }else{
    //         return response()->json(1, 200);
    //     }
    // }
    // public function retrieve(Request $request)
    // {
    //     $username = $request->username;
    //     $data = DB::select('SELECT `customer`.`id`, `user`.`username`, `customer`.`fullname`, `customer`.`decription`, `customer`.`birthDay`, `customer`.`avatar`, `customer`.`background` FROM `customer`,`user` WHERE `user`.`id` = `customer`.`usernameId` AND `user`.`username` = ?',[$username]);
    //     return response()->json($data, 200);
    // }
    // public function phone(Request $request)
    // {
    //     $phone = $request->phone;
    
    //     if( !$check = DB::table('user')
    //     ->select('id')
    //     ->where('username','=', $phone)
    //     ->first()){
    //         $pin = mt_rand(1000, 9999);
    //         $string = str_shuffle($pin);
    //         return response()->json( (int) $string,
    //          201);
    //     }
    //     return response()->json('error',300);
 
       

    // }
    // public function getService(Request $request)
    // {
        
    //     $ownerLongitude = "105.7706";
    //     $ownerLatitude =  "10.0299";
    //     $distance = json_decode($request->range) / 1000.0;
    //     $type = json_decode($request->type);

    //     $raw = DB::raw(' ( 6371 * acos( cos( radians(' . $ownerLatitude . ') ) * 
    //     cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $ownerLongitude . ') ) + 
    //     sin( radians(' . $ownerLatitude . ') ) *
    //     sin( radians( latitude ) ) ) )  AS distance');
    //     if($type[0] == -1){
    //         $cares = DB::table('service')->select('*', $raw)
    //         ->addSelect($raw)
    //         ->orderBy('distance', 'ASC')
    //         ->having('distance', '<=', $distance)->get();
    //     }else if(in_array(0, $type)){
    //         $cares = DB::table('service')->select('*', $raw)
    //         ->addSelect($raw)->whereNotIn('type',[1,2,3,4,5,6,7,8,9])
    //         ->orderBy('distance', 'ASC')
    //         ->having('distance', '<=', $distance)->get();
    //     }
    //     else{
    //         $cares = DB::table('service')->select('*', $raw)
    //         ->addSelect($raw)->whereIn('type',$type)
    //         ->orderBy('distance', 'ASC')
    //         ->having('distance', '<=', $distance)->get();
    //     }
    //     return response()->json($cares,200);
    // }

    // public function getListPost(Request $request)
    // {
    //     $username = $request->username;
    //     $posts = DB::select('SELECT `service`.`id`,`posts`.`id` as postId ,`user`.`username` ,`service`.`name`, `service`.`avatar`, `posts`.`title`, `posts`.`image`, `posts`.`date` , COUNT(`customer`.`usernameId`) as countlike FROM `likes`, `posts`, `customer`, `user`,`service` WHERE `customer`.`usernameId` = `likes`.`usernameId` AND `service`.`usernameId` = `posts`.`usernameId` AND `service`.`usernameId` =  `user`.`id`  AND `posts`.`id` = `likes`.`postId` AND `service`.`usernameId` = ?',[$username]);
    //     return response()->json($posts,200);
    // }
    // public function checklike(Request $request)
    // {
    //     $username = $request->username;
    //     $postid = $request->postid;
    //     if($check = DB::select('SELECT * from `likes`, `user` WHERE  `likes`.`usernameId` = `user`.`id`and `user`.`username` = ? AND `likes`.`postId` = ?',[$username, $postid])){
    //         return response()->json(true, 200);
    //     }else{
    //         return response()->json(false, 200);
    //     }
 
    // }
    // public function like(Request $request)
    // {
    //     $username = $request->username;
    //     $postid = $request->postid;
    //     $id  = DB::table('user')->where('username','=',$username)->get()->pluck('id');
    //     if($check = DB::select('SELECT * from `likes`, `user` WHERE  `likes`.`usernameId` = `user`.`id`and `user`.`username` = ? AND `likes`.`postId` = ?',[$username, $postid])){
    //         DB::select('DELETE  from `likes` WHERE  `likes`.`usernameId` = ? AND `likes`.`postId` = ?',[$id[0], $postid]);
    //         return response()->json(0, 200);
    //         // return $id[0];
    //     }else{
    //         DB::select('INSERT INTO `likes` (`usernameId`, `postId`) VALUES (?,?)',[$id[0], $postid]);
    //         return response()->json(0, 200);
    //         // return $id[0];
    //     }
 
    // }
}