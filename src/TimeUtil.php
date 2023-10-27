<?php
namespace Ted\Escort;

use DateTime;

class TimeUtil
{
    const TIME_ZONE = 'Asia/Shanghai';

    /**
     * 两个日期跨越的天数
     * @param $startdate
     * @param $enddate
     * @return int
     * @throws \Exception
     */
    public static function gap($startdate, $enddate): int
    {
        //$startTime = strtotime($startdate);
        $startTime = self::timestamp(self::date(self::timestamp($startdate)));
        //$endTime = strtotime($enddate);
        $endTime = self::timestamp(self::date(self::timestamp($enddate)));
        $days = (int)round(($endTime - $startTime) / 3600 / 24);
        return $days;
    }

    /**
     * 两个日期之间的所有日期数组
     * @param $start_date
     * @param $end_date
     * @return array
     * @throws \Exception
     */
    public static function range($start_date, $end_date): array
    {
        return array_map(function ($n) {
            return self::date($n);
        }, range(self::timestamp($start_date), self::timestamp($end_date), 24 * 3600));
    }

    /**
     * 返回当前Unix时间戳（毫秒）
     * 根据标识参数，获取当前 Unix 时间戳和微秒数，再处理时间戳为符合系统数据存储的格式
     * @param $flag
     * @return float
     */
    public static function microtime($flag = 0): float
    {
        $flag = (int)$flag;
        if ($flag <= 0) {
            $microtime = round(microtime(true) * 1000);
        } else {
            list($usec, $sec) = explode(' ', microtime());
            $microtime = (float)sprintf('%.0f', (floatval($usec) + floatval($sec)) * 1000);
        }
        return $microtime;
    }

    /**
     * 返回Unix时间戳
     * 处理[Y2K38]漏洞
     * @param $date
     * @return int
     * @throws \Exception
     */
    public static function timestamp($date = ''): int
    {
//        if ($date == "") {
//            $dateTime = new \DateTime($date);
//
//            // 设置时区
//            $timezone = timezone_open(self::TIME_ZONE);
//            $dateTime->setTimezone($timezone);
//
//            // 获取Unix时间戳
//            $timestamp = $dateTime->format('U');
//        } else {
//            $formatter = Zhi::getApp()->formatter;
//            $formatter->timeZone = self::TIME_ZONE;
//            $formatter->defaultTimeZone = self::TIME_ZONE;
//            $timestamp = $formatter->asTimestamp($date);
//        }

        $dateTime = new DateTime($date);

        // 设置时区
        $timezone = timezone_open(self::TIME_ZONE);
        $dateTime->setTimezone($timezone);

        // 获取Unix时间戳
        $timestamp = $dateTime->format('U');

        // 如果没有获取到时间戳，返回time()
        (!$timestamp) && $timestamp = time();

        return (int)$timestamp;
    }

    /**
     * 将Unix时间戳格式转换为指定的时间格式（默认格式：Y-m-d）
     * 处理[Y2K38]漏洞
     * @param $timestamp
     * @param $format
     * @return string
     * @throws \Exception
     */
    public static function date($timestamp = '', $format = 'Y-m-d'): string
    {
        // 如果没有时间戳，默认取当前时间
        if (!$timestamp || empty($timestamp) || $timestamp == '' || (int)$timestamp <= 0) {
            $timestamp = self::timestamp();
        }

        // 如果时间戳为13位（毫秒级），截取前10位
        $timestamp = (strlen($timestamp) <= 10) ? $timestamp : substr($timestamp, 0, 10);

        $dateTime = new DateTime('@' . $timestamp);

        // 设置时区
        $timezone = timezone_open(self::TIME_ZONE);
        $dateTime->setTimezone($timezone);

        // 按照格式将时间戳转换为字符串
        return $dateTime->format($format);

//        $formatter = Zhi::getApp()->formatter;
//        $formatter->timeZone = self::TIME_ZONE;
//        $formatter->defaultTimeZone = self::TIME_ZONE;
//        return $formatter->asDate($timestamp, 'php:' . $format);
    }

    /**
     * 指定日期的开始时间点
     * @param $date
     * @param $time
     * @return string
     */
    public static function begin($date = "", $time = " 00:00:00"): string
    {
        return self::limitTime($date, $time);
    }

    /**
     * 指定日期的最后时间点
     * @param $date
     * @param $time
     * @return string
     * @throws \Exception
     */
    public static function end($date = "", $time = " 23:59:59"): string
    {
        return self::limitTime($date, $time);
    }

    /**
     * @param $date
     * @param $time
     * @return string
     * @throws \Exception
     */
    private static function limitTime($date = '', $time = ''): string
    {
        // 1.转换为时间戳
        $timestamp = self::timestamp($date);
        // 2.转换为Y-m-d格式
        // 3.拼接“H:i:s”
        return self::date($timestamp) . $time;
    }

