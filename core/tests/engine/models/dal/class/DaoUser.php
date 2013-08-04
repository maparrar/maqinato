<?php
/** DaoUser File
 * @package models @subpackage dal */
if(!class_exists('Router')) require_once '../../config/Router.php';
include_once Router::rel('models').'dal/Dao.php';
include_once Router::rel('models').'dal/core/DaoUserType.php';
include_once Router::rel('models').'core/User.php';
/**
 * DaoUser Class
 *
 * Class data layer for the User class
 * 
 * @author https://github.com/maparrar/maqinato
 * @author Alejandro Parra <maparrar@gmail.com> 
 * @package models
 * @subpackage dal
 */
class DaoUser extends Dao{
    /**
     * Constructor: sets the database Object and the PDO handler
     * @param string Type of connection string to use
     */
    function DaoUser($type="all"){
        parent::Dao($type);
    }
    /**
     * Create an user in the database
     * @param string User
     * @param string encoded password
     * @param string encoder salt
     * @param bool If the user is of an NPO
     * @return User User created
     * @return string 'exist' if the user already exist
     * @return false if the user couldn't created
     */
    function create($user,$password,$salt){
        $created=false;
        if(!$this->emailExist($user->getEmail())){
            $stmt = $this->handler->prepare("INSERT INTO users 
                (email,name,lastname,password,salt,created,born,sex,iam,validated,city,type) VALUES 
                (:email,:name,:lastname,:password,:salt,:created,:born,:sex,:iam,:validated,:city,:type)");
            $stmt->bindParam(':email',$user->getEmail());
            $stmt->bindParam(':name',$user->getName());
            $stmt->bindParam(':lastname',$user->getLastname());
            $stmt->bindParam(':password',$password);
            $stmt->bindParam(':salt',$salt);
            $stmt->bindParam(':created',date('Y-m-d H:i:s'));
            $stmt->bindParam(':born',$user->getBorn());
            $stmt->bindParam(':sex',$user->getSex());
            $stmt->bindParam(':iam',$user->getIam());
            $stmt->bindParam(':validated',$user->getValidated());
            $stmt->bindParam(':city',$user->getCity());
            $stmt->bindParam(':type',$user->getType()->getId());
            if($stmt->execute()){
                $user->setId(intval($this->handler->lastInsertID()));
                $created=$user;
            }else{
                $error=$stmt->errorInfo();
                error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
            }
        }else{
            $created="exist";
        }
        return $created;
    }
    /**
     * Read a user from the database
     * @param string user email
     * @param int User identification [optional]
     * @return User User loaded
     */
    function read($email,$id=0){
        $response=false;
        $filter="";
        if($email!=""){
            $stmt = $this->handler->prepare("SELECT id,email,name,lastname,born,sex,iam,validated,city,type FROM users WHERE email = ?");
            $filter=$email;
        }else{
            $stmt = $this->handler->prepare("SELECT id,email,name,lastname,born,sex,iam,validated,city,type FROM users WHERE id = ?");
            $filter=$id;
        }
        if ($stmt->execute(array($filter))){
            $list=$stmt->fetch();
            if($list){
                $daoCity=new DaoCity();
                $daoUserType=new DaoUserType();
                $user=new User(intval($list["id"]),$list["email"],$list["name"],$list["lastname"]);
                $user->setBorn($list["born"]);
                $user->setSex($list["sex"]);
                $user->setIam($list["iam"]);
                $user->setValidated($list["validated"]);
                $user->setCity(intval($list["city"]));
                $user->setType($daoUserType->read(intval($list["type"])));
                $user->setCountry($daoCity->getIdCountry($user->getCity()));
                $response=$user;
            }
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $response;
    }
    /**
     * Update an user in the database
     * @param User User object
     * @return false if could'nt update it
     * @return true if the user was updated
     */
    function update($object){
        $updated=false;
        if($this->emailExist($object->getEmail())){
            $stmt = $this->handler->prepare("UPDATE users SET 
                email=:email, 
                name=:name, 
                lastname=:lastname, 
                born=:born, 
                sex=:sex, 
                iam=:iam, 
                city=:city, 
                type=:type  
                WHERE id=:id"
            );
            $stmt->bindParam(':email',$object->getEmail());
            $stmt->bindParam(':name',$object->getName());
            $stmt->bindParam(':lastname',$object->getLastname());
            $stmt->bindParam(':born',$object->getBorn());
            $stmt->bindParam(':sex',$object->getSex());
            $stmt->bindParam(':iam',$object->getIam());
            $stmt->bindParam(':city',$object->getCity());
            $stmt->bindParam(':type',$object->getType()->getId());
            $stmt->bindParam(':id',$object->getId());
            if($stmt->execute()){
                $updated=true;
            }else{
                $error=$stmt->errorInfo();
                error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
            }
        }
        return $updated;
    }
    /**
     * Update an user  pass in the database
     * @param User email pass salt
     *      * @return false if could'nt update it
     * @return true if the user was updated
     */
    function updatePass($email,$password,$salt){
        $updated=false;
        if($this->emailExist($email)){
            $stmt = $this->handler->prepare("UPDATE users SET 
                password=?,
                salt=?
                WHERE email=?"
            );
            $vars=array(
                $password,
                $salt,
                $email
            );
            if($stmt->execute($vars)){
                $updated=true;
            }else{
                $error=$stmt->errorInfo();
                error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
            }
        }else{
            $updated=false;
        }
        return $updated;
    }
    /**
     * Actualiza el tipo de usuario para un usuario
     * @param User Objeto de tipo usuario con el nuevo tipo
     * @return true if the user was updated
     */
    function updateType($user){
        $updated=false;
        if($this->emailExist($user->getEmail())){
            $stmt = $this->handler->prepare("UPDATE users SET type=:type WHERE id=:id");
            $stmt->bindParam(':type',$user->getType()->getId());
            $stmt->bindParam(':id',$user->getId());
            if($stmt->execute()){
                $updated=true;
            }else{
                $error=$stmt->errorInfo();
                error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
            }
        }else{
            $updated=false;
        }
        return $updated;
    }
    /**
     * Delete an user from the database
     * @param string user email
     * @return false if could'nt delete it
     * @return true if was deleted
     * @todo implement
     */
    function delete($email){
        
    }
    /**
     * Return if a user exist in the database
     * @param string email
     * @return false if doesn't exist
     * @return true if exist
     */
    function emailExist($email){
        $exist=false;
        $stmt = $this->handler->prepare("SELECT email FROM users WHERE email = ?");
        if ($stmt->execute(array($email))) {
            $list=$stmt->fetch();
            if($list["email"]==$email){
                $exist=true;
            }else{
                $exist=false;
            }
        }
        return $exist;
    }
    /**
     * Verifica si han pasado más de 24 horas de que un usuario se haya registrado
     * para dejarlo ingresar
     * @param User $user Objeto User
     * @return true si han pasado las 24 horas logear, false si no han pasado
     */
    function checkWaitingUser($user){
        $response=false;
        $stmt = $this->handler->prepare("
            SELECT 
                id 
            FROM 
                users 
            WHERE 
                DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 24 HOUR)>created 
                AND id=:id
            ");
        $stmt->bindParam(':id',intval($user->getId()));
        if ($stmt->execute()) {
            $list=$stmt->fetch();
            if($list){
                $response=true;
            }
        }
        return $response;
    }
    /**
     * Retorna la lista de usuarios del equipo de maqinato
     * @return User[] Lista de usuarios del equipo de maqinato
     */
    function teamFriends(){
        $list=array();
        $daoUserType=new DaoUserType();
        $stmt = $this->handler->prepare("SELECT id FROM users WHERE type=:type OR type=1");
        $stmt->bindParam(':type',$daoUserType->readFromName("team")->getId());
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()){
                $object=$this->read("",intval($row["id"]));
                array_push($list,$object);
            }
        }
        return $list;
    }
    /**
     * Return the hash password stored in the database
     * @param string user email
     * @return string the hash password for the email
     * @return false if doesn't exist
     */    
    function readHash($email){
        $hash=false;
        $stmt = $this->handler->prepare("SELECT password FROM users WHERE email = ?");
        if ($stmt->execute(array($email))) {
            $list=$stmt->fetch();
            $hash=$list["password"];
        }
        return $hash;
    }
    /**
     * Return the salt encoder stored in the database
     * @param string user email
     * @return string the salt encoder for the email
     * @return false if doesn't exist
     */ 
    function readSalt($email){
        $salt=false;
        $stmt = $this->handler->prepare("SELECT salt FROM users WHERE email = ?");
        if ($stmt->execute(array($email))) {
            $list=$stmt->fetch();
            $salt=$list["salt"];
        }
        return $salt;
    }
    /**
     * Return a list of Users, searching in: name, lastname, email
     * @param string Keyword to search
     * @param int Identification of the current user to know if each user loaded is friend or not
     * @param int Max quantity of users to return
     * @param mixed User id if not need return the user, false otherwise
     * @return User[] List of Users that match with the keyword
     */
    function searchUsers($keyword,$currentUser,$quantity=10,$notLoad=false){
        $prepared='%'.strtolower($keyword).'%';
        $list=array();
        if($notLoad!==false){
            $avoidLoad=' AND id<>? ';
        }
        $stmt = $this->handler->prepare("
            SELECT id,email,name,lastname  
            FROM users 
            WHERE 
                (LOWER(email) LIKE ?
            OR
                LOWER(name) LIKE ?
            OR
                LOWER(lastname) LIKE ?) 
            ".$avoidLoad." 
            LIMIT ?
            ");
        $stmt->bindParam(1,$prepared);
        $stmt->bindParam(2,$prepared);
        $stmt->bindParam(3,$prepared);
        if($notLoad!==false){
            $stmt->bindParam(4,$notLoad,PDO::PARAM_INT);
            $stmt->bindParam(5,$quantity,PDO::PARAM_INT);
        }else{
            $stmt->bindParam(4,$quantity,PDO::PARAM_INT);
        }
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()){
                $object=$this->read("",intval($row["id"]));
                $object->setIsFriend($this->isFriend($object->getId(),$currentUser));
                array_push($list,$object);
            }
        }
        return $list;
    }
    /**
     * Return the list of friends of an User
     * @param User Object of User type
     * @return User[] List of friends of the input User
     */
    function listOfFriends($user){
        $list=array();
        $stmt = $this->handler->prepare("
            SELECT user,friend,accepted 
            FROM friends 
            WHERE
                (user=:user OR friend=:user) 
                AND accepted=true
            ");
        $stmt->bindParam(':user',$user->getId());
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()){
                $friendId=0;
                if($row["user"]!=$user->getId()){
                    $friendId=$row["user"];
                }else{
                    $friendId=$row["friend"];
                }
                $object=$this->read("",$friendId);
                $object->setIsFriend(true);
                array_push($list,$object);
            }
        }
        return $list;
    }
    /**
     * Retorna la lista de amigos de tipo standard de un usuario
     * @param User Object of User type
     * @return User[] List of friends of the input User
     */
    function listOfStandardFriends($user){
        $list=array();
        $daoUserType=new DaoUserType();
        $stmt = $this->handler->prepare("
            SELECT 
                users.id AS id 
            FROM 
                users,
                friends 
            WHERE 
                (friends.user=users.id OR friends.friend=users.id) AND 
                (user=:user OR friend=:user) AND 
                users.id<>:user AND 
                (users.type=:new OR users.type=:standard) 
            ");
        $stmt->bindParam(':user',$user->getId());
        $stmt->bindParam(':new',$daoUserType->readFromName("new")->getId());
        $stmt->bindParam(':standard',$daoUserType->readFromName("standard")->getId());
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()){
                $object=$this->read("",intval($row["id"]));
                $object->setIsFriend(true);
                array_push($list,$object);
            }
        }
        return $list;
    }
    /**
     * Retorna la lista de usuarios que se hayan registrado entre $from y $to fecha y hora
     * p.e. si $from=24 y $to=25 trae los usuarios que se hayan registrado entre
     * desde 24 a 25 horas
     * @param int $from tiempo máximo en el que se registraron los usuarios
     * @param int $to tiempo mínimo en el que se registraron los usuarios
     * @return User[] Lista de objetos de tipo User
     */
    function listOfWaitingUsers($from,$to){
        $list=array();
        $stmt = $this->handler->prepare("
            SELECT 
                id 
            FROM 
                users 
            WHERE 
                created>=DATE_SUB(CURRENT_TIMESTAMP, INTERVAL :to HOUR) AND 
                created<=DATE_SUB(CURRENT_TIMESTAMP, INTERVAL :from HOUR)
            ");
        $stmt->bindParam(':from',intval($from));
        $stmt->bindParam(':to',intval($to));
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()){
                $object=$this->read("",intval($row["id"]));
                array_push($list,$object);
            }
        }
        return $list;
    }
    /**
     * Retorna la lista de usuarios que no se hayan logueado entre $from y $to fecha y hora
     * p.e. si $from=720 y $to=721 trae los usuarios que se hayan logueado entre
     * desde 720 a 25 721
     * @param int $from tiempo máximo en el que se loguearon los usuarios
     * @param int $to tiempo mínimo en el que se loguearon los usuarios
     * @return User[] Lista de objetos de tipo User
     */
    function listOfInactiveUsers($from,$to){
        $list=array();
        $stmt = $this->handler->prepare("
            SELECT 
                user AS id 
            FROM 
                sessions 
            WHERE 
                ini>=DATE_SUB(CURRENT_TIMESTAMP, INTERVAL :to HOUR) AND 
                ini<=DATE_SUB(CURRENT_TIMESTAMP, INTERVAL :from HOUR)
            ");
        $stmt->bindParam(':from',intval($from));
        $stmt->bindParam(':to',intval($to));
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()){
                $object=$this->read("",intval($row["id"]));
                array_push($list,$object);
            }
        }
        return $list;
    }
    /**
     * Retorna la lista de pioneros
     * @return User[] Lista de pioneros
     */
    function listOfPioneers(){
        $list=array();
        $stmt = $this->handler->prepare("SELECT user FROM users_classes WHERE class=1");
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()){
                $object=$this->read("",intval($row["user"]));
                array_push($list,$object);
            }
        }
        return $list;
    }
    /**
     * Return:
     *      - true if the user A is friend of B
     *      - false if the user A is not friend of B
     *      - "sent" if A sent an invitation to B
     *      - "received" if A received an invitation from B
     * @param int Identification of User A
     * @param int Identification of User B
     * @return mixed Boolean or string if are or not friends, or string with request state
     */
    function isFriend($userA,$userB){
        $friends=false;
        $stmt = $this->handler->prepare("SELECT user,friend,accepted FROM friends WHERE (user=:userA AND friend=:userB) OR (user=:userB AND friend=:userA) ");
        $stmt->bindParam(':userA',$userA);
        $stmt->bindParam(':userB',$userB);
        if ($stmt->execute()){
            if($stmt->rowCount()>0){
                $row=$stmt->fetch();
                if($row["accepted"]==true){
                    $friends=true;
                }elseif($row["user"]==$userA){
                    $friends="sent";
                }elseif($row["user"]==$userB){
                    $friends="received";
                }
            }
        }
        return $friends;
    }
    /**
     * Add a friend request from the User A to the User B
     * @param int Identification of User A
     * @param int Identification of User B
     * @return bool True if the request could be done, false otherwise
     */
    function requestFriend($idUserA,$idUserB){
        $requested=false;
        $userA=$this->read("",$idUserA);
        $userB=$this->read("",$idUserB);
        if($this->emailExist($userA->getEmail())&&$this->emailExist($userB->getEmail())){
            $state=$this->isFriend($userA->getId(),$userB->getId());
            //Only store an request if nobody were sent an invitation
            if($state===false){
                $stmt = $this->handler->prepare("INSERT INTO friends (user,friend,requestDate) VALUES (:user,:friend,:requestDate)");
                $stmt->bindParam(':user',$userA->getId());
                $stmt->bindParam(':friend',$userB->getId());
                $stmt->bindParam(':requestDate',date('Y-m-d H:i:s'));
                $stmt->execute();
                $requested=true;
            }
        }
        return $requested;
    }
    /**
     * Accept a presend friend request from the User A to the User B
     * @param int Identification of User A
     * @param int Identification of User B
     * @return bool True if the friendship was accepted, false otherwise
     */
    function acceptFriend($idUserA,$idUserB){
        $accepted=false;
        $userA=$this->read("",$idUserA);
        $userB=$this->read("",$idUserB);
        if($this->emailExist($userA->getEmail())&&$this->emailExist($userB->getEmail())){
            $state=$this->isFriend($userA->getId(),$userB->getId());
            //Only accept the invitation if A recived an invitation from B
            if($state==="received"){
                $stmt = $this->handler->prepare("UPDATE friends SET accepted=?, acceptedDate=? WHERE user=? AND friend=?");
                $accept=1;
                $stmt->bindParam(1,$accept,PDO::PARAM_INT);
                $stmt->bindParam(2,date('Y-m-d H:i:s'));
                $stmt->bindParam(3,$userB->getId(),PDO::PARAM_INT);
                $stmt->bindParam(4,$userA->getId(),PDO::PARAM_INT);
                if($stmt->execute()){
                    $accepted=true;
                }
                //Associate the past activities from both friends to both friends
                
            }
        }
        return $accepted;
    }
    /**
     * Remove friendship or request between User A and User B
     * @param int Identification of User A
     * @param int Identification of User B
     * @return bool True if the request could be deleted, false otherwise
     */
    function removeFriendship($idUserA,$idUserB){
        $removed=false;
        $userA=$this->read("",$idUserA);
        $userB=$this->read("",$idUserB);
        if($this->emailExist($userA->getEmail())&&$this->emailExist($userB->getEmail())){
            $stmt = $this->handler->prepare("DELETE FROM friends WHERE (user=:userA AND friend=:userB) OR (user=:userB AND friend=:userA) ");
            $stmt->bindParam(':userA',$userA->getId());
            $stmt->bindParam(':userB',$userB->getId());
            $stmt->execute();
            $removed=true;
        }
        return $removed;
    }
    /**
     * Comprueba si la clave de validación y el correo corresponden, si corresponden
     * marca como verificado el usuario
     * @param string $email Email del usuario que se quiere verificar
     * @param string $key Clave enviada al email
     * @return bool True si la clave corresponde con el correo
     */
    function verifyValidationKey($email,$key){
        $exist=false;
        $stmt = $this->handler->prepare("SELECT email,validationKey FROM users WHERE email=:email");
        $stmt->bindParam(':email',$email);
        if ($stmt->execute()){
            $list=$stmt->fetch();
            if($list){
                if($list["validationKey"]===$key){
                    $exist=true;
                }else{
                    $exist=false;
                }
            }
        }
        return $exist;
    }
    /**
     * Actualiza una clave de validación cuando se envía un nuevo email al usuario
     * @param User $user Objeto de tipo User
     * @param bool $status True para establecer como validado, false en otro caso
     * @return bool True si la clave se ha actualizado
     */
    function updateValidationState($user,$status){
        $updated=false;
        $date=date('Y-m-d H:i:s');
        $stmt = $this->handler->prepare("UPDATE users SET 
            validated=:status,
            validationDate=:date 
            WHERE id=:id"
        );
        $stmt->bindParam(':status',$status);
        $stmt->bindParam(':date',$date);
        $stmt->bindParam(':id',$user->getId());
        if($stmt->execute()){
            $updated=true;
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $updated;
    }
    /**
     * Actualiza una clave de validación cuando se envía un nuevo email al usuario
     * @param User $user Objeto de tipo User
     * @param string $key Key de verificación del correo
     * @return bool True si la clave se ha actualizado
     */
    function updateValidationKey($user,$key){
        $updated=false;
        $stmt = $this->handler->prepare("UPDATE users SET 
            validationKey=:key
            WHERE id=:id"
        );
        $stmt->bindParam(':key',$key);
        $stmt->bindParam(':id',$user->getId());
        if($stmt->execute()){
            $updated=true;
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $updated;
    }
    /**
     * Retorna si un usuario ha validado su cuenta
     * @param User $user Objeto de tipo User, usuario que se quiere verificar
     * @return bool True si la cuenta se ha validado
     */
    function isValidated($user){
        $response=0;
        $sql="
            SELECT 
                validated 
            FROM 
                users 
            WHERE 
                email=:email
            ";
        $stmt = $this->handler->prepare($sql);
        $stmt->bindParam(':email',$user->getEmail());
        if ($stmt->execute()) {
            $list=$stmt->fetch();
            if($list){
                $response=$list["validated"];
            }
        }
        return $response;
    }
    /**
     * Retorna la cantidad de veces que un usuario se ha logueado
     * @param User $user Objeto de tipo User
     * @return int Cantidad de veces que un usuario se ha logueado
     */
    function timesConected($user){
        $times=0;
        $sql="
            SELECT 
                timesConected
            FROM 
                users 
            WHERE 
                id=:user
            ";
        $stmt = $this->handler->prepare($sql);
        $stmt->bindParam(':user',$user->getId());
        if ($stmt->execute()) {
            $list=$stmt->fetch();
            if($list){
                $times=intval($list["timesConected"]);
            }
        }
        return $times;
    }
    /**
     * Aumenta el contador de visitas para cadu usuario cuando un usuario se loguea
     * @param User $user Objeto de tipo User
     * @return bool True si se pudo modificar, false en otro caso
     */
    function incrementTimesConected($user){
        $times=$this->timesConected($user)+1;
        $updated=false;
        $stmt = $this->handler->prepare("UPDATE users SET 
            timesConected=:times
            WHERE id=:id"
        );
        $stmt->bindParam(':times',$times);
        $stmt->bindParam(':id',$user->getId());
        if($stmt->execute()){
            $updated=true;
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $updated;
    }
    /********************************* GIVING *********************************/
    /**
     * Return the total of points for an User
     * @param int User identificator
     * @return int Total of points
     */
    function userPoints($idUser){
        $amount=0;
        $sql="
            SELECT 
                SUM(points) AS points 
            FROM 
                points 
            WHERE 
                user=?
            ";
        $stmt = $this->handler->prepare($sql);
        if ($stmt->execute(array($idUser))) {
            $list=$stmt->fetch();
            if($list){
                $amount=$list["points"];
            }
        }
        return $amount;
    }
    /**
     * Return the id of a pointType
     * @param string Type of points
     * @return int Id of point type
     */
    function getIdPointType($type){
        $points=0;
        $stmt = $this->handler->prepare("SELECT id FROM point_types WHERE LCASE(name)=?");
        if ($stmt->execute(array(strtolower($type)))) {
            $list=$stmt->fetch();
            $points=intval($list["id"]);
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $points;
    }
    /**
     * Return points of an especifical type
     * @param int User identificator
     * @param int Type of points
     *      +----+----------+--------------+------------+
            | id | name     | predefpoints | predefined |
            +----+----------+--------------+------------+
            |  1 | Set      |            0 |          0 |
            |  2 | Giving   |            0 |          0 |
            |  3 | Folio    |            0 |          0 |
            |  4 | Reprnt   |            1 |          1 |
            |  5 | Signup   |          100 |          1 |
            |  6 | Share    |            2 |          1 |
            |  7 | Like     |            1 |          1 |
            |  8 | Reprnted |            2 |          1 |
            |  9 | Invite   |            5 |          1 |
            | 10 | Bondeed  |            5 |          1 |
            | 11 | Story    |            3 |          1 |
            +----+----------+--------------+------------+
     * @return int Total of Points
     */
    function pointsOfType($userId,$type){
        $amount=0;
        $sql="
            SELECT 
                SUM(points) AS points 
            FROM 
                points 
            WHERE                
                user=? AND
                type=?
            ";
        $stmt = $this->handler->prepare($sql);
        if ($stmt->execute(array($userId,$type))) {
            $list=$stmt->fetch();
            if($list){
                $amount=$list["points"];
            }
        }
        return $amount;
    }
    
    
    /**
     * Return the total amount donated for an User
     * @param int User identificator
     * @return float Total donated to the Area
     */
    function userAmount($idUser){
        $amount=0;
        $stmt = $this->handler->prepare("
            SELECT 
                SUM(givings.amount) AS amount 
            FROM 
                sets,
                givings 
            WHERE 
                sets.id=givings.set AND 
                sets.user=?
            ");
        if ($stmt->execute(array($idUser))) {
            $list=$stmt->fetch();
            if($list){
                $amount=$list["amount"];
            }
        }
        return $amount;
    }
    /**
     * Return the amount of the donations of the User to an Area
     * @param int Area identificator
     * @param int User identificator
     * @return float Total donated to the Area for the user
     */
    function amountArea($idUser,$idArea){
        $amount=0;
        $stmt = $this->handler->prepare("
            SELECT 
                SUM(givings.amount) AS amount 
            FROM 
                sets,
                givings,
                subcategories 
            WHERE 
                sets.id=givings.set AND 
                givings.subcategory=subcategories.id AND 
                sets.user=? AND 
                subcategories.area=? 
            ");
        if ($stmt->execute(array($idUser,$idArea))) {
            $list=$stmt->fetch();
            if($list){
                $amount=$list["amount"];
            }
        }
        return $amount;
    }
    /**
     * Return the percent (0,1) of the donations of the User to an Area
     * @param int Area identificator
     * @param int User identificator
     * @return float Total donated to the Area
     */
    function percentArea($idUser,$idArea){
        $percent=0;
        $totalAmount=$this->userAmount($idUser);
        $areaAmount=$this->amountArea($idUser,$idArea);
        if($totalAmount>0){
            $percent=$areaAmount/$totalAmount;
        }
        return $percent;
    }
    /**
     * Return a number of random users from database
     * @param int number of users to return
     * @param int id of user to not load
     * @return User[] Users loaded
     */
    function randomUsers($number=1,$notload=0){
        $users=array();
        $stmt = $this->handler->prepare("SELECT id FROM users WHERE id!=? ORDER BY RAND() LIMIT 0,".$number);
        if ($stmt->execute(array($notload))){
            while ($row = $stmt->fetch()){
                $user=$this->read("",$row["id"]);
                array_push($users,$user);
            }
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $users;
    }
    /**
     * Return a number of suggested friends from database
     * @param int User Id to find suggested friends
     * @param int number of users to return
     * @return User[] Users loaded
     */
    function suggestedFriends($userId=0,$number=1){
        $users=array();
        $stmt = $this->handler->prepare("
            SELECT 
                users.id AS id 
            FROM 
                users 
            WHERE 
                users.id NOT IN (
                    SELECT 
                        CASE 
                            WHEN 
                                user=:user 
                            THEN 
                                friend 
                            ELSE 
                                user 
                        END AS selected 
                    FROM 
                        friends 
                    WHERE 
                        (
                            user=:user AND 
                            accepted=true
                        ) OR 
                        (
                            friend=:user AND 
                            accepted=true
                        )
                ) AND 
                users.id<>:user 
            ORDER BY RAND() LIMIT 0,".$number);
        $stmt->bindParam(':user',$userId);
        if ($stmt->execute()){
            while ($row = $stmt->fetch()){
                $user=$this->read("",$row["id"]);
                array_push($users,$user);
            }
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $users;
    }
    /**
     * Return a list of pairs of the last N days with the amount giving for each day
     * @param int User identificator
     * @param int Quantity of days
     * @return int[] pairs (day,amount)
     */
    function dailyAmount($idUser,$lastDays){
        $days=array();
        $stmt=$this->handler->prepare("
                SELECT 
                    MONTH(dateCreated) AS month,
                    DAY(dateCreated) AS day, 
                    ROUND(SUM(amount)) AS amount 
                FROM 
                    givings,
                    sets 
                WHERE 
                    dateCreated>DATE_SUB(NOW(),INTERVAL ? DAY) AND 
                    givings.set=sets.id AND 
                    sets.user=? 
                GROUP BY 
                    day;
            ");
        if ($stmt->execute(array($lastDays,$idUser))){
            while ($row = $stmt->fetch()){
                array_push($days,array($row["day"]."/".$row["month"],$row["amount"]));
            }
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $days;
    }
    /**
     * Return a list of pairs of the last N months with the amount giving for each month
     * @param int User identificator
     * @param int Quantity of months
     * @return int[] pairs (month,amount)
     */
    function monthlyAmount($idUser,$lastMonths){
        $days=array();
        $stmt=$this->handler->prepare("
                SELECT 
                    MONTH(dateCreated) AS month,
                    DAY(dateCreated) AS day,
                    ROUND(SUM(amount)) AS amount 
                FROM 
                    givings,
                    sets 
                WHERE 
                    dateCreated>DATE_SUB(NOW(),INTERVAL ? MONTH) AND 
                    givings.set=sets.id AND 
                    sets.user=? 
                GROUP BY 
                    month
            ");
        if ($stmt->execute(array($lastMonths,$idUser))){
            while ($row = $stmt->fetch()){
                array_push($days,array($row["month"],$row["amount"]));
            }
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $days;
    }
    /**
     * Return a list of pairs of the last N months with the points for each month
     * @param int User identificator
     * @param int Quantity of months
     * @return int[] pairs (month,points)
     */
    function monthlyPoints($idUser,$lastMonths){
        $months=array();
        $stmt=$this->handler->prepare("
                SELECT 
                    MONTH(date) AS month,
                    DAY(date) AS day,
                    ROUND(SUM(points)) AS points 
                FROM 
                    points 
                WHERE 
                    date>DATE_SUB(NOW(),INTERVAL ? MONTH) AND 
                    user=? 
                GROUP BY 
                    month
            ");
        if ($stmt->execute(array($lastMonths,$idUser))){
            while ($row = $stmt->fetch()){
                $months[$row["month"]]=$row["points"];
            }
        }else{
            $error=$stmt->errorInfo();
            error_log("[".__FILE__.":".__LINE__."]"."Mysql: ".$error[2]);
        }
        return $months;
    }
    /******************************* GOOD DEEDS *******************************/
    /**
     * Return the amount of the donations of the User to an Area
     * @param int User identificator
     * @param int Area identificator
     * @return float Total donated to the Area for the user
     */
    function numberDeedsUserToArea($idUser,$idArea){
        $number=0;
        $stmt = $this->handler->prepare("
            SELECT 
                COUNT(deeds.id) AS deeds 
            FROM 
                subcategories,
                tags,
                deeds_tags,
                deeds 
            WHERE 
                subcategories.id=tags.subcategory AND 
                tags.id=deeds_tags.tag AND 
                tags.type=2 AND 
                deeds_tags.deed=deeds.id AND 
                deeds.user=? AND 
                subcategories.area=?; 
            ");
        if ($stmt->execute(array($idUser,$idArea))) {
            $list=$stmt->fetch();
            if($list){
                $number=$list["deeds"];
            }
        }
        return $number;
    }
    /**
     * Return the percent (0,1) of the quantity of Good deeds of the User to an Area
     * @param int Area identificator
     * @param int User identificator
     * @return float Total donated to the Area
     */
    function percentDeedsUserToArea($idUser,$idArea){
        $sumation=0;
        $percent=0;
        for($i=0;$i<5;$i++){
            $sumation+=$this->numberDeedsUserToArea($idUser,$i);
        }
        if($sumation>0){
            $percent=$this->numberDeedsUserToArea($idUser,$idArea)/$sumation;
        }
        return $percent;
    }
}

?>
