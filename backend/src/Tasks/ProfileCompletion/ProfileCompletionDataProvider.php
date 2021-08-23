<?php


namespace App\Tasks\ProfileCompletion;

use Doctrine\DBAL\Connection;

class ProfileCompletionDataProvider
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    public function forUser(int $id): ProfileCompletionTaskData
    {
        $data = $this->connection->createQueryBuilder()
            ->select([
                "users.full_name->>'first' as \"firstName\"",
                "users.full_name->>'last' as \"lastName\"",
                "users.full_name->>'middle' as \"middleName\"",
            ])
            ->from('users')
            ->leftJoin('users', 'phone_credentials', 'phone_credentials', 'phone_credentials.id = users.id')
            ->andWhere('users.id = :id')
            ->setParameter('id', $id)
            ->execute()
            ->fetch();

        return new ProfileCompletionTaskData(
            $data['firstName'],
            $data['lastName'],
            $data['middleName']
        );
    }
}
