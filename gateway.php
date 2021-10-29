<?php
//   1     2    3
//78 78 | 0d | 01 | 08 60 01 60 22 76 19 64 | 00 01 | 0c f3 | 0d 0a
$msg = str_split('78780d01086001602276196400010cf30d0a',2);

$pack = [];
$pack['start'] = "{$msg[0]}{$msg[1]}";
$pack['packLength'] = hexdec($msg[2]);
$pack['protocolNumber'] = hexdec($msg[3]);

$pack['serialNumber'] = $msg[$pack['packLength']-1].$msg[$pack['packLength']];
$pack['errorCheck'] = $msg[$pack['packLength']+1].$msg[$pack['packLength']+2];
$pack['stop'] = $msg[$pack['packLength']+3].$msg[$pack['packLength']+4];

//Login Message
if($pack['protocolNumber'] == 0x01){
    echo "Login message\r\n";

    $pack['terminalId'] = $msg[4].$msg[5].$msg[6].$msg[7].$msg[8].$msg[9].$msg[10].$msg[11];

}
var_dump($pack);







/**
 * Protocol Number
Login Message 0x01
Location Data 0x12
Status information 0x13
String information 0x15
Alarm data 0x16
GPS, query address information by phone number 0x1A
Command information sent by the server to the terminal 0x80
 */