    /**
     * 问候语
     * @return string
     * @throws \Exception
     */
    public static function hello(): string
    {
        // 获取当前的小时
        $h = self::date('H');

        if ($h < 11) $hello = "早上好";
        else if ($h < 13) $hello = "中午好";
        else if ($h < 17) $hello = "下午好";
        else $hello = "晚上好";

        return $hello;
    }

    /**
     * @desc
     *  时间转换为刚刚，几分钟前，几小时前，几个月前，几年前
     *
     * @param (string) $time
     * @param (string) $format
     * @return (string) $str
     * @author zz
     * @date 2016-5-6
     */
//    public function tranTime($time, $format = 'Y-m-d H:i:s')
//    {
//        $transformTime = new TransformTime();
//        $transformTime->setTime($time);
//
//        $str = $transformTime->index($format);
//
//        return $str;
//    }
}

/**
 * filename : custom/libs/TimeUtil.php
 * date     : 2015-8-28 16:15:35
 * author   : zz
 * memo     : 时间转换为“刚刚”、“几分钟前”...
 *            发博时间计算(年，月，日，时，分，秒)
 *            $timestamp 可以是当前时间
 *            $time 你要传进来的时间
 *
 * (c)copyright zz
 *
 * $Id: custom/libs/TimeUtil.php,v1.0 2015-8-28 16:15:35 $
 */
//class TransformTime
//{
//    /**
//     * 当前时间戳
//     * @var string
//     */
//    private $timestamp;
//
//    /**
//     * 目标时间戳
//     * @var string
//     */
//    private $time;
//
//    function __construct()
//    {
//        $this->timestamp = TimeUtil::timestamp();
//    }
//
//    /**
//     * @return string
//     */
//    public function getTime()
//    {
//        return $this->time;
//    }
//
//    /**
//     * @param string $time
//     */
//    public function setTime($time)
//    {
//        $this->time = $time;
//    }
//
//    /**
//     * @desc
//     *  时间戳相差
//     *
//     * @return (string)
//     * @author zz
//     * @date 2016-5-6
//     */
//    function getSeconds()
//    {
//        return $this->timestamp - $this->time;
//    }
//
//    /**
//     * @desc
//     *  时间戳相差 - 转换为 分
//     *
//     * @return (string)
//     * @author zz
//     * @date 2016-5-6
//     */
//    function getMinutes()
//    {
//        return $this->getSeconds() / (60);
//    }
//
//    /**
//     * @desc
//     *  时间戳相差 - 转换为 时
//     *
//     * @return (string)
//     * @author zz
//     * @date 2016-5-6
//     */
//    function getHours()
//    {
//        return $this->getSeconds() / (60 * 60);
//    }
//
//    /**
//     * @desc
//     *  时间戳相差 - 转换为 天
//     *
//     * @return (string)
//     * @author zz
//     * @date 2016-5-6
//     */
//    function getDay()
//    {
//        return $this->getSeconds() / (60 * 60 * 24);
//    }
//
//    /**
//     * @desc
//     *  时间戳相差 - 转换为 月
//     *
//     * @return (string)
//     * @author zz
//     * @date 2016-5-6
//     */
//    function getMonth()
//    {
//        return $this->getSeconds() / (60 * 60 * 24 * 30);
//    }
//
//    /**
//     * @desc
//     *  时间戳相差 - 转换为 年
//     *
//     * @return (string)
//     * @author zz
//     * @date 2016-5-6
//     */
//    function getYear()
//    {
//        return $this->getSeconds() / (60 * 60 * 24 * 30 * 12);
//    }
//
//    function index($format = 'Y-m-d H:i:s')
//    {
//        if ($this->getYear() > 2) {
//            return date($format, $this->time);
//        }
//
//        if ($this->getYear() > 1) {
//            return intval($this->getYear()) . " 年前";
//        }
//
//        if ($this->getMonth() > 1) {
//            return intval($this->getMonth()) . " 月前";
//        }
//
//        if ($this->getDay() > 1) {
//            return intval($this->getDay()) . " 天前";
//        }
//
//        if ($this->getHours() > 1) {
//            return intval($this->getHours()) . " 小时前";
//        }
//
//        if ($this->getMinutes() > 1) {
//            return intval($this->getMinutes()) . " 分钟前";
//        }
//
//        if ($this->getSeconds() > 1) {
//            if ($this->getSeconds() > 10) {
//                return intval($this->getSeconds() - 1) . " 秒前";
//            }
//            return "刚刚";
//        } else {
//            return "刚刚";
//        }
//    }
//}