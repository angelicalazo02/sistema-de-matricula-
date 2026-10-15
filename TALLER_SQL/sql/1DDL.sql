-- ============================================================
-- 1DDL.sql  |  DDL (Data Definition Language)
-- Define la ESTRUCTURA: CREATE DATABASE / CREATE TABLE
-- Traduce el modelo Entidad-Relación (docs/modelo_er.svg) a tablas.
-- Ejecutar PRIMERO en phpMyAdmin (pestaña SQL o Importar).
-- ============================================================

CREATE DATABASE IF NOT EXISTS universidad
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE universidad;

-- Entidad ESTUDIANTE
CREATE TABLE estudiantes (
    id_estudiante INT PRIMARY KEY AUTO_INCREMENT,
    nombre        VARCHAR(100) NOT NULL,
    email         VARCHAR(100) NOT NULL UNIQUE
);

-- Entidad CURSO
CREATE TABLE cursos (
    id_curso INT PRIMARY KEY AUTO_INCREMENT,
    nombre   VARCHAR(100) NOT NULL,
    cupos    INT NOT NULL DEFAULT 0,
    CONSTRAINT chk_cupos_no_negativos CHECK (cupos >= 0)
);

-- Entidad MATRICULA (resuelve la relación N:M entre Estudiante y Curso)
CREATE TABLE matriculas (
    id_matricula  INT PRIMARY KEY AUTO_INCREMENT,
    id_estudiante INT NOT NULL,
    id_curso      INT NOT NULL,
    fecha         DATE NOT NULL,
    CONSTRAINT uq_estudiante_curso UNIQUE (id_estudiante, id_curso),
    CONSTRAINT fk_matricula_estudiante FOREIGN KEY (id_estudiante)
        REFERENCES estudiantes(id_estudiante) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_matricula_curso FOREIGN KEY (id_curso)
        REFERENCES cursos(id_curso) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Usuarios del SISTEMA WEB (login por perfil). La contraseña se guarda con hash bcrypt.
CREATE TABLE usuarios (
    id_usuario     INT PRIMARY KEY AUTO_INCREMENT,
    username       VARCHAR(50)  NOT NULL UNIQUE,
    password_hash  VARCHAR(255) NOT NULL,
    nombre         VARCHAR(100) NOT NULL,
    rol            ENUM('secretario','asistente') NOT NULL
);
