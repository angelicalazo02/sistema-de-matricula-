-- ============================================================
-- 2DML.sql  |  DML (Data Manipulation Language)
-- Manipula los DATOS: INSERT (también UPDATE/DELETE/SELECT en la app)
-- Ejecutar SEGUNDO.
-- ============================================================
USE universidad;

INSERT INTO estudiantes (nombre, email) VALUES
('Juan Pérez',   'juan@uni.edu'),
('María López',  'maria@uni.edu'),
('Carlos Gómez', 'carlos@uni.edu');

INSERT INTO cursos (nombre, cupos) VALUES
('Bases de Datos',         5),
('Programación',           8),
('Ingeniería de Software', 3);

-- Usuarios del sistema web (contraseñas: sec123 y asi123; cámbielas en producción)
INSERT INTO usuarios (username, password_hash, nombre, rol) VALUES
('secretario', '$2y$12$hoIFqjDx0tuEgn1FiqeBvu2yliA25ND36Vh8OjkFnCPZ.SlcWu.cK', 'Secretario Académico', 'secretario'),
('asistente',  '$2y$12$LNBn3wLGeBFtx3FGfHxFx.cPe7O08cb89JoU8Qx8rtydaaV9ZzWSG', 'Asistente Académico',  'asistente');
