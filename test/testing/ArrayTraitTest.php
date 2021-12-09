<?php declare(strict_types=1);
/**
 * This file is part of Agarwood Cloud.
 *
 * @link     https://www.agarwood-cloud.com
 * @document https://www.agarwood-cloud.com/docs
 * @contact  676786620@qq.com
 * @author   agarwood
 */

namespace CommonTest\Testing;

use Agarwood\Core\Support\Impl\AbstractBaseDTO;
use Agarwood\Core\Util\StringUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayTraitTest
 * @package CommonTest\Testing

 */
class ArrayTraitTest extends TestCase
{
    public function testToArrayNotNull(): void
    {
        $fill = [
            'id'        => 1,
            'nameSpace' => null,
            'age'       => 0,
            'hold'      => false,
            'class'     => '',
        ];

        $unit = new ArrayTestUnit;
        $unit->setAttr($fill);
        $toArray = $unit->toArrayNotNull();

        $nullValueKeys = $falseOrEmptyKeys = [];
        foreach ($fill as $key => $value) {
            $value === null                   && $nullValueKeys[]                      = $key;
            in_array($key, [false, ''], true) && $falseOrEmptyKeys[]                   = $key;
        }

        $this->assertIsArray($toArray);
        foreach ($nullValueKeys as $nullKey) {
            $this->assertArrayNotHasKey($nullKey, $toArray);
        }
        // 保证不会删除掉false或者空的值
        foreach ($falseOrEmptyKeys as $nullKey) {
            $this->assertArrayHasKey($nullKey, $toArray);
        }
    }

    public function testToArrayLine(): void
    {
        $fill = [
            'id'        => 1,
            'nameSpace' => __NAMESPACE__,
            'age'       => 999,
            'hold'      => false,
        ];

        $unit = new ArrayTestUnit;
        $unit->setAttr($fill);
        $toArray = $unit->toArrayLine();

        $this->assertIsArray($toArray);
        $line = StringUtil::toLine('nameSpace');
        $this->assertArrayHasKey($line, $toArray);
    }

    public function testToArray(): void
    {
        $fill = [
            'id'        => 1,
            'nameSpace' => __NAMESPACE__,
            'age'       => 999,
            'hold'      => false,
        ];

        $unit = new ArrayTestUnit;
        $unit->setAttr($fill);

        $unit1   = clone $unit;
        $toArray = $unit1->toArray();

        $this->assertIsArray($toArray);
        foreach (array_keys($fill) as $key) {
            $this->assertArrayHasKey($key, $toArray);
        }
        unset($unit1, $toArray);

        $unit2   = clone $unit;
        $toArray = $unit2->toArray(['nameSpace']);
        $this->assertIsArray($toArray);
        $this->assertArrayNotHasKey('nameSpace', $toArray);
    }
}

/**
 * Class ArrayTestUnit
 * @package CommonTest\Testing
 */
class ArrayTestUnit extends AbstractBaseDTO
{
    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $nameSpace;

    /**
     * @var int|null
     */
    protected $age;

    /**
     * @var bool|null
     */
    protected $hold;

    /**
     * @var string|null
     */
    protected $class;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getNameSpace(): ?string
    {
        return $this->nameSpace;
    }

    /**
     * @param string|null $nameSpace
     */
    public function setNameSpace(?string $nameSpace): void
    {
        $this->nameSpace = $nameSpace;
    }

    /**
     * @return int|null
     */
    public function getAge(): ?int
    {
        return $this->age;
    }

    /**
     * @param int|null $age
     */
    public function setAge(?int $age): void
    {
        $this->age = $age;
    }

    /**
     * @return bool|null
     */
    public function isHold(): ?bool
    {
        return $this->hold;
    }

    /**
     * @param bool|null $hold
     */
    public function setHold(?bool $hold): void
    {
        $this->hold = $hold;
    }

    /**
     * @return string|null
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * @param string|null $class
     */
    public function setClass(?string $class): void
    {
        $this->class = $class;
    }
}
