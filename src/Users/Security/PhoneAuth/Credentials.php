<?php


namespace App\Users\Security\PhoneAuth;

use App\Infrastructure\DomainEvents\EventProducer;
use App\Infrastructure\DomainEvents\ProducesEvents;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="phone_credentials")
 */
class Credentials implements EventProducer
{
    use ProducesEvents;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id()
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    private $number;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     * @var \DateTimeImmutable
     */
    private $createdAt;

    public function __construct(int $id, string $number)
    {
        $this->id = $id;
        $this->number = $number;
        $this->createdAt = new \DateTimeImmutable();
        $this->remember(new PhoneCredentialsCreated($id));
    }

    public function id()
    {
        return $this->id;
    }

    public function changeNumber(string $newNumber)
    {
        $this->number = $newNumber;
    }
}
