<?php declare(strict_types=1);
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\Bundle\CookieBundle\Services;

use Shopware\Bundle\CookieBundle\CookieCollection;
use Shopware\Bundle\CookieBundle\CookieGroupCollection;
use Shopware\Bundle\CookieBundle\Structs\CookieGroupStruct;

class CookieHandler implements CookieHandlerInterface
{
    public const PREFERENCES_COOKIE_NAME = 'cookiePreferences';

    /**
     * @var CookieCollector
     */
    private $cookieCollector;

    public function __construct(CookieCollector $cookieCollector)
    {
        $this->cookieCollector = $cookieCollector;
    }

    public function getCookies(): CookieGroupCollection
    {
        return $this->cookieCollector->collect();
    }

    public function getTechnicallyRequiredCookies(): CookieCollection
    {
        return $this->cookieCollector->collect()->getGroupByName(CookieGroupStruct::TECHNICAL)->getCookies();
    }

    public function isCookieAllowedByPreferences(string $cookieName, array $preferences): bool
    {
        $foundCookie = $this->getCookies()->matchCookieByName($cookieName);

        if (!$foundCookie) {
            return false;
        }

        foreach ($preferences['groups'] as $cookieGroup) {
            foreach ($cookieGroup['cookies'] as $cookie) {
                if ($cookie['name'] !== $foundCookie->getName()) {
                    continue;
                }

                return $cookie['active'];
            }
        }

        return $this->getTechnicallyRequiredCookies()->hasCookieWithName($cookieName);
    }
}
