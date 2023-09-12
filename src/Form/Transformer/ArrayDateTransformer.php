<?php


namespace App\Form\Transformer;

use DateTime;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @todo sure there is a transform in symfony: search
 */
class ArrayDateTransformer implements DataTransformerInterface
{

    /**
     * @param mixed $value
     * @return DateTime
     */
    public function transform(mixed $value)
    {
        $result = new DateTime();
        $result->setTimestamp(
            mktime(
                $value['time']['hour'],
                $value['time']['minute'],
                0,
                $value['date']['month'],
                $value['date']['day'],
                $value['date']['year']
            )
        );
        return $result;
    }

    /**
     * @param mixed $value
     * @return array[]
     */
    public function reverseTransform(mixed $value)
    {
        return [
            'time' => [
                'hour' => $value->format('H'),
                'minute' => $value->format('i')
            ],
            'date' => [
                'month' => $value->format('m'),
                'year' => $value->format('Y'),
                'day' => $value->format('d')
            ]
        ];
    }
}
