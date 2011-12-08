<?php

require_once('Users.php');
require_once('show_list.php');

$user = new Users();

echo "<pre>";
print_r($user->check_user('jobabb', 'jobabb'));
echo "<br />";
print_r($user->user_name('jobabb'));
echo "<br />";
print_r(parse_phone($user->user_phone('jobabb')));
echo "</pre>";
