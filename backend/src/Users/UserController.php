<?php


namespace App\Users;

use App\AdminpanelPermissions\AdminpanelPermission;
use App\Blog\Image;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Doctrine\Transactional;
use App\Infrastructure\FileReferenceCollection;
use App\Infrastructure\Firebase\Exception;
use App\Infrastructure\Firebase\InvalidIdToken;
use App\Infrastructure\Firebase\ProfileFetcher;
use App\Infrastructure\ObjectResolver\ValidationException;
use App\Infrastructure\Storage\Storage;
use App\Levels\LevelRepository;
use App\Tasks\CurrentTaskDataProvider;
use App\Tasks\UserTasksFinder;
use App\UserAbilities\UnlockedAbility;
use App\UserEvents\UserEventsFinder;
use App\Users\Security\PhoneAuth\Credentials;
use App\Users\Security\PhoneAuth\CredentialsRepository;
use App\Users\Services\ExportToExcelService;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Imgproxy\UrlBuilder;
use OpenApi\Annotations\Delete;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Put;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Webmozart\Assert\Assert;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route(path="/api")
 */
final class UserController extends AbstractController
{
    /**
     * @Route(path="/profile", methods={"GET"})
     * @IsGranted("ROLE_USER")
     * @Get(
     *     path="/api/profile",
     *     tags={"Пользователи"},
     *     summary="Профиль пользователя",
     *     security={{"clientAuth": {}}},
     *     @Parameter(in="query", name="cityId", @Schema(type="integer", nullable=true), description="id города для Задач"),
     *     @Response(response=401, description=""),
     *     @\OpenApi\Annotations\Response(
     *         response="200",
     *         description="",
     *         @JsonContent(
     *             @Property(property="email", type="string", nullable=true),
     *             @Property(property="phone", type="string", nullable=true),
     *             @Property(property="avatar", type="string", nullable=true),
     *             @Property(property="firstName", type="string", nullable=true),
     *             @Property(property="lastName", type="string", nullable=true),
     *             @Property(property="middleName", type="string", nullable=true),
     *             @Property(
     *                 property="currentTask",
     *                 type="object",
     *                 nullable=true,
     *                 @Property(type="number", property="progress"),
     *                 @Property(type="string", property="title"),
     *             ),
     *             @Property(
     *                 property="abilities",
     *                 type="array",
     *                 description="Разблокированные возможности",
     *                 @Items(
     *                     type="string",
     *                     enum={"status_change", "avatar_upload"}
     *                 )
     *             ),
     *             @Property(property="status", type="string", nullable=true, description="Отображаемый статус"),
     *             @Property(
     *                 property="level",
     *                 type="object",
     *                 @Property(property="current", type="number", description="Текущий уровень"),
     *                 @Property(property="currentPoints", type="number", description="Текущее количество баллов"),
     *                 @Property(property="progressToNext", type="number", description="Количество баллов оставшееся до получения следующего уровня"),
     *                 @Property(property="nextLevelThreshold", type="number", description="Количество баллов необходимых для достижения следующего уровня"),
     *             ),
     *             @Property(property="gender", type="string", nullable=true),
     *             @Property(property="category", type="string", nullable=true),
     *             @Property(property="city_id", type="integer", nullable=true),
     *             @Property(property="birthday", type="string", nullable=true),
     *         )
     *     ),
     * )
     * @param TokenStorageInterface $tokenStorage
     * @param Connection $connection
     * @param Request $request
     * @param CurrentTaskDataProvider $currentTaskProvider
     * @param LevelRepository $levelRepository
     * @return ProfileData
     * @throws DBALException
     */
    public function profile(TokenStorageInterface $tokenStorage, Connection $connection, Request $request,
                            CurrentTaskDataProvider $currentTaskProvider, LevelRepository $levelRepository)
    {
        $user = $connection->createQueryBuilder()
            ->select('users.id', 'name', 'email', 'phone_credentials.number as phone', 'roles', 'avatar', 'full_name', 'status',
                'users.gender', 'users.category', 'users.city_id', 'users.birthday')
            ->from('users')
            ->leftJoin('users', 'phone_credentials', 'phone_credentials', 'users.id = phone_credentials.id')
            ->andWhere('users.id = :id')
            ->setParameter('id', $tokenStorage->getToken()->getUser()->id())
            ->setMaxResults(1)
            ->execute()
            ->fetch();

        /**
         * @var $fullName FullName
         */
        $fullName = $connection->convertToPHPValue($user['full_name'], FullName::class);

        $level = $levelRepository->find($user['id']);

        return new ProfileData(
            $user['id'],
            $user['email'],
            $user['phone'],
            $user['avatar'] ? $user['avatar'] : null,
            $fullName->first,
            $fullName->last,
            $fullName->middle,
            $currentTaskProvider->forUser($user['id'], $request->getLocale(), $request->query->getInt('cityId', 0)),
            [
                'current' => $level->value() ?? 0,
                'currentPoints' => $level->points() ?? 0,
                'progressToNext' => $level->progressToNextLevel() ?? 0,
                'nextLevelThreshold' => $level->nextLevelThreshold() ?? 0
            ],
            [
                'objects' => $connection->createQueryBuilder()->select('COUNT(*) FROM objects WHERE created_by = :userId')->setParameter('userId', $user['id'])->execute()->fetchColumn(),
                'complaints' => $connection->createQueryBuilder()->select('COUNT(*) FROM complaints WHERE complainant_id = :userId')->setParameter('userId', $user['id'])->execute()->fetchColumn(),
            ],
            $connection->executeQuery('SELECT key FROM unlocked_abilities WHERE user_id = :userId', ['userId' => $user['id']])->fetchAll(\PDO::FETCH_COLUMN),
            $user['status'],
            $user['gender'],
            $user['category'],
            $user['city_id'],
            $user['birthday'],
        );
    }

