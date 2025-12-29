# Store - MVP de Tienda de Recargas (Mobile Legends)

Este es un sistema web completo para la gestión de recargas de diamantes de Mobile Legends, construido con **Flight PHP**, **MySQL** y **Tailwind CSS**.

## 🚀 Características

- **Arquitectura MVC**: Estructura limpia y organizada.
- **Diseño Gaming**: Interfaz moderna con colores pasteles y responsive.
- **Métodos de Pago**:
  - **Pago Móvil (Venezuela)**: Reporte manual con subida de comprobante.
  - **Binance Pay**: Integración lista para API (simulada en MVP).
- **Panel Administrativo**: Gestión completa de pedidos, cambio de estados y visualización de comprobantes.
- **Seguridad**: Protección de rutas, hash de contraseñas y validación de datos.

## 🛠️ Requisitos

- PHP 8.0 o superior.
- MySQL / MariaDB.
- Composer.

## 📦 Instalación

1. **Clonar el proyecto** o descargar los archivos.
2. **Instalar dependencias**:
   ```bash
   composer install
   ```
3. **Configurar la Base de Datos**:
   - Importar el archivo `sql/database.sql` en tu servidor MySQL.
4. **Configurar Variables de Entorno**:
   - Renombrar `.env.example` a `.env` (o crear uno nuevo).
   - Configurar tus credenciales de DB y API de Binance.
5. **Configurar el Servidor**:
   - Apuntar el *Document Root* de tu servidor a la carpeta `public/`.
   - Asegurarse de que el módulo `mod_rewrite` de Apache esté activo.

## 🔐 Acceso Admin

- **URL**: `/admin/login`
- **Usuario por defecto**: `admin`
- **Contraseña por defecto**: `admin123` (Se recomienda cambiarla en producción).

## 📄 Aviso Legal

Este sitio no está afiliado a Moonton. Mobile Legends: Bang Bang es marca registrada de Moonton.
