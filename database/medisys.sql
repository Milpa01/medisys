-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-10-2025 a las 23:29:19
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `medisys`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `accion` varchar(100) NOT NULL,
  `tabla_afectada` varchar(50) DEFAULT NULL,
  `registro_id` int(11) DEFAULT NULL,
  `datos_anteriores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_anteriores`)),
  `datos_nuevos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_nuevos`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `codigo_cita` varchar(20) DEFAULT NULL,
  `paciente_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `usuario_registro_id` int(11) NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `motivo_consulta` varchar(255) NOT NULL,
  `notas` text DEFAULT NULL,
  `estado` enum('programada','confirmada','en_curso','completada','cancelada','no_asistio') DEFAULT 'programada',
  `costo` decimal(10,2) DEFAULT 0.00,
  `observaciones` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `codigo_cita`, `paciente_id`, `medico_id`, `usuario_registro_id`, `fecha_cita`, `hora_cita`, `motivo_consulta`, `notas`, `estado`, `costo`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 'CIT202509270001', 1, 1, 1, '2025-09-27', '10:00:00', 'malesta estomacal', '', 'completada', 0.00, NULL, '2025-09-21 15:05:18', '2025-10-30 02:39:37'),
(2, 'CIT202510290001', 1, 1, 3, '2025-10-29', '16:00:00', 'Dolor de Cabeza', '', 'completada', 0.00, NULL, '2025-10-29 07:31:55', '2025-10-30 02:39:27'),
(3, 'CIT202510300001', 1, 1, 3, '2025-10-30', '16:00:00', 'Consulta general', '', 'completada', 0.00, NULL, '2025-10-30 15:03:39', '2025-10-30 16:20:57'),
(4, 'CIT202510300002', 1, 1, 3, '2025-10-30', '17:00:00', 'Consulta general', '', 'completada', 0.00, NULL, '2025-10-30 16:20:03', '2025-10-30 16:22:39');

--
-- Disparadores `citas`
--
DELIMITER $$
CREATE TRIGGER `tr_citas_codigo` BEFORE INSERT ON `citas` FOR EACH ROW BEGIN
    IF NEW.codigo_cita IS NULL THEN
        SET NEW.codigo_cita = CONCAT('CIT', DATE_FORMAT(NEW.fecha_cita, '%Y%m%d'), LPAD(LAST_INSERT_ID() + 1, 4, '0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas_backup_20251030`
--