    /**
     * @Route(path="/profile", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     * @param UpdateUserProfileData $profileData
     * @param UserRepository $repository
     * @param TokenStorageInterface $tokenStorage
     * @param ProfileFetcher $profileFetcher
     * @param CredentialsRepository $credentialsRepository
     * @param Transactional $transactional
     * @throws ValidationException
     * @Put(
     *     path="/api/profile",
     *     tags={"Пользователи"},
     *     summary="Обновление профиля пользователя",
     *     security={{"clientAuth": {}}},
     *     @RequestBody(
     *         @JsonContent(
     *             @Property(property="firstName", type="string", nullable=true),
     *             @Property(property="lastName", type="string", nullable=true),
     *             @Property(property="middleName", type="string", nullable=true),
     *             @Property(property="email", type="string", nullable=true),
     *             @Property(property="status", type="string", nullable=true, description="Отображаемый статус"),
     *             @Property(property="phoneChangeToken", type="string", nullable=true, description="firebase IdToken для смены номера телефона"),
     *             @Property(property="gender", type="string", nullable=true),
     *             @Property(property="category", type="string", nullable=true),
     *             @Property(property="city_id", type="integer", nullable=true),
     *             @Property(property="birthday", type="string", nullable=true),
     *         )
     *     ),
     *     @Response(response=401, description=""),
     *     @Response(
     *         response=400,
     *         description="Validation Failed",
     *         @JsonContent(
     *             @Property(property="message", type="string", example="Validation Failed"),
     *             @Property(property="code", type="number", example=400),
     *             @Property(
     *                 property="errors",
     *                 type="array",
     *                 @Items(
     *                     type="object",
     *                     @Property(property="property", type="string", description="Имя свойства"),
     *                     @Property(property="message", type="string", description="Текст ошибки"),
     *                 )
     *             )
     *         )
     *     ),
     * )
     *
     */
    public function updateProfile(
        UpdateUserProfileData $profileData,
        UserRepository $repository,
        TokenStorageInterface $tokenStorage,
        ProfileFetcher $profileFetcher,
        CredentialsRepository $credentialsRepository,
        Transactional $transactional
    ) {
        $user = $repository->find($tokenStorage->getToken()->getUser()->id());

        if ($profileData->email) {
            $userByEmail = $repository->findOneBy(['email' => $profileData->email]);
            if ($userByEmail && $userByEmail !== $user) {
                throw new ValidationException(new ConstraintViolationList([new ConstraintViolation('Этот email занят другим пользователем', '', [], '', 'email', '')]));
            }
        }

        $transactional->transaction(function () use ($tokenStorage, $repository, $profileData, $profileFetcher, $credentialsRepository, $user) {
            if ($profileData->phoneChangeToken) {
                try {
                    $userProfile = $profileFetcher->fetch($profileData->phoneChangeToken);
                    Assert::notEmpty($userProfile->phoneNumber);
                    $existingCredentials = $credentialsRepository->findByPhoneNumber($userProfile->phoneNumber);
                    if ($existingCredentials) {
                        if ($existingCredentials->id() !== $user->id()) {
                            throw new ValidationException(new ConstraintViolationList([new ConstraintViolation('Этот номер занят другим пользователем', '', [], '', 'phoneChangeToken', '')]));
                        }
                    } else {
                        $credentials = $credentialsRepository->find($user->id());
                        if ($credentials) {
                            $credentials->changeNumber($userProfile->phoneNumber);
                        } else {
                            $credentials = new Credentials($user->id(), $userProfile->phoneNumber);
                            $credentialsRepository->add($credentials);
                        }
                    }
                } catch (InvalidIdToken $exception) {
                    throw new ValidationException(new ConstraintViolationList([new ConstraintViolation('Неверный id токен', '', [], '', 'phoneChangeToken', '')]));
                }
            }

            $em = $this->getDoctrine()->getManager();
            $city = $profileData->city_id ? $em->getRepository('App\Cities\Cities')->find($profileData->city_id) : null;

            $user->updateProfile(new FullName($profileData->firstName, $profileData->lastName, $profileData->middleName),
                $profileData->email,
                $profileData->status,
                $profileData->gender,
                $profileData->category,
                $city,
                $profileData->birthday,
            );
        });
    }


