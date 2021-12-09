<?php declare(strict_types=1);
/**
 * This file is part of Agarwood Cloud.
 *
 * @link     https://www.agarwood-cloud.com
 * @document https://www.agarwood-cloud.com/docs
 * @contact  676786620@qq.com
 * @author   agarwood
 */

namespace Agarwood\Core\Support\Traits;

/**
 * @\Swoft\Validator\Annotation\Mapping\Validator()
 */
trait ObjectTrait
{
    /**
     * @\Swoft\Validator\Annotation\Mapping\IsInt()
     * @\Swoft\Validator\Annotation\Mapping\NotEmpty()
     * @var int|null
     */
    public ?int $objectId = null;

    /**
     * @\Swoft\Validator\Annotation\Mapping\IsString()
     * @\Swoft\Validator\Annotation\Mapping\NotEmpty()
     * @var string|null
     */
    public ?string $objectModule = null;

    /**
     * @\Swoft\Validator\Annotation\Mapping\IsString()
     * @\Swoft\Validator\Annotation\Mapping\NotEmpty()
     * @var string|null
     */
    public ?string $objectAction = null;

    /**
     * @\Swoft\Validator\Annotation\Mapping\IsString()
     * @\Swoft\Validator\Annotation\Mapping\NotEmpty()
     * @var string|null
     */
    public ?string $origin = null;

    /**
     * @return int|null
     */
    public function getObjectId(): ?int
    {
        return $this->objectId;
    }

    /**
     * @param int|null $objectId
     */
    public function setObjectId(?int $objectId): void
    {
        $this->objectId = $objectId;
    }

    /**
     * @return string|null
     */
    public function getObjectModule(): ?string
    {
        return $this->objectModule;
    }

    /**
     * @param string|null $objectModule
     */
    public function setObjectModule(?string $objectModule): void
    {
        $this->objectModule = $objectModule;
    }

    /**
     * @return string|null
     */
    public function getObjectAction(): ?string
    {
        return $this->objectAction;
    }

    /**
     * @param string|null $objectAction
     */
    public function setObjectAction(?string $objectAction): void
    {
        $this->objectAction = $objectAction;
    }

    /**
     * @return string|null
     */
    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    /**
     * @param string|null $origin
     */
    public function setOrigin(?string $origin): void
    {
        $this->origin = $origin;
    }
}
