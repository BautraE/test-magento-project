<?php

declare(strict_types=1);

namespace Magebit\QuestionsAndAnswers\Block\Account;

use Magento\Framework\View\Element\Template;
use Magebit\QuestionsAndAnswers\Api\QuestionRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session as UserSession;

class QuestionList extends Template
{
    /**
     * @var QuestionRepositoryInterface
     */
    private QuestionRepositoryInterface $questionRepositoryInterface;
    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    private ProductRepositoryInterface $productRepository;
    private UserSession $userSession;

    public function __construct(
        Template\Context $context,
        array $data,
        QuestionRepositoryInterface $questionRepositoryInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ProductRepositoryInterface $productRepository,
        UserSession $userSession
    ) {
        parent::__construct($context, $data);
        $this->questionRepositoryInterface = $questionRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productRepository = $productRepository;
        $this->userSession = $userSession;
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function getQuestions(): array
    {
        $userId = $this->userSession->getCustomer()->getId();

        $searchCriteria = $this->searchCriteriaBuilder
        ->addFilter('user_id', $userId, 'eq')
        ->create();

        return $this->questionRepositoryInterface->getList($searchCriteria)->getItems();
    }

    public function isAnswered($question): string
    {
        if($question->getVisibility()) {
            return "Answered";
        }
        return "Not Answered";
    }

    public function getProductName($question): string
    {
        $product = $this->productRepository->getById($question->getProductId());
        return $product->getName();
    }
}
