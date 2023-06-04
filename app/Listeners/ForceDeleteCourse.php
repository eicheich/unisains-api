<?php

namespace App\Listeners;

use App\Events\CourseDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Carbon\Carbon;

class ForceDeleteCourse implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(CourseDeleted $event)
    {
        $course = $event->course;
        $deletedAt = $course->deleted_at;
        $forceDeleteAt = Carbon::parse($deletedAt)->addMinute();

        // Tunggu sampai waktu force delete
        $this->release($forceDeleteAt);

        // Lakukan force delete
        $course->forceDelete();
    }
}
