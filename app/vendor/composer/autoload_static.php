<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb1a37822f0fae1b47ea1e09e62684d1a
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Verot\\Upload\\Upload' => __DIR__ . '/..' . '/verot/class.upload.php/src/class.upload.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb1a37822f0fae1b47ea1e09e62684d1a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb1a37822f0fae1b47ea1e09e62684d1a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb1a37822f0fae1b47ea1e09e62684d1a::$classMap;

        }, null, ClassLoader::class);
    }
}
