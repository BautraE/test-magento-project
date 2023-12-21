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
        // var_dump($this->_redirect->getRefererUrl());
        // die();
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if(!$this->customerSession->isLoggedIn())
        {
            $this->messageManager->addErrorMessage(__('You must be logged in to submit a question.'));
        } 
        else 
        {
            $data = $this->getRequest()->getPostValue();
            
            $questionModel = $this->questionFactory->create();
            $data['id'] = null;
    
            $questionModel->setData($data);
    
            try {
                $this->questionRepository->save($questionModel);
                $this->messageManager->addSuccessMessage(__('You saved the question.'));
            } catch (LocalizedException $exception) {
                $this->messageManager->addExceptionMessage($exception);
            } catch (\Throwable $error) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the question!'));
            }
        }

        return $resultRedirect->setPath($this->_redirect->getRefererUrl());

        // var_dump($data);
        // var_dump("This somehow worked");
        // die();
        // return $this->_pageFactory->create();
    }
}
