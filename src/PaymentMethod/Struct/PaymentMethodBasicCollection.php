<?php declare(strict_types=1);

namespace Shopware\PaymentMethod\Struct;

use Shopware\Framework\Struct\Collection;

class PaymentMethodBasicCollection extends Collection
{
    /**
     * @var PaymentMethodBasicStruct[]
     */
    protected $elements = [];

    public function add(PaymentMethodBasicStruct $paymentMethod): void
    {
        $key = $this->getKey($paymentMethod);
        $this->elements[$key] = $paymentMethod;
    }

    public function remove(string $uuid): void
    {
        parent::doRemoveByKey($uuid);
    }

    public function removeElement(PaymentMethodBasicStruct $paymentMethod): void
    {
        parent::doRemoveByKey($this->getKey($paymentMethod));
    }

    public function exists(PaymentMethodBasicStruct $paymentMethod): bool
    {
        return parent::has($this->getKey($paymentMethod));
    }

    public function getList(array $uuids): PaymentMethodBasicCollection
    {
        return new self(array_intersect_key($this->elements, array_flip($uuids)));
    }

    public function get(string $uuid): ? PaymentMethodBasicStruct
    {
        if ($this->has($uuid)) {
            return $this->elements[$uuid];
        }

        return null;
    }

    public function getUuids(): array
    {
        return $this->fmap(function (PaymentMethodBasicStruct $paymentMethod) {
            return $paymentMethod->getUuid();
        });
    }

    public function merge(PaymentMethodBasicCollection $collection)
    {
        /** @var PaymentMethodBasicStruct $paymentMethod */
        foreach ($collection as $paymentMethod) {
            if ($this->has($this->getKey($paymentMethod))) {
                continue;
            }
            $this->add($paymentMethod);
        }
    }

    public function getPluginUuids(): array
    {
        return $this->fmap(function (PaymentMethodBasicStruct $paymentMethod) {
            return $paymentMethod->getPluginUuid();
        });
    }

    public function filterByPluginUuid(string $uuid): PaymentMethodBasicCollection
    {
        return $this->filter(function (PaymentMethodBasicStruct $paymentMethod) use ($uuid) {
            return $paymentMethod->getPluginUuid() === $uuid;
        });
    }

    protected function getKey(PaymentMethodBasicStruct $element): string
    {
        return $element->getUuid();
    }
}