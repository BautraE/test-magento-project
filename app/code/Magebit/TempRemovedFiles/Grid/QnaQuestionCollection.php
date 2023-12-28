<?php

namespace Magebit\QuestionsAndAnswers\Model\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class QnaQuestionCollection extends SearchResult
{
    protected function _renderFiltersBefore()
    {
        $this->getSelect()
            ->join(
                ['product' => $this->getTable('catalog_product_entity')],
                'main_table.product_id = product.entity_id',
                ['product_name' => 'product.name'] // Adjust the column names accordingly
            )
            ->join(
                ['user' => $this->getTable('customer_entity')],
                'main_table.user_id = user.entity_id',
                ['user_name' => 'CONCAT(user.firstname, " ", user.lastname)'] // Adjust the column names accordingly
            );

        parent::_renderFiltersBefore();
    }
}
