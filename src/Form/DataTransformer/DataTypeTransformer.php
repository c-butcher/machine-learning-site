<?php

namespace App\Form\DataTransformer;

use App\Entity\DataColumn;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class DataTypeTransformer
 *
 * @package App\Form\DataTransformer
 *
 * This transform is used in the DataColumnType form. It is responsible
 * for transforming our spreadsheet data types into form field types.
 */
class DataTypeTransformer implements DataTransformerInterface
{
    /**
     * We are transform our spreadsheet data types, to a data type
     * that our form can understand.
     *
     * @param string $data_type
     *
     * @return string
     */
    public function transform($data_type)
    {
        switch ($data_type) {
            case DataType::TYPE_NUMERIC:
                $data_type = NumberType::class;
                break;

            case DataType::TYPE_BOOL:
                $data_type = CheckboxType::class;
                break;

            case DataType::TYPE_STRING:
            case DataType::TYPE_STRING2:
            case DataType::TYPE_NULL:
            case DataType::TYPE_FORMULA:
            case DataType::TYPE_ERROR:
            case DataType::TYPE_INLINE:
                $data_type = TextType::class;
                break;
        }

        return $data_type;
    }

    /**
     * We aren't doing a reverse transformation, as we are only
     * transforming the spreadsheet data type, to a data type that we
     * can use for our forms.
     *
     * @param string $data_type
     *
     * @return string
     */
    public function reverseTransform($data_type)
    {
        return $data_type;
    }
}
