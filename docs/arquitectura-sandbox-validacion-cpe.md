# Sandbox de validación CPE para Signia

## Conclusión ejecutiva

Signia puede ofrecer un entorno demo que valide facturas, boletas de venta, notas de crédito y notas de débito sin consumir operaciones de QPSE. El producto debe presentarse como un **simulador de prevalidación compatible con reglas SUNAT**, no como una aceptación oficial de SUNAT ni como un OSE acreditado.

El sandbox puede reproducir con alta fidelidad:

- estructura XML UBL 2.1 y namespaces;
- formatos, cardinalidades y obligatoriedad condicional;
- firma XMLDSig y coherencia del certificado;
- catálogos SUNAT;
- cálculos tributarios, redondeos y consistencia entre líneas y totales;
- reglas cruzadas entre tipo de operación, afectación, tributos, moneda, identidad y documento modificado;
- duplicidad, correlatividad e historial dentro del propio sandbox;
- códigos de error y observación de la matriz publicada por SUNAT;
- una respuesta de prueba equivalente funcionalmente a un CDR, marcada inequívocamente como simulada.

No puede garantizar por sí solo que producción aceptará el documento. Algunas reglas dependen del estado en tiempo real de sistemas externos: RUC y condición del contribuyente, autorización del emisor o PSE, alta de series/locales, documentos previamente recibidos por SUNAT/OSE, tipo de cambio oficial, vigencias de padrones, indisponibilidad del receptor y reglas internas no publicadas. Estas comprobaciones deben devolverse como `verificada`, `simulada`, `no_verificable` o `requiere_produccion`, nunca como una aceptación real.

## Fuente canónica y dimensión del alcance

La fuente principal es la matriz **Reglas de validación CPE actualizada al 26/08/2026**, publicada por SUNAT. El análisis del archivo arroja el siguiente volumen para la fase inicial:

| Grupo | Filas de reglas/estructura | Retornos ERROR | Retornos OBSERV | Códigos distintos aproximados |
|---|---:|---:|---:|---:|
| General | 18 | 18 | 0 | 17 |
| Firma digital | 26 | 25 | 0 | 25 |
| Factura UBL 2.1 | 926 | 488 | 322 | 380 |
| Boleta UBL 2.1 | 832 | 368 | 341 | 335 |
| Nota de crédito UBL 2.1 | 488 | 309 | 149 | 248 |
| Nota de débito UBL 2.1 | 468 | 285 | 152 | 238 |

Las cifras no equivalen directamente a funciones independientes: existen encabezados, reglas informativas, condiciones alternativas y códigos repetidos. Sirven para dimensionar la implementación y demostrar que conviene compilar las reglas desde datos versionados, en vez de codificarlas todas en controladores PHP.

La distribución oficial descargable incluye además 101 archivos XSD y 15 transformaciones XSL. Para los cuatro documentos de esta fase existen validadores XSL específicos de factura, boleta, nota de crédito y nota de débito. Los XSD/XSL publicados no sustituyen la matriz vigente: la página oficial fecha el paquete XSD al 28/02/2022, el XSL al 06/09/2022 y la matriz de reglas al 26/08/2026.

## Regla fundamental de versión

Cada regla debe tener, como mínimo:

```text
rule_id
sunat_code
document_type
severity
effective_from
effective_until
source_version
xpath
condition
dependencies
message
verification_scope
```

El motor seleccionará el paquete según la fecha de evaluación y conservará la versión usada en cada ejecución. Esto es indispensable porque el control de cambios oficial incluye reglas nuevas, eliminadas, modificadas y postergadas. La matriz del 26/08/2026 contiene ajustes cuya vigencia fue trasladada al 01/01/2027, incluidos cambios para notas de crédito y débito.

Modos recomendados:

- `current`: reglas vigentes en la fecha del servidor.
- `production_date`: reglas vigentes para una fecha indicada.
- `next`: reglas anunciadas con vigencia futura, útil para anticipar migraciones.
- `compare`: informa qué resultado cambiaría entre el paquete actual y el siguiente.

## Capas de validación

### 1. Transporte y envoltorio

