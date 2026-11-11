# Guía de Uso y Compilación - App Android Veterinaria Digital

**Proyecto:** Veterinaria Digital (Componente Móvil Android)  
**Institución:** SENA - ADSO  
**Aprendiz:** Johan Muñoz  
**Tipo de Aplicación:** Cliente Nativo Android (Java / Android SDK / Material Design)  
**Ubicación del Código Fuente:** `C:\xampp\htdocs\veterinaria\android`  

---

## 1. Características Técnicas de la Aplicación Android

- **Arquitectura:** Cliente Nativo Android con integración Web Híbrida (`WebView` de alto rendimiento).
- **Lenguaje:** Java 17 / Android SDK API 24 a 34.
- **Componentes Nativos Implementados:**
  - `SwipeRefreshLayout` (Pull to refresh): Permite deslizar hacia abajo para refrescar datos.
  - `ProgressBar` de estado superior: Indicador visual en tiempo real de carga de datos.
  - Soporte de navegación con botón Atrás físico/virtual del dispositivo.
  - **Integración Nativa con WhatsApp:** Intercepta URLs `https://api.whatsapp.com` y abre directamente la app de WhatsApp del teléfono para enviar recordatorios a los clientes.
  - **Manejo de Errores de Red:** Pantalla amigable si el servidor está desconectado con botón de reintento.

---

## 2. Cómo Abrir y Generar el APK en Android Studio (Paso a Paso)

Dado que ya tienes instalado **Android Studio** en tu computador:

### Paso 1: Abrir el Proyecto
1. Abre **Android Studio**.
2. En la pantalla de bienvenida, selecciona **Open** (o en el menú superior: `File > Open...`).
3. Busca y selecciona la carpeta del proyecto Android:
   ```text
   C:\xampp\htdocs\veterinaria\android
   ```
4. Espera a que Android Studio sincronice las dependencias de Gradle.

### Paso 2: Generar el archivo instalador (.apk)
1. En el menú superior de Android Studio, haz clic en:
   ```text
   Build > Build Bundle(s) / APK(s) > Build APK(s)
   ```
2. Android Studio compilará el instalador en unos segundos.
3. Al finalizar, aparecerá una notificación abajo a la derecha: **`APK(s) generated successfully`**.
4. Haz clic en **`locate`** para abrir la carpeta donde quedó el archivo instalador:
   ```text
   C:\xampp\htdocs\veterinaria\android\app\build\outputs\apk\debug\app-debug.apk
   ```

### Paso 3: Probar e Instalar en tu Celular Android
1. Conecta tu teléfono Android al computador o envíate el archivo `app-debug.apk` por WhatsApp / Google Drive.
2. Abre el archivo en tu teléfono y selecciona **Instalar**.
3. Asegúrate de que el celular esté conectado a la **misma red Wi-Fi** que tu computador (IP actual del servidor: `10.1.174.177`).
4. Abre la app **Veterinaria Digital** en tu móvil y verás todo el sistema funcionando de manera fluida y responsiva.
