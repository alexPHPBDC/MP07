<?php
include_once("connexio.php");
include_once("classes.php");


/**
 * Checks if user exists on database
 * @param string $user
 * @param string $name
 * @return bool True if user is correct, else False.
 */
function usuariExists($user,$name):bool{
    global $link;
    $query = $link->prepare("SELECT * FROM users where `user`=? AND `name`=?");
    $query->bindParam(1, $user);
    $query->bindParam(2, $name);
    $query->execute();

    return($query->rowCount()>0);

}

/**
 * Checks if combination of user and password exists in users table
 * @param Usuari $usuari
 * @return bool True if user is correct, else False.
 */
function comprovarUsuari(Usuari $usuari)
{
    global $link;
    $usuari->password = hash('sha512', $usuari->password);
    $query = $link->prepare("SELECT * FROM users where `user`=? AND `password`=?");
    $query->bindParam(1, $usuari->user);
    $query->bindParam(2, $usuari->password);
    
    $query->execute();

    return($query->rowCount()>0);
}

/**
 * Adds user to users table
 * @param Usuari $usuari
 * @return string|false String with user Id on success, false if error ocurred;
 */
function addUsuari(Usuari $usuari):string|false
{
    global $link;
    $query = $link->prepare("INSERT INTO users (`name`,`user`,`password`) VALUES(?,?,?)");
    $usuari->password = hash('sha512', $usuari->password);
    $query->bindParam(1, $usuari->name);
    $query->bindParam(2, $usuari->user);
    $query->bindParam(3, $usuari->password);
    $query->execute();
    return $link->lastInsertId();
}

function getUserId(string $user): string|null{
    global $link;
    $query = $link->prepare("SELECT id FROM users where `user`=?");
    $query->bindParam(1, $user);
    $query->execute();
    $id = null;
    $result = $query->fetch();
    if($result){
        $id = $result['id'];
    }
    return $id;
}

/**
 * Given a userId, returns an Usuari object filled with values, or
 * empty user elsewhere
 * @param string $userId
 * @return Usuari user found in database
 */
function getUser(string $userId): Usuari{

    global $link;
    $query = $link->prepare("SELECT * FROM users where `id`=?");
    $query->bindParam(1, $userId);
    $query->execute();
    $result = $query->fetch();
    $usuari = new Usuari();
    if($result){
        $usuari->id = $result['id'];
        $usuari->name = $result['name'];
        $usuari->user = $result['user'];
        $usuari->password = $result['password'];
        $usuari->followers = getFollowers($usuari);
        $usuari->following = getFollowing($usuari);
    }

    return $usuari;
}

/**
 * Gets all the users in the database excepting parameter user
 * @param Usuari $usuari usuari that shall not be found
 * @return array Array of users, with only their ids and names.
 */
function getOtherUsers(Usuari $usuari):array{
    global $link;
    $users = array();
    $query = $link->prepare("SELECT `id`,`name` FROM users where id != ? ");
    $query->bindParam(1, $usuari->id);
    $query->execute();
    $rows = $query->fetchAll();
    foreach($rows as $row){
        $user = new Usuari();
        $user->id =$row['id'];
        $user->name =$row['name'];
        $users[] = $user;
    }
    
    return $users;
}

/**
 * Gets array full of Usuaris, with only their id and name, they
 * are the parameter's usuari followers'
 * @param Usuari $usuari user that we want the followers of
 * @return array of Usuari
 */
function getFollowers(Usuari $usuari):array{
    global $link;
    $followers = array();
    $query = $link->prepare("SELECT usuari.id,usuari.name FROM users as usuari INNER JOIN followers ON usuari.id = followers.idfollower WHERE followers.idfollowed=?");
    $query->bindParam(1, $usuari->id);
    $query->execute();
    $rows = $query->fetchAll();

    foreach($rows as $row){
        $follower = new Usuari();
        $follower->id=$row['id'];
        $follower->name=$row['name'];
        $followers[$row['id']] = $follower;
    }
    
    return $followers;
}

/**
 * Gets array full of Usuaris, with only their id and name,they
 * are the parameter's usuari following'
 * @param Usuari $usuari user that we want the users who he's following
 * @return array of Usuari
 */
function getFollowing(Usuari $usuari):array{
    global $link;
    $following = array();
    $query = $link->prepare("SELECT usuari.id,usuari.name FROM users as usuari INNER JOIN followers ON usuari.id = followers.idfollowed WHERE followers.idfollower=?");
    $query->bindParam(1, $usuari->id);
    $query->execute();
    $rows = $query->fetchAll();
    $follower = array();
    foreach($rows as $row){
        $follower = new Usuari();
        $follower->id=$row['id'];
        $follower->name=$row['name'];
        $following[] = $follower;
    }
    
    return $following;

}

/**
 * Given a user and the id of who he wants to follow, executes query.
 * @param string $usuariId 
 * @param string $idFollowed
 * @return bool True on success, false on failure
 */
function followUser(string $usuariId,string $idFollowed):bool{
    global $link;
    $query = $link->prepare("INSERT INTO followers (`idfollower`,`idfollowed`) VALUES(?,?)");
    $query->bindParam(1, $usuariId);
    $query->bindParam(2, $idFollowed); 
    return $query->execute();
}

