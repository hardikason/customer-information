<?php
namespace SK\CustomerInformation\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;
use SK\CustomerInformation\Model\Email;

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
     * @param \Magento\Framework\App\Action\Context $context
     * @param ResultJsonFactory $resultJsonFactory
     * @param Email $email
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        ResultJsonFactory $resultJsonFactory,
        Email $email
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->email = $email;
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
        try {
            $this->email->sendRemoveRequestEmail($post['customerId'], $post['orderId']);
            $data = ['success' => true, 'message' => __('Remove request submitted successfully.')];
        } catch (\Exception $e) {
            $data = ['success' => false, 'message' => __($e->getMessage())];
        }

        return $result->setData($data);
    }
}
