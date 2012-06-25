<?php

$additional_controls = isset($additional_controls) ? $additional_controls : '';
$user_type = isset($user_type) ? $user_type : '';
$additional_controls .= Controls::buildUserTypeControl($user_type);
