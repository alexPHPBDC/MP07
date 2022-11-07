<?php
class Usuari{
    public string $id;
    public string $name;
    public string $user;
    public string $password;
    public array $followers;
    public array $following;
    public array $tweets;
    public array $likes;
}

class Missatge{
    public string $id;
    public string $text;
    public string $date;
    public string $userId;

    function __construct($id,$text,$date,$userId) {
        $this->id = $id;
        $this->text = $text;
        $this->date = $date;
        $this->userId = $userId;
      }
}
?>