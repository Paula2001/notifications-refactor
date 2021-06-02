<?php
namespace App\Enums;
use ReflectionClass;

final class DeviceStateEnum {
    const ACTIVE = 'ACTIVE';
    const NOT_ACTIVE = 'NOT_ACTIVE';
    public static function getConstants()
    {
        return (new ReflectionClass(__CLASS__))->getConstants();
    }
}
