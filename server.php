<?php

//call library
require_once("Includes/db.php");
require_once('lib/nusoap.php');
$server = new soap_server;
$server->soap_defencoding = 'utf-8';
$server->decode_utf8 = false;
$server->configureWSDL('My Pratice WS With PHP', "urn:TestWS");

$server->register(
        'hello', array('name' => 'xsd:string'), array('result' => 'xsd:string'), 'urn:helloResponse'
);
$server->register(
        'addTwoNum', array('num1' => 'xsd:int', 'num2' => 'xsd:int'), array('result' => 'xsd:int'), 'urn:addTwoNumResponse'
);

$server->wsdl->addComplexType('user_array_php', 'complexType', 'struct', 'all', '', array(
    'userId' => array('name' => 'userId', 'type' => 'xsd:int'),
    'login' => array('name' => 'login', 'type' => 'xsd:string'),
    'pass' => array('name' => 'pass', 'type' => 'xsd:string'),
    'fullname' => array('name' => 'fullname', 'type' => 'xsd:string'),
    'phone' => array('name' => 'phone', 'type' => 'xsd:string'),
    'address' => array('name' => 'address', 'type' => 'xsd:string'),
    'type' => array('name' => 'type', 'type' => 'xsd:string'),
    'birthday' => array('name' => 'birthday', 'type' => 'xsd:string'),
    'email' => array('name' => 'email', 'type' => 'xsd:string'),
    'inactive' => array('name' => 'inactive', 'type' => 'xsd:int')
        )
);

$server->wsdl->addComplexType('return_user_array_php', 'complexType', 'array', 'all', 'SOAP-ENC:Array', array(), array(
    array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:user_array_php[]')
        ), 'tns:user_array_php'
);

$server->register('getListUser', array(), array('return' => 'tns:return_user_array_php'), 'urn:TestWS', 'urn:TestWS#getListUser', 'rpc', 'encoded', 'Returns array data in php web service'
);

function getListUser() {
    $result = array();
    $listUser = DBUtil::getInstance()->getListUser();
    while ($row = mysqli_fetch_array($listUser)) {
        $result[] = $row;
    }
    mysqli_free_result($listUser);
    return $result;
}

// create the function
function hello($name) {
    if (!$name) {
        return new soap_fault('Client', '', 'Put your name!');
    }
    $result = "Hello, " . $name;
    return $result;
}

function addTwoNum($num1, $num2) {
    return $num1 + $num2;
}

// create HTTP listener 
$server->service(file_get_contents("php://input"));
exit();
?>