Validar tamaño, codificación, ZIP, cantidad y nombre de archivos, ausencia de rutas maliciosas y coincidencia entre nombre, RUC, tipo, serie y correlativo. La matriz general contempla, entre otros, autenticación, perfil del remitente y formato `RRRRRRRRRRR-TT-SSSS-NNNNNNNN.zip`.

En el API JSON de Signia esta capa debe validar el payload y el XML generado. Si se ofrece un endpoint de XML crudo, debe aceptar exactamente un XML o ZIP y aplicar los mismos límites del flujo productivo.

### 2. XML seguro y XSD

- desactivar DTD y entidades externas para impedir XXE;
- imponer límites de profundidad, nodos, atributos y longitud;
- verificar XML bien formado y UTF-8;
- resolver únicamente esquemas locales fijados por versión;
- validar el documento contra el XSD aplicable;
- validar elemento raíz, namespaces, orden, cardinalidad y tipos.

Esta capa debe terminar antes de ejecutar XPath o cálculos de negocio.

### 3. Perfil UBL/SUNAT

Validar `UBLVersionID=2.1`, `CustomizationID=2.0`, identificador, fechas, moneda, emisor, adquirente, líneas, referencias y atributos de listas. Los cuatro documentos no comparten la misma raíz:

- Factura y boleta: `Invoice`, diferenciadas por `InvoiceTypeCode` 01 y 03 y sus series.
- Nota de crédito: `CreditNote`, tipo 07.
- Nota de débito: `DebitNote`, tipo 08.

La guía oficial señala que la numeración debe conservar correlatividad. Para factura la serie usual alfanumérica comienza con `F`; para boleta comienza con `B`. Las notas deben mantener formato y referencia coherentes con el documento afectado.

### 4. Firma XMLDSig

La matriz de firma contiene reglas específicas para `Signature`, `SignedInfo`, algoritmos, referencias, digest, certificado y valor de firma. El sandbox debe comprobar:

- existencia y ubicación de la firma;
- referencia al documento correcto;
- canonicalización y transformaciones permitidas;
- digest recalculado;
- firma criptográfica válida;
- certificado X.509 vigente para la fecha de emisión;
- RUC del certificado coherente con el emisor cuando pueda extraerse de forma confiable;
- cadena y uso de clave cuando se disponga de la información necesaria.

Debe soportar dos recorridos: validar un XML ya firmado o firmar el XML con el certificado almacenado y validar inmediatamente el resultado.

### 5. Catálogos

Los catálogos deben almacenarse como conjuntos versionados y referenciarse por código, no incrustarse en condicionales. En esta fase aparecen, entre otros:

- 01: tipo de documento;
- 02: monedas;
- 03: unidades de medida;
- 05: tributos;
- 06: documentos de identidad;
- 07: afectación del IGV;
- 08: sistema ISC;
- 09: motivos de nota de crédito;
- 10: motivos de nota de débito;
- 12: documentos relacionados;
- 16: tipo de precio;
- 17/51: tipo de operación;
- 18: modalidad de transporte cuando corresponda;
- 52: leyendas;
- 53: cargos/descuentos;
- 54: bienes y servicios sujetos a detracción.

Cada valor debe evaluarse contra la versión vigente y contra su contexto. Que un código exista no implica que sea válido para cualquier documento u operación.

### 6. Identidad y numeración

- RUC del emisor con 11 dígitos, checksum válido y coincidencia con archivo/cuenta;
- tipo de documento del emisor igual a 6;
- documento del adquirente con formato según catálogo 06;
- requisitos distintos para factura y boleta;
- serie compatible con tipo de comprobante;
- correlativo con longitud y rango permitidos;
- ID del XML coincidente con el nombre del archivo;
- no duplicidad dentro del tenant y entorno;
- razón social, nombre y domicilio con reglas de presencia, longitud y caracteres.

Las consultas de estado/habido y autorización real deben usar snapshots oficiales autorizados o declararse no verificables. No conviene consultar SUNAT en línea en cada prueba si el objetivo es un sandbox autónomo.

### 7. Fechas y estado temporal

