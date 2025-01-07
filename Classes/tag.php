<?php

class tag {

private $db;
public $id;
public $name;

public function __construct($db, $id = null) {
    $this->db = $db;
    if ($id) {
        $this->loadTagById($id);
    }
}

public function loadTagById($id) {
    $sql = "SELECT * FROM tag WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $this->id = $result['id'];
        $this->name = $result['name'];
    } else {
        throw new Exception("Tag not found.");
    }



}

public function createTag($name) {
    $sql = "INSERT INTO tag (name) VALUES (:name)";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':name', $name);
    return $stmt->execute();

}

public function getAllTags() {
    $sql = "SELECT * FROM tag";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}


?>