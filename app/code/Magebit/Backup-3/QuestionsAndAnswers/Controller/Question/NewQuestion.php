<?php
namespace Magebit\QuestionsAndAnswers\Controller\Question;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magebit\QuestionsAndAnswers\Model\QuestionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\Result\Redirect;
use Magebit\QuestionsAndAnswers\Api\QuestionRepositoryInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ObjectManager;

use Magento\Customer\Model\Session as CustomerSession;

class NewQuestion extends Action implements HttpPostActionInterface
{
    private QuestionFactory $questionFactory;
    private QuestionRepositoryInterface $questionRepository;
    private CustomerSession $customerSession;

    public function __construct(
       Context $context,
       QuestionFactory $questionFactory = null,
       QuestionRepositoryInterface $questionRepository = null,
       CustomerSession $customerSession
    ) {
       parent::__construct($context);
       $this->questionFactory = $questionFactory ?: ObjectManager::getInstance()->get(QuestionFactory::class);
       $this->questionRepository = $questionRepository ?: ObjectManager::getInstance()->get(QuestionRepositoryInterface::class);
       $this->customerSession = $customerSession;
    }

    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if(!$this->customerSession->isLoggedIn())
        {
            $this->messageManager->addErrorMessage(__('You must be logged in to submit a question!'));
        } 
        else 
        {
            $data = $this->getRequest()->getPostValue();
            if(!$data['question']) {
                $this->messageManager->addErrorMessage(__('The question field cannot be empty!'));
                return $resultRedirect->setPath($this->_redirect->getRefererUrl());
            }

            $data['id'] = null;
            $data['user_id'] = $this->customerSession->getCustomer()->getId();
            
            $questionModel = $this->questionFactory->create();
            
    
            $questionModel->setData($data);
    
            try {
                $this->questionRepository->save($questionModel);
                $this->messageManager->addSuccessMessage(__('Your question has been submitted.'));
            } catch (LocalizedException $exception) {
                $this->messageManager->addExceptionMessage($exception);
            } catch (\Throwable $error) {
                $this->messageManager->addErrorMessage(__('Something went wrong while submitting the question!'));
            }
        }

        return $resultRedirect->setPath($this->_redirect->getRefererUrl());
    }
}
