<?php
$id = !empty($_GET['id']) ? $_GET['id'] : 'marry';

header("Content-Type:text/html;charset=utf-8");
header("Content-Disposition: attachment;filename=$id.jpg ");

$name = !empty($_GET['name']) ? $_GET['name'] : '';
$gender = !empty($_GET['gender']) ? $_GET['gender'] : 1;
$spousename = !empty($_GET['spousename']) ? $_GET['spousename'] : '';

$groom = $bride = '还没定';

$flag = '';

if(empty($spousename)){
    $flag = 'm';
}

if($gender==1){
    $groom = !empty($name) ? $name : $groom;
    $bride = !empty($spousename) ? $spousename : $bride;
}else{
    $groom = !empty($spousename) ? $spousename : $groom;
    $bride = !empty($name) ? $name : $bride;
}

$dst_path = $flag.'d2.jpg';

//创建图片的实例
$dst = imagecreatefromstring(file_get_contents($dst_path));

//打上文字
$font = './font.ttf';//字体
$green = imagecolorallocate($dst, 95, 156, 158);//字体颜色
$red = imagecolorallocate($dst, 240, 136, 124);//字体颜色
imagefttext($dst, 30, 0, 473, 760, $green, $font, $groom);
imagefttext($dst, 30, 0, 473, 850, $red, $font, $bride);
//imagefttext($dst, 21, 0, 12, 590, $black, $font, '在来福士来场命中注定的偶遇吧！');
//输出图片
list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
switch ($dst_type) {
    case 1://GIF
        header('Content-Type: image/gif');
        imagegif($dst);
        break;
    case 2://JPG
        header('Content-Type: image/jpeg');
        imagejpeg($dst);
        break;
    case 3://PNG
        header('Content-Type: image/png');
        imagepng($dst);
        break;
    default:
        break;
}

imagedestroy($dst);
?>
