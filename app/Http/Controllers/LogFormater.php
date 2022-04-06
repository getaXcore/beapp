<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class LogFormater{
    protected $dateFormat;

    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
        $this->dateFormat = date("Y-m-d h:i:s");
    }

    public function loginfo($statuscode,$method,$error,$type,$urlpath,$clienthost,$requestContent,$responseContent,$sessionid){
        $data = array(
            "timestamp"=>$this->dateFormat,
            "method"=>$method,
            "type_name"=>$type,
            "url_path"=>$urlpath,
            "client_host"=>$clienthost,
            "statuscode"=>$statuscode,
            "request"=>$requestContent,
            "response"=>$responseContent,
            "token"=>$sessionid,
            "error"=>$error
        );

        Log::channel('custom')->info(json_encode($data,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

}