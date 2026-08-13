<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

/**
 * `full_name` est affiché partout (messagerie, tableaux de bord, journal
 * admin) et se rabat sur plusieurs champs selon ce qui est renseigné. Ce
 * comportement n'était couvert par aucun test.
 */
class UserFullNameTest extends TestCase
{
    public function test_it_joins_the_first_and_last_name(): void
    {
        $user = new User(['first_name' => 'Awa', 'last_name' => 'Diop']);

        $this->assertSame('Awa Diop', $user->full_name);
    }

    public function test_it_falls_back_to_the_account_name_when_both_are_missing(): void
    {
        $user = new User(['name' => 'awa@example.com']);

        $this->assertSame('awa@example.com', $user->full_name);
    }

    public function test_it_does_not_leave_a_dangling_space_when_only_one_is_set(): void
    {
        $this->assertSame('Awa', (new User(['first_name' => 'Awa']))->full_name);
        $this->assertSame('Diop', (new User(['last_name' => 'Diop']))->full_name);
    }

    public function test_it_returns_an_empty_string_when_nothing_is_set(): void
    {
        $this->assertSame('', (new User)->full_name);
    }
}
