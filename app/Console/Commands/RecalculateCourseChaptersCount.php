<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\CourseSetting\Entities\Course;

class RecalculateCourseChaptersCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'courses:recalculate-chapters {--course_id= : Specific course ID to update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculates total_chapters for courses to count only chapters named as chapter 1, chapter 2, etc.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $courseId = $this->option('course_id');

        $query = Course::with('chapters');
        if ($courseId) {
            $query->where('id', $courseId);
        }

        $courses = $query->get();
        $updated = 0;

        $this->info("Processing {$courses->count()} course(s)...");

        foreach ($courses as $course) {
            $chapters = $course->chapters ?? collect();
            $numberedChapters = $chapters->filter(function ($chapter) {
                return preg_match('/chapter\s*[-:\s]?\s*\d+/i', strip_tags(html_entity_decode($chapter->name ?? '')));
            });

            $oldCount = $course->getRawOriginal('total_chapters');
            $newCount = $numberedChapters->count() > 0 ? $numberedChapters->count() : $chapters->count();

            if ($oldCount != $newCount) {
                \Illuminate\Support\Facades\DB::table('courses')
                    ->where('id', $course->id)
                    ->update(['total_chapters' => $newCount]);

                $this->line("Course ID #{$course->id} ('{$course->title}'): {$oldCount} -> {$newCount} chapters");
                $updated++;
            }
        }

        $this->info("Completed. Updated {$updated} course(s).");

        return 0;
    }
}