CREATE TABLE `citas_backup_20251030` (
  `id` int(11) NOT NULL DEFAULT 0,
  `codigo_cita` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paciente_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `usuario_registro_id` int(11) NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `duracion_minutos` int(11) DEFAULT 30,
  `motivo_consulta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notas` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('programada','confirmada','en_curso','completada','cancelada','no_asistio') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'programada',
  `tipo_cita` enum('primera_vez','control','emergencia','especializada') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'primera_vez',
  `costo` decimal(10,2) DEFAULT 0.00,
  `observaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas_backup_20251030`
--

INSERT INTO `citas_backup_20251030` (`id`, `codigo_cita`, `paciente_id`, `medico_id`, `usuario_registro_id`, `fecha_cita`, `hora_cita`, `duracion_minutos`, `motivo_consulta`, `notas`, `estado`, `tipo_cita`, `costo`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 'CIT202509270001', 1, 1, 1, '2025-09-27', '10:00:00', 60, 'malesta estomacal', '', 'completada', 'primera_vez', 0.00, NULL, '2025-09-21 15:05:18', '2025-10-30 02:39:37'),
(2, 'CIT202510290001', 1, 1, 3, '2025-10-29', '16:00:00', 30, 'Dolor de Cabeza', '', 'completada', 'control', 0.00, NULL, '2025-10-29 07:31:55', '2025-10-30 02:39:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuraciones`
--

CREATE TABLE `configuraciones` (
  `id` int(11) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `valor` text DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('texto','numero','booleano','json') DEFAULT 'texto',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuraciones`
--

INSERT INTO `configuraciones` (`id`, `clave`, `valor`, `descripcion`, `tipo`, `updated_at`) VALUES
(1, 'nombre_clinica', 'MediSys Clínica', 'Nombre de la clínica', 'texto', '2025-09-17 21:37:23'),
(2, 'direccion_clinica', '', 'Dirección de la clínica', 'texto', '2025-09-17 21:37:23'),
(3, 'telefono_clinica', '', 'Teléfono de la clínica', 'texto', '2025-09-17 21:37:23'),
(4, 'email_clinica', '', 'Email de contacto de la clínica', 'texto', '2025-09-17 21:37:23'),
(5, 'duracion_cita_default', '30', 'Duración por defecto de las citas en minutos', 'numero', '2025-09-17 21:37:23'),
(6, 'horario_inicio', '08:00', 'Hora de inicio de atención', 'texto', '2025-09-17 21:37:23'),
(7, 'horario_fin', '17:00', 'Hora de fin de atención', 'texto', '2025-09-17 21:37:23'),
(8, 'dias_atencion', 'lunes,martes,miercoles,jueves,viernes', 'Días de atención', 'texto', '2025-09-17 21:37:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consultas`
--

CREATE TABLE `consultas` (
  `id` int(11) NOT NULL,
  `cita_id` int(11) NOT NULL,
  `numero_consulta` varchar(20) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `altura` decimal(4,2) DEFAULT NULL,
  `temperatura` decimal(4,2) DEFAULT NULL,
  `presion_sistolica` int(11) DEFAULT NULL,
  `presion_diastolica` int(11) DEFAULT NULL,
  `frecuencia_cardiaca` int(11) DEFAULT NULL,
  `sintomas` text DEFAULT NULL,
  `exploracion_fisica` text DEFAULT NULL,
  `diagnostico_principal` varchar(255) DEFAULT NULL,
  `diagnosticos_secundarios` text DEFAULT NULL,
  `plan_tratamiento` text DEFAULT NULL,
  `indicaciones` text DEFAULT NULL,
  `proxima_cita` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Disparadores `consultas`
--
DELIMITER $$
CREATE TRIGGER `tr_consultas_numero` BEFORE INSERT ON `consultas` FOR EACH ROW BEGIN
    IF NEW.numero_consulta IS NULL THEN
        SET NEW.numero_consulta = CONCAT('CON', DATE_FORMAT(NOW(), '%Y%m%d'), LPAD(LAST_INSERT_ID() + 1, 4, '0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id`, `nombre`, `descripcion`, `is_active`, `created_at`) VALUES
(1, 'Medicina General', 'Atención médica integral y preventiva', 1, '2025-09-17 21:37:23'),
(2, 'Pediatría', 'Atención médica especializada en niños', 1, '2025-09-17 21:37:23'),
(3, 'Ginecología', 'Atención médica especializada en salud femenina', 1, '2025-09-17 21:37:23'),
(4, 'Cardiología', 'Atención médica especializada en el corazón', 1, '2025-09-17 21:37:23'),
(5, 'Dermatología', 'Atención médica especializada en la piel', 1, '2025-09-17 21:37:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expedientes`
--

CREATE TABLE `expedientes` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `numero_expediente` varchar(20) DEFAULT NULL,
  `antecedentes_familiares` text DEFAULT NULL,
  `antecedentes_personales` text DEFAULT NULL,
  `antecedentes_quirurgicos` text DEFAULT NULL,
  `antecedentes_alergicos` text DEFAULT NULL,
  `vacunas` text DEFAULT NULL,
  `grupo_sanguineo` varchar(5) DEFAULT NULL,
  `factor_rh` enum('+','-') DEFAULT NULL,
  `peso_actual` decimal(5,2) DEFAULT NULL,
  `altura_actual` decimal(6,2) DEFAULT NULL,
  `imc` decimal(4,2) GENERATED ALWAYS AS (`peso_actual` / (`altura_actual` / 100 * (`altura_actual` / 100))) STORED,
  `estado_civil` enum('soltero','casado','divorciado','viudo','union_libre') DEFAULT NULL,
  `ocupacion` varchar(100) DEFAULT NULL,
  `escolaridad` varchar(50) DEFAULT NULL,
  `observaciones_generales` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `expedientes`
--

INSERT INTO `expedientes` (`id`, `paciente_id`, `numero_expediente`, `antecedentes_familiares`, `antecedentes_personales`, `antecedentes_quirurgicos`, `antecedentes_alergicos`, `vacunas`, `grupo_sanguineo`, `factor_rh`, `peso_actual`, `altura_actual`, `estado_civil`, `ocupacion`, `escolaridad`, `observaciones_generales`, `created_at`, `updated_at`) VALUES
(1, 1, 'EXP000001', '', '', '', '', '', 'O', '-', 55.00, 155.00, 'soltero', '', 'Universitaria', '', '2025-09-21 14:33:31', '2025-10-30 04:19:56');

--
-- Disparadores `expedientes`
--
DELIMITER $$
CREATE TRIGGER `tr_expedientes_numero` BEFORE INSERT ON `expedientes` FOR EACH ROW BEGIN
    IF NEW.numero_expediente IS NULL THEN
        SET NEW.numero_expediente = CONCAT('EXP', LPAD(NEW.paciente_id, 6, '0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expediente_archivos`
--

CREATE TABLE `expediente_archivos` (
  `id` int(11) NOT NULL,
  `expediente_id` int(11) NOT NULL,
  `nombre_original` varchar(255) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `ruta_archivo` varchar(500) NOT NULL,
  `tipo_archivo` varchar(50) NOT NULL,
  `tamano_bytes` bigint(20) NOT NULL,
  `tipo_documento` enum('analisis_laboratorio','radiografia','receta','informe_medico','consentimiento','otro') DEFAULT 'otro',
  `descripcion` text DEFAULT NULL,
  `usuario_id` int(11) NOT NULL COMMENT 'Usuario que subió el archivo',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicamentos`
--

CREATE TABLE `medicamentos` (
  `id` int(11) NOT NULL,
  `nombre_comercial` varchar(100) NOT NULL,
  `nombre_generico` varchar(100) DEFAULT NULL,
  `presentacion` varchar(100) DEFAULT NULL,
  `concentracion` varchar(50) DEFAULT NULL,
  `laboratorio` varchar(100) DEFAULT NULL,
  `codigo_barras` varchar(50) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT 0.00,
  `stock` int(11) DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicos`
--

CREATE TABLE `medicos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `cedula_profesional` varchar(50) NOT NULL,
  `especialidad_id` int(11) NOT NULL,
  `experiencia_anos` int(11) DEFAULT 0,
  `consultorio` varchar(50) DEFAULT NULL,
  `horario_inicio` time DEFAULT '08:00:00',
  `horario_fin` time DEFAULT '17:00:00',
  `dias_atencion` set('lunes','martes','miercoles','jueves','viernes','sabado','domingo') DEFAULT 'lunes,martes,miercoles,jueves,viernes',
  `costo_consulta` decimal(10,2) DEFAULT 0.00,
  `observaciones` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `medicos`
--

INSERT INTO `medicos` (`id`, `usuario_id`, `cedula_profesional`, `especialidad_id`, `experiencia_anos`, `consultorio`, `horario_inicio`, `horario_fin`, `dias_atencion`, `costo_consulta`, `observaciones`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 2, '45619813486', 1, 20, 'Consultorio 1', '08:00:00', '23:00:00', 'lunes,martes,miercoles,jueves,viernes,sabado', 150.00, '', 1, '2025-09-21 14:31:30', '2025-10-29 04:45:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id` int(11) NOT NULL,
  `codigo_paciente` varchar(20) DEFAULT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` enum('M','F','Otro') NOT NULL,
  `tipo_sangre` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `dpi` varchar(20) DEFAULT NULL,
  `contacto_emergencia` varchar(100) DEFAULT NULL,
  `telefono_emergencia` varchar(20) DEFAULT NULL,
  `seguro_medico` varchar(100) DEFAULT NULL,
  `numero_seguro` varchar(50) DEFAULT NULL,
  `alergias` text DEFAULT NULL,
  `medicamentos_actuales` text DEFAULT NULL,
  `enfermedades_cronicas` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id`, `codigo_paciente`, `nombre`, `apellidos`, `fecha_nacimiento`, `genero`, `tipo_sangre`, `email`, `telefono`, `celular`, `direccion`, `ciudad`, `dpi`, `contacto_emergencia`, `telefono_emergencia`, `seguro_medico`, `numero_seguro`, `alergias`, `medicamentos_actuales`, `enfermedades_cronicas`, `observaciones`, `imagen`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'PAC000001', 'Valesca', 'Gomez', '2002-09-18', 'F', 'O-', 'vlscagomez@gmail.com', '53503536', '53503536', '10a calle 12-92 Zona 4', 'Zona 4', '1234567890123', 'Angela Alonzo', '53317986', '', '', '', '', '', '', NULL, 1, '2025-09-21 14:33:31', '2025-09-21 14:33:31');

--
-- Disparadores `pacientes`
--
DELIMITER $$
CREATE TRIGGER `tr_pacientes_codigo` BEFORE INSERT ON `pacientes` FOR EACH ROW BEGIN
    IF NEW.codigo_paciente IS NULL THEN
        SET NEW.codigo_paciente = CONCAT('PAC', LPAD(LAST_INSERT_ID() + 1, 6, '0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prescripciones`
--

CREATE TABLE `prescripciones` (
  `id` int(11) NOT NULL,
  `consulta_id` int(11) NOT NULL,
  `medicamento_id` int(11) NOT NULL,
  `dosis` varchar(100) NOT NULL,
  `frecuencia` varchar(100) NOT NULL,
  `duracion` varchar(100) NOT NULL,
  `via_administracion` enum('oral','intravenosa','intramuscular','topica','sublingual','otra') DEFAULT 'oral',
  `cantidad_recetada` int(11) DEFAULT NULL,
  `indicaciones_especiales` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `created_at`) VALUES
(1, 'administrador', 'Control total del sistema, gestión de usuarios y configuraciones', '2025-09-17 21:37:22'),
(2, 'medico', 'Acceso a consultas, expedientes y prescripciones médicas', '2025-09-17 21:37:22'),
(3, 'secretario', 'Gestión de citas, registro de pacientes y tareas administrativas', '2025-09-17 21:37:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `id` varchar(128) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `ultima_actividad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `rol_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `ultimo_acceso` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `nombre`, `apellidos`, `email`, `password`, `telefono`, `direccion`, `imagen`, `rol_id`, `is_active`, `ultimo_acceso`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrador', 'Sistema', 'admin@medisys.com', 'e589c9c50ded1af49997ce52558aed6f26d0890e7738508aa907d59f6e4bdaca', '45043276', '', NULL, 1, 1, '2025-10-30 05:47:27', '2025-09-17 21:37:23', '2025-10-30 05:47:27'),
(2, 'fergz', 'Fernando', 'Gómez', 'rodfergomez@gmail.com', 'f564129b4ebc8dae1c59c5958be92be05b024e88b82dc059ab3faee0fda386a9', '40363533', '10a calle 12-92 Zona 4', NULL, 2, 1, '2025-10-30 16:20:52', '2025-09-21 13:21:54', '2025-10-30 16:20:52'),
(3, 'alonzo', 'Angela', 'Alonzo', 'madaiangela@gmail.com', '79fb97e99e0b375d0ce0422db1551dd1ff46d6c6079a3656b18f80f2e8fbe5c9', '53317986', '10a calle 12-92 Zona 4', NULL, 3, 1, '2025-10-30 16:18:21', '2025-09-21 13:23:16', '2025-10-30 16:18:21');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_citas_completas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_citas_completas` (
`id` int(11)
,`codigo_cita` varchar(20)
,`fecha_cita` date
,`hora_cita` time
,`motivo_consulta` varchar(255)
,`estado` enum('programada','confirmada','en_curso','completada','cancelada','no_asistio')
,`costo` decimal(10,2)
,`notas` text
,`observaciones` text
,`nombre_paciente` varchar(151)
,`telefono_paciente` varchar(20)
,`codigo_paciente` varchar(20)
,`nombre_medico` varchar(151)
,`especialidad` varchar(100)
,`consultorio` varchar(50)
,`registrado_por` varchar(151)
,`created_at` datetime
,`updated_at` datetime
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_citas_completas`
--
DROP TABLE IF EXISTS `vista_citas_completas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_citas_completas`  AS SELECT `c`.`id` AS `id`, `c`.`codigo_cita` AS `codigo_cita`, `c`.`fecha_cita` AS `fecha_cita`, `c`.`hora_cita` AS `hora_cita`, `c`.`motivo_consulta` AS `motivo_consulta`, `c`.`estado` AS `estado`, `c`.`costo` AS `costo`, `c`.`notas` AS `notas`, `c`.`observaciones` AS `observaciones`, concat(`p`.`nombre`,' ',`p`.`apellidos`) AS `nombre_paciente`, `p`.`telefono` AS `telefono_paciente`, `p`.`codigo_paciente` AS `codigo_paciente`, concat(`u`.`nombre`,' ',`u`.`apellidos`) AS `nombre_medico`, `e`.`nombre` AS `especialidad`, `med`.`consultorio` AS `consultorio`, concat(`ur`.`nombre`,' ',`ur`.`apellidos`) AS `registrado_por`, `c`.`created_at` AS `created_at`, `c`.`updated_at` AS `updated_at` FROM (((((`citas` `c` join `pacientes` `p` on(`c`.`paciente_id` = `p`.`id`)) join `medicos` `med` on(`c`.`medico_id` = `med`.`id`)) join `usuarios` `u` on(`med`.`usuario_id` = `u`.`id`)) join `especialidades` `e` on(`med`.`especialidad_id` = `e`.`id`)) join `usuarios` `ur` on(`c`.`usuario_registro_id` = `ur`.`id`)) WHERE `c`.`estado` <> 'cancelada' ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `idx_auditoria_fecha` (`created_at`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_cita` (`codigo_cita`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `usuario_registro_id` (`usuario_registro_id`),
  ADD KEY `idx_fecha_cita` (`fecha_cita`,`hora_cita`),
  ADD KEY `idx_medico_fecha` (`medico_id`,`fecha_cita`),
  ADD KEY `idx_citas_estado` (`estado`),
  ADD KEY `idx_citas_fecha_medico` (`fecha_cita`,`medico_id`);

--
-- Indices de la tabla `configuraciones`
--
ALTER TABLE `configuraciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `consultas`
--
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cita_id` (`cita_id`),
  ADD UNIQUE KEY `numero_consulta` (`numero_consulta`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `expedientes`
--
ALTER TABLE `expedientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `paciente_id` (`paciente_id`),
  ADD UNIQUE KEY `numero_expediente` (`numero_expediente`);

--
-- Indices de la tabla `expediente_archivos`
--
ALTER TABLE `expediente_archivos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_expediente` (`expediente_id`),
  ADD KEY `idx_tipo_documento` (`tipo_documento`),
  ADD KEY `fk_expediente_archivos_usuario` (`usuario_id`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_tipo_archivo` (`tipo_archivo`);

--
-- Indices de la tabla `medicamentos`
--
ALTER TABLE `medicamentos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`),
  ADD UNIQUE KEY `cedula_profesional` (`cedula_profesional`),
  ADD KEY `especialidad_id` (`especialidad_id`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_paciente` (`codigo_paciente`),
  ADD UNIQUE KEY `dpi` (`dpi`),
  ADD KEY `idx_pacientes_nombre` (`nombre`,`apellidos`),
  ADD KEY `idx_pacientes_dpi` (`dpi`);

--
-- Indices de la tabla `prescripciones`
--
ALTER TABLE `prescripciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consulta_id` (`consulta_id`),
  ADD KEY `medicamento_id` (`medicamento_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `idx_sesiones_actividad` (`ultima_actividad`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `rol_id` (`rol_id`),
  ADD KEY `idx_usuarios_email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `configuraciones`
--
ALTER TABLE `configuraciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `consultas`
--
ALTER TABLE `consultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `expedientes`
--
ALTER TABLE `expedientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `expediente_archivos`
--
ALTER TABLE `expediente_archivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `medicamentos`
--
ALTER TABLE `medicamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `medicos`
--
ALTER TABLE `medicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `prescripciones`
--
ALTER TABLE `prescripciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`),
  ADD CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`usuario_registro_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `consultas`
--
ALTER TABLE `consultas`
  ADD CONSTRAINT `consultas_ibfk_1` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `expedientes`
--
ALTER TABLE `expedientes`
  ADD CONSTRAINT `expedientes_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `expediente_archivos`
--
ALTER TABLE `expediente_archivos`
  ADD CONSTRAINT `fk_expediente_archivos_expediente` FOREIGN KEY (`expediente_id`) REFERENCES `expedientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_expediente_archivos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD CONSTRAINT `medicos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `medicos_ibfk_2` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades` (`id`);

--
-- Filtros para la tabla `prescripciones`
--
ALTER TABLE `prescripciones`
  ADD CONSTRAINT `prescripciones_ibfk_1` FOREIGN KEY (`consulta_id`) REFERENCES `consultas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescripciones_ibfk_2` FOREIGN KEY (`medicamento_id`) REFERENCES `medicamentos` (`id`);

--
-- Filtros para la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD CONSTRAINT `sesiones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
