<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User_cms as UserModel;
use Exception;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller{
    protected $resOK;
    protected $resNotOK;
    protected $resKey;
    protected $key;
    protected $logging;
    protected $errData;
    protected $resData;
    protected $userStatus;
    protected $token;
    protected $resSesOK;
    protected $resSesNotOK;

    public function __construct(){
        date_default_timezone_set("Asia/Jakarta");

        $this->resOK = Config::get('app.resLogin.OK'); 
        $this->resNotOK = Config::get('app.resLogin.notOK');    
        $this->resKey = Config::get('app.resKey'); 
        $this->key= trim(Config::get('app.secret_key')); 
        $this->logging = new LogFormater;
        $this->userStatus = array("not active","active");
        $this->resSesOK = Config::get('app.resSes.OK');
        $this->resSesNotOK = Config::get('app.resSes.notOK');
    }

    public function isAuth(Request $request){
        $param = json_decode($request->getContent(),true);
        $appid = trim($param['appid']);
        $email = trim($param['email']);
        $password = md5(trim($param['password']));

        if($this->key == $appid){
            try{

                $user = UserModel::where(['email'=>$email,'password'=>$password,'StatusActive'=>1])->first();
                if(!empty($user)){
                    $user_ = new User;
                    
                    //create user
                    $user_->name = $user->Fullname;
                    $user_->email = $email;
                    $user_->password = $password;
                    $user_->save();

                    //create token
                    $userToken = $user_->createToken($email);
                    $this->token = $userToken->plainTextToken; //issuing token

                    $this->resData = array_merge(
                        $this->resOK,
                        array("data" => array("user" => 
                            array(
                                "id" => $user->IdUser,
                                "user_id" => $user->UserId,
                                "fullname" => $user->Fullname,
                                "email" => $user->Email,
                                "status" => $this->userStatus[$user->StatusActive]
                            ),
                            "token" => $this->token,
                            "type" => "Bearer"
                            
                        ))
                    );

                }else{
                    $this->resData = $this->resNotOK;
                }

            }catch(Exception $exception){
                $this->errData = $exception->getMessage();
            }

        }else{
            $this->resData = $this->resKey;
        }

        //logging
        $this->logging->loginfo(200,$request->method(),$this->errData,"LOGIN AUTH",$request->path(),$request->getClientIp(),$param,$this->resData,$this->token);

        //response
        return response($this->resData,200);
    }

    public function checkAuth(Request $request){
        $param = json_decode($request->getContent(),true);
        $appid = trim($param['appid']);
        $email = trim($param['email']);

        if($this->key == $appid){
            try{
                //$users = User::where('email', $email)->get();    
                $users = DB::table('users')
                ->join('personal_access_tokens', 'users.id', '=', 'personal_access_tokens.tokenable_id')
                ->count('*');
                
                if( $users > 0){
                    $this->resData = $this->resSesOK;
                }else{
                    $this->resData = $this->resSesNotOK;
                }
            }catch(Exception $exception){
                $this->errData = $exception->getMessage();
            }
        }else{
            $this->resData = $this->resKey;
        }
        
        //logging
        $this->logging->loginfo(200,$request->method(),$this->errData,"LOGIN AUTH",$request->path(),$request->getClientIp(),$param,$this->resData,$this->token);

        //response
        return response($this->resData,200);        
    }

}