<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Storage\Business;

use Codeception\Test\Unit;
use SprykerTest\Client\Storage\Helper\StorageHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Storage
 * @group Business
 * @group Facade
 * @group StorageFacadeTest
 * Add your own group annotations below this line
 */
class StorageFacadeTest extends Unit
{
    use StorageHelperTrait;

    /**
     * @var \SprykerTest\Zed\Storage\StorageBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetTotalCount(): void
    {
        $this->getStorageHelper()->getStorageClient()->set('random-key', 'value');
        $this->assertGreaterThan(0, $this->getStorageFacade()->getTotalCount());
    }

    /**
     * @return void
     */
    public function testGetTimestamps(): void
    {
        $this->assertIsArray($this->getStorageFacade()->getTimestamps());
    }

    /**
     * @return \Spryker\Zed\Storage\Business\StorageFacadeInterface
     */
    protected function getStorageFacade()
    {
        return $this->tester->getFacade();
    }
}
