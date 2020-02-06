<?php

declare(strict_types=1);

namespace Buddy\Repman\Tests\Integration\MessageHandler\Organization;

use Buddy\Repman\Message\Organization\AddToken;
use Buddy\Repman\Message\Organization\CreateOrganization;
use Buddy\Repman\Message\User\CreateUser;
use Buddy\Repman\Query\User\OrganizationQuery\DbalOrganizationQuery;
use Buddy\Repman\Tests\Integration\IntegrationTestCase;

final class AddTokenHandlerTest extends IntegrationTestCase
{
    public function testAddToken(): void
    {
        // given
        $this->dispatchMessage(new CreateUser($userId = 'a257d5e9-a01d-4e4e-9984-a9b701ae24b9', 'test@buddy.works', 'secret', 'token'));
        $this->dispatchMessage(new CreateOrganization($orgId = '9378a46a-87d8-4d6e-bafd-26293b4f8a89', $userId, 'Buddy'));
        // when
        $this->dispatchMessage(new AddToken($orgId, 'random-string', 'prod'));
        // then
        $tokens = $this->container()->get(DbalOrganizationQuery::class)->findAllTokens($orgId);
        self::assertCount(1, $tokens);
        self::assertEquals('random-string', $tokens[0]->value());
        self::assertEquals('prod', $tokens[0]->name());
    }
}
