<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Registration;
use App\Models\UnregisteredAttendance;

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
                if (!isset($row[0]) || empty($row[0])) {
                    continue;
                }

                $student_id = $row[0];
                $full_name = isset($row[1]) && !empty($row[1]) ? $row[1] : 'Không xác định'; // Chỉ đặt 'Không xác định' nếu không có trong file
                $email = isset($row[2]) && !empty($row[2]) ? $row[2] : 'Không có email';     // Tương tự cho email
                $phone = isset($row[3]) && !empty($row[3]) ? $row[3] : 'Không có số điện thoại'; // Tương tự cho phone

                // Tìm đăng ký trong hệ thống bằng student_id và activity_id
                $registration = Registration::where('student_id', $student_id)
                    ->where('activity_id', $id)
                    ->first();

                if ($registration) {
                    // Sinh viên đã đăng ký và điểm danh
                    $registration->check = true;
                    $registration->save();

                    // Lấy thêm thông tin từ bảng registrations (nếu tồn tại)
                    $full_name = $registration->full_name ?: $full_name;
                    $email = $registration->email ?: $email;
                    $phone = $registration->phone ?: $phone;
                }

                // Kiểm tra sinh viên đã tồn tại trong unregistered_attendances chưa
                UnregisteredAttendance::updateOrCreate(
                    [
                        'student_id' => $student_id,
                        'activity_id' => $id,
                    ],
                    [
                        'full_name' => $full_name,
                        'email' => $email,
                        'phone' => $phone,
                        'batch' => 'Không xác định',
                    ]
                );
            }

            return redirect()->route('admin.activities.registered-users', $id)
                ->with('success', 'Điểm danh đã được nhập thành công.');
        }

        return redirect()->back()->with('error', 'Vui lòng tải lên file điểm danh hợp lệ.');
    }
}
