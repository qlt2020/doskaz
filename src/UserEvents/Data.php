<?php


namespace App\UserEvents;

use App\UserEvents\AwardIssued\AwardIssuedData;
use App\UserEvents\BlogCommentReplied\BlogCommentRepliedData;
use App\UserEvents\LevelReached\LevelReachedData;
use App\UserEvents\ObjectAdded\ObjectAddedData;
use App\UserEvents\ObjectReviewed\ObjectReviewedData;
use App\UserEvents\ObjectSupplemented\ObjectSupplementedData;
use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @ODM()
 * @DiscriminatorMap(mapping=Data::DISCRIMINATOR_MAP, typeProperty="type")
 */
abstract class Data
{
    public const DISCRIMINATOR_MAP = [
        'object_reviewed' => ObjectReviewedData::class,
        'level_reached' => LevelReachedData::class,
        'blog_comment_replied' => BlogCommentRepliedData::class,
        'award_issued' => AwardIssuedData::class,
        'object_added' => ObjectAddedData::class,
        'object_supplemented' => ObjectSupplementedData::class
    ];
}
