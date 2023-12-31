<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testValidEntity(): void
    {
        $user = (new User())
            ->setEmail('lalalalala@gmail.com');
        self::assertSame('lalalalala@gmail.com', $user->getEmail());
    }
}
