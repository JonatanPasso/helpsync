<?php


$fp = fsockopen('18.231.20.123', 9090, $errno, $errstr, 30);

while (true) {
    fwrite($fp, "Your message");
    time_nanosleep(0,8000);
}
