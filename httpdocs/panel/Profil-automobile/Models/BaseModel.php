<?php
// filepath: panel/Profil-automobile/models/BaseModel.php
class BaseModel {
    protected $bdd;
    protected $table;
    
    public function __construct() {
        global $bdd;
        $this->bdd = $bdd;
    }
    
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->bdd->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findAll($conditions = [], $orderBy = null, $limit = null) {
        $sql = "SELECT * FROM {$this->table}";
        
        // Add WHERE conditions if any
        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $whereConditions = [];
            
            foreach (array_keys($conditions) as $field) {
                $whereConditions[] = "$field = :$field";
            }
            
            $sql .= implode(' AND ', $whereConditions);
        }
        
        // Add ORDER BY if specified
        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }
        
        // Add LIMIT if specified
        if ($limit) {
            $sql .= " LIMIT $limit";
        }
        
        $stmt = $this->bdd->prepare($sql);
        $stmt->execute($conditions);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function insert($data) {
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ($fields) VALUES ($placeholders)";
        $stmt = $this->bdd->prepare($sql);
        $stmt->execute($data);
        
        return $this->bdd->lastInsertId();
    }
    
    public function update($id, $data) {
        $sets = [];
        foreach (array_keys($data) as $field) {
            $sets[] = "$field = :$field";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = :id";
        $data['id'] = $id;
        
        $stmt = $this->bdd->prepare($sql);
        $result = $stmt->execute($data);
        
        return $result;
    }
    
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->bdd->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function count($conditions = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        
        // Add WHERE conditions if any
        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $whereConditions = [];
            
            foreach (array_keys($conditions) as $field) {
                $whereConditions[] = "$field = :$field";
            }
            
            $sql .= implode(' AND ', $whereConditions);
        }
        
        $stmt = $this->bdd->prepare($sql);
        $stmt->execute($conditions);
        return $stmt->fetchColumn();
    }
}