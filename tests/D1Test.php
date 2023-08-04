<?php

namespace RenokiCo\L1\Test;

use Illuminate\Support\Facades\DB;
use RenokiCo\L1\Test\Models\User;

class D1Test extends TestCase
{
    public function test_d1_database_select()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
    }

    public function test_d1_database_transaction()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        DB::transaction(function () {
            /** @var User $user */
            $user = factory(User::class)->create();
            $dbUser = User::whereEmail($user->email)->first();

            $this->assertEquals($user->id, $dbUser->id);

            return $dbUser;
        });
    }
}