/**
 * Given a user and the id of who he wants to unfollow, executes query.
 * @param string $usuariId 
 * @param string $idFollowed
 * @return bool True on success, false on failure
 */
function unfollowUser(string $usuariId, string $idFollowed):bool{
    global $link;
    $query = $link->prepare("DELETE FROM followers WHERE `idfollower`=? AND `idfollowed`=?");
    $query->bindParam(1, $usuariId);
    $query->bindParam(2, $idFollowed); 
    return $query->execute();

}

/**
 * Gets all messages a certain user has posted
 * @param int $userId The user id
 * @return array Array with all the messages
 */
function getMissatges(Usuari $usuari): array
{
    global $link;
    $missatges = [];
    $query = $link->prepare("SELECT * FROM tweets INNER JOIN users ON tweets.user_id = users.id WHERE users.user_id=?");
    $query->bindParam(1, $usuari->id);
    $query->execute();
    $rows = $query->fetchAll();

    foreach ($rows as $row) {
        $missatges[] = $row['text'];
    }
    return $missatges;
}

/**
 * Gets all messages a certain user has posted ordered by date
 * @param int $userId The user id
 * @return array Array with all the messages
 */
function getMissatgesOrderByDate(Usuari $usuari):array
{
    global $link;
    $missatges = [];
    $query = $link->prepare("SELECT t.id,t.text,t.date,t.user_id FROM tweets as t INNER JOIN users ON t.user_id = users.id WHERE users.id=? ORDER BY t.date DESC");
    $query->bindParam(1, $usuari->id);
    $query->execute();
    $rows = $query->fetchAll();
    foreach ($rows as $row) {
        $missatge = new Missatge($row['id'],$row['text'],$row['date'],$row['user_id']);
        $missatges[] = $missatge;
    }
    return $missatges;
}

function getMissatgesFromPeopleIfollow($userId){
    global $link;
    $missatges = [];
    $query = $link->prepare("SELECT t.id,t.text,t.date,t.user_id FROM tweets as t INNER JOIN users ON t.user_id = users.id INNER JOIN followers ON followers.idfollowed = users.id AND followers.idfollower = ? ORDER BY t.date DESC");
    $query->bindParam(1, $userId);
    $query->execute();
    $rows = $query->fetchAll();
    foreach ($rows as $row) {
        $missatge = new Missatge($row['id'],$row['text'],$row['date'],$row['user_id']);
        $missatges[] = $missatge;
    }
    return $missatges;
}



/**
 * Adds message to tweets table, owner is userId
 * @param int $userId
 * @param string $text 
 * @return bool True on success, false on failure
 */
function addMissatge(int $userId, string $text): bool
{
    global $link;
    $query = $link->prepare("INSERT INTO tweets (`text`,`date`,`user_id`) VALUES(?,?,?)");
    $date = date('Y-m-d H:i:s');
    $query->bindParam(1, $text);
    $query->bindParam(2, $date); 
    $query->bindParam(3, $userId);
    return $query->execute();
}

/**
 * Check if tweet is owned by user
 * @param string $tweetId 
 * @param string $userId
 * @return bool
 */
function isOwner(string $tweetId,string $userId): bool{

    global $link;
    $query = $link->prepare("SELECT * FROM tweets where `id`=? AND `user_id`=?");
    $query->bindParam(1, $tweetId);
    $query->bindParam(2, $userId);
    $query->execute();

    return($query->rowCount()>0);

}

//TODO DELETE FROM LIKES FIRST :)
function deleteTweet(string $tweetId): void{
    global $link;
    $query = $link->prepare("DELETE FROM tweets where `id`=?");
    $query->bindParam(1, $tweetId);
    $query->execute();
}


function getTweetLikesAndWho($tweetId){
    global $link;
    $likes = array();
    $query = $link->prepare("SELECT * FROM likes where `tweetId`=?");
    $query->bindParam(1, $tweetId);
    $query->execute();
    $rows = $query->fetchAll();
    $like = array();
    foreach ($rows as $row) {
        $like['id']=$row['id'];
        $like['usuariQueHaFetLike']=$row['idUsuariLiked'];
        $likes[$row['idUsuariLiked']] = $like;
    }
    
    return $likes;
}

function getLikes($usuari){
    global $link;
    $likes = array();
    $query = $link->prepare("SELECT * FROM likes WHERE idUsuariLiked=?");
    $query->bindParam(1, $usuari->id);
    $query->execute();
    $rows = $query->fetchAll();

    foreach ($rows as $row) {
        $tweetId = $row['tweetId'];
        $likes[$tweetId] = true;
    }
    return $likes;

}

function likeTweet($usuariId, $idTweet){
    global $link;
    $query = $link->prepare("INSERT INTO likes (`tweetId`,`idUsuariLiked`) VALUES(?,?)");
    $query->bindParam(1, $idTweet);
    $query->bindParam(2, $usuariId); 
    return $query->execute();
}

function dislikeTweet($usuariId, $idTweet){
    global $link;
    $query = $link->prepare("DELETE FROM likes WHERE `tweetId`=? AND `idUsuariLiked`=?");
    $query->bindParam(1, $idTweet);
    $query->bindParam(2, $usuariId); 
    return $query->execute();
}
