<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\HomeCollection;
use App\Models\User;
use DB;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Storage;
use File;
use Illuminate\Support\Str;

class ShopController extends Controller 
{
    public function index($id)
    {
       
       $data = DB::select('SELECT `shop`.`id`, `shopID`, `nameShop`, `img`, `imgBg`, `ratingShop`, `follow`, `description`, `address`, `phone`, `email`, `fee`, `price`, `createshop` FROM `user`,`shop` WHERE `shop`.`shopID` = `user`.`username` AND `user`.`id` = ?',[$id]);
       return response()->json($data);
    }
    public function store($id,Request $request)
    {
        $shopcode = DB::select('SELECT `shop`.`id` FROM `shop`,`user` WHERE `user`.`id` = ? AND `user`.`username` = `shop`.`shopID`',[$id]);
        $nameproduct = $request->nameproduct;
        $priceproduct = $request->priceproduct;
        $description = $request->description;
        $amountproduct = $request->amountproduct;
        $discountproduct = $request->discountproduct;
        $date = $request->date;
        $type = $request->type;
        $shopcodeid = $shopcode[0]->id;
        $data = DB::select('INSERT INTO `product`(`groupProduct`, `shopCode`, `name`, `price`, `description`, `amount`,`discount`, `lastday`) VALUES (?,?,?,?,?,?,?,?)',[$type,$shopcodeid,$nameproduct,$priceproduct,$description,$amountproduct,$discountproduct,$date]);
        $lastid = intval(DB::getPdo()->lastInsertId());
        // --------------------------------------
        $images = $request->file('file');
        $googleDriveStorage = Storage::disk('google');
        if(isset($images))
        {
            foreach($images as $image){
                $name = Str::Random(5).'_'.$image->getClientOriginalName();
                $fileData = File::get($image);
                $googleDriveStorage->put($name,  $fileData);
                $recursive = false;
                $dir = '/';
                $fileinfo = collect($googleDriveStorage->listContents($dir, $recursive))
                ->where('type', 'file')
                ->where('name', $name)
                ->first();
                $contents = $fileinfo['path'];
                $url = "https://drive.google.com/uc?export=view&id=".$contents;
                DB::select('INSERT INTO `imageproduct`(`productCode`, `imgpath`) VALUES (?,?)',[$lastid,$url]);
            }   
        };
        return response()->json("success",200);
        
    }
    public function getgroup()
    {
       $data = DB::select('SELECT `id`, `name`, `create` FROM `group` WHERE 1');
       return response()->json($data);
    }

    public function home($id)
    {
        $data = DB::select('SELECT `product`.`id`,GROUP_CONCAT(`imageproduct`.`imgpath`) as listpath, `groupProduct`, `shopCode`, `name`, `product`.`price`, `product`.`description`, `rating`, `amount`, `countSale`, `discount`, `lastday`, `create` FROM `product`,`user`,`shop`,`imageproduct` WHERE `user`.`id` = ? AND `user`.`username` = `shop`.`shopID` AND `shop`.`id` = `product`.`shopCode` AND `imageproduct`.`productCode`= `product`.`id` GROUP BY `imageproduct`.`productCode`',[$id]);
        return response()->json($data);
    }
    public function delete($id)
    {
        $data = DB::select('SELECT `imgpath` FROM `imageproduct` WHERE `imageproduct`.`productCode` = ?',[$id]);
        for($i=0; $i <count($data); $i++){
            $name = explode("id=", $data[$i]->imgpath,2)[1];
            Storage::disk('google')->delete("1y39sFmnrDdAd2buIR7Y2ZyQKANW7Su7w/".$name);
        }
        DB::select('DELETE FROM `product` WHERE `product`.`id` = ?',[$id]);
        DB::select('DELETE FROM `imageproduct` WHERE `imageproduct`.`productCode` = ?',[$id]);
        
        return response()->json('successfully deleted');
    }

