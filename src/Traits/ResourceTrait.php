<?php

namespace Rabsana\Trade\Traits;

trait ResourceTrait
{

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->setCustomWith();
    }

    public function setCustomWith(array $with = [])
    {
        $this->with = array_merge(
            $this->with,
            [
                'status'                => $with['status'] ?? 200,
                'success'               => $with['success'] ?? true,
                'message'               => $with['message'] ?? '',
                'errors'                => $with['errors'] ?? [],
            ]
        );

        return $this;
    }
}
