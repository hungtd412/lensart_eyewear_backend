<?php

namespace App\Services;

use App\Repositories\BlogRepository;
use App\Repositories\BlogReposity;

class BlogService
{
    protected $blogRepository;

    public function __construct(BlogReposity $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    /**
     * Lấy danh sách các blog có trạng thái 'active'
     *
     * @param int $limit
     * @return mixed
     */
    public function getActiveBlogs($limit = 10)
    {
        return $this->blogRepository->getActiveBlogs($limit);
    }
}
