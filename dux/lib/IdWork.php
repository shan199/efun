<?php
/**
 * Created by PhpStorm.
 * User: TL
 * Date: 2017/3/23
 * Time: 17:16
 */

namespace dux\lib;

class IdWork
{
    const debug = 1;
    static $workerId = 11;
    static $twepoch = 1361753741828;
    static $sequence = 0;
    const workerIdBits = 5;
    static $maxWorkerId = 15;
    const sequenceBits = 10;
    static $workerIdShift = 10;
    static $timestampLeftShift = 15;
    static $sequenceMask = 1023;
    private static $lastTimestamp = -1;

    function __construct()
    {
//        $workId = self::$workerId;
//        if ($workId > self::$maxWorkerId || $workId < 0) {
//            throw new \Exception("worker Id can't be greater than 15 or less than 0");
//        }
//        self::$workerId = $workId;

    }

    function timeGen()
    {
        //获得当前时间戳
        $time = explode(' ', microtime());
        $time2 = substr($time[0], 2, 3);
        return $time[1] . $time2;
    }

    function tilNextMillis($lastTimestamp)
    {
        $timestamp = $this->timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->timeGen();
        }

        return $timestamp;
    }

    public function nextId()
    {
        $timestamp = $this->timeGen();

        if (self::$lastTimestamp == $timestamp) {
            self::$sequence = (self::$sequence + 1) & self::$sequenceMask;
            if (self::$sequence == 0) {
                $timestamp = $this->tilNextMillis(self::$lastTimestamp);
            }
        } else {
            self::$sequence = 0;
        }


        if ($timestamp < self::$lastTimestamp) {
            throw new \Excwption("Clock moved backwards.  Refusing to generate id for " . (self::$lastTimestamp - $timestamp) . " milliseconds");
        }
        self::$lastTimestamp = $timestamp;
        $nextId = ((sprintf('%.0f', $timestamp) - sprintf('%.0f', self::$twepoch)) << self::$timestampLeftShift) | (self::$workerId << self::$workerIdShift) | self::$sequence;
        return $nextId;
    }

}