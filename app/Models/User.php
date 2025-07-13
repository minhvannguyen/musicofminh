<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($name, $email, $password, $role)
    {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $password, $role]);
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }   

    public function findByName($searchTerm)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE name LIKE ? OR email LIKE ?");
        $stmt->execute(['%' . $searchTerm . '%', '%' . $searchTerm . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updatePasswordByEmail($email, $hashedPassword)
    {
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE email = :email");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);

        return $stmt->execute();
    }

    public function getUsersPaginated($limit, $offset, $keyword = null)
{
    if ($keyword) {
        $stmt = $this->db->prepare("
            SELECT * FROM users 
            WHERE name LIKE :keyword 
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
    } else {
        $stmt = $this->db->prepare("
            SELECT * FROM users 
            LIMIT :limit OFFSET :offset
        ");
    }

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function countUsers($keyword = null)
{
    if ($keyword) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE name LIKE :keyword");
        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
    } else {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users");
    }
    $stmt->execute();
    return $stmt->fetchColumn();
}

    public function update($id, $name, $email, $password, $role)
    {
        $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, password = ?, role = ? WHERE id = ?");
        return $stmt->execute([$name, $email, $password, $role, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>