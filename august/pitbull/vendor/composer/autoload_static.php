<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3e7ff809b57514cc0183d5cd38bdc7d4
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'August\\Pitbull\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'August\\Pitbull\\' => 
        array (
            0 => '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3e7ff809b57514cc0183d5cd38bdc7d4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3e7ff809b57514cc0183d5cd38bdc7d4::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
