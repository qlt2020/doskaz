<?php


namespace App\ProfileNotifications;

use App\Infrastructure\Doctrine\Flusher;
use Doctrine\DBAL\Connection;
use OpenApi\Annotations\Delete;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/profileNotifications")
 * @IsGranted("ROLE_USER")
 */
class ProfileNotificationController extends AbstractController
{
    /**
     * @Route(methods={"GET"})
     * @param Connection $connection
     * @return array
     * @Get(
     *     path="/api/profileNotifications",
     *     summary="Уведомления",
     *     tags={"Пользователи"},
     *     security={{"clientAuth": {}}},
     *     @Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *             type="array",
     *             @Items(
     *                 @Property(property="id", type="string", example="ad8ff7d4-5ac4-4575-a67b-3c2be3e6027a"),
     *                 @Property(
     *                     property="data",
     *                     type="object",
     *                     oneOf={
     *                         @Schema(
     *                             title="levelReached",
     *                             @Property(property="level", type="number"),
     *                             @Property(property="type", type="string", example="levelReached"),
     *                             @Property(property="unlockedAbility", type="string", example="avatar_upload"),
     *                         ),
     *                         @Schema(
     *                             title="pointsEarned",
     *                             @Property(property="points", type="number"),
     *                             @Property(property="type", type="string", example="pointsEarned"),
     *                             @Property(property="taskName", type="string", example="Заполните профиль"),
     *                             @Property(property="taskType", type="string", enum={"profile_completion", "administration", "daily"}),
     *                         ),
     *                     }
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function list(Connection $connection)
    {
        $notifications = $connection->createQueryBuilder()
            ->select('id', 'data')
            ->from('profile_notifications')
            ->where('closed_at IS NULL')
            ->orderBy('created_at', 'asc')
            ->andWhere('user_id = :userId')
            ->setParameter('userId', $this->getUser()->id())
            ->execute()
            ->fetchAll();

        return array_map(fn ($notification) => [
            'id' => $notification['id'],
            'data' => $connection->convertToPHPValue($notification['data'], ProfileNotificationData::class)
        ], $notifications);
    }

    /**
     * @param ProfileNotification $notification
     * @param Flusher $flusher
     * @Route(path="/{id}", methods={"DELETE"})
     * @Delete(
     *     path="/api/profileNotifications/{id}",
     *     summary="Закрыть уведомление",
     *     tags={"Пользователи"},
     *     security={{"clientAuth": {}}},
     *     @Parameter(name="id", in="path", required=true, description="id уведомления", @Schema(type="string"), example="ad8ff7d4-5ac4-4575-a67b-3c2be3e6027a"),
     *     @Response(response=204, description=""),
     *     @Response(response=404, description=""),
     *     @Response(response=401, description="")
     * )
     */
    public function close(ProfileNotification $notification, Flusher $flusher)
    {
        $notification->close();
        $flusher->flush();
    }
}
