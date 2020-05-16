# rsaphp

RSA PH + Laravel no Linux
===============================================================================================================================================

Como trabalhar com algoritmos RSA (Rivest-Shamir-Adleman) com PHP no Laravel.

Abrir terminal e digitar o comando a seguir para gerar a
chave privada


openssl genrsa -out rsa_1024_priv.pem 1024


Agora digite o seguinte comando para gerar a sua chave publica

openssl rsa -pubout -in rsa_1024_priv.pem -out rsa_1024_pub.pem

Agora na pratica como funciona.

As duas chaves geradas, em seu computador esta no diretório 
cd ~/.ssh/ 

Ta mais antes oque significa esse nome rsa_1024_priv.pem?
RSA=Rivest-Shamir-Adleman
1024=Número de bits
priv=A sua chave privada a que você deve guardar para si, para somente você ver oque foi encriptado

e  rsa_1024_pub.pem? Da mesma forma porém "_pub" significa chave pública, ou seja, a combinação ou
bits que faz com que seja encriptada a informação, dado o nome de criptografia assimétrica.


openssl=OpenSSL is a robust, commercial-grade, and full-featured toolkit for the Transport 
     Layer Security (TLS) and Secure Sockets Layer (SSL) protocols. It is also a 
     general-purpose cryptography library.   https://www.openssl.org/

Então logo eu preciso de dois metodos para resolver essse problema encrypt:

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
