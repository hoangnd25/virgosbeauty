<?php

namespace VB\CommerceBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * Class OrderStatus
 * @package VB\CommerceBundle\Model
 */
class OrderStatus
{
    protected static $statuses = array(
        'received' => 'Đã nhận',
        'processing' => 'Đang xử lý',
        'finished' => 'Đã hoàn thành',
        'canceled' => 'Huỷ'
    );

    static function getName($code){
        foreach(self::statuses as $statusCode => $status){
            if($statusCode == $code){
                return $status;
            }
        }
    }

}