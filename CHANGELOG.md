# Changelog
Todos los cambios notables en este proyecto están documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto adhiere a [Versionamiento Semántico](https://semver.org/spec/v2.0.0.html).
 
## [Unreleased]
 
### Added
- Añadido el módulo `Soap` que contiene el `TransbankSoapClient` que firma XML usando los `Credentials` provistos. 
- Definido `WebpayClient`
- Implementado `SoapWebpayClient`
- Implementado `Psr14WebpayClient`
- Añadidos los helpers para respuestas fáciles en html
- Añadido CS-Fixer, Psalm y PHPUnit
- Añadido el cliente SOAP con Logger
- Añadido Webpay decorado con Event Dispatcher
 
[Unreleased]: https://github.com/better-transbank/sdk/compare/0.1.0...HEAD
[0.1.0]: https://github.com/better-transbank/sdk/compare/releases/tag/0.1.0