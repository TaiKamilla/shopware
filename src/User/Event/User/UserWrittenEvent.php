<?php declare(strict_types=1);

namespace Shopware\User\Event\User;

use Shopware\Api\Write\WrittenEvent;
use Shopware\User\Definition\UserDefinition;

class UserWrittenEvent extends WrittenEvent
{
    const NAME = 'user.written';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getDefinition(): string
    {
        return UserDefinition::class;
    }
}