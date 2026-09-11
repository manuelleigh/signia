# API B2B - Gestión de Empresas

La API de Signia permite a las Agencias (SaaS) automatizar el registro y activación de empresas RUC directamente desde su propio software, sin necesidad de usar el panel web de Signia.

## Autenticación
Todos los endpoints de esta sección requieren autenticación mediante un Token API.
`Authorization: Bearer {api_token}`

---

## 1. Listar Empresas
Obtiene todas las empresas asociadas a la cuenta de tu agencia.

**Endpoint:** `GET /api/v1/empresas`

**Respuesta Exitosa (200 OK):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "ruc": "20123456789",
            "business_name": "Mi Empresa SAC",
            "environment": "demo",
            "engine_type": "qpse",
            "qpse_username": "MIUSUARIO",
            "sol_user": null,
            "created_at": "2026-09-10T15:30:00.000000Z"
        }
    ]
}
```

---

## 2. Crear Empresa
Registra una nueva empresa en el sistema y provisiona sus credenciales en el Motor PSE (si aplica).

**Endpoint:** `POST /api/v1/empresa/crear`

**Body:**
```json
{
    "ruc": "20123456789",
    "business_name": "Mi Empresa SAC",
    "environment": "demo",
    "engine_type": "qpse"
}
```
- `environment` (opcional): "demo" o "production". Por defecto es "demo".
- `engine_type` (requerido): "qpse" (Firma delegada) o "native" (Firma propia con certificado).

**Respuesta Exitosa (201 Created):**
```json
{
    "success": true,
    "message": "Empresa registrada satisfactoriamente",
    "data": {
        "ruc": "20123456789",
        "business_name": "Mi Empresa SAC",
        "environment": "demo",
        "engine_type": "qpse",
        "username": "USUARIO_GENERADO",
        "password": "PASSWORD_GENERADO"
    }
}
```

---

## 3. Pasar Empresa a Producción
Cambia el entorno de una empresa de demo a producción. Al hacerlo, Signia la activará formalmente en el proveedor de firmas (si es `qpse`).

**Endpoint:** `POST /api/v1/empresa/produccion`

**Body:**
```json
{
    "ruc": "20123456789"
}
```

**Respuesta Exitosa (200 OK):**
```json
{
    "success": true,
    "message": "Empresa actualizada a producción exitosamente.",
    "data": {
        "ruc": "20123456789",
        "environment": "production"
    }
}
```

---

## 💡 Propuestas de 5 Nuevos Endpoints B2B

Para hacer la API B2B de Signia mucho más robusta y útil para los desarrolladores SaaS (como FacturaYa), propongo incorporar los siguientes endpoints:

### 1. `GET /api/v1/saldo` (Consultar Saldo / Bolsa)
Permite a las agencias consultar programáticamente cuántas firmas (Nativas y PSE) les quedan en su bolsa. Muy útil para que FacturaYa envíe alertas automáticas a sus administradores cuando estén por quedarse sin firmas.

### 2. `GET /api/v1/documents?ruc=XXX` (Historial de Comprobantes)
Permite a las agencias descargar el listado de todos los documentos emitidos (con su estado, links al XML, CDR y PDF). Útil para que reconstruyan el panel de control directamente dentro de su propio software y para conciliaciones.

### 3. `POST /api/v1/empresa/certificado` (Subida Automática de Certificado)
Para los clientes que usan `engine_type: native`, este endpoint les permitiría subir su archivo `.p12` y contraseña directamente desde la API, sin que el administrador de la agencia tenga que entrar al panel de Signia a cargarlo manualmente.

### 4. `POST /api/v1/documents/resend` (Reenvío a SUNAT)
Para reintentar procesar facturas que quedaron en estado de excepción, timeout, o con tickets pendientes. Permite que el propio ERP del cliente inicie el proceso de reintento.

### 5. `DELETE /api/v1/empresa` (Baja de Empresa)
Permite a una agencia suspender o dar de baja a una empresa RUC si este cliente dejó de pagarles su mensualidad en el SaaS, bloqueándoles inmediatamente el acceso a facturación desde Signia.
