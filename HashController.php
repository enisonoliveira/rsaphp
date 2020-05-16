<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\HashPayerController;

class HashController extends Controller
{

    protected $request;
    private $hash;
    
    public function __construct(Request $request) {

        $this->request = $request;
        $this->msg= new MessageController;
    }

     /*
    * Parâmetro texto: O parâmetro texto precisa ser o resultado do hash 
    * Parâmetro $path: Caminho da chave privada 
    * returns
    *
    */
    public function decrypt($text,$pathKey) {

        try {
            $fp = fopen($pathKey, "r");
            $priv_key = fread($fp, 8192);
            $decrypted = '';
            if (!$privateKey = openssl_pkey_get_private($priv_key))
                return \Response::json([
                            'status' => 403,
                            'message' => 'Loading Private Key failed !',
                                ], 403);
            $decrypted_text = "";
            if (!openssl_private_decrypt(base64_decode($text), $decrypted_text, $privateKey))
                return \Response::json([
                            'status' => 403,
                            'message' => 'descrypt  failed !',
                                ], 403);
            return $decrypted_text;
        } catch (\Exception $e) {
            return $this->msg->msgError($e);
        }
    }
    /*
    * Parâmetro texto: O parâmetro texto precisa ser um texto comum sem acentuação ou espaços
    * Parâmetro $path: Caminho da chave publica 
    * returns
    *
    */
    public function encrypt($text,$pathKey) {
        
        try {
            $fp = fopen($pathKey, "r");
            $public_key = fread($fp, 8192);
            $decrypted = '';
            if (!$publicKey =openssl_get_publickey($public_key))
                return \Response::json([
                            'status' => 403,
                            'message' => 'Loading Public Key failed !',
                                ], 403);
            $encrypted_text = "";
            if (!openssl_public_encrypt($text, $encrypted_text, $publicKey))
                return \Response::json([
                            'status' => 403,
                            'message' => 'encrypt text failed !',
                                ], 403);
            return (base64_encode($encrypted_text));
        } catch (\Exception $e) {
            return $this->msg->msgError($e);
        }
    }
}

