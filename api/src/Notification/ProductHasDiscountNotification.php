<?php

declare(strict_types=1);

namespace App\Notification;

final readonly class ProductHasDiscountNotification implements NotificationInterface
{
    public function __construct(private string $productId, private string $discountId)
    {
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getDiscountId(): string
    {
        return $this->discountId;
    }
}
