<?php

namespace Karamel\Encrypt;
class Encrypt
{
    private $key;
    private $cipher;

    public function __construct($key, $cipher = 'AES-128-CBC')
    {
        $this->key = $key;
        $this->cipher = $cipher;
        if (!in_array($this->cipher, openssl_get_cipher_methods()))
            throw new \Exception("There is wrong cipher method");
    }

    public function encrypt($string)
    {
        $ivlen = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext = openssl_encrypt($string, $this->cipher, $this->key, $options = 0, $iv);
        return base64_encode(json_encode(['iv' => $iv, 'value' => $ciphertext]));
    }

    public function decrypt($string)
    {
        $data = base64_decode($string);
        $data = json_decode($data, true);
        $iv = $data['iv'];
        $value = $data['value'];
        return openssl_decrypt($value, $this->cipher, $this->key, $options = 0, $iv);
    }


}