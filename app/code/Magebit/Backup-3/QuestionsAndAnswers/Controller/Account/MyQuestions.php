<?php

namespace Magebit\QuestionsAndAnswers\Controller\Account;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page\Interceptor;
// use Magento\Framework\Controller\Result\RedirectFactory as ResultRedirectFactory;
use Magento\Customer\Model\Session as CustomerSession;

class MyQuestions extends AbstractAccount implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;
    protected CustomerSession $customerSession;
    // protected ResultRedirectFactory $resultRedirectFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        // ResultRedirectFactory $resultRedirectFactory,
        CustomerSession $customerSession
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        // $this->resultRedirectFactory = $resultRedirectFactory;
        $this->customerSession = $customerSession;
    }

    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        }
        return $this->resultPageFactory->create();
    }
}

