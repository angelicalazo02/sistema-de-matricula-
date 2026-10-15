-- ============================================================
-- 3DCL.sql  |  DCL (Data Control Language)
-- Controla PERMISOS: CREATE USER, GRANT, REVOKE
-- Ejecutar TERCERO, como root (phpMyAdmin).
-- Principio de mínimo privilegio: cada usuario solo tiene lo necesario.
-- ============================================================
USE mysql;

DROP USER IF EXISTS 'secretario'@'localhost';
DROP USER IF EXISTS 'asistente'@'localhost';
DROP USER IF EXISTS 'auth'@'localhost';

CREATE USER 'secretario'@'localhost' IDENTIFIED BY 'sec123';
CREATE USER 'asistente'@'localhost'  IDENTIFIED BY 'asi123';
CREATE USER 'auth'@'localhost'       IDENTIFIED BY 'auth123';

-- SECRETARIO: matricula (inserta) y descuenta cupos (actualiza)
GRANT SELECT, INSERT ON universidad.matriculas TO 'secretario'@'localhost';
GRANT SELECT, UPDATE ON universidad.cursos     TO 'secretario'@'localhost';
GRANT SELECT         ON universidad.estudiantes TO 'secretario'@'localhost';

-- ASISTENTE: SOLO lectura de las tablas académicas
GRANT SELECT ON universidad.estudiantes TO 'asistente'@'localhost';
GRANT SELECT ON universidad.cursos      TO 'asistente'@'localhost';
GRANT SELECT ON universidad.matriculas  TO 'asistente'@'localhost';

-- AUTH: solo puede leer usuarios para validar el login
GRANT SELECT ON universidad.usuarios TO 'auth'@'localhost';

FLUSH PRIVILEGES;
