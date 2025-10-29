<?php
class Usuario extends BaseModelo {
    protected $table = 'usuarios';
    
    protected $fillable = [
        'username', 'nombre', 'apellidos', 'email', 'password',
        'telefono', 'direccion', 'imagen', 'rol_id', 'is_active'
    ];
    
    public function getAllWithRoles() {
        $sql = "SELECT u.*, r.nombre as rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                ORDER BY u.created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    public function findWithRole($id) {
        $sql = "SELECT u.*, r.nombre as rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE u.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function findByUsername($username) {
        return $this->findBy('username', $username);
    }
    
    public function findByEmail($email) {
        return $this->findBy('email', $email);
    }
    
    public function existsUsername($username, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM usuarios WHERE username = ?";
        $params = [$username];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    public function existsEmail($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM usuarios WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    public function create($data) {
        // Hash de la contraseña
        if (isset($data['password'])) {
            $data['password'] = Auth::hashPassword($data['password']);
        }
        
        return parent::create($data);
    }
    
    public function update($id, $data) {
        // Hash de la contraseña si se proporciona
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Auth::hashPassword($data['password']);
        } else {
            // Eliminar password del array si está vacío
            unset($data['password']);
        }
        
        return parent::update($id, $data);
    }
    
    public function getByRole($roleName) {
        $sql = "SELECT u.*, r.nombre as rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE r.nombre = ? AND u.is_active = 1 
                ORDER BY u.nombre, u.apellidos";
        return $this->db->fetchAll($sql, [$roleName]);
    }
    
    public function getMedicos() {
        return $this->getByRole('medico');
    }
    
    public function getSecretarios() {
        return $this->getByRole('secretario');
    }
    
    public function updateLastAccess($id) {
        $sql = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
}
?>