- formato y zona horaria;
- fecha de emisión no futura fuera de tolerancia;
- plazo máximo de envío según tipo y reglas vigentes;
- vencimiento coherente con la emisión;
- documento referenciado anterior o compatible;
- vigencia del certificado;
- vigencia de catálogo, tasa y regla;
- consistencia entre forma de pago, cuotas y vencimientos.

### 8. Importes y aritmética tributaria

Todos los cálculos deben realizarse con decimal exacto, nunca con `float`. El motor debe verificar por línea y globalmente:

- cantidad por valor unitario;
- valor de venta;
- precio unitario con impuestos;
- descuentos y cargos por línea;
- descuentos y cargos globales;
- base imponible por tributo;
- IGV, IVAP, ISC y otros tributos;
- operaciones gravadas, exoneradas, inafectas y gratuitas;
- sumatoria de impuestos;
- total de valor de venta;
- total precio de venta;
- importe pagable;
- moneda uniforme en todos los importes;
- tolerancias y redondeos definidos por cada regla;
- detracción, anticipos y percepción cuando el tipo de operación los active.

Cada fallo debe conservar los operandos calculados y esperados para que el cliente entienda cómo corregirlo.

### 9. Factura y boleta

Además de las capas compartidas, deben cubrirse perfiles por operación: venta interna, exportación, anticipos, operaciones gratuitas, detracción, crédito y contado. Cuando la venta es a crédito deben validarse monto pendiente, cuotas, fechas y reconciliación con el total. La guía oficial vigente de preguntas frecuentes distingue contado —pago en la fecha de emisión— de crédito —pago total o parcial posterior— y exige datos adicionales para este último.

La boleta debe aplicar las restricciones propias del adquirente consumidor y de las operaciones que requieren identificación. No debe reutilizarse el conjunto de reglas de factura cambiando únicamente el código 01 por 03.

### 10. Notas de crédito y débito

Validar:

- documento afectado, tipo, serie, correlativo y moneda;
- existencia del documento base en el sandbox;
- emisor y adquirente coherentes con el documento base;
- motivo según catálogo 09 o 10;
- sustento obligatorio;
- límites del motivo: anulación, corrección, descuento, devolución, aumento, penalidad, intereses u otros según catálogo vigente;
- importes y líneas compatibles con el tipo de modificación;
- no modificar documentos ajenos o de otro entorno;
- acumulación de notas y saldo modificable restante;
- reglas temporales y estados previos.

Si el comprobante base solo existe en producción y Signia no dispone de evidencia verificable, el resultado debe quedar como `conditional_pass`, indicando que la referencia no pudo comprobarse.

### 11. Reglas con estado o fuentes externas

Estas reglas necesitan adaptadores de datos versionados:

- padrón de contribuyentes y estado del RUC;
- relación PSE-emisor;
- series/locales autorizados;
- tipos de cambio;
- parámetros y plazos SUNAT;
- comprobantes previamente aceptados, anulados o modificados;
- duplicados recibidos por SUNAT/OSE.

Cada adaptador debe devolver valor, fuente, fecha de actualización y nivel de confianza. Una ausencia de datos no debe convertirse silenciosamente en aprobación.

## Modelo de resultado

Respuesta propuesta:

```json
{
  "environment": "signia-sandbox",
  "official_acceptance": false,
  "document_id": "20123456789-01-F001-123",
  "rule_pack": "SUNAT-CPE-2026-08-26",
  "evaluated_at": "2026-09-09T10:30:00-05:00",
  "status": "rejected",
  "summary": {
    "errors": 1,
    "observations": 2,
    "passed": 487,
    "not_verifiable": 3
  },
  "results": [
    {
      "code": "2074",
      "severity": "error",
      "scope": "published_rule",
      "path": "/Invoice/cbc:UBLVersionID",
      "message": "UBLVersionID - La versión del UBL no es correcta",
      "expected": "2.1",
      "actual": "2.0",
      "source_version": "2026-08-26"
    }
  ],
  "simulated_cdr": {
    "generated": true,
    "legal_validity": false,
    "watermark": "SIGNIA SANDBOX - SIN VALIDEZ TRIBUTARIA"
  }
}
```

