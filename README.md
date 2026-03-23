# 🌳 Apadrina un Árbol | UTSC

Sistema web desarrollado para la Universidad Tecnológica de Santa Catarina (UTSC) que permite a la comunidad estudiantil y docente apadrinar o realizar donaciones para la conservación de especies arbóreas nativas del campus.

## 🏗️ Arquitectura del Proyecto

El proyecto sigue una arquitectura modular con una clara separación de responsabilidades:

* **`/pages/`**: Contiene las vistas principales del frontend (Catálogo, Perfil de Usuario, Detalles del Árbol). No contienen lógica compleja de base de datos.
* **`/includes/`**: Módulos de backend reutilizables (Conexión a BD, validaciones de login, endpoints de búsqueda REST, Header y Footer).
* **`/assets/`**: Recursos estáticos (CSS con Flexbox/Grid, imágenes de los árboles, scripts de UI).
* **`/libs/`**: Librerías de terceros (FPDF para la generación dinámica de certificados).

## ✨ Funcionalidades Principales

1. **Catálogo Dinámico:** Búsqueda en tiempo real de especies nativas (Anacahuita, Ébano, Duraznillo) consumiendo un endpoint JSON (`buscar_arboles.php`).
2. **Sistema de Autenticación:** Registro e inicio de sesión seguro con protección de rutas.
3. **Flujo de Adopción y Donación:** Modales interactivos con validación de formularios, selector de métodos de pago y autocompletado de máscaras (ej. tarjetas y fechas).
4. **Certificados PDF:** Generación al vuelo de diplomas de "Guardián" usando FPDF, con folio y sello digital (Hash MD5) de autenticidad.
5. **Panel de Usuario:** Dashboard personal con el historial de árboles apadrinados extraídos mediante consultas relacionales (`JOIN`).

## 🛡️ Seguridad Implementada

* **Prevención de SQL Injection:** Uso estricto de Consultas Preparadas (`Prepared Statements`) en todos los formularios críticos (Login, Registro y Procesamiento de Adopción).
* **Validación de Sesiones:** Restricción de acceso a perfiles y endpoints solo a usuarios autenticados (`$_SESSION`).
* **Manejo Silencioso de Errores:** Registro de fallos de base de datos en los logs del servidor (`error_log`) sin exponer vulnerabilidades al usuario final.

## 🚀 Instrucciones de Instalación (Entorno Local)

1. **Requisitos:** Servidor Apache y MySQL (XAMPP/WAMP recomendados) con PHP 7.4 o superior.
2. **Clonar el repositorio:** Ubica la carpeta del proyecto dentro de `htdocs` (ej. `C:\xampp\htdocs\Arbol`).
3. **Base de Datos:** * Abre phpMyAdmin y crea una base de datos llamada `arbolbd`.
   * Importa el archivo `.sql` (si se proporciona) o asegúrate de tener las tablas `usuarios`, `arboles` y `apadrinamientos` configuradas.
4. **Configuración:** Verifica que las credenciales en `includes/db.php` coincidan con tu servidor local (por defecto: usuario `root`, contraseña en blanco).
5. **Ejecución:** Abre tu navegador y visita `http://localhost/Arbol/index.php`.

---
*Desarrollado por el equipo "Apadrina un Arbol" - Proyecto de Desarrollo Web Profesional.*