<?php

namespace App\Enums;

enum LeaveTypes: string
{
    case ANNUAL = 'Annual';
    case SICK = 'Sick';
    case FAMILY = 'Family';
    case MATERNITY = 'Maternity';
    case UNPAID = 'Unpaid';
    case STUDY = 'Study';
}
