<?php

namespace App\LoginModule;

use phpseclib\Crypt\RSA;

class Keys
{

    private static $files = [
        'private' => 'lm-private.key',
        'public' => 'lm-public.key',
    ];


    static function getPath($target) {
        $file = self::$files[$target];
        return storage_path($file);
    }


    static function getPrivate() {
        return file_get_contents(self::getPath('private'));
    }


    static function getPublic() {
        return file_get_contents(self::getPath('public'));
    }


    static function generate() {
        $rsa = new RSA;
        $keys = $rsa->createKey(512);
        file_put_contents(self::getPath('private'), array_get($keys, 'privatekey'));
        file_put_contents(self::getPath('public'), array_get($keys, 'publickey'));
    }

}