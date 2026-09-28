# E-commerce MVC (PHP + SQLite) - Scaffold (sin frameworks)

Este repo contiene un scaffold MVC ligero en PHP sin frameworks, con SQLite y autenticación local básica (usuario admin seed).

Pasos rápidos:
1. Copia `.env.example` a `.env` y actualiza BASE_URL si es necesario.
2. Crea la carpeta `data/` y dale permisos de escritura: `mkdir data && chmod 0777 data`.
3. Ejecuta `php src/init_db.php` desde la raíz del proyecto para crear `data/database.sqlite` y seed inicial.
4. Levanta el servidor desde la carpeta `public`:
   `php -S localhost:8000 -t public`
5. Abre `http://localhost:8000`

Credenciales de admin seed (solo local):
- email: root@local
- username/display_name: root
- password: admin

Notas de seguridad:
- No incluí credenciales de Google OAuth. Añadiremos OAuth más adelante.
- En producción: usar HTTPS, proteger sesiones, CSRF, y guardar secretos fuera del repo.