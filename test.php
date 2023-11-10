<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\User;

class UserController extends Controller
{
    //
    function create(Request $req){
        error_log("create user called");
        $n= $req->name;
        $u= $req->username;
        $pw= $req->password;
        $p= $req->phone;


        try{
                $isAdmin=1;
                if(DB::Select("Select * from users where isAdmin='1'")){
                    $isAdmin=0;
                }
                return DB::Insert("Insert into users(name,username,password,phone,isAdmin) values('$n','$u','$pw','$p','$isAdmin')");
            }
        catch(\Exception $e){
            return 0;
        }
    }
    function read(Request $req){
        error_log("read user called");
        //$h=$req->headers;
          //  return response(['response'=>"".$h]);
        $index=$req->header('startIndex');
        //   return response(['response'=>"".$index]);

          //return response(['response'=>"".$index]);
          try{

            return DB::Select("Select * from users LIMIT 9 OFFSET $index ");
        }
        catch(\Exception $e){
           // error_log("Exception: ".$e);
            return 0;
        }
    }
    function update(Request $req){
        error_log("update user called");
        $n= $req->name;
        $uid= $req->id;
        $pw= $req->password;
        $p= $req->phone;
        try{
            return DB::update("Update users set name='$n',password='$pw',phone='$p' where id='$uid'");
        }
        catch(\Exception $e){
            error_log("Exception: ".$e);
            return 0;
        }
    }
    function delete(Request $req){
        error_log("delete user called");
        $uid= $req->userid;
        //echo $uid;
        try{
            return DB::delete("Delete from users where id='$uid'");
        }
        catch(\Exception $e){
            error_log("Exception: ".$e);
            return 0;
        }
    }


    function getToken(Request $req){
        error_log("getToken called");

        $username=$req->username;
        $password=$req->password;
        // return response(['username'=>$username]);
        try{
            $user=user::Where('username',$username)->first();

            if($user && $user->password==$password){
                //$user->id=$user->userid;
                $token= $user->createToken('jwt')->plainTextToken;
                $isAdmin= $user->isAdmin;
                return response([$token.":".$isAdmin]);//::all();//->createToken('jwt')->plaintext;
            }
            else
                return 0;
        }
        catch(\Exception $e){
            error_log("Exception: ".$e);
            return 0;
        }
    }

    function fake(){
        error_log("fake called");
        $uid=auth()->user()->id;
        foreach(range(1,1000) as $v){
            $faker=Faker::create();
            $n=$faker->name();
            $u=$faker->unique()->username();
            $pw=$n.$u;
            $pw=str_replace(' ', '', $pw);
            $p=$faker->phoneNumber;

            $s= $faker->randomNumber(2,false);
            $r= $faker->randomNumber(1,false);
            $p= $faker->randomNumber(5,true);
            $g= $faker->randomNumber(1,false);
            $f= $faker->randomNumber(1,false);
            //$i= $faker->randomNumber(2,false);
            $st='Pending';

            $a=$faker->address;
            // $a= str_replace(',', '',$a);
            // return response(['size'=>$s,'rooms'=>$r,'price'=>$p,'garage'=>$g,'status'=>$st,'add'=>"$a"]);

            try{
                DB::Insert("Insert into users(name,username,password,phone,isAdmin) values('$n','$u','$pw','$p','0')");
                DB::Insert("Insert into houses(size,rooms,price,garage,floors,img,status,address,userid) values('$s','$r','$p','$g','$f','','$st','".$a."','$uid')");
            }
            catch(\Exception $e){
                error_log("Exception: ".$e);
                return response(['Error']);;
            }
        }
        return response(['Added']);
    }
    function drop(){
        error_log("drop called");
        try{
            DB::table("houses")->truncate();
            DB::table("users")->truncate();
            return response(['Dropped']);
        }
        catch(\Exception $e){
            error_log("Exception: ".$e);
            return response(['Error']);
        }
    }


}