    /**
     * @IsGranted("ROLE_USER")
     * @Route(path="/profile/chooseAvatarPreset/{presetNumber}", methods={"POST", "PUT"}, requirements={"presetNumber" = "\d+"})
     * @param $presetNumber
     * @param UserRepository $repository
     * @param Flusher $flusher
     * @return array
     * @Put(
     *     path="/api/profile/chooseAvatarPreset/{presetNumber}",
     *     tags={"Пользователи"},
     *     summary="Выбор аватара",
     *     security={{"clientAuth": {}}},
     *     @Parameter(name="presetNumber", in="path", description="Номер пресета", @Schema(type="int", enum={1, 2,3,4,5,6})),
     *     @Response(response=401, description=""),
     *     @Response(response=200,
     *         description="",
     *         @JsonContent(
     *             @Property(property="avatar", type="string", example="/static/presets/av5.svg")
     *         )
     *     )
     * )
     */
    public function chooseAvatarPreset($presetNumber, UserRepository $repository, Flusher $flusher): array
    {
        $presets = [
            1 => 'av1.svg',
            2 => 'av2.svg',
            3 => 'av3.svg',
            4 => 'av4.svg',
            5 => 'av5.svg',
            6 => 'av6.svg',
        ];

        if (!array_key_exists($presetNumber, $presets)) {
            throw new NotFoundHttpException(sprintf('Preset "%s" not exists!', $presets));
        }
        $selected = $presets[$presetNumber];
        $user = $repository->find($this->getUser()->id());
        $avatar = sprintf('/static/presets/%s', $selected);
        $user->changeAvatar($avatar);
        $flusher->flush();
        return [
            'avatar' => $avatar
        ];
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route(path="/profile/avatar", methods={"DELETE"})
     * @param UserRepository $repository
     * @param Flusher $flusher
     * @param Storage $storage
     * @throws \Doctrine\DBAL\ConnectionException
     * @Delete(
     *     path="/api/profile/avatar",
     *     tags={"Пользователи"},
     *     summary="Удаление аватара",
     *     security={{"clientAuth": {}}},
     *     @Response(response=401, description=""),
     *     @Response(response=204, description=""),
     * )
     */
    public function removeAvatar(UserRepository $repository, Flusher $flusher, Storage $storage, EntityManagerInterface $em)
    {
        $em->getConnection()->beginTransaction();
        try {
            $user = $repository->find($this->getUser()->id());
            $avatar = $user->getAvatar();
            $result = $storage->deleteOldFile($avatar);
            $user->removeAvatar();
            $flusher->flush();
            $em->getConnection()->commit();
            return new JsonResponse([
                'status' => true,
                'message' => "Успешно"
            ]);
        } catch (\Exception $exception) {
            $em->getConnection()->rollBack();
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage()
            ],400);
        }
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route(path="/profile/objects", methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @param UrlBuilder $urlBuilder
     * @return array
     * @Get(
     *     path="/api/profile/objects",
     *     tags={"Пользователи"},
     *     summary="Объекты пользователя",
     *     security={{"clientAuth": {}}},
     *     @Parameter(in="query", name="overallScore", @Schema(type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS, nullable=true), description="Оценка доступности"),
     *     @Parameter(in="query", name="sort", @Schema(type="string", enum={"date desc", "date asc"}, nullable=true), description="Сортировка"),
     *     @Parameter(in="query", name="page", @Schema(type="integer", nullable=true), description="Страница"),
     *     @Response(response=401, description=""),
     *     @Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *             @Property(property="pages", type="integer", description="Количество страниц"),
     *             @Property(
     *                 property="items",
     *                 type="array",
     *                 @Items(
     *                     type="object",
     *                     @Property(property="id", type="integer"),
     *                     @Property(property="title", type="string"),
     *                     @Property(property="date", type="string", format="date-time"),
     *                     @Property(property="overallScore", type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS, description="Общая оценка доступности"),
     *                     @Property(property="reviewsCount", type="integer", description="Количество отзывов"),
     *                     @Property(property="complaintsCount", type="integer", description="Количество жалоб"),
     *                     @Property(property="image", type="string", description="Изображение", nullable=true),
     *                 )
     *             ),
     *         )
     *     )
     * )
     */
    public function objects(Request $request, Connection $connection, UrlBuilder $urlBuilder)
    {
        $perPage = 10;

        $qb = $connection->createQueryBuilder()
            ->select([
                'o.id',
                'o.title',
                'o.created_at as date',
                'o.overall_score_movement as "overallScore"',
                'o.photos',
                'o.address',
                'ocp.icon as category_icon',
                'ocp.title as category_title',
                'oc.title as sub_category_title',
            ])
            ->from('objects', 'o')
            ->join('o','object_categories','oc','o.category_id = oc.id')
            ->join('o','object_categories','ocp', 'oc.parent_id = ocp.id')
            ->andWhere('o.created_by = :userId')
            ->andWhere('o.deleted_at IS NULL')
            ->setParameter('userId', $this->getUser()->id());


        $overallScore = $request->query->get('overallScore', 'all');
        if ($overallScore !== 'all') {
            $qb->andWhere('o.overall_score_movement = :score')
                ->setParameter('score', $overallScore);
        }

        $reviewsCountQuery = $connection->createQueryBuilder()
            ->select('count(*) AS "reviewsCount"')
            ->from('object_reviews')
            ->andWhere('object_id = o.id')
            ->getSQL();

        $complaintsCountQuery = $connection->createQueryBuilder()
            ->select('count(*) AS "complaintsCount"')
            ->from('complaints')
            ->andWhere('object_id = o.id')
            ->getSQL();

        [$field, $sort] = explode(' ', $request->query->get('sort', 'date desc'));
        $objects = (clone $qb)
            ->addSelect('reviews."reviewsCount"')
            ->addSelect('complaints."complaintsCount"')
            ->join('o', "LATERAL ($reviewsCountQuery)", 'reviews', 'true')
            ->join('o', "LATERAL ($complaintsCountQuery)", 'complaints', 'true')
            ->setMaxResults($perPage)
            ->setFirstResult(($request->query->getInt('page', 1) - 1) * $perPage)
            ->orderBy($field, $sort)
            ->execute()
            ->fetchAll();

        return [
            'pages' => $qb->select('CEIL(count(*)::FLOAT / :perPage)::INT')->setParameter('perPage', $perPage)->execute()->fetchColumn(),
            'items' => array_map(function ($object) use ($connection, $request, $urlBuilder) {
                $image = null;
                /**
                 * @var $photos FileReferenceCollection
                 */
                $photos = $connection->convertToPHPValue($object['photos'], FileReferenceCollection::class);
                if ($photos->count()) {
                    $image = $urlBuilder->build('local:///storage/' . $photos->first()->relativePath, 220, 160)->toString();
                }

                return [
                    'id' => $object['id'],
                    'title' => $object['title'],
                    'address' => $object['address'],
                    'date' => $connection->convertToPHPValue($object['date'], 'datetimetz_immutable'),
                    'category_icon' => $object['category_icon'],
                    'category_title' => $object['category_title'],
                    'sub_category_title' => $object['sub_category_title'],
                    'overallScore' => $object['overallScore'],
                    'reviewsCount' => $object['reviewsCount'],
                    'complaintsCount' => $object['complaintsCount'],
                    'image' => $image
                ];
            }, $objects)
        ];
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route(path="/profile/comments", methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @param UrlBuilder $urlBuilder
     * @return array
     * @Get(
     *     path="/api/profile/comments",
     *     tags={"Пользователи"},
     *     summary="Комментарии пользователя",
     *     security={{"clientAuth": {}}},
     *     @Parameter(in="query", name="sort", @Schema(type="string", enum={"date desc", "popularity desc"}, nullable=true), description="Сортировка"),
     *     @Parameter(in="query", name="page", @Schema(type="integer", nullable=true), description="Страница"),
     *     @Response(response=401, description=""),
     *     @Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *             @Property(property="pages", type="integer", description="Количество страниц"),
     *             @Property(
     *                 property="items",
     *                 type="array",
     *                 @Items(
     *                     type="object",
     *                     @Property(property="date", type="string", format="date-time"),
     *                     @Property(property="type", type="string", enum={"post", "object"}, description="вид комментария"),
     *                     @Property(property="image", type="string", description="Изображение", nullable=true),
     *                     @Property(property="title", type="string", description="Название комментируемого материала"),
     *                     @Property(property="text", type="string", description="Текст комментария"),
     *                     @Property(property="objectId", type="integer", nullable=true, description="Id объекта (если type == object)"),
     *                     @Property(property="postId", type="integer", nullable=true, description="Id поста (если type == post)"),
     *                     @Property(property="slug", type="string", nullable=true, description=""),
     *                     @Property(property="categorySlug", type="string", nullable=true, description=""),
     *                 )
     *             ),
     *         )
     *     )
     * )
     */
    public function comments(Request $request, Connection $connection, UrlBuilder $urlBuilder)
    {
        [$field, $sort] = explode(' ', $request->query->get('sort', 'date desc'));
        $perPage = 10;
        $userId = $this->getUser()->id();
        $page = $request->query->getInt('page', 1) - 1;

        $postComments = $connection->createQueryBuilder()
            ->select([
                'blog_comments.id',
                'blog_comments.text',
                'blog_comments.created_at as date',
                'blog_comments.popularity',
                'blog_posts.title',
                'blog_posts.id as "postId"',
                'blog_posts.slug_value as slug',
                'blog_categories.slug_value as "categorySlug"',
                'blog_posts.image',
            ])
            ->from('blog_comments')
            ->leftJoin('blog_comments', 'blog_posts', 'blog_posts', 'blog_posts.id = blog_comments.post_id')
            ->leftJoin('blog_posts', 'blog_categories', 'blog_categories', 'blog_posts.category_id = blog_categories.id')
            ->andWhere('blog_comments.user_id = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('blog_comments.is_published = true')
            ->setMaxResults($perPage * ($page + 1))
            ->execute()
            ->fetchAll(\PDO::FETCH_UNIQUE);

        $objectReviews = $connection->createQueryBuilder()
            ->select([
                'object_reviews.id',
                'object_reviews.text',
                'object_reviews.created_at as date',
                'objects.title',
                'objects.id as "objectId"',
                'objects.photos'
            ])
            ->from('object_reviews')
            ->leftJoin('object_reviews', 'objects', 'objects', 'objects.id = object_reviews.object_id')
            ->andWhere('object_reviews.author_id = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('object_reviews.deleted_at is NULL')
            ->andWhere('object_reviews.is_published = true')
            ->setMaxResults($perPage * ($page + 1))
            ->execute()
            ->fetchAll(\PDO::FETCH_UNIQUE);

        $items = array_merge($postComments, $objectReviews);

        uasort($items, function ($a, $b) use ($sort, $field) {
            $value = ($a[$field] ?? 0) <=> ($b[$field] ?? 0);
            return ($sort === 'desc') ? (- $value) : $value;
        });

        $results = [];

        foreach (array_slice($items, $page * $perPage , $perPage) as $id => $item) {
            $result = [
                'id' => $id,
                'date' => $connection->convertToPHPValue($item['date'], 'datetimetz_immutable'),
                'image' => null,
                'objectId' => null,
                'postId' => null,
                'slug' => null,
                'categorySlug' => null
            ];

            if (array_key_exists($id, $postComments)) {
                $postComment = $postComments[$id];
                $result['type'] = 'post';
                $result['slug'] = $postComment['slug'];
                $result['categorySlug'] = $postComment['categorySlug'];
                $result['title'] = $postComment['title'];
                $result['text'] = $postComment['text'];
                $result['postId'] = $postComment['postId'];

                /**
                 * @var $image Image|null
                 */
                $image = $connection->convertToPHPValue($postComment['image'], Image::class);
                if ($image) {
                    $result['image'] = $image->resize(140, 100);
                }
            }

            if (array_key_exists($id, $objectReviews)) {
                $objectReview = $objectReviews[$id];
                $result['type'] = 'object';
                $result['title'] = $objectReview['title'];
                $result['text'] = $objectReview['text'];
                $result['objectId'] = $objectReview['objectId'];

                /**
                 * @var $photos FileReferenceCollection
                 */
                $photos = $connection->convertToPHPValue($objectReview['photos'], FileReferenceCollection::class);
                if ($photos->count()) {
                    $result['image'] = $urlBuilder->build('local:///storage/' . $photos->first()->relativePath, 140, 100)->toString();
                }
            }

            $results[] = $result;
        }

        return [
            'pages' => ceil(count($items) / $perPage),
            'items' => $results
        ];
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route(path="/profile/complaints", methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @param UrlBuilder $urlBuilder
     * @return array
     * @Get(
     *     path="/api/profile/complaints",
     *     tags={"Пользователи"},
     *     security={{"clientAuth": {}}},
     *     summary="Жалобы пользователя",
     *     @Parameter(in="query", name="sort", @Schema(type="string", enum={"date desc", "date asc"}, nullable=true), description="Сортировка"),
     *     @Parameter(in="query", name="page", @Schema(type="integer", nullable=true), description="Страница"),
     *     @Response(response=401, description=""),
     *     @Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *             @Property(property="pages", type="integer"),
     *             @Property(
     *                 property="items",
     *                 type="array",
     *                 @Items(
     *                     type="object",
     *                     @Property(property="id", type="integer"),
     *                     @Property(property="type", type="string", enum={"complaint1", "complaint2"}, description="Вид жалобы (жалоба на отсутствие пандуса, жалоба на отсутствие доступа)"),
     *                     @Property(property="title", type="string"),
     *                     @Property(property="date", type="string", format="date-time"),
     *                     @Property(property="image", type="string", nullable=true),
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function complaints(Request $request, Connection $connection, UrlBuilder $urlBuilder)
    {
        $perPage = 10;

        $qb = $connection->createQueryBuilder()
            ->from('complaints')
            ->andWhere('complainant_id = :userId')
            ->setParameter('userId', $this->getUser()->id());

        [$field, $sort] = explode(' ', $request->query->get('sort', 'date desc'));


        $items = (clone $qb)->select([
            'id',
            'created_at',
            'created_at as date',
            'content->>\'objectName\' as "title"',
            'content->>\'photos\' as photos',
            'content->>\'type\' as type',
            'content->>\'cityId\' as city_id',
            'content->>\'street\' as street',
            'content->>\'building\' as building',
            'content->>\'office\' as office',
            'content->>\'visitPurpose\' as visit_purpose',
        ])
            ->orderBy($field, $sort)
            ->setMaxResults($perPage)
            ->setFirstResult(($request->query->getInt('page', 1) - 1) * $perPage)
            ->execute()->fetchAll();

        return [
            'pages' => $qb->select('CEIL(count(*)::FLOAT / :perPage)::INT')->setParameter('perPage', $perPage)->execute()->fetchColumn(),
            'items' => array_map(function ($item) use ($connection, $request, $urlBuilder) {

                $em = $this->getDoctrine()->getManager();
                $city = $item['city_id'] ? $em->getRepository('App\Cities\Cities')->find($item['city_id']) : null;
                $city_name = $city ? $city->getName() : "";

                $image = null;
                $photos = $connection->convertToPHPValue($item['photos'], 'json');
                if (count($photos)) {
                    $image = $urlBuilder->build('local://' . $photos[0], 220, 160)->toString();
                }

                return [
                    'id' => $item['id'],
                    'type' => $item['type'],
                    'title' => $item['title'],
                    'date' => $connection->convertToPHPValue($item['created_at'], 'datetimetz_immutable'),
                    'image' => $image,
                    'city_name' => $city_name,
                    'street' => $item['street'],
                    'building' => $item['building'],
                    'office' => $item['office'],
                    'visit_purpose' => $item['visit_purpose'],
                ];
            }, $items)
        ];
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route(path="/profile/tasks", methods={"GET"})
     * @param Request $request
     * @param UserTasksFinder $userTasksFinder
     * @return mixed[]
     * @Get(
     *     path="/api/profile/tasks",
     *     tags={"Пользователи"},
     *     security={{"clientAuth": {}}},
     *     summary="Задания пользователя",
     *     @Parameter(in="query", name="sort", @Schema(type="string", enum={"createdAt desc", "createdAt asc"}, nullable=true), description="Сортировка"),
     *     @Parameter(in="query", name="page", @Schema(type="integer", nullable=true), description="Страница"),
     *     @Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *             @Property(property="pages", type="integer", description="Количество страниц"),
     *             @Property(
     *                 property="items",
     *                 type="array",
     *                 @Items(
     *                     type="object",
     *                     @Property(property="completedAt", type="string", format="date-time", nullable=true),
     *                     @Property(property="createdAt", type="string", format="date-time", nullable=true),
     *                     @Property(property="title", type="string", description="Название задачи"),
     *                     @Property(property="points", type="integer", description="Количество баллов за выполнение задачи", example=5),
     *                 )
     *             ),
     *         )
     *     ),
     *     @Response(response=401, description=""),
     * )
     */
    public function tasks(Request $request, UserTasksFinder $userTasksFinder)
    {
        return $userTasksFinder->findForUser($this->getUser()->id(), $request->query->getInt('cityId', 0), $request->query->getInt('page', 1), $request->query->get('sort', 'createdAt desc'), $request->getLocale());
    }

    /**
     * @Route(path="/profile/events", methods={"GET"})
     * @param UserEventsFinder $eventsFinder
     * @return array
     * @Get(
     *     path="/api/profile/events",
     *     summary="Лог событий",
     *     tags={"Пользователи"},
     *     security={{"clientAuth": {}}},
     *     @Response(response=401, description=""),
     *     @Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *             @Property(
     *                 property="items",
     *                 type="array",
     *                 @Items(
     *                     type="object",
     *                     @Property(property="date", type="string", format="date-time"),
     *                     @Property(property="type", type="string", enum={"object_reviewed", "level_reached", "blog_comment_replied", "award_issued", "object_created", "object_supplemented"}),
     *                     @Property(
     *                         property="data",
     *                         type="object",
     *                         oneOf={
     *                             @Schema(
     *                                 title="award_issued",
     *                                 @Property(property="title", type="string", description="Наименование награды"),
     *                                 @Property(property="type", type="string", description="Вид", enum={"bronze", "silver", "gold"}),
     *                             ),
     *                             @Schema(
     *                                 title="level_reached",
     *                                 @Property(property="level", type="integer", description="Уровень"),
     *                                 @Property(property="pointsUntilNextLevel", type="integer", description="Баллов до следующего уровня"),
     *                                 @Property(property="unlockedAbility", type="string", nullable=true, description="Разблокированная возможность", enum={"status_change", "avatar_upload"}),
     *                             ),
     *                             @Schema(
     *                                 title="object_reviewed",
     *                                 @Property(property="id", type="integer", description="id объекта"),
     *                                 @Property(property="username", type="string", nullable=true, description="Имя пользователя"),
     *                                 @Property(property="title", type="string", description="Наименование объекта"),
     *                             ),
     *                             @Schema(
     *                                 title="blog_comment_replied",
     *                                 @Property(property="id", type="integer", description="id поста"),
     *                                 @Property(property="username", type="string", nullable=true, description="Имя пользователя"),
     *                                 @Property(property="title", type="string", description="Наименование поста"),
     *                             ),
     *                             @Schema(
     *                                 title="object_added",
     *                                 @Property(property="id", type="integer", description="id объекта"),
     *                                 @Property(property="title", type="string", description="Наименование поста"),
     *                                 @Property(property="categoryTitle", type="string", description="Наименование подкатегории"),
     *                             ),
     *                              @Schema(
     *                                 title="object_supplemented",
     *                                 @Property(property="id", type="integer", description="id объекта"),
     *                                 @Property(property="username", type="string", nullable=true, description="Имя пользователя"),
     *                                 @Property(property="userId", type="string", nullable=true, description="Id пользователя"),
     *                                 @Property(property="title", type="string", description="Наименование объекта"),
     *                             ),
     *                         },
     *                     ),
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function events(UserEventsFinder $eventsFinder)
    {
        return $eventsFinder->execute($this->getUser()->id(), 1, 'date', 'desc');
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route(path="/profile/avatar")
     * @param Request $request
     * @param UserRepository $userRepository
     * @param Storage $storage
     * @throws DBALException
     * @Post(
     *     path="/api/profile/avatar",
     *     tags={"Пользователи"},
     *     security={{"clientAuth": {}}},
     *     summary="Загрузка аватара",
     *     @Response(response=401, description=""),
     *     @Response(response=403, description=""),
     *     @Response(response=204, description=""),
     *     @RequestBody(
     *         description="файл для загрузки",
     *         @MediaType(
     *             mediaType="application/octet-stream"
     *         )
     *     ),
     * )
     */
    public function avatar(Request $request, UserRepository $userRepository, Storage $storage, Flusher $flusher, EntityManagerInterface $em)
    {
        $em->getConnection()->beginTransaction();
        try {
            $id = $this->getUser()->id();
            $user = $userRepository->find($id);
            $avatar = $user->getAvatar();
            $result = $storage->deleteOldFile($avatar);
            $avatarPath = $storage->uploadFile($request->getContent(true));
            $user->changeAvatar('/storage/' . $avatarPath);
            $flusher->flush();
            $em->getConnection()->commit();
            return new JsonResponse([
                'status' => true,
                'message' => "Успешно"
            ]);
        } catch (\Exception $exception) {
            $em->getConnection()->rollBack();
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage()
            ],400);
        }
    }


    /**
     * @IsGranted("ROLE_USER")
     * @Route(path="/profile/awards", methods={"GET"})
     * @Get(
     *     path="/api/profile/awards",
     *     tags={"Пользователи"},
     *     summary="Награды пользователя",
     *     security={{"clientAuth": {}}},
     *     @Response(response=401, description=""),
     *     @Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *             @Property(property="id", type="string", example="0eea2236-fa6f-4068-aebf-94c478fa3d38"),
     *             @Property(property="title", type="string", example="Добавлено 3 объекта"),
     *             @Property(property="type", type="string", enum={"bronze", "silver", "gold"}),
     *         )
     *     ),
     * )
     * @param Connection $connection
     * @param Request $request
     * @return mixed[]
     */
    public function awards(Connection $connection, Request $request, TranslatorInterface $translator)
    {
        $lang = $request->getLocale();
 
        return $connection->createQueryBuilder()
            ->addSelect('id', 'type')
            ->addSelect("CASE WHEN title = 'Добавлено 3 объекта' then '{$translator->trans('Добавлено 3 объекта', [], 'attributes', $lang)}'
                        when title = 'Добавлено 8 объектов' then '{$translator->trans('Добавлено 8 объектов', [], 'attributes', $lang)}'
                        when title = 'Добавлено 15 объектов' then '{$translator->trans('Добавлено 15 объектов', [], 'attributes', $lang)}'
                        else title end as title")
            ->from('awards')
            ->orderBy('issued_at', 'desc')
            ->where('awards.user_id = :userId')
            ->setParameter('userId', $this->getUser()->id())
            ->execute()
            ->fetchAll();
    }

    /**
     * @Route(path="/dashboard/users/statistics", methods={"GET"})
     * @Get(
     *     path="/api/dashboard/users/statistics",
     *     summary="Статистика по кол-ву пользователей в разрезе категории",
     *     tags={"Пользователи"},
     *     security={{"clientAuth": {}}},
     *     @Response(response=200, description=""),
     * )
     */
    public function dashboardUserStatistics(Connection $connection)
    {
        try {
            $query = $connection->createQueryBuilder();
            $categories = User::USER_CATEGORIES;

            foreach ($categories as $category) {
                $query->addSelect("SUM (CASE WHEN users.category = '{$category}' THEN 1 ELSE 0 END) AS {$category}");
            }
            $query->addSelect('count(*) as registered');
            $query->addSelect('SUM (CASE WHEN users.gender = \'m\' THEN 1 ELSE 0 END) AS men');
            $query->addSelect('SUM (CASE WHEN users.gender = \'f\' THEN 1 ELSE 0 END) AS women');
            $query = $query->from('users')
                ->execute()
                ->fetchAll();

            $data = [];
            foreach ($query[0] as $key => $value) {
               if(in_array($key, array_map('strtolower', $categories))){
                   $data['categories'][$key] = $value;
               }else{
                   $data[$key] = $value;
               }
            }

            return new JsonResponse($data);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * @Route(path="/dashboard/users/age-statistics", methods={"GET"})
     * @Get(
     *     path="/api/dashboard/users/age-statistics",
     *     summary="Статистика по возрасту в разрезе категории",
     *     @Parameter(name="category", in="query", description="Категория", @Schema(type="string", nullable=true)),
     *     tags={"Пользователи"},
     *     security={{"clientAuth": {}}},
     *     @Response(response=200, description=""),
     * )
     */
    public function dashboardCategoryByAges(Connection $connection, Request $request)
    {
        try {
            $query = $connection->createQueryBuilder()
                ->addSelect('SUM (CASE WHEN date_part(\'year\', age(users.birthday)) BETWEEN \'18\' and \'23\' THEN 1 ELSE 0 END) AS from_18_to_23')
                ->addSelect('SUM (CASE WHEN date_part(\'year\', age(users.birthday)) BETWEEN \'23\' and \'28\' THEN 1 ELSE 0 END) AS from_23_to_28')
                ->addSelect('SUM (CASE WHEN date_part(\'year\', age(users.birthday)) BETWEEN \'28\' and \'33\' THEN 1 ELSE 0 END) AS from_28_to_33')
                ->addSelect('SUM (CASE WHEN date_part(\'year\', age(users.birthday)) BETWEEN \'33\' and \'38\' THEN 1 ELSE 0 END) AS from_33_to_38')
                ->addSelect('SUM (CASE WHEN date_part(\'year\', age(users.birthday)) BETWEEN \'38\' and \'43\' THEN 1 ELSE 0 END) AS from_38_to_43')
                ->addSelect('SUM (CASE WHEN date_part(\'year\', age(users.birthday)) BETWEEN \'43\' and \'50\' THEN 1 ELSE 0 END) AS from_43_to_50')
                ->addSelect('SUM (CASE WHEN date_part(\'year\', age(users.birthday)) BETWEEN \'50\' and \'100\' THEN 1 ELSE 0 END) AS from_50_to_100')
                ->from('users');

            if ($request->query->has('category')) {
                Assert::oneOf($request->query->getAlpha('category'), User::USER_CATEGORIES);
                $query->where("users.category = '{$request->query->getAlpha('category')}'");
            }

            return new JsonResponse($query->execute()->fetchAll());
        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
