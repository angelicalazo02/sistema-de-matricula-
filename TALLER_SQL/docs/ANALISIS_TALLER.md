# Análisis del taller: Sistema de Matrícula (MVC + SQL)

## 1. Requerimiento
- El **secretario académico** matricula estudiantes; el **asistente** solo consulta.
- Al matricular se deben cumplir **dos operaciones atómicas**:
  1. Crear el registro de matrícula (1 estudiante).
  2. Descontar un cupo del curso.
- Si falla cualquiera de las dos, **ninguna** debe aplicarse.

## 2. Modelo Entidad-Relación
Ver `docs/modelo_er.svg`. Entidades: **Estudiante**, **Matrícula** y **Curso**.
La relación N:M entre estudiante y curso se resuelve con la entidad Matrícula (dos relaciones 1:N).

| Entidad | Atributos | Clave primaria |
|---|---|---|
| Estudiante | id_estudiante, nombre, email | id_estudiante |
| Curso | id_curso, nombre, cupos | id_curso |
| Matrícula | id_matricula, id_estudiante (FK), id_curso (FK), fecha | id_matricula |

## 3. Preguntas del código (comentarios "¿El nombre del archivo corresponde…?")

| Archivo original | Contenido real | Lenguaje | Corrección |
|---|---|---|---|
| `1DCL.sql` | CREATE DATABASE / CREATE TABLE | **DDL** | Renombrado a `1DDL.sql` |
| `2DDL.sql` | INSERT | **DML** | Renombrado a `2DML.sql` |
| `3DCL.sql` | CREATE USER / GRANT | **DCL** | Correcto |

Espacios en blanco completados: nombre de la base de datos (`universidad`) en `database.php`, usuario `secretario` y usuario `asistente` en `3DCL.sql`.

## 4. Análisis de la solución

**Transacción (TCL):** `Matricula::matricular()` ejecuta `beginTransaction()`, el INSERT en matrículas y el UPDATE de cupos, y termina con `commit()`. Si cualquier paso lanza una excepción, el `catch` ejecuta `rollBack()`. Así se cumple la atomicidad: o se aplican las dos operaciones o ninguna.

**Problema detectado:** la verificación de cupos (SELECT) y el descuento (UPDATE) estaban separados. Dos secretarios podían leer "1 cupo" al mismo tiempo y matricular a dos estudiantes en el último cupo.
**Corrección:** `SELECT ... FOR UPDATE` bloquea la fila del curso durante la transacción, y el UPDATE incluye `AND cupos > 0` con verificación de `rowCount()`.

**Seguridad (DCL):** cada perfil se conecta con su propio usuario de MySQL. El asistente solo tiene SELECT, por lo que aunque se modificara el código no podría insertar matrículas. La tabla `usuarios` solo es accesible por el usuario `auth`.

**Riesgos a tener en cuenta:** las credenciales de MySQL están en `config/database.php`. Para un entorno real deberían guardarse fuera del código o en variables de entorno.

## 5. Login por perfil (requerimiento 3)
| Usuario | Contraseña | Perfil | Puede |
|---|---|---|---|
| secretario | sec123 | Secretario | Matricular y consultar |
| asistente | asi123 | Asistente | Solo consultar |

Flujo: `AuthController` valida con `password_verify()` (bcrypt), guarda el perfil en la sesión y cada controlador llama a `requerirRol()`. Un asistente que intente entrar a `MatriculaController` recibe **403 Acceso denegado**.
