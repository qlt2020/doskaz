<?php


namespace App\Feedback;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use App\Cities\Cities;

/**
 * @ORM\Entity()
 * @ORM\Table(name="feedback")
 */
class Feedback
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="text")
     */
    private string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $surname;

    /**
     * @ORM\Column(type="text")
     */
    private string $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Cities\Cities")
     * @ORM\JoinColumn(nullable=true, name="city_id", referencedColumnName="id")
     */
    private $city;

    /**
     * @ORM\Column(type="text")
     */
    private string $text;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $deletedAt;

    /**
     * Feedback constructor.
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $text
     * @throws \Exception
     */
    public function __construct(string $name, string $surname, string $email, string $text, ?Cities $city)
    {
        $this->id = Uuid::uuid4();
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->text = $text;
        $this->setCity($city); 
        $this->createdAt = new \DateTimeImmutable();
    }

    public function delete()
    {
        $this->deletedAt = new \DateTimeImmutable();
    }

    /**
     * @param Cities $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }
}
