<?php

declare(strict_types=1);

namespace Scheb\Tombstone\Analyzer\Matching;

use Scheb\Tombstone\Analyzer\TombstoneIndex;
use Scheb\Tombstone\Tombstone;
use Scheb\Tombstone\Vampire;

class MethodNameStrategy implements MatchingStrategyInterface
{
    public function matchVampireToTombstone(Vampire $vampire, TombstoneIndex $tombstoneIndex): ?Tombstone
    {
        $method = $vampire->getMethod();
        if (null === $method) {
            return null;
        }

        if ($matchingTombstones = $tombstoneIndex->getInMethod($method)) {
            foreach ($matchingTombstones as $matchingTombstone) {
                if ($vampire->inscriptionEquals($matchingTombstone)) {
                    return $matchingTombstone;
                }
            }
        }

        return null;
    }
}