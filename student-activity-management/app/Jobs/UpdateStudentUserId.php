<?php

namespace App\Jobs;

use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateStudentUserId implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $studentId;

    /**
     * Create a new job instance.
     */
    // Khởi tạo job với student_id
    public function __construct($studentId)
    {
        $this->studentId = $studentId;
    }

    /**
     * Execute the job.
     */
    // Xử lý logic cập nhật user_id
    public function handle()
    {
        $student = Student::where('student_id', $this->studentId)->first();
        if ($student) {
            $user = User::where('student_id', $this->studentId)->first();
            if ($user) {
                $student->user_id = $user->id;
                $student->save();
            }
        }
    }
}
