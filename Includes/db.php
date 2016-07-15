<?php

class DBUtil extends mysqli {

    // single instance of self shared among all instances
    private static $instance = null;
    // db connection config vars
    private $user = "root";
    private $pass = "root1";
    private $dbName = "cus_management";
    private $dbHost = "localhost";

    //This method must be static, and must return an instance of the object if the object
    //does not already exist.
    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
    // thus eliminating the possibility of duplicate objects.
    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }

    // private constructor
    private function __construct() {
        parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
        if (mysqli_connect_error()) {
            exit('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        }
        parent::set_charset('utf-8');
    }

    public function checkLogin($login, $pass) {
        $login = $this->real_escape_string($login);
        $pass = $this->real_escape_string($pass);
        $user = $this->query("SELECT userId, type, fullname FROM user WHERE inactive = 0 and login='" . $login . "'" . " and pass='" . $pass . "'");
        return $user->fetch_assoc();
    }

    public function getListAppointment($userId) {
        return $this->query("SELECT a.appId, a.cusId, c.cusName, a.datetime, a.reason, a.position FROM appointment a  LEFT JOIN customer c 
                                    on a.cusId = c.cusId WHERE a.userId=" . $userId . " and (a.datetime > now() and a.datetime < NOW() + INTERVAL 1 WEEK) order by a.datetime");
    }

    public function getListAppointmentByCusId($cusId) {
        return $this->query("SELECT c.*, u.login FROM appointment c LEFT JOIN user u on c.userId = u.userId WHERE cusId=" . $cusId . " order by datetime");
    }

    public function getListAppointmentByAppId($appId) {
        $result = $this->query("SELECT * FROM appointment WHERE appId = " . $appId);
        return $result->fetch_assoc();
    }

    public function insertAppointment($cusId, $datetime, $position, $reason, $userId) {
        $datetime = $this->real_escape_string($datetime);
        $position = $this->real_escape_string($position);
        $reason = $this->real_escape_string($reason);

        $this->query("INSERT appointment (cusId, datetime, reason, position, userId) VALUES "
                . " ('" . $cusId . "', '" . $datetime . "', '" . $reason . "', '" . $position . "', " . $userId . ")");
    }

    public function updateAppointment($cusId, $datetime, $position, $reason, $appId) {
        $datetime = $this->real_escape_string($datetime);
        $position = $this->real_escape_string($position);
        $reason = $this->real_escape_string($reason);
        $this->query("update appointment set cusId=" . $cusId . ", datetime = '" . $datetime . "', position='" . $position . "', reason='" . $reason . "' WHERE appId=" . $appId);
    }

    public function deleteAppointment($appId) {
        $this->query("DELETE FROM appointment WHERE appId = " . $appId);
    }

    //-------------------------END APPOINTMENT-----------------------------//

    public function insertCustomer($cusName, $cusPhone, $email, $birthday, $cusAddress, $cusNote, $userId) {
        $cusName = $this->real_escape_string($cusName);
        $cusPhone = $this->real_escape_string($cusPhone);
        $email = $this->real_escape_string($email);
        $birthday = $this->real_escape_string($birthday);
        $cusAddress = $this->real_escape_string($cusAddress);
        $cusNote = $this->real_escape_string($cusNote);

        $this->query("INSERT customer (cusName, cusPhone, email, cusAddress, cusDescription, birthday, userId) VALUES "
                . " ('" . $cusName . "', '" . $cusPhone . "', '" . $email . "', '" . $cusAddress . "', '" . $cusNote . "', '" . $birthday . "', " . $userId . ")");
    }

    public function updateCustomer($cusName, $cusPhone, $email, $birthday, $cusAddress, $cusNote, $cusId) {
        $cusName = $this->real_escape_string($cusName);
        $cusPhone = $this->real_escape_string($cusPhone);
        $email = $this->real_escape_string($email);
        $birthday = $this->real_escape_string($birthday);
        $cusAddress = $this->real_escape_string($cusAddress);
        $cusNote = $this->real_escape_string($cusNote);
        $this->query("update customer set cusName = '" . $cusName . "', email='" . $email . "', cusPhone='" . $cusPhone . "', cusAddress='" . $cusAddress .
                "', cusDescription='" . $cusNote . "', birthday='" . $birthday . "' WHERE cusId=" . $cusId);
    }

    public function getListCustomer($userId) {
        return $this->query("SELECT *, u.login FROM customer c LEFT JOIN user u on c.userId = u.userId WHERE c.userId=" . $userId);
    }

    public function getListCustomerAdmin() {
        return $this->query("SELECT *, u.login FROM customer c LEFT JOIN user u on c.userId = u.userId");
    }

    public function getCustomerDetail($cusId) {
        $result = $this->query("SELECT * FROM customer WHERE cusId = " . $cusId);
        return $result->fetch_assoc();
    }

    public function deleteCustomer($cusId) {
        $this->query("DELETE FROM appointment WHERE cusId = " . $cusId);
        $this->query("DELETE FROM transaction WHERE cusId = " . $cusId);
        $this->query("DELETE FROM customer WHERE cusId = " . $cusId);
    }

    //-------------------------END CUSTOMER-----------------------------//

    public function getListUser() {
        return $this->query("SELECT * FROM user WHERE inactive=0");
    }

    public function getUserDetail($userId) {
        $result = $this->query("SELECT * FROM user WHERE userId=" . $userId);
        return $result->fetch_assoc();
    }
    
    public function checkUserExisted($login) {
        $result = $this->query("SELECT * FROM user WHERE login='" . $login . "'");
        if (!$result) {
            throw new Exception("Database Error [{$this->errno}] {$this->error}");
        }
        return $result->fetch_assoc();
    }

    public function insertUser($login, $pass, $fullname, $phone, $address, $birthday, $email) {
        $login = $this->real_escape_string($login);
        $pass = $this->real_escape_string($pass);
        $fullname = $this->real_escape_string($fullname);
        $phone = $this->real_escape_string($phone);
        $address = $this->real_escape_string($address);
        $birthday = $this->real_escape_string($birthday);
        $email = $this->real_escape_string($email);

        $this->query("INSERT user (login, pass, fullname, phone, address, birthday, email) VALUES "
                . " ('" . $login . "', '" . $pass . "', '" . $fullname . "', '" . $phone . "', '" . $address . "', '" . $birthday . "', '" . $email . "')");
    }

    public function updateUser($userId, $login, $pass, $fullname, $phone, $address, $birthday, $email) {
        $login = $this->real_escape_string($login);
        $pass = $this->real_escape_string($pass);
        $fullname = $this->real_escape_string($fullname);
        $phone = $this->real_escape_string($phone);
        $address = $this->real_escape_string($address);
        $birthday = $this->real_escape_string($birthday);
        $email = $this->real_escape_string($email);

        $this->query("UPDATE user SET login = '" . $login . "', pass='" . $pass . "', fullname='" . $fullname .
                "', phone='" . $phone . "', address='" . $address . "', birthday='" . $birthday . "', email='" . $email . "' WHERE userId=" . $userId);
    }

    public function deleteUser($userId) {
        $this->query("UPDATE user SET inactive=1 WHERE userId=" . $userId);
    }

    //-------------------------END USER-----------------------------//

    public function insertTransaction($cusId, $project, $depositAmount, $totalAmount, $note, $datetime, $size, $assetNum, $userId) {
        $project = $this->real_escape_string($project);
        if (!empty($depositAmount)) {
            $depositAmount = $this->real_escape_string($depositAmount);
        } else {
            $depositAmount = 0;
        }
        $totalAmount = $this->real_escape_string($totalAmount);
        $note = $this->real_escape_string($note);
        $datetime = $this->real_escape_string($datetime);
        $size = $this->real_escape_string($size);
        $assetNum = $this->real_escape_string($assetNum);

        $result = $this->query("INSERT transaction (cusId, project, depositAmount, totalAmount, note, datetime, size, assetNum, userId) VALUES "
                . " (" . $cusId . ", '" . $project . "', '" . $depositAmount . "', '" . $totalAmount . "', '" . $note . "', '" . $datetime .
                "', '" . $size . "', '" . $assetNum . "', " . $userId . ")");
        if (!$result) {
            throw new Exception("Database Error [{$this->errno}] {$this->error}");
        }
    }

    public function updateTransaction($tranId, $cusId, $project, $depositAmount, $totalAmount, $note, $datetime, $size, $assetNum) {
        $project = $this->real_escape_string($project);
        if (!empty($depositAmount)) {
            $depositAmount = $this->real_escape_string($depositAmount);
        } else {
            $depositAmount = 0;
        }
        $totalAmount = $this->real_escape_string($totalAmount);
        $note = $this->real_escape_string($note);
        $datetime = $this->real_escape_string($datetime);
        $size = $this->real_escape_string($size);
        $assetNum = $this->real_escape_string($assetNum);
        $this->query("update transaction set cusId = " . $cusId . ", project='" . $project . "', depositAmount='" . $depositAmount . "', totalAmount='" . $totalAmount .
                "', note='" . $note . "', datetime='" . $datetime . "', size='" . $size . "', assetNum='" . $assetNum . "' WHERE tranId=" . $tranId);
    }

    public function getListTransaction($userId) {
        return $this->query("SELECT a.*, c.cusName FROM transaction a LEFT JOIN customer c 
                                    on a.cusId = c.cusId WHERE a.userId=" . $userId . " and (a.datetime > NOW() - INTERVAL 1 WEEK) order by a.datetime");
    }

    public function getListTransactionByCusId($cusId) {
        return $this->query("SELECT c.*, u.login FROM transaction c LEFT JOIN user u on c.userId = u.userId WHERE cusId=" . $cusId . " order by datetime");
    }

    public function getTransactionDetail($tranId) {
        $result = $this->query("SELECT * FROM transaction WHERE tranId = " . $tranId);
        return $result->fetch_assoc();
    }

    public function deleteTransaction($tranId) {
        $this->query("DELETE FROM transaction WHERE tranId = " . $tranId);
    }

    //-------------------------END TRANSACTION-----------------------------//
}
