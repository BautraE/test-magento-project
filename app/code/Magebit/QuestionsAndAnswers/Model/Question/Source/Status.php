<?php

namespace Magebit\QuestionsAndAnswers\Model\Question\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            [
                'label' => 'Inactive',
                'value' => 0,
            ],
            [
                'label' => 'Active',
                'value' => 1,
            ],
        ];
    }
}
