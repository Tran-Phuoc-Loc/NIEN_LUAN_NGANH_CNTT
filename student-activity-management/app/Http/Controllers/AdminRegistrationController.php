<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Registration;
use App\Models\UnregisteredAttendance;
use Illuminate\Support\Facades\Log;

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

            // Mảng để theo dõi các student_id đã xử lý
            $processedEntries = [];

            // Xử lý dữ liệu từ file, kiểm tra sinh viên đã điểm danh
            foreach ($sheetData as $row) {
                if (!isset($row[0]) || empty($row[0])) {
                    continue; // Bỏ qua nếu không có student_id
                }

                $student_id = trim($row[0]); // Lấy student_id từ cột đầu tiên
                $full_name = isset($row[1]) && !empty($row[1]) ? trim($row[1]) : 'Không xác định'; // Kiểm tra và lấy full_name
                $email = isset($row[2]) && !empty($row[2]) ? trim($row[2]) : 'Không có email'; // Kiểm tra và lấy email
                $phone = isset($row[3]) && !empty($row[3]) ? trim($row[3]) : 'Không có số điện thoại'; // Kiểm tra và lấy phone

                // Bỏ qua nếu student_id đã được xử lý
                if (isset($processedEntries[$student_id])) {
                    continue;
                }

                // Thêm student_id vào danh sách đã xử lý để tránh trùng lặp
                $processedEntries[$student_id] = true;

                // Tìm đăng ký trong hệ thống bằng student_id và activity_id
                $registration = Registration::where('student_id', $student_id)
                    ->where('activity_id', $id)
                    ->first();

                if ($registration) {
                    // Sinh viên đã đăng ký và điểm danh
                    $registration->check = true;
                    $registration->save();

                    // Lấy thông tin từ bảng registrations nếu tồn tại
                    $full_name = $registration->full_name ?: $full_name;
                    $email = $registration->email ?: $email;
                    $phone = $registration->phone ?: $phone;
                }

                // Kiểm tra sinh viên đã tồn tại trong unregistered_attendances chưa
                UnregisteredAttendance::updateOrCreate(
                    [
                        'student_id' => $student_id,
                        'activity_id' => $id, // Điều kiện để xác định bản ghi
                    ],
                    [
                        'full_name' => $full_name,  // Nếu tồn tại, cập nhật thông tin
                        'email' => $email,
                        'phone' => $phone,
                        'batch' => 'Không xác định', // Thiết lập batch mặc định nếu không có thông tin
                    ]
                );
            }

            return redirect()->route('admin.activities.registered-users', $id)
                ->with('success', 'Điểm danh đã được nhập thành công.');
        }

        return redirect()->back()->with('error', 'Vui lòng tải lên file điểm danh hợp lệ.');
    }
}
