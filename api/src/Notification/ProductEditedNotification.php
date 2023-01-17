<?php

declare(strict_types=1);

namespace App\Notification;

final readonly class ProductEditedNotification implements NotificationInterface
{
    public function __construct(private string $productId)
    {
    }

    public function getProductId(): string
    {
        return $this->productId;
    }
}
