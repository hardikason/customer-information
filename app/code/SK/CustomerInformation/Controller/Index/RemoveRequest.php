<?php
namespace SK\CustomerInformation\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;
use SK\CustomerInformation\Model\Email;
use Magento\Customer\Model\Session as CustomerSession;

class RemoveRequest extends \Magento\Framework\App\Action\Action implements HttpGetActionInterface
{

    /**
     * @var ResultJsonFactory
     */
    protected $resultJsonFactory;
    /**
     * @var Email
     */
    protected $email;
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param ResultJsonFactory $resultJsonFactory
     * @param Email $email
     * @param CustomerSession $customerSession
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        ResultJsonFactory $resultJsonFactory,
        Email $email,
        CustomerSession $customerSession
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->email = $email;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        $post = $this->_request->getParams();

        $customerId = $this->customerSession->getCustomer()->getId();

        if ($customerId == $post['customerId']) {
            try {
                $this->email->sendRemoveRequestEmail($post['customerId'], $post['orderId']);
                $data = ['success' => true, 'message' => __('Remove request submitted successfully.')];
            } catch (\Exception $e) {
                $data = ['success' => false, 'message' => __($e->getMessage())];
            }
        } else {
            $data = ['success' => false, 'message' => __('Customer id is not same as requested customer id.')];
        }

        return $result->setData($data);
    }
}
