<?php

namespace Orchestra\Installation\Specifications;

use Illuminate\Support\Facades\DB;
use PDOException;

class DatabaseConnection extends Specification
{
    /**
     * Specification uid.
     *
     * @var string
     */
    protected $uid = 'db';

    /**
     * Specification title.
     *
     * @var string
     */
    protected $title = 'Database connection has been configured correctly';

    /**
     * Specification description.
     *
     * @var string
     */
    protected $description = 'Orchestra Platform would use database connection configured via <code>config/database.php</code> or <code>.env</code>. Please make sure the configuration is correct.';

    /**
     * Check specification requirement.
     *
     * @return bool
     */
    public function check(): bool
    {
        try {
            DB::connection()->getPdo();
        } catch (PDOException $e) {
            $this->error = $e->getMessage();

            return false;
        }

        return true;
    }
}
