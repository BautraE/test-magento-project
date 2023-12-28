<?php

namespace Magebit\QuestionsAndAnswers\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class UserEmail extends Column
{
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CustomerRepositoryInterface $customerRepository,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->customerRepository = $customerRepository;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {

                if(!$item['user_id']) {
                    $item['user_email'] = NULL;
                }
                else {
                    if (!$user = $this->customerRepository->getById($item['user_id'])){
                        $item['user_email'] = NULL;
                    }
                    else {
                        $item['user_email'] = $user->getEmail();
                    }
                }
            }
        }

        return $dataSource;
    }
}
