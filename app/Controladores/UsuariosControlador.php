<?php
require_once APP_PATH . '/Modelos/Usuario.php';

class UsuariosControlador extends BaseControlador {
    private $usuarioModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAdmin(); // Solo administradores pueden gestionar usuarios
        $this->usuarioModel = new Usuario();
    }
    
    public function index() {
        $page = (int)$this->getGet('page', 1);
        $search = $this->getGet('search', '');
        
        $conditions = [];
        if ($search) {
            // Para búsqueda, usar consulta personalizada
            $sql = "SELECT u.*, r.nombre as rol_nombre 
                    FROM usuarios u 
                    INNER JOIN roles r ON u.rol_id = r.id 
                    WHERE u.nombre LIKE ? OR u.apellidos LIKE ? OR u.username LIKE ? OR u.email LIKE ?
                    ORDER BY u.created_at DESC";
            
            $searchTerm = "%{$search}%";
            $usuarios = $this->usuarioModel->query($sql, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
            
            // Simular paginación para búsqueda
            $pagination = [
                'data' => $usuarios,
                'total' => count($usuarios),
                'current_page' => 1,
                'last_page' => 1
            ];
        } else {
            $usuarios = $this->usuarioModel->getAllWithRoles();
            
            // Simular paginación simple
            $pagination = [
                'data' => $usuarios,
                'total' => count($usuarios),
                'current_page' => 1,
                'last_page' => 1
            ];
        }
        
        // Obtener roles para el formulario
        $db = Database::getInstance();
        $roles = $db->fetchAll("SELECT * FROM roles ORDER BY nombre");
        
        $this->data['title'] = 'Gestión de Usuarios';
        $this->data['usuarios'] = $pagination['data'];
        $this->data['pagination'] = $pagination;
        $this->data['roles'] = $roles;
        $this->data['search'] = $search;
        
        $this->render('usuarios/index');
    }
    
    public function crear() {
        if ($this->isPost()) {
            return $this->guardar();
        }
        
        // Obtener roles
        $db = Database::getInstance();
        $roles = $db->fetchAll("SELECT * FROM roles ORDER BY nombre");
        
        $this->data['title'] = 'Crear Usuario';
        $this->data['roles'] = $roles;
        $this->data['usuario'] = null;
        
        $this->render('usuarios/crear');
    }
    
    public function guardar() {
        $data = [
            'username' => $this->getPost('username'),
            'nombre' => $this->getPost('nombre'),
            'apellidos' => $this->getPost('apellidos'),
            'email' => $this->getPost('email'),
            'password' => $this->getPost('password'),
            'telefono' => $this->getPost('telefono'),
            'direccion' => $this->getPost('direccion'),
            'rol_id' => $this->getPost('rol_id'),
            'is_active' => 1
        ];
        
        // Validaciones básicas
        $errors = [];
        
        if (empty($data['username'])) {
            $errors[] = 'El nombre de usuario es requerido';
        } elseif ($this->usuarioModel->existsUsername($data['username'])) {
            $errors[] = 'El nombre de usuario ya existe';
        }
        
        if (empty($data['email'])) {
            $errors[] = 'El email es requerido';
        } elseif (!Util::validateEmail($data['email'])) {
            $errors[] = 'El email no es válido';
        } elseif ($this->usuarioModel->existsEmail($data['email'])) {
            $errors[] = 'El email ya está registrado';
        }
        
        if (empty($data['password'])) {
            $errors[] = 'La contraseña es requerida';
        } elseif (strlen($data['password']) < 6) {
            $errors[] = 'La contraseña debe tener al menos 6 caracteres';
        }
        
        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es requerido';
        }
        
        if (empty($data['apellidos'])) {
            $errors[] = 'Los apellidos son requeridos';
        }
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                Flash::error($error);
            }
            $this->redirect('usuarios/crear');
        }
        
        try {
            $id = $this->usuarioModel->create($data);
            if ($id) {
                Flash::success('Usuario creado exitosamente');
                $this->redirect('usuarios');
            } else {
                Flash::error('Error al crear el usuario');
                $this->redirect('usuarios/crear');
            }
        } catch (Exception $e) {
            Flash::error('Error al crear el usuario: ' . $e->getMessage());
            $this->redirect('usuarios/crear');
        }
    }
    
    public function editar() {
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de usuario no válido');
            $this->redirect('usuarios');
        }
        
        if ($this->isPost()) {
            return $this->actualizar();
        }
        
        $usuario = $this->usuarioModel->findWithRole($id);
        if (!$usuario) {
            Flash::error('Usuario no encontrado');
            $this->redirect('usuarios');
        }
        
        // Obtener roles
        $db = Database::getInstance();
        $roles = $db->fetchAll("SELECT * FROM roles ORDER BY nombre");
        
        $this->data['title'] = 'Editar Usuario';
        $this->data['usuario'] = $usuario;
        $this->data['roles'] = $roles;
        
        $this->render('usuarios/editar');
    }
    
    public function actualizar() {
        $id = $this->getPost('id');
        $data = [
            'username' => $this->getPost('username'),
            'nombre' => $this->getPost('nombre'),
            'apellidos' => $this->getPost('apellidos'),
            'email' => $this->getPost('email'),
            'telefono' => $this->getPost('telefono'),
            'direccion' => $this->getPost('direccion'),
            'rol_id' => $this->getPost('rol_id'),
            'is_active' => $this->getPost('is_active', 1)
        ];
        
        // Solo actualizar contraseña si se proporciona
        $password = $this->getPost('password');
        if (!empty($password)) {
            $data['password'] = $password;
        }
        
        // Validaciones
        $errors = [];
        
        if (empty($data['username'])) {
            $errors[] = 'El nombre de usuario es requerido';
        } elseif ($this->usuarioModel->existsUsername($data['username'], $id)) {
            $errors[] = 'El nombre de usuario ya existe';
        }
        
        if (empty($data['email'])) {
            $errors[] = 'El email es requerido';
        } elseif (!Util::validateEmail($data['email'])) {
            $errors[] = 'El email no es válido';
        } elseif ($this->usuarioModel->existsEmail($data['email'], $id)) {
            $errors[] = 'El email ya está registrado';
        }
        
        if (!empty($password) && strlen($password) < 6) {
            $errors[] = 'La contraseña debe tener al menos 6 caracteres';
        }
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                Flash::error($error);
            }
            $this->redirect('usuarios/editar?id=' . $id);
        }
        
        try {
            $result = $this->usuarioModel->update($id, $data);
            if ($result) {
                Flash::success('Usuario actualizado exitosamente');
                $this->redirect('usuarios');
            } else {
                Flash::error('Error al actualizar el usuario');
                $this->redirect('usuarios/editar?id=' . $id);
            }
        } catch (Exception $e) {
            Flash::error('Error al actualizar el usuario: ' . $e->getMessage());
            $this->redirect('usuarios/editar?id=' . $id);
        }
    }
    
    public function eliminar() {
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de usuario no válido');
            $this->redirect('usuarios');
        }
        
        // No permitir eliminar al usuario actual
        if ($id == Auth::id()) {
            Flash::error('No puedes eliminar tu propio usuario');
            $this->redirect('usuarios');
        }
        
        try {
            // Usar soft delete
            $result = $this->usuarioModel->softDelete($id);
            if ($result) {
                Flash::success('Usuario desactivado exitosamente');
            } else {
                Flash::error('Error al desactivar el usuario');
            }
        } catch (Exception $e) {
            Flash::error('Error al desactivar el usuario: ' . $e->getMessage());
        }
        
        $this->redirect('usuarios');
    }
    
    public function ver() {
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de usuario no válido');
            $this->redirect('usuarios');
        }
        
        $usuario = $this->usuarioModel->findWithRole($id);
        if (!$usuario) {
            Flash::error('Usuario no encontrado');
            $this->redirect('usuarios');
        }
        
        $this->data['title'] = 'Ver Usuario';
        $this->data['usuario'] = $usuario;
        
        $this->render('usuarios/ver');
    }
}
?>