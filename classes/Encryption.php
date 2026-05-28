<?php

class Encryption {

    public static function encrypt($data, $password) {

        $iv = random_bytes(16);

        $encrypted = openssl_encrypt(
            $data,
            'AES-256-CBC',
            $password,
            0,
            $iv
        );

        return base64_encode($iv . '::' . $encrypted);

    }

    public static function decrypt($data, $password) {

        $parts = explode('::', base64_decode($data), 2);

        $iv = $parts[0];
        $encrypted_data = $parts[1];

        return openssl_decrypt(
            $encrypted_data,
            'AES-256-CBC',
            $password,
            0,
            $iv
        );

    }

}

?>