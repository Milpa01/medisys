<?php
// Definición de rutas del sistema

return [
    // Rutas de autenticación
    '' => ['controller' => 'AutenticacionControlador', 'method' => 'login'],
    'login' => ['controller' => 'AutenticacionControlador', 'method' => 'login'],
    'logout' => ['controller' => 'AutenticacionControlador', 'method' => 'logout'],
    'auth' => ['controller' => 'AutenticacionControlador', 'method' => 'authenticate'],
    
    // Dashboard
    'dashboard' => ['controller' => 'InicioControlador', 'method' => 'index'],
    'inicio' => ['controller' => 'InicioControlador', 'method' => 'index'],
    
    // Usuarios
    'usuarios' => ['controller' => 'UsuariosControlador', 'method' => 'index'],
    'usuarios/crear' => ['controller' => 'UsuariosControlador', 'method' => 'crear'],
    'usuarios/guardar' => ['controller' => 'UsuariosControlador', 'method' => 'guardar'],
    'usuarios/editar' => ['controller' => 'UsuariosControlador', 'method' => 'editar'],
    'usuarios/actualizar' => ['controller' => 'UsuariosControlador', 'method' => 'actualizar'],
    'usuarios/eliminar' => ['controller' => 'UsuariosControlador', 'method' => 'eliminar'],
    'usuarios/ver' => ['controller' => 'UsuariosControlador', 'method' => 'ver'],
    
    // Pacientes
    'pacientes' => ['controller' => 'PacientesControlador', 'method' => 'index'],
    'pacientes/crear' => ['controller' => 'PacientesControlador', 'method' => 'crear'],
    'pacientes/guardar' => ['controller' => 'PacientesControlador', 'method' => 'guardar'],
    'pacientes/editar' => ['controller' => 'PacientesControlador', 'method' => 'editar'],
    'pacientes/actualizar' => ['controller' => 'PacientesControlador', 'method' => 'actualizar'],
    'pacientes/eliminar' => ['controller' => 'PacientesControlador', 'method' => 'eliminar'],
    'pacientes/ver' => ['controller' => 'PacientesControlador', 'method' => 'ver'],
    
    // Médicos
    'medicos' => ['controller' => 'MedicosControlador', 'method' => 'index'],
    'medicos/crear' => ['controller' => 'MedicosControlador', 'method' => 'crear'],
    'medicos/guardar' => ['controller' => 'MedicosControlador', 'method' => 'guardar'],
    'medicos/editar' => ['controller' => 'MedicosControlador', 'method' => 'editar'],
    'medicos/actualizar' => ['controller' => 'MedicosControlador', 'method' => 'actualizar'],
    'medicos/eliminar' => ['controller' => 'MedicosControlador', 'method' => 'eliminar'],
    'medicos/ver' => ['controller' => 'MedicosControlador', 'method' => 'ver'],
    'medicos/horarios' => ['controller' => 'MedicosControlador', 'method' => 'horarios'],
    
    // ========== EXPEDIENTES ==========
    'expedientes' => ['controller' => 'ExpedientesControlador', 'method' => 'index'],
    'expedientes/ver' => ['controller' => 'ExpedientesControlador', 'method' => 'ver'],
    'expedientes/editar' => ['controller' => 'ExpedientesControlador', 'method' => 'editar'],
    'expedientes/actualizar' => ['controller' => 'ExpedientesControlador', 'method' => 'actualizar'],
    'expedientes/crear' => ['controller' => 'ExpedientesControlador', 'method' => 'crear'],
    'expedientes/guardar' => ['controller' => 'ExpedientesControlador', 'method' => 'guardar'],
    'expedientes/imprimir' => ['controller' => 'ExpedientesControlador', 'method' => 'imprimir'],
    'expedientes/buscarPorPaciente' => ['controller' => 'ExpedientesControlador', 'method' => 'buscarPorPaciente'],

    // Citas
    'citas' => ['controller' => 'CitasControlador', 'method' => 'index'],
    'citas/crear' => ['controller' => 'CitasControlador', 'method' => 'crear'],
    'citas/guardar' => ['controller' => 'CitasControlador', 'method' => 'guardar'],
    'citas/editar' => ['controller' => 'CitasControlador', 'method' => 'editar'],
    'citas/actualizar' => ['controller' => 'CitasControlador', 'method' => 'actualizar'],
    'citas/eliminar' => ['controller' => 'CitasControlador', 'method' => 'eliminar'],
    'citas/ver' => ['controller' => 'CitasControlador', 'method' => 'ver'],
    'citas/calendario' => ['controller' => 'CitasControlador', 'method' => 'calendario'],
    'citas/agenda' => ['controller' => 'CitasControlador', 'method' => 'agenda'],
    'citas/cambiarEstado' => ['controller' => 'CitasControlador', 'method' => 'cambiarEstado'],
    
    // AJAX para citas
    'citas/horarios-disponibles' => ['controller' => 'CitasControlador', 'method' => 'obtenerHorariosDisponibles'],
    
    // Consultas
    'consultas' => ['controller' => 'ConsultasControlador', 'method' => 'index'],
    'consultas/nueva' => ['controller' => 'ConsultasControlador', 'method' => 'nueva'],
    'consultas/guardar' => ['controller' => 'ConsultasControlador', 'method' => 'guardar'],
    'consultas/ver' => ['controller' => 'ConsultasControlador', 'method' => 'ver'],
    'consultas/editar' => ['controller' => 'ConsultasControlador', 'method' => 'editar'],
    'consultas/actualizar' => ['controller' => 'ConsultasControlador', 'method' => 'actualizar'],
    
    // Especialidades (para administradores)
    'especialidades' => ['controller' => 'EspecialidadesControlador', 'method' => 'index'],
    'especialidades/crear' => ['controller' => 'EspecialidadesControlador', 'method' => 'crear'],
    'especialidades/guardar' => ['controller' => 'EspecialidadesControlador', 'method' => 'guardar'],
    'especialidades/editar' => ['controller' => 'EspecialidadesControlador', 'method' => 'editar'],
    'especialidades/actualizar' => ['controller' => 'EspecialidadesControlador', 'method' => 'actualizar'],
    
    // Reportes (para administradores)
    'reportes' => ['controller' => 'ReportesControlador', 'method' => 'index'],
    'reportes/citas' => ['controller' => 'ReportesControlador', 'method' => 'citas'],
    'reportes/pacientes' => ['controller' => 'ReportesControlador', 'method' => 'pacientes'],
    'reportes/medicos' => ['controller' => 'ReportesControlador', 'method' => 'medicos'],
];
?>