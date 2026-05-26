<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Default number of records per page for paginated admin listings.
     */
    public const PER_PAGE = 15;
}
