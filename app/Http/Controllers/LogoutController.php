<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use App\Http\Controllers\LogFormater;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller{
    protected $resOK;
    protected $resNotOK;
    protected $resKey;
    protected $key;
    protected $logging;
    protected $errData;
    protected $resData;
    protected $token;

    public function __construct(){
        date_default_timezone_set("Asia/Jakarta");

        $this->resOK = Config::get('app.resLogout.OK'); 
        $this->resNotOK = Config::get('app.resLogout.notOK');    
        $this->resKey = Config::get('app.resKey'); 
        $this->key= trim(Config::get('app.secret_key')); 
        $this->logging = new LogFormater;
        $this->userStatus = array("not active","active");
    }

    public function logout(Request $request){
        $param = json_decode($request->getContent(),true);
        $appid = trim($param['appid']);
        $email = trim($param['email']);

        if($this->key == $appid){
            try{
                $request->user()->currentAccessToken()->delete();
                $request->user()->delete();
                $this->resData = $this->resOK;                
            }catch(Exception $exception){
                $this->errData = $exception->getMessage();
            }
            
        }else{
            $this->resData = $this->resKey;
        }

        //logging
        $this->logging->loginfo(200,$request->method(),$this->errData,"LOGOUT",$request->path(),$request->getClientIp(),$param,$this->resData,$this->token);

        //response
        return response($this->resData,200);
    }
}