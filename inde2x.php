<?php
/**
 * 获取推流地址
 * 如果不传key和过期时间，将返回不含防盗链的url
 * @param bizId 您在腾讯云分配到的bizid
 *        streamId 您用来区别不同推流地址的唯一id
 *        key 安全密钥
 *        time 过期时间 sample 2016-11-12 12:00:00
 * @return String url
 */
function getPushUrl($bizId, $streamId, $key = null, $time = null)
{

    if ($key && $time) {
        $txTime = strtoupper(base_convert(strtotime($time), 10, 16));
//txSecret = MD5( KEY + livecode + txTime )
//livecode = bizid+"_"+stream_id  如 8888_test123456
        $livecode = $bizId . "_" . $streamId; //直播码
        $txSecret = md5($key . $livecode . $txTime);
        $ext_str = "?" . http_build_query(array(
                "bizid" => $bizId,
                "txSecret" => $txSecret,
                "txTime" => $txTime
            ));
    }
    return "rtmp://" . $bizId . ".livepush.myqcloud.com/live/" . $livecode . (isset($ext_str) ? $ext_str : "");
}


/**
 * 获取播放地址
 * @param bizId 您在腾讯云分配到的bizid
 *        streamId 您用来区别不同推流地址的唯一id
 * @return String url
 */
function getPlayUrl($bizId, $streamId)
{
    $livecode = $bizId . "_" . $streamId; //直播码
    return array(
        "rtmp://" . $bizId . ".liveplay.myqcloud.com/live/" . $livecode,
        "http://" . $bizId . ".liveplay.myqcloud.com/live/" . $livecode . ".flv",
        "http://" . $bizId . ".liveplay.myqcloud.com/live/" . $livecode . ".m3u8"
    );
}

$dTime= '2018-12-31 23:59:59'; //设置视频有效时间
$mcs = 17;//从第几个机器开始生成
$mc = 20;//机器总数
$bizId = '16221';
$key = 'b95a453a4f00824033e2e6e46e114a21';
for ($i = $mcs; $i <= $mc; $i++) {

    $str = 'ipc';

    if ($i <= 9) {
        $i = '0' . $i;
    }

    echo '机器号:' . $str . $i;
    echo "<br>";
    echo '推流地址：'.getPushUrl($bizId, $str . $i, $key, $dTime);
    echo "<br />";

    $playUrlArr = (getPlayUrl($bizId, $str . $i));


    echo 'rtmp播放地址:'.$playUrlArr[0];
    echo "<br>";
    echo 'FLV 播放地址:'.$playUrlArr[1];
    echo "<br>";
    echo 'HLS 播放地址:'.$playUrlArr[2];
    echo "<br>";
    echo "<br>";


}

?>