Estados sugeridos:

- `accepted_simulated`: no hay errores publicados aplicables;
- `accepted_with_observations`: no hay errores, pero sí observaciones;
- `rejected`: existe al menos un error;
- `conditional_pass`: no hay error local, pero faltan verificaciones externas esenciales;
- `invalid_request`: no se pudo procesar de forma segura.

El CDR simulado debe estar separado del CDR real en almacenamiento, dominio, base de datos y UI. Debe incluir marcas visibles y metadatos propios. No se debe usar una firma o presentación que permita confundirlo con una constancia emitida por SUNAT/OSE.

## Arquitectura propuesta para Laravel

```text
API Sandbox
  -> Ingesta segura y normalización
  -> Detector de documento/versión
  -> XSD Validator
  -> XMLDSig Validator
  -> Rule Pack Resolver (fecha + documento + modo)
  -> Rule Engine
       -> XPath/estructura
       -> catálogos
       -> decimales/tributos
       -> identidad/numeración
       -> historial sandbox
       -> adaptadores externos/snapshots
  -> Agregador de resultados
  -> CDR simulado + reporte pedagógico
  -> Persistencia de evidencia y trazabilidad
```

Componentes recomendados:

```text
app/Domain/Cpe/Validation/
  Contracts/
  Xml/
  Signature/
  Rules/
  Catalogs/
  Calculations/
  ExternalData/
  Results/
  SimulatedCdr/

resources/cpe/
  rule-packs/2026-08-26/
  rule-packs/2027-01-01/
  catalogs/
  xsd/
  xsl/
  fixtures/
```

Las reglas deben compilarse durante despliegue a una representación ejecutable y cacheable. No se recomienda interpretar directamente el Excel en cada solicitud. Los cálculos delicados conviene expresarlos como clases probadas; las reglas declarativas pueden almacenarse en JSON/YAML generado desde la matriz con revisión humana.

## Fidelidad y seguridad comercial

Signia debería mostrar dos indicadores distintos:

1. **Cobertura local:** porcentaje de reglas publicadas que el motor ejecutó.
2. **Verificación externa:** qué dependencias reales fueron confirmadas, simuladas o no comprobadas.

No debe mostrarse “100% listo para producción” únicamente porque el XML pasó. Un mensaje correcto sería: “Pasó 100% de las reglas publicadas aplicables; quedan 3 comprobaciones dependientes de SUNAT/OSE”.

El modo demo no debe descontar firmas de producción. Puede tener límites separados por agencia para prevenir abuso y pruebas de carga. Todos los recursos deben usar identificadores y credenciales diferentes a producción.

## Estrategia de pruebas

- prueba positiva por documento y perfil de operación;
- una prueba negativa por cada código de retorno implementado;
- combinaciones por pares para campos condicionales;
- pruebas de frontera de longitud, decimal, fecha y tolerancia;
- XML malicioso: XXE, entity expansion, ZIP bomb y paths;
- mutación de XML firmado;
- certificados vencidos, incorrectos o sin clave;
- duplicados, correlatividad y concurrencia;
- notas contra documentos inexistentes, anulados o totalmente modificados;
- pruebas diferenciales contra XSD/XSL oficiales;
- muestreo controlado contra beta SUNAT y, cuando exista autorización, contra QPSE beta;
- golden files de request, resultado de reglas y CDR simulado;
- regresión por cada nueva versión de la matriz.

La comparación con SUNAT debe tratar discrepancias como incidentes de reglas. No conviene hacerla en cada consulta del cliente: se ejecuta en un proceso interno controlado de certificación del paquete.

## Plan de implementación

### Etapa 1: base reproducible

- congelar XSD, XSL, matriz y catálogos oficiales con checksum;
- crear importador de matriz y normalización de reglas;
- implementar parser XML seguro y validador XSD;
- definir resultado estándar y trazabilidad;
- separar por completo demo y producción.

### Etapa 2: factura

