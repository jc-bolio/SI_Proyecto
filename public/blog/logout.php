<?php
require_once 'lib/common.php';

session_start();
logout();
redirectExit('index.php');