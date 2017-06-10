<?php

declare(strict_types=1);

namespace Building\Domain\Service;

interface IsUserBanned
{
    public function __invoke(string $username) : bool;
}
