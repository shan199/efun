<?php
/**
 * app接口专用配置
 */
return array(
    'dux.app' =>
        array(
            'BASE_URL' => 'http://' . $GLOBALS['_SERVER']['HTTP_HOST'],

            'GET_DATA_METHOD' => $_REQUEST,//接口获取参数的方法默认$_REQUEST,正式上线后,用$_POST方法获取参数.
            'PAGINATION' => 10,//每页显示的条数默认10
            'COMMENT_INTERVAL_TIME' => 2,//评论间隔时间

            /**
             * 加密配置方式
             */
            'IS_DECRYPT' => 1,//1开启  0关闭  仅输出参数有效
            'DECRYPT_KEY' => '1wbnZBxNFyW0o16X',
            'DECRYPT_IV' => 'TdijJgSMaCn9HuWn',
            /**
             * 加密结束
             */

            /**
             * 验证码发送短信配置
             */
            'CAPTCHA_STATUS' => 0,//测试模式0,正式模式1
            'CAPTCHA_TIME' => 5,//过期时间默认5
            'CAPTCHA_INTERVAL_TIME' => 2,//验证码获取时间间隔
            'CAPTCHA_URL' => 'http://222.73.117.156/msg/HttpBatchSendSM?',//发送短信地址
            'CAPTCHA_ACCOUNT' => 'shengdikj_meimaigou',//帐号
            'CAPTCHA_PASSWORD' => 'meimaigou?147',//密码

            /**
             * 配置默认img拼接返回地址
             */
            'IMG_URL' => '',
            'IMG_ACCESSPRY_TYPE' => 'png,jpg,bmp',//图片上传后缀名

            /**
             * 信鸽推送配置
             */
            //GX推送推送配置 ios端
            'XG_IOS_ACCESS_ID' => '',
            'XG_IOS_SECRET_KEY' => '',
            'XG_IOS_PREFIX' => '',
            //GX推送推送配置 Android端
            'XG_Android_ACCESS_ID' => '',
            'XG_Android_SECRET_KEY' => '',

            /**
             * 支付宝回调参数V2 APP 支付
             * 接口配置
             **/
            'ALI_PARTNER' => '',
            'ALI_PRIVATE_KEY_PATH' => 'key/rsa_private_key.pem',
            'ALI_PUBLIC_KEY_PATH' => 'key/alipay_public_key.pem',
            'ALI_CACERT' => 'key/cacert.pem',

            /**
             * 微信回调参数
             * WxPayConfig 需要配置相同，用于调用
             */
            'WECHAT_APP_ID' => '',// 公众号身份标识
            'WECHAT_APP_SECRET' => '',// 权限获取所需密钥 Key
            'WECHAT_PARTNER_ID' => '',// 财付通商户身份标识
            'WECHAT_PARTNER_KEY' => '',// 财付通商户权限密钥 Key
            'WECHAT_WX_NOTIFY_URL' => $GLOBALS['_SERVER']['HTTP_HOST'] . '/index.php/api/wxpay_notify_url',// 微信支付完成服务器通知页面地址

            /**
             * 环信配置参数
             */
            'EASEMOB_CLIENT_ID' => '',
            'EASEMOB_CLIENT_SECRET' => '',
            'EASEMOB_BASE_URL' => '',//斜杠结尾
            'EASEMOB_OWNER' => '',//约伴创建群的管理员名称

        ),
);