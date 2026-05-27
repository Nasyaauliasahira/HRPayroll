<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\LeaveRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Departments
        $departments = [
            ['name' => 'Engineering', 'code' => 'ENG'],
            ['name' => 'Finance', 'code' => 'FIN'],
            ['name' => 'HR', 'code' => 'HR'],
            ['name' => 'Marketing', 'code' => 'MKT'],
            ['name' => 'Operations', 'code' => 'OPS'],
            ['name' => 'Legal', 'code' => 'LEG'],
        ];
        foreach ($departments as $dept) {
            Department::firstOrCreate(['code' => $dept['code']], $dept);
        }

        // Positions
        $positions = [
            ['title' => 'Senior Engineer', 'department_id' => 1],
            ['title' => 'Accountant', 'department_id' => 2],
            ['title' => 'HR Manager', 'department_id' => 3],
            ['title' => 'Marketing Lead', 'department_id' => 4],
            ['title' => 'Ops Supervisor', 'department_id' => 5],
            ['title' => 'Legal Counsel', 'department_id' => 6],
        ];
        foreach ($positions as $pos) {
            Position::firstOrCreate(['title' => $pos['title']], $pos);
        }

        // Employees
        $faker = \Faker\Factory::create('id_ID');
        $employees = [];
        foreach (range(1, 18) as $i) {
            $dept = Department::inRandomOrder()->first();
            $pos = Position::where('department_id', $dept->id)->inRandomOrder()->first();
            $emp = Employee::create([
                'employee_code' => 'EMP' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'nik' => $faker->nik(),
                'birth_date' => $faker->date('Y-m-d', '-22 years'),
                'gender' => Arr::random(['male', 'female']),
                'address' => $faker->address,
                'photo' => null,
                'join_date' => $faker->date('Y-m-d', '-2 years'),
                'employment_type' => Arr::random(['fulltime', 'contract']),
                'department_id' => $dept->id,
                'position_id' => $pos->id,
                'base_salary' => rand(5000000, 18000000),
                'status' => 'active',
            ]);
            $employees[] = $emp;
        }

        // Attendance (last 30 days)
        foreach ($employees as $emp) {
            foreach (range(0, 29) as $d) {
                $date = Carbon::now()->subDays($d);
                $status = Arr::random(['present','present','present','late','absent','leave']);
                $check_in = $status === 'absent' ? null : $date->copy()->setTime(rand(8,9), rand(0,59));
                $check_out = $status === 'absent' ? null : $date->copy()->setTime(rand(17,18), rand(0,59));
                Attendance::create([
                    'employee_id' => $emp->id,
                    'date' => $date->format('Y-m-d'),
                    'check_in' => $check_in,
                    'check_out' => $check_out,
                    'work_hours' => $check_in && $check_out ? rand(7,9) : null,
                    'late_minutes' => $status === 'late' ? rand(5, 45) : 0,
                    'overtime_minutes' => rand(0, 60),
                    'status' => $status,
                    'notes' => $status === 'leave' ? 'Cuti' : null,
                ]);
            }
        }

        // Payroll (last 3 months)
        foreach ($employees as $emp) {
            foreach (range(0, 2) as $m) {
                $month = Carbon::now()->subMonths($m)->month;
                $year = Carbon::now()->subMonths($m)->year;
                Payroll::firstOrCreate([
                    'employee_id' => $emp->id,
                    'period_month' => $month,
                    'period_year' => $year,
                ], [
                    'base_salary' => $emp->base_salary,
                    'other_allowance' => rand(500000, 2000000),
                    'gross_salary' => $emp->base_salary + rand(500000, 2000000),
                    'late_deduction' => rand(0, 200000),
                    'absence_deduction' => rand(0, 300000),
                    'tax_deduction' => rand(100000, 400000),
                    'bpjs_deduction' => rand(50000, 150000),
                    'other_deduction' => rand(0, 100000),
                    'total_deductions' => rand(200000, 800000),
                    'overtime_pay' => rand(0, 250000),
                    'bonus' => rand(0, 500000),
                    'net_salary' => $emp->base_salary + rand(500000, 2000000) - rand(200000, 800000),
                    'status' => Arr::random(['draft','finalized','paid']),
                    'generated_at' => Carbon::now()->subMonths($m)->startOfMonth(),
                    'paid_at' => Arr::random([null, Carbon::now()->subMonths($m)->endOfMonth()]),
                    'notes' => null,
                ]);
            }
        }

        // Leave Requests
        foreach ($employees as $emp) {
            foreach (range(1, 2) as $i) {
                $start = Carbon::now()->subDays(rand(1, 60));
                $end = $start->copy()->addDays(rand(1, 5));
                $status = Arr::random(['pending','approved','rejected']);
                LeaveRequest::create([
                    'employee_id' => $emp->id,
                    'type' => Arr::random(['annual', 'sick', 'personal', 'unpaid', 'maternity', 'paternity']),
                    'start_date' => $start,
                    'end_date' => $end,
                    'total_days' => $start->diffInDays($end) + 1,
                    'reason' => Arr::random(['Cuti tahunan','Sakit','Keperluan keluarga','Liburan']),
                    'status' => $status,
                    'approver_id' => $status !== 'pending' ? 1 : null,
                    'approved_at' => $status === 'approved' ? $end->copy()->addDay() : null,
                    'rejected_at' => $status === 'rejected' ? $end->copy()->addDay() : null,
                ]);
            }
        }
    }
}
