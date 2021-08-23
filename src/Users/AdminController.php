<?php


namespace App\Users;

use App\AdminpanelPermissions\AdminpanelPermission;
use App\Infrastructure\Doctrine\Flusher;
use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route(path="/api")
 * @IsGranted("ROLE_USER")
 */
class AdminController extends AbstractController
{
    /**
     * @Route(path="/admin/profile", methods={"GET"})
     * @param TokenStorageInterface $tokenStorage
     * @param Connection $connection
     * @return array
     */
    public function me(TokenStorageInterface $tokenStorage, Connection $connection)
    {
        $this->denyAccessUnlessGranted('adminpanel_access');

        $user = $connection->createQueryBuilder()
            ->select('users.id', 'full_name->>\'firstAndLast\' as name', 'email', 'phone_credentials.number as phone', 'roles', 'users.created_at as "createdAt"')
            ->from('users')
            ->leftJoin('users', 'phone_credentials', 'phone_credentials', 'users.id = phone_credentials.id')
            ->andWhere('users.id = :id')
            ->setParameter('id', $tokenStorage->getToken()->getUser()->id())
            ->setMaxResults(1)
            ->execute()
            ->fetch();

        return array_replace($user, [
            'roles' => $connection->convertToPHPValue($user['roles'], 'json_array'),
            'createdAt' => $connection->convertToPHPValue($user['createdAt'], 'datetimetz_immutable'),
            'permissions' => array_filter(AdminpanelPermission::ALL, function ($permission) {
                return $this->isGranted($permission);
            })
        ]);
    }

    /**
     * @Route(path="/users", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param Connection $connection
     * @return array
     */
    public function list(Request $request, Connection $connection)
    {
        $filter = json_decode($request->query->get('filter', '{}'), true);

        $usersQb = $connection->createQueryBuilder()
            ->select('users.id', 'full_name->>\'firstAndLast\' as name', 'email', 'phone_credentials.number as phone', 'roles', 'users.created_at as "createdAt"')
            ->from('users')
            ->leftJoin('users', 'phone_credentials', 'phone_credentials', 'users.id = phone_credentials.id');


        foreach ($filter as $key => $value) {
            if (empty($value)) {
                continue;
            }
            switch ($key) {
                case 'email':
                    $usersQb->orWhere('email ilike :email')
                        ->setParameter('email', "%$value%");
                    break;
                case 'phone':
                    $usersQb->orWhere('phone_credentials.number ilike :phone')
                        ->setParameter('phone', "%$value%");
                    break;
            }
        }

        $count = (clone $usersQb)->select('count(*)')->execute()->fetchColumn();

        $items = array_map(function ($user) use ($connection) {
            return array_replace($user, [
                'roles' => $connection->convertToPHPValue($user['roles'], 'json_array'),
                'createdAt' => $connection->convertToPHPValue($user['createdAt'], 'datetimetz_immutable')
            ]);
        }, $usersQb->setMaxResults($request->query->getInt('limit', 20))->setFirstResult($request->query->getInt('offset'))->orderBy('id', 'desc')->execute()->fetchAll());

        return [
            'items' => $items,
            'count' => $count
        ];
    }

    /**
     * @Route(path="/users/{id}", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @param $id
     * @param Connection $connection
     * @return array
     */
    public function user($id, Connection $connection)
    {
        $user = $connection->createQueryBuilder()
            ->select('users.id', 'name', 'email', 'phone_credentials.number as phone', 'roles', 'users.created_at as "createdAt"')
            ->from('users')
            ->leftJoin('users', 'phone_credentials', 'phone_credentials', 'users.id = phone_credentials.id')
            ->andWhere('users.id = :id')
            ->setParameter('id', $id)
            ->execute()
            ->fetch();

        if (!$user) {
            throw new NotFoundHttpException();
        }

        return array_replace($user, [
            'roles' => $connection->convertToPHPValue($user['roles'], 'json_array'),
            'createdAt' => $connection->convertToPHPValue($user['createdAt'], 'datetimetz_immutable')
        ]);
    }

    /**
     * @Route(path="/users/{id}", methods={"PUT"})
     * @IsGranted("ROLE_ADMIN")
     * @param User $user
     * @param UserData $data
     * @param Flusher $flusher
     */
    public function update(User $user, UserData $data, Flusher $flusher)
    {
        $user->update($data);
        $flusher->flush();
    }
}
