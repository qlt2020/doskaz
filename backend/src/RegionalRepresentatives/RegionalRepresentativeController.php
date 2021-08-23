<?php


namespace App\RegionalRepresentatives;

use App\Blog\Image;
use App\Infrastructure\Doctrine\Flusher;
use Doctrine\DBAL\Connection;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RegionalRepresentativeController extends AbstractController
{
    /**
     * @Route(path="/api/regionalRepresentatives", methods={"GET"})
     * @param Connection $connection
     * @return array
     * @Get(
     *     path="/api/regionalRepresentatives",
     *     tags={"Региональные координаторы"},
     *     summary="Список региональных координаторов",
     *     @Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *             type="array",
     *             @Items(
     *                 type="object",
     *                 @Property(property="id", type="string"),
     *                 @Property(property="name", type="string"),
     *                 @Property(property="email", type="string"),
     *                 @Property(property="phone", type="string"),
     *                 @Property(property="department", type="string"),
     *                 @Property(property="cityId", type="integer"),
     *                 @Property(property="image", type="string"),
     *             )
     *         )
     *     )
     * )
     */
    public function index(Connection $connection)
    {
        $items = $connection->createQueryBuilder()
            ->select([
                'id',
                'name',
                'email',
                'phone',
                'department',
                'city_id as "cityId"',
                'image'
            ])
            ->from('regional_representatives')
            ->andWhere('deleted_at IS NULL')
            ->orderBy('created_at', 'desc')
            ->execute()
            ->fetchAll();

        return array_map(function ($item) use ($connection) {
            /**
             * @var $photo Image
             */
            $photo = $connection->convertToPHPValue($item['image'], Image::class);
            return array_replace($item, [
                'image' => $photo->resize(200)
            ]);
        }, $items);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(path="/api/admin/regionalRepresentatives", methods={"GET"})
     * @param Connection $connection
     */
    public function adminList(Connection $connection, Request $request)
    {
        $qb = $connection->createQueryBuilder()
            ->select([
                'id',
                'name',
                'email',
                'phone',
                'department',
                'city_id as "cityId"'
            ])
            ->from('regional_representatives')
            ->andWhere('deleted_at IS NULL');

        $count = (clone $qb)->select('count(*)')->execute()->fetchColumn();
        $items = $qb
            ->setMaxResults(20)
            ->setFirstResult($request->query->getInt('offset'))
            ->orderBy('created_at', 'desc')
            ->execute()
            ->fetchAll();

        return [
            'count' => $count,
            'items' => $items
        ];
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(path="/api/admin/regionalRepresentatives", methods={"POST"})
     * @param RegionalRepresentativeRepository $repository
     * @param Flusher $flusher
     * @param RegionalRepresentativeData $data
     */
    public function create(RegionalRepresentativeRepository $repository, Connection $connection, Flusher $flusher, RegionalRepresentativeData $data)
    {
        $item = new RegionalRepresentative(
            $data->cityId,
            $data->name,
            $data->email,
            $data->phone,
            $data->department,
            $data->photo
        );
        $repository->add($item);
        $flusher->flush();

        return $this->retrieve($item->id(), $connection);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(path="/api/admin/regionalRepresentatives/{id}", methods={"GET"})
     * @param $id
     * @param Connection $connection
     * @return RegionalRepresentativeData
     */
    public function retrieve($id, Connection $connection)
    {
        $item = $connection->createQueryBuilder()
            ->select([
                'id',
                'name',
                'phone',
                'city_id',
                'department',
                'image',
                'email'
            ])
            ->from('regional_representatives')
            ->andWhere('id = :id')
            ->setParameter('id', $id)
            ->andWhere('deleted_at IS NULL')
            ->execute()
            ->fetch();

        if (!$item) {
            throw new NotFoundHttpException('Not Found');
        }

        $data = new RegionalRepresentativeData();
        $data->id = $item['id'];
        $data->name = $item['name'];
        $data->email = $item['email'];
        $data->phone = $item['phone'];
        $data->department = $item['department'];
        $data->cityId = $item['city_id'];
        $data->photo = $connection->convertToPHPValue($item['image'], Image::class);

        return $data;
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(path="/api/admin/regionalRepresentatives/{id}", methods={"PUT"})
     * @param RegionalRepresentativeData $data
     * @param RegionalRepresentative $item
     * @param Flusher $flusher
     */
    public function update(RegionalRepresentativeData $data, RegionalRepresentative $item, Flusher $flusher)
    {
        $item->update($data);
        $flusher->flush();
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(path="/api/admin/regionalRepresentatives/{id}", methods={"DELETE"})
     * @param RegionalRepresentative $item
     * @param Flusher $flusher
     */
    public function delete(RegionalRepresentative $item, Flusher $flusher)
    {
        $item->markAsDeleted();
        $flusher->flush();
    }
}
