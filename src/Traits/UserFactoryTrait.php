<?php

namespace Tripteki\Helpers\Traits;

use Tripteki\Helpers\Contracts\AuthModelContract;

trait UserFactoryTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function user()
    {
        return app(AuthModelContract::class)->factory()->create();
    }
};
