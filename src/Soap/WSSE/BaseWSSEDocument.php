<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Soap\WSSE;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Generator;
use Iterator;
use RuntimeException;

/**
 * Class BaseWSSEDocument.
 */
abstract class BaseWSSEDocument extends DOMDocument
{
    protected const SOAP_NS = 'http://schemas.xmlsoap.org/soap/envelope/';
    protected const WSU_NS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
    protected const WSSE_NS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    protected const DS_NS = 'http://www.w3.org/2000/09/xmldsig#';
    /**
     * @var DOMXPath
     */
    private $xp;

    public function __construct(string $xml)
    {
        parent::__construct('1.0', 'UTF-8');
        $this->loadXML($xml);
        $this->xp = new DOMXPath($this);
        $this->xp->registerNamespace('SOAP-ENV', self::SOAP_NS);
        $this->xp->registerNamespace('soapenv', self::SOAP_NS);
        $this->xp->registerNamespace('wsu', self::WSU_NS);
        $this->xp->registerNamespace('wsse', self::WSSE_NS);
        $this->xp->registerNamespace('ds', self::DS_NS);
    }

    /**
     * @param string $xPathNode
     *
     * @return DOMElement
     *
     * @throws NodeNotFound
     */
    protected function queryElement(string $xPathNode): DOMElement
    {
        $nodes = $this->xp->query($xPathNode);
        if (!$nodes) {
            throw new RuntimeException('Namespace error in finding node');
        }
        if ($nodes->count() === 0) {
            throw new NodeNotFound();
        }
        $node = $nodes->item(0);
        if (!$node instanceof DOMElement) {
            throw new RuntimeException(sprintf('Node to query must be an instance of %s', DOMElement::class));
        }

        return $node;
    }

    /**
     * @param string $xPathNode
     *
     * @return Generator<int, DOMElement, mixed, void>
     */
    protected function queryElements(string $xPathNode): Iterator
    {
        $nodes = $this->xp->query($xPathNode);
        if (!$nodes) {
            throw new RuntimeException('Namespace error in finding node');
        }
        foreach ($nodes as $node) {
            if (!$node instanceof DOMElement) {
                throw new RuntimeException(sprintf('Node to query must be an instance of %s', DOMElement::class));
            }
            yield $node;
        }
    }

    /**
     * @return DOMElement
     */
    protected function getBodyNode(): DOMElement
    {
        $nodes = $this->queryElements('asfasf');

        return $this->queryElement('/SOAP-ENV:Envelope/SOAP-ENV:Body');
    }
}
