<?php declare(strict_types=1);

namespace Shopware\Category\Event;

use Shopware\Category\Struct\CategoryDetailCollection;
use Shopware\Context\Struct\TranslationContext;
use Shopware\CustomerGroup\Event\CustomerGroupBasicLoadedEvent;
use Shopware\Framework\Event\NestedEvent;
use Shopware\Framework\Event\NestedEventCollection;
use Shopware\Media\Event\MediaBasicLoadedEvent;
use Shopware\Product\Event\ProductBasicLoadedEvent;
use Shopware\ProductStream\Event\ProductStreamBasicLoadedEvent;

class CategoryDetailLoadedEvent extends NestedEvent
{
    const NAME = 'category.detail.loaded';

    /**
     * @var CategoryDetailCollection
     */
    protected $categories;

    /**
     * @var TranslationContext
     */
    protected $context;

    public function __construct(CategoryDetailCollection $categories, TranslationContext $context)
    {
        $this->categories = $categories;
        $this->context = $context;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getCategories(): CategoryDetailCollection
    {
        return $this->categories;
    }

    public function getContext(): TranslationContext
    {
        return $this->context;
    }

    public function getEvents(): ?NestedEventCollection
    {
        $events = [
            new CategoryBasicLoadedEvent($this->categories, $this->context),
        ];

        if ($this->categories->getProductStreams()->count() > 0) {
            $events[] = new ProductStreamBasicLoadedEvent($this->categories->getProductStreams(), $this->context);
        }
        if ($this->categories->getMedia()->count() > 0) {
            $events[] = new MediaBasicLoadedEvent($this->categories->getMedia(), $this->context);
        }
        if ($this->categories->getProducts()->count() > 0) {
            $events[] = new ProductBasicLoadedEvent($this->categories->getProducts(), $this->context);
        }
        if ($this->categories->getBlockedCustomerGroups()->count() > 0) {
            $events[] = new CustomerGroupBasicLoadedEvent($this->categories->getBlockedCustomerGroups(), $this->context);
        }

        return new NestedEventCollection($events);
    }
}