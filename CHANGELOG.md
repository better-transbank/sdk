# Changelog
Todos los cambios notables en este proyecto están documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto adhiere a [Versionamiento Semántico](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## Modified
- Se quitaron los repositorios de certificados y se centralizaron en `better-transbank/certificates`.
- Se quitó el CI de la rama master, debido a que es redundante
- Se quitó la documentación porque será colocada en su propio repositorio `better-transbank/docs`
- Quitadas las comparaciones con estilo Yoda

## Added
- Integración Continua de cambios en código, con pruebas unitarias y manejo de estilo
- Psalm fue añadido al pipeline y ya no muestra errores de tipado en el código
- Dependencia a `better-transbank/certificates` como repositorio de certificados

## [0.3.0] - 2020-01-09 

### Changed
- Movida la lógica de formado WSSE fuera de `TransbankSoapClient` y encapsulada en la nueva clase `WSSE\RequestDocument`.

### Added
- Creada la clase `WSSE\ResponseDocument` que encapsula la validación de una respuesta XML de Transbank.

### Fixed
- Corregido el objeto `Details` dentro de `TransactionResult`. Ahora, puede contener un arreglo de details.
Esto soporta Webpay Mall con varias transacciones.

## [0.2.0] - 2020-01-08

### Added
- Webpay Plus Pat Pass
- Ejemplos extra del SDK para Webpay Normal en todos sus modos (Normal, Mall y PatPass)

### Changed
- Las clases `Certificate` y `PrivateKey` ahora abren los archivos al momento de instanciación, para
ahorrar I/O en las operaciones.

### Deprecated
- El signature del constructor de la clase `Credentials` ha sido deprecado. A partir de 1.0.0 ya
 no soportará nombres de archivo como parámetros, sino las instancias `Certificate` y `PrivateKey`. Como
 reemplazo para instanciación a través de nombres de archivo, se disponibiliza el método estático
 `Credentials::fromFilesPath()`.

## [0.1.0] - 2020-01-08
 
### Added
- Añadido el módulo `Soap` que contiene el `TransbankSoapClient` que firma XML usando los `Credentials` provistos. 
- Definido `WebpayClient`
- Implementado `SoapWebpayClient`
- Implementado `Psr14WebpayClient`
- Añadidos los helpers para respuestas fáciles en html
- Añadido CS-Fixer, Psalm y PHPUnit
- Añadido el cliente SOAP con Logger
- Añadido Webpay decorado con Event Dispatcher
 
[Unreleased]: https://github.com/better-transbank/sdk/compare/0.3.0...HEAD
[0.3.0]: https://github.com/better-transbank/sdk/compare/0.2.0...0.3.0
[0.2.0]: https://github.com/better-transbank/sdk/compare/0.1.0...0.2.0
[0.1.0]: https://github.com/better-transbank/sdk/compare/releases/tag/0.1.0