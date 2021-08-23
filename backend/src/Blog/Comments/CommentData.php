<?php


namespace App\Blog\Comments;

use App\Infrastructure\ObjectResolver\DataObject;
use Doctrine\DBAL\Connection;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Schema(title="Комментарий", schema="Comment")
 */
class CommentData implements DataObject
{
    /**
     * @var UuidInterface|null
     * @Property(readOnly=true, type="string")
     */
    public $id;

    /**
     * @var integer
     * @Property(readOnly=true, example=1)
     */
    public $userId;

    /**
     * @var string
     * @Property(readOnly=true, description="Имя пользователя")
     */
    public $userName;

    /**
     * @var string
     * @Property(readOnly=true, description="Аватар пользователя")
     */
    public $userAvatar;

    /**
     * @var string
     * @Property(nullable=false, description="Текст")
     * @Assert\NotBlank()
     */
    public $text;

    /**
     * @var \DateTimeImmutable
     * @Property(readOnly=true, description="Дата создания")
     */
    public $createdAt;

    /**
     * @var UuidInterface|null
     * @Property(nullable=true, type="string", example=null, description="Id родительского комментария")
     */
    public $parentId;

    /**
     * @var array
     * @Property(type="array", readOnly=true, @Items(ref="#/components/schemas/Comment"))
     */
    public $replies = [

    ];

    public static function fromArray(array $data, Connection $connection): self
    {
        $self = new self();
        $self->id = $data['id'];
        $self->userId = $data['userId'];
        $self->userName = $data['userName'];
        $self->userAvatar = '/static/ava.png';
        $self->text = $data['text'];
        $self->parentId = $data['parentId'];
        $self->createdAt = $connection->convertToPHPValue($data['createdAt'], 'datetimetz_immutable');
        $self->replies = array_map(function ($item) use ($connection) {
            return self::fromArray($item, $connection);
        }, $data['replies'] ?? []);
        return $self;
    }
}
