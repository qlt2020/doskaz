<?php

namespace App\Help;

use App\Blog\Image;
use App\Help\HelpCategory\HelpCategory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="helps")
 */
class Help
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=255)
     * @ORM\Column(type="text", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=255)
     * @ORM\Column(type="text", length=255, nullable=false)
     */
    private $title_kz;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=255)
     * @ORM\Column(type="text", length=255, nullable=false)
     */
    private $title_en;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=65000)
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=65000)
     * @ORM\Column(type="text", nullable=false)
     */
    private $description_kz;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=65000)
     * @ORM\Column(type="text", nullable=false)
     */
    private $description_en;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=255)
     * @ORM\Column(type="text", length=255, nullable=false)
     */
    private $image;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isPublished = true;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    /**
     * @ORM\ManyToOne(targetEntity="App\Help\HelpCategory\HelpCategory")
     * @ORM\JoinColumn(nullable=false, name="category_id", referencedColumnName="id")
     */
    private $category;

    public function setPublish(){
        if ($this->isPublished == true)
            $this->isPublished = false;
        $this->isPublished = true;
    }

    public function getAttributes(){
        return [
            'id' => $this->id,
            'title' => $this->title,
            'title_kz' => $this->title_kz,
            'title_en' => $this->title_en,
            'description' => $this->description,
            'description_kz' => $this->description_kz,
            'description_en' => $this->description_en,
            'image' => $this->image,
            'category' => $this->category,
            'is_published' => $this->isPublished
        ];
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getTitleEn()
    {
        return $this->title_en;
    }

    public function getTitleKz()
    {
        return $this->title_kz;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getDescriptionEn()
    {
        return $this->description_en;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getDescriptionKz()
    {
        return $this->description_kz;
    }

    public function getId()
    {
        return $this->id;
    }

//    public function getCreatedAt(): string
//    {
//        return $this->createdAt;
//    }

    public function setDeletedAt(){
        $this->deletedAt = new \DateTimeImmutable();
    }

    public function setCreatedAt(){
        $this->createdAt = new \DateTimeImmutable();
    }

    public function setUpdatedAt(){
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function setTitle(string $value){
        $this->title = $value;
    }

    public function setTitleKz(string $value){
        $this->title_kz = $value;
    }

    public function setTitleEn(string $value){
        $this->title_en = $value;
    }

    public function setDescription(string $value){
        $this->description = $value;
    }

    public function setDescriptionKz(string $value){
        $this->description_kz = $value;
    }

    public function setDescriptionEn(string $value){
        $this->description_en = $value;
    }

    public function setCategory(EntityManagerInterface $entityManager, $categoryId){
        $this->category = $entityManager->getRepository(HelpCategory::class)->find( $categoryId );
    }

    public function setImage(?Image $image){
            $this->image = $image->image;
    }

}