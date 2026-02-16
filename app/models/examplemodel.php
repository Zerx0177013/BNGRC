<?php
namespace app\models;

class UserModel
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }
    public function getNumberOfUsers(): int
    {
        $stmt = $this->db->query('SELECT * FROM `V_COUNT_USERS`');
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int) ($result['total_users'] ?? 0);
    }

    public function checkEmail(string $email){
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM `user` WHERE email = :email');
            $stmt->execute([':email' => $email]);
            return (int) $stmt->fetchColumn() > 0;      
    }

    public function checkInfo($email , $password){
        $stmt = $this->db->prepare('SELECT idUser,username FROM `user` WHERE email = :email AND password = :password');
        $stmt->execute([':email' => $email, ':password' => $password]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$user) {
            return null;
        }
        
        $user['isAdmin'] = $this->isAdmin($user['idUser']);
        return $user; 
    }
    public function isAdmin($id){
        $stmtm = $this->db->prepare('SELECT COUNT(*) FROM `admin` WHERE idUser = :id');
        $stmtm->execute([':id' => $id]);
        return (int) $stmtm->fetchColumn() > 0;
    }

    public function register(string $username, string $email, string $password): bool
    {
        $stmt = $this->db->prepare('INSERT INTO `user` (username, email, password) VALUES (:username, :email, :password)');
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $password
        ]);
    }
    
}
