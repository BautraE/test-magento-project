<?php

namespace Your\Module\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class ProductName extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                // Assuming that the product name is available in the 'product_name' column
                $item['product_name'] = $item['product_name'];
            }
        }

        return $dataSource;
    }
}