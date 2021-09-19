<?php


namespace App\Users;

use App\Infrastructure\DomainEvents\EventProducer;
use App\Infrastructure\DomainEvents\ProducesEvents;
use App\Users\Subscribe\UserSubscribe;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Cities\Cities;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements EventProducer
{
    use ProducesEvents;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @var FullName|null
     * @ORM\Column(type=FullName::class, options={"jsonb" = true}, nullable=true)
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=1, options={"default":"u"}, nullable=true)
     */
    private $gender;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Cities\Cities")
     * @ORM\JoinColumn(nullable=true, name="city_id", referencedColumnName="id")
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity="App\Users\Subscribe\UserSubscribe", mappedBy="user")
     */
    private $subscribes;

    /**
     * @var string
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="json", options={"jsonb": true})
     */
    private $roles = [];

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $createdAt;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $updatedAt;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $status = null;

    public const CATEGORY_MOVEMENT = 'movement';
    public const CATEGORY_VISION = 'vision';
    public const CATEGORY_LIMB = 'limb';
    public const CATEGORY_KIDS = 'kids';
    public const CATEGORY_HEARING = 'hearing';
    public const CATEGORY_TEMPORAL = 'temporal';
    public const CATEGORY_BABY_CARRIAGE = 'babyCarriage';
    public const CATEGORY_MISSING_LIMBS = 'missingLimbs';
    public const CATEGORY_PREGNANT = 'pregnant';
    public const CATEGORY_INTELLECTUAL = 'intellectual';
    public const CATEGORY_AGED_PEOPLE = 'agedPeople';
    public const CATEGORY_JUST_VIEW = 'justView';
    public const CATEGORY_WITH_CHILD = 'withChild';
    public const CATEGORY_UNDEFINED = 'undefined';

    public const USER_CATEGORIES = [
        self::CATEGORY_MOVEMENT,
        self::CATEGORY_VISION,
        self::CATEGORY_LIMB,
        self::CATEGORY_HEARING,
        self::CATEGORY_TEMPORAL,
        self::CATEGORY_BABY_CARRIAGE,
        self::CATEGORY_MISSING_LIMBS,
        self::CATEGORY_PREGNANT,
        self::CATEGORY_INTELLECTUAL,
        self::CATEGORY_AGED_PEOPLE,
        self::CATEGORY_JUST_VIEW,
        self::CATEGORY_WITH_CHILD,
        self::CATEGORY_UNDEFINED
    ];

    public const USER_SPECIAL_GROUPS =
        [
            self::CATEGORY_MOVEMENT => [
                self::CATEGORY_MOVEMENT,
                self::CATEGORY_BABY_CARRIAGE,
                self::CATEGORY_JUST_VIEW
            ],
            self::CATEGORY_VISION => [
                self::CATEGORY_VISION
            ],
            self::CATEGORY_LIMB => [
                self::CATEGORY_LIMB,
                self::CATEGORY_TEMPORAL,
                self::CATEGORY_MISSING_LIMBS,
                self::CATEGORY_PREGNANT,
                self::CATEGORY_AGED_PEOPLE,
            ],
            self::CATEGORY_HEARING => [
                self::CATEGORY_HEARING
            ],
            self::CATEGORY_INTELLECTUAL => [
                self::CATEGORY_INTELLECTUAL
            ],
            self::CATEGORY_KIDS => [
                self::CATEGORY_WITH_CHILD
            ]
        ];

    public const USER_CATEGORIES_NAMES = [
        self::CATEGORY_MOVEMENT => 'Люди, передвигающиеся на кресло-коляске',
        self::CATEGORY_VISION => 'Люди с с инвалидностью по зрению',
        self::CATEGORY_LIMB => 'Люди с нарушениями опорно-двигательного аппарата',
        self::CATEGORY_HEARING => 'Люди с инвалидностью по слуху',
        self::CATEGORY_TEMPORAL => 'Временно травмированные люди',
        self::CATEGORY_BABY_CARRIAGE => 'Люди с детскими колясками',
        self::CATEGORY_MISSING_LIMBS => 'Люди с отсутствующими конечностями',
        self::CATEGORY_PREGNANT => 'Беременные женщины',
        self::CATEGORY_INTELLECTUAL => 'Люди с интеллектуальной инвалидностью',
        self::CATEGORY_AGED_PEOPLE => 'Пожилые люди',
        self::CATEGORY_JUST_VIEW => 'Просто посмотреть',
        self::CATEGORY_WITH_CHILD => 'Люди с детьми до семи лет',
        self::CATEGORY_UNDEFINED => 'Неизвестно'
    ];

    public function __construct(?FullName $fullName, ?string $email = null)
    {
        $this->name = '';
        $this->fullName = $fullName ?? new FullName();
        $this->email = $email;
        $this->roles = ['ROLE_USER'];
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->subscribes = new ArrayCollection();
    }

    /**
     * @return Collection|UserSubscribe[]
     */
    public function getSubscribes(): Collection
    {
        return $this->subscribes;
    }

    public function update(UserData $data)
    {
        $this->name = $data->name;
        $this->fullName = FullName::parseFromString($data->name);
        $this->roles = $data->roles;
    }

    public function updateProfile(FullName $fullName, ?string $email, ?string $status, ?string $gender, ?string $category, ?Cities $city, ?string $birthday)
    {
        $this->fullName = $fullName;
        $this->email = empty($email) ? null : $email;
        $this->updatedAt = new \DateTimeImmutable();
        $this->status = $status;
        $this->gender = $gender ?? 'u';
        $this->category = $category ?? null;
        $this->setCity($city);
        $this->birthday = $birthday ? new \DateTime($birthday) : null;
        $this->remember(new UserProfileUpdated($this->id));
    }

    public function migrateFullName()
    {
        $this->fullName = FullName::parseFromString($this->name);
    }

    public function changeAvatar(string $avatar)
    {
        $this->avatar = $avatar;
    }

    public function id()
    {
        return $this->id;
    }

    public function removeAvatar()
    {
        $this->avatar = null;
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * @ORM\PostPersist()
     */
    public function fireRegisteredEvent()
    {
        $this->remember(new UserRegistered($this->id));
    }

    /**
     * @param Cities $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function getAccessibilityGroup(string $category): ?string
    {
        foreach (self::USER_SPECIAL_GROUPS as $group => $usg) {
            if(in_array($category, $usg)) return $group;
       }
        return null;
    }

    public function getRoles(){
        return $this->roles;
    }

}