- firma XMLDSig;
- perfil UBL/SUNAT;
- catálogos e identidad;
- motor decimal y tributario;
- venta interna gravada, exonerada, inafecta y gratuita;
- contado, crédito, descuentos, cargos, anticipos y detracción;
- corpus de errores y observaciones.

### Etapa 3: boleta

- reglas propias de identidad y consumidor;
- perfiles y tributos;
- pruebas específicas, sin heredar ciegamente factura.

### Etapa 4: notas

- referencia e historial de documentos;
- motivos 09/10;
- límites por modificación y acumulación;
- reglas de moneda, identidad, fechas y estados.

### Etapa 5: homologación interna

- prueba diferencial controlada;
- tablero de cobertura por código;
- revisión de discrepancias;
- paquete actual y futuro;
- publicación de una matriz de capacidades y limitaciones para clientes.

## Criterio para pasar a producción

Un RUC puede mostrarse como candidato a producción cuando:

- completó casos positivos obligatorios para los tipos de operación que usará;
- no tiene errores en el paquete vigente;
- revisó y aceptó las observaciones;
- validó firma/certificado;
- probó notas contra documentos base;
- pasó idempotencia, reintentos y duplicidad;
- ejecutó al menos una prueba controlada contra el receptor real o beta aplicable;
- reconoce expresamente las comprobaciones externas que el sandbox no puede garantizar.

La decisión final no debe depender de un porcentaje único. Debe existir una lista de capacidades habilitadas por RUC, documento y tipo de operación.

## Fuentes

1. SUNAT. [Guías y manuales de Comprobantes de Pago Electrónicos](https://cpe.sunat.gob.pe/guias-y-manuales). Incluye la matriz de reglas actualizada al 26/08/2026, XSD 2.1, XSL 2.1, guías UBL 2.1 y manuales técnicos.
2. SUNAT. [Servicio Beta para realizar pruebas UBL 2.1](https://cpe.sunat.gob.pe/noticias/servicio-beta-para-realizar-pruebas-ubl-21). Define que la beta valida estructuras y no verifica consistencia de datos; también restringe pruebas de estrés y envíos masivos.
3. SUNAT. [Guía de elaboración XML de Factura Electrónica UBL 2.1](https://cpe.sunat.gob.pe/sites/default/files/inline-files/guia%2Bxml%2Bfactura%2Bversion%202-1%2B1%2B0%20%282%29_0%20%282%29.pdf).
4. SUNAT. [Guía de elaboración XML de Boleta de Venta Electrónica UBL 2.1](https://cpe.sunat.gob.pe/sites/default/files/inline-files/guia%2Bxml%2Bboleta%2Bversion%202-1%2B1%2B0_0_0%20%282%29.pdf).
5. SUNAT. [Guía de elaboración XML de Nota de Crédito Electrónica UBL 2.1](https://cpe.sunat.gob.pe/sites/default/files/inline-files/guia%2Bxml%2Bnota%20de%20cr%C3%A9dito%2Bversion%202-1%2B1%2B0_0_0%20%282%29.pdf).
6. SUNAT. [Guía de elaboración XML de Nota de Débito Electrónica UBL 2.1](https://cpe.sunat.gob.pe/sites/default/files/inline-files/guia%2Bxml%2Bnota%20de%20d%C3%A9bito%2Bversion%202-1%2B1%2B0_0_0%20%282%29.pdf).
7. SUNAT. [Manual del Programador del SEE del contribuyente](https://cpe.sunat.gob.pe/sites/default/files/inline-files/manual_programador%20%281%29.pdf).
8. SUNAT. [Normas legales de Comprobantes de Pago Electrónicos](https://cpe.sunat.gob.pe/node/98).
9. SUNAT. [Preguntas frecuentes de Comprobantes de Pago Electrónicos](https://cpe.sunat.gob.pe/informacion_general/preguntas_frecuentes).

## Nota de mantenimiento

La investigación queda fechada al 09/09/2026. Antes de cada publicación de un paquete de reglas, Signia debe comprobar si SUNAT sustituyó la matriz, los catálogos, XSD, XSL, manuales o normas relacionadas. El archivo fuente y su checksum deben conservarse para auditoría.