    public function getinfupdate($id)
    {
        $data = DB::select('SELECT `id`, `groupProduct`, `shopCode`, `name`, `price`, `description`, `rating`, `amount`, `countSale`, `discount`, `lastday`, `create` FROM `product` WHERE `id` = ?',[$id]);
        return response()->json($data);
    }
    public function update($id,Request $request)
    {
        $name = $request->name;
        $price = $request->price;
        $description = $request->description;
        $amount = $request->amount;
        $discount = $request->discount;
        $lastday = $request->lastday;
        $groupProduct = $request->groupProduct;
        DB::select('UPDATE `product` SET `groupProduct`=?,`name`=?,`price`=?,`description`=?,`amount`=?,`discount`=?,`lastday`=? WHERE `id`= ?',[$groupProduct,$name,$price,$description,$amount,$discount,$lastday,$id]);
        return response()->json('successfully update');
    }
    public function getfee($id)
    {
       $data = DB::select('SELECT `shop`.`id`, `shopID`, `nameShop`, `img`, `imgBg`, `ratingShop`, `follow`, `description`, `address`, `phone`, `email`, `fee`, `price`, `createshop` FROM `shop`,`user` WHERE `user`.`id` = ? AND `user`.`username` = `shop`.`shopID`',[$id]);
       return response()->json($data);
    }
    public function addfee($id,Request $request)
    {
        $fee = $request->fee;
        $price = $request->price;
        $data = DB::select('UPDATE `shop` SET `fee`=?,`price`=? WHERE `shop`.`id` = (SELECT `shop`.`id` FROM `shop`,`user` WHERE `user`.`id` = ?)',[$fee,$price,$id]);
        return response()->json('successfully update');
    }
    public function getinfoShop($id)
    {
        $data = DB::select('SELECT `credit`.`shopCode`, `credit`.`cardNumber`, `credit`.`expiryDate`, `credit`.`cardHolderName`, `credit`.`cvvCode`,`shop`.`id`, `shopID`, `nameShop`, `img`, `imgBg`, `ratingShop`, `follow`, `description`, `address`, `phone`, `email`, `fee`, `price`, `createshop`,`user`.`check` FROM `shop`,`user`,`credit` WHERE `shop`.`shopID` = `user`.`username` and `shop`.`id` = `credit`.`shopCode` and`user`.`id`=?',[$id]);
        return response()->json($data);
    }
    public function updateinfShop($id,Request $request)
    {
        $nameShop = $request->nameShop;
        $description = $request->description;
        $address = $request->address;
        $phone = $request->phone;
        $email = $request->email;
        $data = DB::select('UPDATE `shop` SET `nameShop`= ? ,`description`= ?,`address`= ?,`phone`= ?,`email`=? WHERE `shop`.`id` = (SELECT `shop`.`id` FROM `shop`,`user` WHERE `user`.`id` = ? AND `user`.`username` = `shop`.`shopID`)',[$nameShop,$description,$address,$phone,$email,$id]);
        return response()->json('successfully update'); 
    }
    public function updatecreditShop($id,Request $request)
    {
        $cardHolderName = $request->cardHolderName;
        $cardNumber = $request->cardNumber;
        $expiryDate = $request->expiryDate;
        $cvvCode = $request->cvvCode;
        $data = DB::select('UPDATE `credit` SET `cardNumber`= ?,`expiryDate`= ?,`cardHolderName`= ?,`cvvCode`= ? WHERE `credit`.`shopCode` =  (SELECT `shop`.`id` FROM `shop`,`user` WHERE `user`.`id` = ? AND `user`.`username` = `shop`.`shopID`)',[$cardNumber,$expiryDate,$cardHolderName,$cvvCode,$id]);
        return response()->json('successfully update'); 
    }
    public function updateAvatar($id,Request $request)
    {
        $images = $request->file('file');
        $googleDriveStorage = Storage::disk('google');
        if(isset($images))
        {
                $name = Str::Random(5).'_'.$images->getClientOriginalName();
                $fileData = File::get($images);
                $googleDriveStorage->put($name,  $fileData);
                $recursive = false;
                $dir = '/';
                $fileinfo = collect($googleDriveStorage->listContents($dir, $recursive))
                ->where('type', 'file')
                ->where('name', $name)
                ->first();
                $contents = $fileinfo['path'];
                $url = "https://drive.google.com/uc?export=view&id=".$contents;
                DB::select('UPDATE `shop` SET `img`=? WHERE `shop`.`id` = (SELECT `shop`.`id` FROM `shop`,`user` WHERE `user`.`id` = ? AND `user`.`username` = `shop`.`shopID`)',[$url,$id]);
        };
        return response()->json("success",200);
    }

