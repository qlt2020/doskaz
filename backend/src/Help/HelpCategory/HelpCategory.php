<?php

namespace App\Help\HelpCategory;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="help_categories")
 */
class HelpCategory
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="text", length=255, nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="text", length=255, nullable=false)
     */
    protected $name_kz;

    /**
     * @var string
     * @ORM\Column(type="text", length=255, nullable=false)
     */
    protected $name_en;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    protected $deletedAt;

    public function getAttributes(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_kz' => $this->name_kz,
            'name_en' => $this->name_en
        ];
    }

    public function getName()
    {
        return $this->name;
    }

    public function getNameKz()
    {
        return $this->name_kz;
    }

    public function getNameEn()
    {
        return $this->name_en;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName(string $value)
    {
        $this->name = $value;
    }

    public function setNameKz(string $value)
    {
        $this->name_kz = $value;
    }

    public function setNameEn(string $value)
    {
        $this->name_en = $value;
    }

    public function setDeletedAt()
    {
        $this->deletedAt = new \DateTimeImmutable();
    }

    public function setCreatedAt()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}