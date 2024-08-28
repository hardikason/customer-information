<?php
namespace SK\ShippingDeliveryDate\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Psr\Log\LoggerInterface;

class UpdateDeliveryDateToOrder implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * __construct
     *
     * @param  LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * Update shipping delivery date to order
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        try {
            /** @var AddressInterface $quoteAddress */
            $quoteAddress = $observer->getQuote()->getShippingAddress();

            $order = $observer->getOrder();

            // Set shipping delivery date in sales $order
            $order->setShippingDeliveryDate($quoteAddress->getShippingDeliveryDate());

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
