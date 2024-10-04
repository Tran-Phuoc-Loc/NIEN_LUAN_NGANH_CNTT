<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Registration;

class AdminRegistrationController extends Controller
{
    public function importAttendance(Request $request, $id)
    {
        // Kiểm tra file được upload
        if ($request->hasFile('attendance_file')) {
            $file = $request->file('attendance_file');

            // Sử dụng phpoffice/phpspreadsheet để đọc file Excel
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            // Xử lý dữ liệu từ file, kiểm tra sinh viên đã điểm danh
            foreach ($sheetData as $row) {
                // Giả sử cột đầu tiên của file là student_id
                $student_id = $row[0]; // Ví dụ: student_id ở cột đầu tiên của file Excel

                // Tìm đăng ký trong hệ thống bằng student_id và activity_id
                $registration = Registration::where('student_id', $student_id)->where('activity_id', $id)->first();

                if ($registration) {
                    // Nếu tìm thấy đăng ký, cập nhật kiểm chứng
                    $registration->check = true;
                    $registration->save();
                }
            }

            return redirect()->route('admin.activities.registered-users', $id)
                ->with('success', 'Điểm danh đã được nhập thành công.');
        }

        return redirect()->back()->with('error', 'Vui lòng tải lên file điểm danh hợp lệ.');
    }
}