    public function order($id,Request $request)
    {
        $data = DB::select('SELECT `order`.`id`, (SELECT GROUP_CONCAT(`imageproduct`.`imgpath`) as images FROM `imageproduct` WHERE `imageproduct`.`productCode` = `product`.`id`) as listpath,`product`.`name`,`order`.`username`, `order`.`productCode`, `order`.`amount`, `order`.`price`, `priceship`, `order`.`type`, `order`.`check`, `order`.`create` FROM `order`,`product`,`shop`,`user` WHERE `order`.`productCode` = `product`.`id` AND `product`.`shopCode` =`shop`.`id` AND `shop`.`shopID` = `user`.`username` AND  `order`.`check` = 0 AND `user`.`id` = ?',[$id]);
        return response()->json($data);
    }
    public function confirm($id)
    {
       DB::select('UPDATE `order` SET `check`= 1 WHERE `order`.`id` = ?',[$id]);
       return response()->json("success",200);
    }
    public function cancel($id)
    {
       DB::select('UPDATE `order` SET `check`= 2 WHERE `order`.`id` = ?',[$id]);
       return response()->json("success",200);
    }

    public function listcancel($id)
    {
        $data = DB::select('SELECT `order`.`id`, (SELECT GROUP_CONCAT(`imageproduct`.`imgpath`) as images FROM `imageproduct` WHERE `imageproduct`.`productCode` = `product`.`id`) as listpath,`product`.`name`,`order`.`username`, `order`.`productCode`, `order`.`amount`, `order`.`price`, `priceship`, `order`.`type`, `order`.`check`, `order`.`create` FROM `order`,`product`,`shop`,`user` WHERE `order`.`productCode` = `product`.`id` AND `product`.`shopCode` =`shop`.`id` AND `shop`.`shopID` = `user`.`username` AND  `order`.`check` = 2 AND `user`.`id` = ?',[$id]);
        return response()->json($data);
    }
    public function listcofirm($id)
    {
        $data = DB::select('SELECT `order`.`id`, (SELECT GROUP_CONCAT(`imageproduct`.`imgpath`) as images FROM `imageproduct` WHERE `imageproduct`.`productCode` = `product`.`id`) as listpath,`product`.`name`,`order`.`username`, `order`.`productCode`, `order`.`amount`, `order`.`price`, `priceship`, `order`.`type`, `order`.`check`, `order`.`create` FROM `order`,`product`,`shop`,`user` WHERE `order`.`productCode` = `product`.`id` AND `product`.`shopCode` =`shop`.`id` AND `shop`.`shopID` = `user`.`username` AND  `order`.`check` = 1 AND `user`.`id` = ?',[$id]);
        return response()->json($data);
    }
    public function groupbar($year,Request $request)
  {
    $data = DB::select('SELECT `group`.`name`, COUNT(`group`.`name`) as amount FROM `group`,`order`,`product`,`shop`,`user` WHERE DATE_FORMAT(`order`.`create`, "%Y") - ? = 0 AND`group`.`id` = `product`.`groupProduct` AND `product`.`id` = `order`.`productCode` AND `product`.`shopCode` = `shop`.`id` AND `shop`.`shopID` = `user`.`username` AND `user`.`id` = ? AND `order`.`check` = 3  GROUP BY `group`.`name` ORDER BY COUNT(`group`.`name`) DESC LIMIT 5',[$year,$request->id]);
    return response()->json($data);
  }
  public function totalline($year,Request $request)
  {
    $data = DB::select('SELECT DATE_FORMAT(`order`.`create`, "%m") as month ,SUM(`order`.`price` * `order`.`amount` + `order`.`priceship`) as total FROM `order`,`product`,`shop`,`user` WHERE DATE_FORMAT(`order`.`create`, "%Y") - ? = 0 and `order`.`check` = 3 AND `product`.`id` = `order`.`productCode` AND `product`.`shopCode` = `shop`.`id` AND `shop`.`shopID` = `user`.`username` AND `user`.`id` = ? GROUP BY DATE_FORMAT(`order`.`create`, "%m") ORDER BY DATE_FORMAT(`order`.`create`, "%m")',[$year,$request->id]);
    return response()->json($data);
  }
  public function detailproduct($id)
  {
    $data = DB::select('SELECT `product`.`id`, `groupProduct`,`groupProduct`,(SELECT GROUP_CONCAT(`imageproduct`.`imgpath`) as images FROM `imageproduct` WHERE `imageproduct`.`productCode` = `product`.`id`) as listpath, `shopCode`, `name`, `price`, `description`, `rating`, `amount`, `countSale`, `discount`, `lastday`, `create` FROM `product`,`imageproduct` WHERE `imageproduct`.`productCode` = `product`.`id` AND `product`.`id` = ? GROUP BY `imageproduct`.`productCode`',[$id]);
    return response()->json($data);
  }
}

