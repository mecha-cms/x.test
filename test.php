<?php

$array = [];
$array['a'] = 'a';
$array[1] = 1;
$function = function () {
    return 123;
};
$object = (object) [];
$object->a = 'a';
$object->{1} = 1;
$string = 'a';

test(
    $array,
    $array['a'],
    $array['a'][0],
    $array[1],
    $function(1, 'a', true, array(false, time())),
    $function,
    $object,
    $object->a,
    $string,
    'a',
    (object) [],
    1,
    1.0,
    INF,
    NAN,
    [1, 2, 3],
    [],
    false,
    new DateTime,
    new stdClass,
    null,
    true,
    time(),
);

test(
    $array[1],
    $array[1 + 0],
    $array[2 - 1],
    $function(),
    $function(1),
    $function(1 + 1),
    $function(2 - 2),
);

test(
    $array ?: 1,
    $array[time()] ?? 1,
);

$var = 1; test(1);

$var = 1; test($var, $var);

test($var + 1, 1 + $var, 1 + $var + 1);

exit;