Better Transbank SDK
====================

Un SDK no oficial (¡pero mejor!) de los servicios de pago de Transbank.

[![Actions Status](https://github.com/better-transbank/sdk/workflows/CI/badge.svg)](https://github.com/better-transbank/sdk/actions)

## Instalación
Puedes instalar este SDK de manera sencilla con composer

```
composer require better-transbank/sdk
```

> **PROMESA DE ESTABILIDAD**:
> 
> Esta librería adhiere a [versionamiento semántico](https://semver.org/lang/es/) por lo
> que prometemos mantener la estabilidad de la api en versiones minor y patch desde
> `1.0.0` en adelante.

## Inicio Rápido
Crear una transacción normal y mostrar el formulario de pago de WebpayPlus es extremadamente sencillo:

```php
use BetterTransbank\SDK\Config;
use BetterTransbank\SDK\Html\PaymentForm;
use BetterTransbank\SDK\Services\WebpayPlus\Transaction;
use BetterTransbank\SDK\TestingCredentials;
use BetterTransbank\SDK\Transbank;

$config = Config::fromCredentials(TestingCredentials::forWebpayPlusNormal());
$transbank = Transbank::create($config);

$transaction = Transaction::normal(
    '12345',
    10000,
    'http://localhost:8000/return',
    'http://localhost:8000/final'
);

$result = $transbank->webpayPlus()->register($transaction);
PaymentForm::prepare($result)->send();
```

> **NOTA:** Evita usar el método `PaymentForm::send()` ya que presupone que usas PHP con CGI y
> que tienes múltiples scripts php en tu codebase.
> En vez de eso, recomendamos usar buenas abstracciones HTTP como `symfony/http-foundation`
> o cualquier implementación de `psr/http-message` y contar con un solo entrypoint.

## ¿Por qué un nuevo SDK?
Por mucho tiempo he usado el SDK oficial de Webpay y he encontrado diversos problemas con él.

- El protocolo SOAP se derrama sobre la insipiente abstracción de los diferentes servicios
- La firma WSSE del XML se realiza con un set de clases arcanas que ya no tienen soporte (y que no tienen licencia MIT)
- Carece de buenas prácticas de programación orientada a objetos (encapsulamiento, coding to an interface, patrones de diseño)
- Carece de tipado estricto en casi todos los lugares
- Tiene una api verbose y compleja
- Carece de suficientes pruebas unitarias
- No documenta las posibles excepciones en el DocBlock
- No utiliza las nuevas funcionalidades y estándares presentes en PHP 7+

Por estas razones decidí desarrollar un nuevo SDK que se ajustara a mis necesidades y que fuera muchísimo
más confiable y fácil de usar.

Para saber más en detalle las razones que me llevaron a desarrollar este SDK, puedes ver [la entrada de mi blog]
donde también explico todo el proceso de desarrollo en detalle.

> **NOTA:** Freshwork Studio desarrolló un SDK que soluciona algunos de estos problemas, pero en esencia es un wrapper
con un poco de funcionalidad extra sobre la misma lógica del SDK oficial. Esta, en cambio, es una implementación
desde cero.

## Servicios de Transbank Implementados
- [x] Webpay Plus (Transacciones Normales, Mútiples y de Subscripción)
- [x] Commerce Integration (Captura Diferida y Anulación)
- [ ] Webpay OneClick

## Documentación
Esta libreria dispone de una [documentación detallada] que puedes consultar para conocer la librería
en profundidad.

## Contribuye
Puedes leer la guía para contribuir a este repositorio, en donde se explica cómo contribuir con código o reportando issues.

Además, si esta librería o alguna otra de las que he desarrollado te ha servido y sacado de un apuro,
puedes comprame una cervecita o un café por Github Sponsors.

[la entrada de mi blog]: https://mnavarro.dev/2020/a-brand-new-transbank-sdk/
[documentación detallada]: https://better-transbank.mnavarro.dev