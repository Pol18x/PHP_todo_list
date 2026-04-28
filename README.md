# Gestión de Tareas (ToDo List) - Prueba Técnica

Aplicación desarrollada como parte de la **Entrevista Técnica para Desarrollador PHP de Be Call**.  
Consiste en un sistema de gestión de tareas con interfaz web, API REST, notificaciones vía Webhook y registro de logs.

---

## Tecnologías utilizadas

- **Lenguaje:** PHP 7.4 o superior  
- **Base de datos:** MySQL
- **Frontend:** HTML y CSS básico 
- **Arquitectura:** PHP con PDO para acceso seguro a la base de datos

---

## Instalación y configuración

### 1. Clonar el repositorio
Coloca el proyecto en tu servidor local, por ejemplo: `C:\xampp\htdocs\GESTION_TAREAS`
### 2. Crear la base de datos
- Accede a **phpMyAdmin**
- Crea una base de datos llamada: `todo_list`
- Importa el archivo: `todo_list.sql` (ubicado en la raíz del proyecto)
### 3. Configurar la conexión
Si es necesario, modifica las credenciales en: `conexion_bd.php`

---

## Uso de la aplicación

La aplicación permite realizar el ciclo completo de gestión de tareas (CRUD):

- **Acceso principal:** http://localhost/GESTION_TAREAS/lista_tareas.php
- **Crear tareas:**
Formulario para añadir tareas con:
  - Título  
  - Descripción  
  - Fecha de vencimiento  
  - Estado  

- **Configuración Webhook:**
La URL del webhook puede modificarse desde la sección **Configuración**.

- **Logs:**
El historial de acciones se guarda en un fichero local y puede consultarse desde **Ver Log**.

---

## API REST

La aplicación expone una API REST que:

- Envía y recibe datos en formato **JSON**
- Incluye un ejemplo de respuesta:

```json
{
  "saludo": "holi"
}

