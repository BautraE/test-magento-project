<?php

declare(strict_types=1);

namespace Magebit\QuestionsAndAnswers\Block;

use Magento\Framework\View\Element\Template;
use Magebit\QuestionsAndAnswers\Api\QuestionRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;

use Magento\Framework\Registry;

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

    protected $_coreRegistry = null;
    protected $_product = null;

    /**
     * @param Template\Context $context
     * @param array $data
     * @param QuestionRepositoryInterface $questionRepositoryInterface
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param AbstractView $abstractView
     */
    public function __construct(
        Template\Context $context,
        array $data,
        QuestionRepositoryInterface $questionRepositoryInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Registry $registry
    ) {
        parent::__construct($context, $data);
        $this->questionRepositoryInterface = $questionRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_coreRegistry = $registry;
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function getQuestions(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        return $this->questionRepositoryInterface->getList($searchCriteria)->getItems();
    }

    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_coreRegistry->registry('product');
        }
        return $this->_product;
    }
}
