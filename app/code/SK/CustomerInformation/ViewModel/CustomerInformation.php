<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 * @author Sonali Kosrabe <sonalikosrabe@outlook.com>
 */

namespace SK\CustomerInformation\ViewModel;

use SK\CustomerInformation\Helper\Data as HelperData;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\App\RequestInterface;

class CustomerInformation implements \Magento\Framework\View\Element\Block\ArgumentInterface
{

    /**
     * @var HelperData
     */
    protected $helperData;
    /**
     * @var AddressConfig
     */
    protected $addressConfig;
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepositoryInterface;
    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;
    /**
     * @var SortOrderBuilder
     */
    protected $sortOrderBuilder;
    /**
     * @var RequestInterface
     */
    protected $requestInterface;

    /**
     * @param HelperData $helperData
     * @param AddressConfig $addressConfig
     * @param OrderRepositoryInterface $orderRepositoryInterface
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param RequestInterface $requestInterface
     */
    public function __construct(
        HelperData $helperData,
        AddressConfig $addressConfig,
        OrderRepositoryInterface $orderRepositoryInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        RequestInterface $requestInterface
    ) {
        $this->helperData = $helperData;
        $this->addressConfig = $addressConfig;
        $this->orderRepositoryInterface = $orderRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->requestInterface = $requestInterface;
    }

    /**
     * Get Allow To Remove Request
     *
     * @return mixed
     */
    public function getAllowToRemoveRequest() : mixed
    {
        return $this->helperData->getConfigValue(HelperData::XML_PATH_ALLOW_TO_REMOVE);
    }

    /**
     * Get Address in Html Format
     *
     * @param  mixed $order
     * @return mixed
     */
    public function getAddressHtml($order)
    {
        $address = $order->getShippingAddress();
        $renderer = $this->addressConfig->getFormatByCode('html')->getRenderer();
        return $renderer->renderArray($address);
    }

    /**
     * Get Customer Orders
     *
     * @param  mixed $customerId
     * @return \Magento\Sales\Api\Data\OrderSearchResultInterface
     */
    public function getCustomerOrders($customerId)
    {
        $currentPage = ($this->requestInterface->getParam('p')) ? (int)$this->requestInterface->getParam('p') : 1;
        $pageSize = ($this->requestInterface->getParam('limit')) ? (int)$this->requestInterface->getParam('limit') : 10;

        // Filter by customer ID
        $this->searchCriteriaBuilder->addFilter('customer_id', $customerId, 'eq');

        // Set pagination
        $this->searchCriteriaBuilder->setPageSize($pageSize);
        $this->searchCriteriaBuilder->setCurrentPage($currentPage);

        // Sort by order creation date (newest first)
        $sortOrder = $this->sortOrderBuilder
            ->setField('created_at')
            ->setDirection('DESC')
            ->create();
        $this->searchCriteriaBuilder->addSortOrder($sortOrder);

        // Build the search criteria
        $searchCriteria = $this->searchCriteriaBuilder->create();

        // Get the order list
        $orderList = $this->orderRepositoryInterface->getList($searchCriteria);

        // Return the result
        return $orderList;
    }
}
