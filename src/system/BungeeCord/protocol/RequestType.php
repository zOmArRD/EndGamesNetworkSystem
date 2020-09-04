<?php
/**
 * Created by PhpStorm.
 * @author zOmArRD
 *       ___               _         ____  ____
 *  ____/ _ \ _ __ ___    / \   _ __|  _ \|  _ \
 * |_  / | | | '_ ` _ \  / _ \ | '__| |_) | | | |
 *  / /| |_| | | | | | |/ ___ \| |  |  _ <| |_| |
 * /___|\___/|_| |_| |_/_/   \_\_|  |_| \_\____/
 *
 */
namespace system\BungeeCord\protocol;

abstract class RequestType
{
    public const TYPE_GET_SERVER_LIST = 1;
    public const TYPE_GET_PLAYER_LIST = 2;
    public const TYPE_GET_PLAYER_COUNT = 3;
    public const TYPE_GET_SERVER = 4;
    public const TYPE_GET_PLAYER_IP = 5;
    public const TYPE_GET_SERVER_IP = 6;
    public const TYPE_GET_PING = 7;
}
