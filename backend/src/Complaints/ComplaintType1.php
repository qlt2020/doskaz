<?php
declare(strict_types=1);

namespace App\Complaints;

use OpenApi\Annotations\Schema;

/**
 * @Schema(title="Жалоба на отсутствие пандуса", schema="ComplaintContent1")
 */
final class ComplaintType1 extends ComplaintContent
{
}
