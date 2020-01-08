Better Transbank SDK
====================

Un SDK no oficial (¡pero mejor!) de los servicios de pago de Transbank.

## Instalación
Puedes instalar este SDK de manera sencilla con composer

```
composer require better-transbank/sdk
```

> **NOTA:** Esta librería usa versionamiento semántico, por lo que garantizamos no romper la api en versiones minor y patch.

## Inicio Rápido
Crear un pago en Webpay Plus para desarrollo y emitir una respuesta es extremadamente sencillo:

```php
use BetterTransbank\SDK\Html\PaymentForm;
use BetterTransbank\SDK\Webpay\SoapWebpayClient;
use BetterTransbank\SDK\Webpay\WebpayCredentials;
use BetterTransbank\SDK\Webpay\Message\Transaction;

// Creamos el cliente de Webpay con credenciales para staging
$cred = WebpayCredentials::normalStaging();
$webpay = SoapWebpayClient::fromCredentials($cred);

// Creamos el objeto Transaction, que representa una transacción a realizarse en webpay
$transaction = Transaction::create('https://the.url/return', 'https://the.url/final')
    ->withAddedDetails('12345', 10000, $cred->publicCert()->getSubjectCN());

// Enviamos la transacción a través del cliente
$response = $webpay->startTransaction($transaction);

// Renderizamos el formulario de pago con la respuesta
PaymentForm::prepare($response)->send();
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

## Productos Implementados
- [x] Webpay Plus (Normal, Mall y PatPass)
- [ ] Webpay Plus Captura Diferida
- [ ] Webpay Plus Anulación
- [ ] Webpay OneClick (Normal y Mall)
- [ ] OnePay

## Documentación
Puedes leer la documentación detallada [aquí](docs/README.md)

## Contribuye
Puedes leer la guía para contribuir a este repositorio, en donde se explica cómo contribuir con código o reportando issues.

Además, si esta librería o alguna otra de las que he desarrollado te ha servido y sacado de un apuro,
puedes comprame una cervecita o un café por Github Sponsors.

[la entrada de mi blog]: https://mnavarro.dev/2020/a-brand-new-transbank-sdk/