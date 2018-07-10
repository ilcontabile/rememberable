<?php

namespace Ilcontabile\Rememberable;

use Ilcontabile\Rememberable\Query\Builder;
use Illuminate\Support\Facades\Cache;

trait Rememberable
{
    protected $builderInstance;
    
    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        $builder = new Builder($conn, $grammar, $conn->getPostProcessor());

        if (isset($this->rememberFor)) {
            $builder->remember($this->rememberFor);
        }

        if (isset($this->rememberCacheTag)) {
            $builder->cacheTags($this->rememberCacheTag);
        }

        if (isset($this->rememberCachePrefix)) {
            $builder->prefix($this->rememberCachePrefix);
        }

        if (isset($this->rememberCacheDriver)) {
            $builder->cacheDriver($this->rememberCacheDriver);
        }

        $this->builderInstance = &$builder;

        return $builder;
    }
    
    /* Forget current cached key */
    public function forget()
    {
        Cache::forget($this->builderInstance->getCacheKey());
    }
}
