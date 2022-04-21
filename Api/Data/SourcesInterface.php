<?php

namespace Marcwatts\Rexstock\Api\Data;

/**
 * Interface SourcesInterface
 * @package Marcwatts\Rexstock\Api\Data
 */
interface SourcesInterface
{
    /**
     * Order ID key
     */
    const ORDER_ID_KEY = 'order_id';

    /**
     * Sources Key
     */
    const SOURCES_KEY = 'sources';

    /**
     * @return string|null
     */
    public function getSources(): ?string;

    /**
     * @param string $sources
     * @return SourcesInterface $this
     */
    public function setSources(string $sources): SourcesInterface;

    /**
     * @return string|null
     */
    public function getOrderId(): ?string;

    /**
     * @param string $id
     * @return SourcesInterface $this
     */
    public function setOrderId(string $id): SourcesInterface;
}
