# Sistema de Matrícula - Universidad del Valle
Aplicación web **PHP (MVC) + MySQL** para XAMPP, con login por perfil.

## Estructura
```
TALLER_SQL/
├── index.php                  → redirige al login
├── config/
│   ├── database.php           → conexión PDO (XAMPP) por perfil
│   └── auth.php               → sesiones, roles y CSRF
├── controllers/
│   ├── AuthController.php     → login / logout
│   ├── MatriculaController.php→ solo secretario
│   └── ConsultaController.php → secretario y asistente
├── models/                    → Estudiante, Curso, Matricula, Usuario
├── views/                     → login, formulario, resultado, consultar
│   └── partials/nav.php       → barra superior según perfil
├── css/styles.css
├── sql/
│   ├── 1DDL.sql               → estructura (CREATE)
│   ├── 2DML.sql               → datos (INSERT)
│   └── 3DCL.sql               → usuarios y permisos (GRANT)
└── docs/
    ├── modelo_er.svg          → diagrama Entidad-Relación
    └── ANALISIS_TALLER.md     → respuestas y análisis del taller
```

## Paso 1 · Instalar XAMPP
1. Descargue XAMPP desde https://www.apachefriends.org (versión PHP 8.x).
2. Instale con Apache, MySQL y phpMyAdmin seleccionados.
3. Abra el **Panel de control de XAMPP** e inicie **Apache** y **MySQL** (deben quedar en verde).

## Paso 2 · Copiar el proyecto
1. Descomprima `TALLER_SQL.zip`.
2. Copie la carpeta `TALLER_SQL` dentro de `C:\xampp\htdocs\`.

## Paso 3 · Crear la base de datos (en orden)
Abra `http://localhost/phpmyadmin` → pestaña **SQL** y pegue el contenido de cada archivo, o use **Importar**:
1. `sql/1DDL.sql`  → crea la base `universidad` y sus tablas.
2. `sql/2DML.sql`  → inserta estudiantes, cursos y usuarios.
3. `sql/3DCL.sql`  → crea los usuarios de MySQL y sus permisos.

> Si `3DCL.sql` falla con "user already exists", es normal: el script ya borra y vuelve a crear los usuarios.

## Paso 4 · Abrir la aplicación
Visite `http://localhost/TALLER_SQL/`.

| Usuario | Contraseña | Perfil |
|---|---|---|
| secretario | sec123 | Matricula y consulta |
| asistente | asi123 | Solo consulta |

## Paso 5 · Abrir el proyecto en Visual Studio Code
1. **Archivo → Abrir carpeta** y seleccione `C:\xampp\htdocs\TALLER_SQL`.
2. Acepte la recomendación de extensiones (Intelephense para PHP, SQLTools para MySQL).
3. Para editar sin copiar archivos, puede trabajar directamente en `htdocs` y recargar el navegador.

## Conexión a la base de datos (`config/database.php`)
```php
const HOST    = "localhost";
const PORT    = 3306;          // puerto por defecto de XAMPP
const DB_NAME = "universidad";
```
Cada perfil usa su usuario de MySQL:
```php
Database::conectar("secretario");   // INSERT en matrículas, UPDATE en cupos
Database::conectar("asistente");    // solo SELECT
Database::conectar("auth");         // solo SELECT en usuarios
```

## Solución de problemas
| Síntoma | Causa y solución |
|---|---|
| "Error de conexión… Access denied" | No se ejecutó `3DCL.sql`, o los usuarios no existen. |
| "Unknown database 'universidad'" | No se ejecutó `1DDL.sql`. |
| MySQL no inicia en XAMPP | El puerto 3306 está ocupado (por ejemplo, por un servicio de MySQL instalado). Detenga ese servicio o cambie el puerto en XAMPP y en `PORT`. |
| Página en blanco | Active `display_errors` en `php.ini` o revise el log de Apache en el panel de XAMPP. |
| Login no acepta la clave | Verifique que `2DML.sql` se ejecutó completo (tabla `usuarios` con 2 filas). |

## Pruebas recomendadas
1. Entre como **asistente** e intente abrir `MatriculaController.php` → debe mostrar 403.
2. Entre como **secretario**, matricule un estudiante y verifique que el cupo baje en 1.
3. Intente matricular al mismo estudiante dos veces en el mismo curso → debe rechazarlo.
4. Matricule en un curso con 0 cupos → debe aparecer deshabilitado en el formulario.
